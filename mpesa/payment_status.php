<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../server/connection.php';
require_once __DIR__ . '/reconcile_payment.php';

function sendPaymentResponse(int $httpCode, array $data): void
{
    http_response_code($httpCode);
    echo json_encode($data);
    exit;
}

// Only logged-in cashiers can check payment status.
if (empty($_SESSION['username'])) {
    sendPaymentResponse(401, [
        'message' => 'Unauthorized'
    ]);
}

$checkoutId = $_GET['checkout_id'] ?? '';

if (
    !is_string($checkoutId) ||
    $checkoutId === '' ||
    strlen($checkoutId) > 120
) {
    sendPaymentResponse(400, [
        'message' => 'Invalid checkout ID'
    ]);
}

$username = $_SESSION['username'];

// Find payment belonging to this cashier.
$stmt = $db->prepare(
    "SELECT id, status, result_code, result_desc,
            sale_receipt_no, last_query_at
     FROM mpesa_payments
     WHERE checkout_request_id = ?
       AND cashier_username = ?
     LIMIT 1"
);

$stmt->bind_param('ss', $checkoutId, $username);
$stmt->execute();

$payment = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$payment) {
    sendPaymentResponse(404, [
        'message' => 'Payment not found'
    ]);
}

$paymentId = (int)$payment['id'];

// If already finalized, return the existing receipt.
if ($payment['sale_receipt_no'] !== null) {
    sendPaymentResponse(200, [
        'status' => 'finalized',
        'receipt_no' => (int)$payment['sale_receipt_no']
    ]);
}

// Do not query Safaricom more than once every 15 seconds.
// The conditional UPDATE also prevents simultaneous polls
// from claiming the same verification window.
$claim = $db->prepare(
    "UPDATE mpesa_payments
     SET last_query_at = NOW()
     WHERE id = ?
       AND sale_receipt_no IS NULL
       AND (
           last_query_at IS NULL
           OR last_query_at <= DATE_SUB(NOW(), INTERVAL 15 SECOND)
       )"
);

$claim->bind_param('i', $paymentId);
$claim->execute();

$shouldQuery = $claim->affected_rows === 1;
$claim->close();

if ($shouldQuery) {
    try {
        // Reconcile existing STK payment with Safaricom.
        // Does not initiate a new payment.
        reconcileMpesaPayment($db, $paymentId);

    } catch (Throwable $e) {
        error_log(
            'M-Pesa reconciliation error for payment ' .
            $paymentId . ': ' . $e->getMessage()
        );
    }
}

// Always reload the latest database status.
$stmt = $db->prepare(
    "SELECT status, result_code, result_desc,
            sale_receipt_no, verified_at
     FROM mpesa_payments
     WHERE id = ?
     LIMIT 1"
);

$stmt->bind_param('i', $paymentId);
$stmt->execute();

$updated = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$updated) {
    sendPaymentResponse(404, [
        'message' => 'Payment not found'
    ]);
}

// Sale successfully recorded.
if ($updated['sale_receipt_no'] !== null) {
    sendPaymentResponse(200, [
        'status' => 'finalized',
        'receipt_no' => (int)$updated['sale_receipt_no'],
        'message' => 'Payment verified and sale recorded'
    ]);
}

// Payment verified, but sale has not finalized.
if (
    $updated['status'] === 'query_confirmed' &&
    $updated['verified_at'] !== null
) {
    sendPaymentResponse(200, [
        'status' => 'query_confirmed',
        'message' => 'Payment verified; sale awaiting finalization'
    ]);
}

// Return the current state without falsely claiming success.
sendPaymentResponse(200, [
    'status' => $updated['status'],
    'result_code' => $updated['result_code'],
    'result_desc' => $updated['result_desc']
]);
