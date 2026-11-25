<?php

/**
 * Finalize a verified M-Pesa payment exactly once.
 *
 * Internal server-side function only.
 * Never call this directly from browser JavaScript.
 *
 * A payment must be independently verified before this
 * function is called. The status "reported_success"
 * alone is not sufficient proof of authenticity.
 */

function finalizeMpesaSale(mysqli $db, int $paymentId): array
{
    $db->begin_transaction();

    try {
        // Lock payment so two processes cannot finalize it together.
        $stmt = $db->prepare(
            "SELECT *
             FROM mpesa_payments
             WHERE id = ?
             FOR UPDATE"
        );
        $stmt->bind_param('i', $paymentId);
        $stmt->execute();
        $payment = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$payment) {
            throw new RuntimeException('Payment not found');
        }

        // Already finalized: do not deduct stock again.
        if ($payment['sale_receipt_no'] !== null) {
            $db->commit();

            return [
                'status' => 'already_finalized',
                'receipt_no' => (int)$payment['sale_receipt_no']
            ];
        }


        if (
            $payment['verified_at'] === null ||
            $payment['status'] !== 'query_confirmed' ||
            (int)$payment['result_code'] !== 0
        ) {
            throw new RuntimeException(
                'Payment has not been independently verified'
            );
        }

        $cart = json_decode(
            $payment['cart_snapshot'] ?? '',
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        if (
            !is_array($cart) ||
            empty($cart['items']) ||
            !is_array($cart['items'])
        ) {
            throw new RuntimeException('Invalid saved cart');
        }

        $items = $cart['items'];
        $totalCents = 0;
        $stockRequired = [];

        foreach ($items as $item) {
            $productId = (string)($item['product_id'] ?? '');
            $qty = filter_var(
                $item['quantity'] ?? null,
                FILTER_VALIDATE_INT
            );
            $lineTotal = $item['line_total'] ?? null;

            if (
                $productId === '' ||
                $qty === false ||
                $qty < 1 ||
                !is_numeric($lineTotal)
            ) {
                throw new RuntimeException(
                    'Invalid cart item'
                );
            }

            $lineCents = (int)round((float)$lineTotal * 100);

            if ($lineCents <= 0) {
                throw new RuntimeException(
                    'Invalid item amount'
                );
            }

            $totalCents += $lineCents;
            $stockRequired[$productId] =
                ($stockRequired[$productId] ?? 0) + $qty;
        }

        $paymentCents =
            (int)round((float)$payment['amount'] * 100);

        if (
            $totalCents !== $paymentCents ||
            $paymentCents <= 0 ||
            (int)round((float)($cart['total'] ?? -1) * 100)
            !== $paymentCents
        ) {
            throw new RuntimeException(
                'Cart total does not match payment'
            );
        }

        // Reserve stock atomically; prevent negative quantities.
        ksort($stockRequired);

        $stockStmt = $db->prepare(
            "UPDATE products
             SET quantity = quantity - ?
             WHERE product_no = ?
               AND quantity >= ?"
        );

        foreach ($stockRequired as $productId => $qty) {
            $stockStmt->bind_param(
                'isi',
                $qty,
                $productId,
                $qty
            );
            $stockStmt->execute();

            if ($stockStmt->affected_rows !== 1) {
                throw new RuntimeException(
                    "Insufficient stock for product {$productId}"
                );
            }
        }

        $stockStmt->close();

        $username = trim((string)$payment['cashier_username']);

        if ($username === '' || strlen($username) > 30) {
            throw new RuntimeException('Invalid cashier username');
        }

        $customerId = 16;
        $discount = 0;
        $paymentMethod = 'MPESA';
        $saleTotal = $paymentCents / 100;

        // Existing sales.total is INT, so only whole KSh.
        if ($paymentCents % 100 !== 0) {
            throw new RuntimeException(
                'Sales table requires whole-KSh amounts'
            );
        }

        $saleTotal = (int)$saleTotal;

        // Unique mpesa_payment_id prevents duplicate sales.
        $saleStmt = $db->prepare(
            "INSERT INTO sales
                (customer_id, username, discount,
                 payment_method, total, mpesa_payment_id)
             VALUES (?, ?, ?, ?, ?, ?)"
        );

        $saleStmt->bind_param(
            'isisii',
            $customerId,
            $username,
            $discount,
            $paymentMethod,
            $saleTotal,
            $paymentId
        );

        $saleStmt->execute();
        $receiptNo = (int)$db->insert_id;
        $saleStmt->close();

        // Insert all purchased products using the actual
        // receipt number from the newly inserted sale.
        $itemStmt = $db->prepare(
            "INSERT INTO sales_product
                (reciept_no, product_id, price, qty)
             VALUES (?, ?, ?, ?)"
        );

        foreach ($items as $item) {

    $productId = (string)$item['product_id'];
    $qty = (int)$item['quantity'];

    $unitPrice = (float)$item['unit_price'];
    $lineTotal = (float)$item['line_total'];

    if (
        $unitPrice <= 0 ||
        $lineTotal <= 0 ||
        $qty <= 0
    ) {
        throw new RuntimeException(
            'Invalid product price or quantity'
        );
    }

    $itemStmt->bind_param(
        'isdi',
        $receiptNo,
        $productId,
        $lineTotal,
        $qty
    );

    $itemStmt->execute();
}

        $itemStmt->close();

        // Link payment and sale in the same transaction.
        $linkStmt = $db->prepare(
            "UPDATE mpesa_payments
             SET sale_receipt_no = ?,
                 finalized_at = NOW()
             WHERE id = ?
               AND sale_receipt_no IS NULL"
        );

        $linkStmt->bind_param(
            'ii',
            $receiptNo,
            $paymentId
        );

        $linkStmt->execute();

        if ($linkStmt->affected_rows !== 1) {
            throw new RuntimeException(
                'Could not link payment to sale'
            );
        }

        $linkStmt->close();

        

        $db->commit();

        return [
            'status' => 'finalized',
            'receipt_no' => $receiptNo
        ];
    } catch (Throwable $e) {
        $db->rollback();
        throw $e;
    }
}
