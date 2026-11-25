<?php

require_once __DIR__ . '/../server/connection.php';
require_once __DIR__ . '/daraja_query.php';
require_once __DIR__ . '/finalize_sale.php';

/**
 * Reconcile an existing M-Pesa payment.
 *
 * Must be called from trusted server-side code.
 * Does not initiate another STK Push.
 */
function reconcileMpesaPayment(mysqli $db, int $paymentId): array
{
    // Retrieve the original checkout request.
    $stmt = $db->prepare(
        "SELECT id, checkout_request_id, status,
                sale_receipt_no, amount, cart_snapshot,verified_at
         FROM mpesa_payments
         WHERE id = ?
         LIMIT 1"
    );

    $stmt->bind_param('i', $paymentId);
    $stmt->execute();
    $payment = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$payment) {
        throw new RuntimeException('Payment not found');
    }

    // Payment was previously verified but not finalized.
    // Retry sale finalization without initiating another STK Push.
    if (
        $payment['status'] === 'query_confirmed' &&
        $payment['verified_at'] !== null
    ) {
        return finalizeMpesaSale($db, $paymentId);
    }

    // Payment was previously verified but not finalized.
    // Retry sale finalization without initiating another STK Push.
    if ($payment['status'] === 'query_confirmed') {
        return finalizeMpesaSale($db, $paymentId);
    }

    // Independently query Safaricom.
    $query = queryMpesaCheckout(
        $payment['checkout_request_id']
    );
    if (!$query['confirmed']) {
        return [
            'status' => 'unconfirmed',
            'message' => 'Safaricom has not confirmed payment'
        ];
    }

    if (!$query['successful']) {
        // Do not finalize unsuccessful payments.
        return [
            'status' => 'not_successful',
            'result_code' => $query['result_code']
        ];
    }

    // Validate saved cart against the original payment.
    $cart = json_decode(
        $payment['cart_snapshot'] ?? '',
        true,
        512,
        JSON_THROW_ON_ERROR
    );

    if (!is_array($cart) || empty($cart['items'])) {
        throw new RuntimeException('Invalid payment cart');
    }

    $amountCents = (int)round(
        (float)$payment['amount'] * 100
    );

    $cartCents = (int)round(
        (float)($cart['total'] ?? 0) * 100
    );

    if ($amountCents <= 0 || $amountCents !== $cartCents) {
        throw new RuntimeException(
            'Payment amount does not match saved cart'
        );
    }

    // Record independent verification.
    $stmt = $db->prepare(
        "UPDATE mpesa_payments
     SET status = 'query_confirmed',
         result_code = 0,
         result_desc = ?,
         verified_at = NOW()
     WHERE id = ?
       AND checkout_request_id = ?
       AND sale_receipt_no IS NULL
       AND verified_at IS NULL"
    );

    $description = 'Success confirmed by Safaricom STK Query';

    $stmt->bind_param(
        'sis',
        $description,
        $paymentId,
        $payment['checkout_request_id']
    );

    $stmt->execute();
    $updatedRows = $stmt->affected_rows;
    $stmt->close();

    if ($updatedRows !== 1) {
        // Another request may have verified this payment.
        // The finalization function checks verification
        // and locks the payment to prevent duplicate sales.
        return finalizeMpesaSale($db, $paymentId);
    }

    // Automatically record sale and deduct stock.
    return finalizeMpesaSale($db, $paymentId);
}
