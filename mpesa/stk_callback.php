<?php

header('Content-Type: application/json; charset=utf-8');

// Safaricom sends callbacks using POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Read the raw callback
$rawBody = file_get_contents('php://input');
$data = json_decode($rawBody ?: '', true);

$callback = $data['Body']['stkCallback'] ?? null;

if (
    !is_array($callback) ||
    empty($callback['CheckoutRequestID']) ||
    !isset($callback['ResultCode'])
) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid callback']);
    exit;
}

$checkoutId = (string)$callback['CheckoutRequestID'];
$resultCode = (int)$callback['ResultCode'];
$resultDesc = substr(
    (string)($callback['ResultDesc'] ?? ''),
    0,
    255
);

// Extract callback metadata
$metadata = [];

$items = $callback['CallbackMetadata']['Item'] ?? [];

if (is_array($items)) {
    foreach ($items as $item) {
        if (
            is_array($item) &&
            isset($item['Name'], $item['Value'])
        ) {
            $metadata[$item['Name']] = $item['Value'];
        }
    }
}

$receipt = isset($metadata['MpesaReceiptNumber'])
    ? (string)$metadata['MpesaReceiptNumber']
    : null;

$amount = isset($metadata['Amount'])
    ? (float)$metadata['Amount']
    : null;

$phone = isset($metadata['PhoneNumber'])
    ? (string)$metadata['PhoneNumber']
    : null;

try {

    // Docker mounts your docker-config/connection.php
    // to this location inside the app container.
    require_once __DIR__ . '/../server/connection.php';

    // Find the original payment
    $stmt = $db->prepare(
        "SELECT id, amount, phone, status
         FROM mpesa_payments
         WHERE checkout_request_id = ?
         LIMIT 1"
    );

    $stmt->bind_param('s', $checkoutId);
    $stmt->execute();

    $result = $stmt->get_result();
    $payment = $result->fetch_assoc();

    $result->free();
    $stmt->close();

    if (!$payment) {

        error_log(
            'MPESA CALLBACK UNMATCHED: ' . $checkoutId
        );

        // Do not falsely acknowledge an unmatched payment.
        http_response_code(503);

        echo json_encode([
            'error' => 'Payment record not found'
        ]);

        exit;
    }

    // Do not overwrite a payment that was already processed.
    if ($payment['status'] !== 'pending') {

        error_log(
            'MPESA CALLBACK DUPLICATE: ' . $checkoutId
        );

        echo json_encode([
            'ResultCode' => 0,
            'ResultDesc' => 'Already processed'
        ]);

        exit;
    }

    // Determine initial status
    $status = match ($resultCode) {
        0 => 'needs_review',
        1032 => 'cancelled',
        1037 => 'timed_out',
        default => 'failed'
    };

    // Validate successful payment metadata
    if ($resultCode === 0) {

        $amountMatches =
            $amount !== null &&
            abs($amount - (float)$payment['amount']) < 0.005;

        $phoneMatches =
            $phone !== null &&
            $phone === (string)$payment['phone'];

        if (
            $receipt !== null &&
            $receipt !== '' &&
            $amountMatches &&
            $phoneMatches
        ) {
            $status = 'reported_success';
        }

    } else {
        $receipt = null;
    }

    // Update the payment
    $stmt = $db->prepare(
        "UPDATE mpesa_payments
         SET
             status = ?,
             result_code = ?,
             result_desc = ?,
             mpesa_receipt = ?,
             callback_amount = ?,
             callback_phone = ?
         WHERE checkout_request_id = ?
           AND status = 'pending'"
    );

    $stmt->bind_param(
        'sissdss',
        $status,
        $resultCode,
        $resultDesc,
        $receipt,
        $amount,
        $phone,
        $checkoutId
    );

    $stmt->execute();
    $updated = $stmt->affected_rows;
    $stmt->close();

    error_log(
        'MPESA CALLBACK PROCESSED: ' .
        json_encode([
            'checkout_id' => $checkoutId,
            'status' => $status,
            'result_code' => $resultCode,
            'rows_updated' => $updated
        ])
    );

    echo json_encode([
        'ResultCode' => 0,
        'ResultDesc' => 'Accepted'
    ]);

} catch (Throwable $e) {

    error_log(
        'MPESA CALLBACK ERROR: ' . $e->getMessage()
    );

    http_response_code(500);

    echo json_encode([
        'error' => 'Callback processing failed'
    ]);
}
