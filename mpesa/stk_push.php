<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../server/connection.php';

// =====================================================
// 1. JSON RESPONSE HELPER
// =====================================================

function respond(int $code, array $data): void
{
    http_response_code($code);
    echo json_encode($data);
    exit;
}

// =====================================================
// 2. REQUEST VALIDATION
// =====================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(405, [
        'status' => 'error',
        'message' => 'Method not allowed'
    ]);
}

if (empty($_SESSION['username'])) {
    respond(401, [
        'status' => 'error',
        'message' => 'Please log in first'
    ]);
}

if (
    empty($_SESSION['pos_csrf']) ||
    !hash_equals(
        (string)$_SESSION['pos_csrf'],
        (string)($_POST['csrf'] ?? '')
    )
) {
    respond(403, [
        'status' => 'error',
        'message' => 'Invalid security token'
    ]);
}

// Sandbox only
if (getenv('MPESA_ENV') !== 'sandbox') {
    respond(403, [
        'status' => 'error',
        'message' => 'Only sandbox payments are enabled'
    ]);
}

// =====================================================
// 3. VALIDATE PHONE NUMBER
// =====================================================

$phone = preg_replace(
    '/\D/',
    '',
    (string)($_POST['phone'] ?? '')
);

if (preg_match('/^0[71]\d{8}$/', $phone)) {
    $phone = '254' . substr($phone, 1);
}

if (!preg_match('/^254[71]\d{8}$/', $phone)) {
    respond(422, [
        'status' => 'error',
        'message' => 'Invalid Safaricom phone number'
    ]);
}

// =====================================================
// 4. VALIDATE SHOPPING CART
// =====================================================

$products = $_POST['product'] ?? [];
$quantities = $_POST['quantity'] ?? [];
$lineTotals = $_POST['line_total'] ?? [];

if (
    !is_array($products) ||
    !is_array($quantities) ||
    !is_array($lineTotals) ||
    count($products) === 0 ||
    count($products) !== count($quantities) ||
    count($products) !== count($lineTotals)
) {
    respond(422, [
        'status' => 'error',
        'message' => 'Invalid shopping cart'
    ]);
}

if (count($products) > 100) {
    respond(422, [
        'status' => 'error',
        'message' => 'Too many cart items'
    ]);
}

// Discounts temporarily disabled
$discount = 0;

$cart = [];
$subtotalCents = 0;
$requestedQuantities = [];

// Each lookup will use a fresh prepared statement
$productSql = "
    SELECT
        product_no,
        sell_price,
        minimum_price,
        quantity
    FROM products
    WHERE product_no = ?
    LIMIT 1
";

foreach ($products as $index => $productId) {

    // Validate product ID
    if (
        !is_string($productId) ||
        trim($productId) === ''
    ) {
        respond(422, [
            'status' => 'error',
            'message' => 'Invalid product ID'
        ]);
    }

    $productId = trim($productId);

    // Validate quantity
    $qty = filter_var(
        $quantities[$index],
        FILTER_VALIDATE_INT,
        [
            'options' => [
                'min_range' => 1
            ]
        ]
    );

    if ($qty === false) {
        respond(422, [
            'status' => 'error',
            'message' => 'Invalid product quantity'
        ]);
    }

    // =================================================
    // FETCH PRODUCT FROM DATABASE
    // =================================================

    $productStmt = $db->prepare($productSql);

    $productStmt->bind_param(
        's',
        $productId
    );

    $productStmt->execute();

    $result = $productStmt->get_result();

    $product = $result->fetch_assoc();

    $result->free();

    $productStmt->close();

    if (!$product) {
        respond(422, [
            'status' => 'error',
            'message' => "Product not found: {$productId}"
        ]);
    }

    // =================================================
    // VALIDATE DATABASE PRICES
    // =================================================

    // Your current products table stores prices as VARCHAR.
    $sellingText = preg_replace(
        '/^(?:Ksh|KES)\s*/i',
        '',
        trim((string)$product['sell_price'])
    );

    $minimumText = preg_replace(
        '/^(?:Ksh|KES)\s*/i',
        '',
        trim((string)$product['minimum_price'])
    );

    $sellingText = str_replace(
        ',',
        '',
        $sellingText
    );

    $minimumText = str_replace(
        ',',
        '',
        $minimumText
    );

    if (
        !preg_match('/^\d+(\.\d{1,2})?$/', $sellingText) ||
        !preg_match('/^\d+(\.\d{1,2})?$/', $minimumText)
    ) {
        respond(422, [
            'status' => 'error',
            'message' => "Invalid database price for {$productId}"
        ]);
    }

    $sellingCents = (int)round(
        (float)$sellingText * 100
    );

    $minimumCents = (int)round(
        (float)$minimumText * 100
    );

    if (
        $sellingCents <= 0 ||
        $minimumCents < 0 ||
        $minimumCents > $sellingCents
    ) {
        respond(422, [
            'status' => 'error',
            'message' => "Invalid price range for {$productId}"
        ]);
    }

    // =================================================
    // VALIDATE EDITED LINE TOTAL
    // =================================================

    $submittedTotal = $lineTotals[$index];

    if (
        !is_scalar($submittedTotal) ||
        !preg_match(
            '/^\d+(\.\d{1,2})?$/',
            (string)$submittedTotal
        )
    ) {
        respond(422, [
            'status' => 'error',
            'message' => "Invalid line total for {$productId}"
        ]);
    }

    $lineCents = (int)round(
        (float)$submittedTotal * 100
    );

    // Multiply permitted unit prices by quantity
    $minAllowed = $minimumCents * $qty;
    $maxAllowed = $sellingCents * $qty;

    if (
        $lineCents < $minAllowed ||
        $lineCents > $maxAllowed
    ) {
        respond(422, [
            'status' => 'error',
            'message' =>
                "Price for {$productId} must be between KSh " .
                number_format($minAllowed / 100, 2) .
                " and KSh " .
                number_format($maxAllowed / 100, 2)
        ]);
    }

    // =================================================
    // VALIDATE AVAILABLE STOCK
    // =================================================

    $requestedQuantities[$productId] =
        ($requestedQuantities[$productId] ?? 0) + $qty;

    if (
        $product['quantity'] === null ||
        $requestedQuantities[$productId] > (int)$product['quantity']
    ) {
        respond(422, [
            'status' => 'error',
            'message' => "Insufficient stock for {$productId}"
        ]);
    }

    // =================================================
    // SAVE VALIDATED CART ITEM
    // =================================================

    $subtotalCents += $lineCents;

    $cart[] = [
        'product_id' => $productId,
        'quantity' => $qty,
        'unit_price' => $lineCents / (100 * $qty),
        'line_total' => $lineCents / 100
    ];
}

// =====================================================
// 5. CALCULATE FINAL PAYMENT AMOUNT
// =====================================================

if ($subtotalCents <= 0) {
    respond(422, [
        'status' => 'error',
        'message' => 'Invalid cart total'
    ]);
}

// Current sandbox checkout uses whole KSh amounts
if ($subtotalCents % 100 !== 0) {
    respond(422, [
        'status' => 'error',
        'message' => 'M-Pesa amount must be a whole KSh value'
    ]);
}

$calculatedAmount = $subtotalCents / 100;

$submittedAmount = filter_var(
    $_POST['amount'] ?? null,
    FILTER_VALIDATE_FLOAT
);

if (
    $submittedAmount === false ||
    !is_finite($submittedAmount) ||
    $submittedAmount < 1 ||
    $submittedAmount > 100000 ||
    abs($submittedAmount - $calculatedAmount) > 0.005
) {
    respond(422, [
        'status' => 'error',
        'message' => 'Cart total mismatch. Refresh and try again.'
    ]);
}

// =====================================================
// 6. CREATE CART SNAPSHOT
// =====================================================

$cartSnapshot = json_encode(
    [
        'items' => $cart,
        'subtotal' => $calculatedAmount,
        'discount' => $discount,
        'total' => $calculatedAmount
    ],
    JSON_THROW_ON_ERROR
);

$cashierUsername = $_SESSION['username'];

// =====================================================
// 7. DARAJA SANDBOX CONFIGURATION
// =====================================================

$consumerKey = getenv('MPESA_CONSUMER_KEY');
$consumerSecret = getenv('MPESA_CONSUMER_SECRET');
$passkey = getenv('MPESA_PASSKEY');
$shortcode = getenv('MPESA_SHORTCODE');
$callbackUrl = getenv('MPESA_CALLBACK_URL');

if (
    !$consumerKey ||
    !$consumerSecret ||
    !$passkey ||
    !$shortcode ||
    !$callbackUrl ||
    !filter_var($callbackUrl, FILTER_VALIDATE_URL) ||
    parse_url($callbackUrl, PHP_URL_SCHEME) !== 'https'
) {
    respond(500, [
        'status' => 'error',
        'message' => 'Daraja sandbox configuration is missing'
    ]);
}

$recordId = null;
$requestAccepted = false;

try {

    // =================================================
    // 8. GET DARAJA ACCESS TOKEN
    // =================================================

    $oauthUrl =
        'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

    $curl = curl_init($oauthUrl);

    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
        CURLOPT_USERPWD =>
            $consumerKey . ':' . $consumerSecret,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 30
    ]);

    $authResponse = curl_exec($curl);

    $authHttp = curl_getinfo(
        $curl,
        CURLINFO_HTTP_CODE
    );

    $authError = curl_error($curl);

    curl_close($curl);

    $auth = json_decode(
        $authResponse ?: '',
        true
    );

    $accessToken = $auth['access_token'] ?? null;

    if (
        $authHttp !== 200 ||
        !$accessToken
    ) {
        error_log(
            'DARAJA OAUTH ERROR: ' .
            json_encode([
                'http_status' => $authHttp,
                'curl_error' => $authError ?: null,
                'error_code' => $auth['errorCode'] ?? null,
                'error_message' => $auth['errorMessage'] ?? null
            ])
        );

        throw new RuntimeException(
            'Daraja authentication failed'
        );
    }

    // =================================================
    // 9. CREATE PENDING PAYMENT RECORD
    // =================================================

    $temporaryId =
        'INIT_' . bin2hex(random_bytes(12));

    $insertSql = "
        INSERT INTO mpesa_payments
        (
            checkout_request_id,
            phone,
            amount,
            status,
            cart_snapshot,
            cashier_username
        )
        VALUES (?, ?, ?, 'pending', ?, ?)
    ";

    $insertStmt = $db->prepare($insertSql);

    $insertStmt->bind_param(
        'ssdss',
        $temporaryId,
        $phone,
        $calculatedAmount,
        $cartSnapshot,
        $cashierUsername
    );

    $insertStmt->execute();

    $recordId = $db->insert_id;

    $insertStmt->close();

    // =================================================
    // 10. PREPARE STK PUSH REQUEST
    // =================================================

    date_default_timezone_set('Africa/Nairobi');

    $timestamp = date('YmdHis');

    $password = base64_encode(
        $shortcode . $passkey . $timestamp
    );

    $reference =
        'POS' . strtoupper(bin2hex(random_bytes(4)));

    $payload = [
        'BusinessShortCode' => $shortcode,
        'Password' => $password,
        'Timestamp' => $timestamp,
        'TransactionType' =>
            getenv('MPESA_TRANSACTION_TYPE')
                ?: 'CustomerPayBillOnline',
        'Amount' => (int)$calculatedAmount,
        'PartyA' => $phone,
        'PartyB' => $shortcode,
        'PhoneNumber' => $phone,
        'CallBackURL' => $callbackUrl,
        'AccountReference' => $reference,
        'TransactionDesc' => 'POS Payment'
    ];

    // =================================================
    // 11. SEND STK PUSH
    // =================================================

    $stkUrl =
        'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

    $curl = curl_init($stkUrl);

    curl_setopt_array($curl, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS =>
            json_encode($payload, JSON_THROW_ON_ERROR),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ],
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 30
    ]);

    $stkResponse = curl_exec($curl);

    $httpCode = curl_getinfo(
        $curl,
        CURLINFO_HTTP_CODE
    );

    $curlError = curl_error($curl);

    curl_close($curl);

    $result = json_decode(
        $stkResponse ?: '',
        true
    );

    // Log only non-sensitive response details
    error_log(
        'DARAJA STK RESPONSE: ' .
        json_encode([
            'http_status' => $httpCode,
            'response_code' =>
                $result['ResponseCode'] ?? null,
            'response_description' =>
                $result['ResponseDescription'] ?? null,
            'error_code' =>
                $result['errorCode'] ?? null,
            'error_message' =>
                $result['errorMessage'] ?? null,
            'curl_error' =>
                $curlError ?: null
        ])
    );

    // =================================================
    // 12. CHECK SAFARICOM RESPONSE
    // =================================================

    if (
        $httpCode !== 200 ||
        (string)($result['ResponseCode'] ?? '') !== '0' ||
        empty($result['CheckoutRequestID'])
    ) {
        throw new RuntimeException(
            'Safaricom did not accept the STK request'
        );
    }

    $requestAccepted = true;

    $checkoutId = $result['CheckoutRequestID'];

    $merchantId =
        $result['MerchantRequestID'] ?? null;

    // =================================================
    // 13. UPDATE PAYMENT WITH CHECKOUT ID
    // =================================================

    $updateSql = "
        UPDATE mpesa_payments
        SET
            checkout_request_id = ?,
            merchant_request_id = ?
        WHERE id = ?
    ";

    $updateStmt = $db->prepare($updateSql);

    $updateStmt->bind_param(
        'ssi',
        $checkoutId,
        $merchantId,
        $recordId
    );

    $updateStmt->execute();

    $updateStmt->close();

    // =================================================
    // 14. RETURN SUCCESSFUL INITIATION
    // =================================================

    respond(202, [
        'status' => 'pending',
        'checkout_request_id' => $checkoutId,
        'message' => 'STK Push sent successfully'
    ]);

} catch (Throwable $e) {

    error_log(
        'STK request error: ' . $e->getMessage()
    );

    // Only mark initiation as failed when Daraja
    // has not confirmed acceptance.
    if (
        $recordId !== null &&
        !$requestAccepted
    ) {
        try {

            $failedSql = "
                UPDATE mpesa_payments
                SET
                    status = 'failed',
                    result_desc = 'STK initiation failed'
                WHERE
                    id = ?
                    AND status = 'pending'
            ";

            $failedStmt = $db->prepare($failedSql);

            $failedStmt->bind_param(
                'i',
                $recordId
            );

            $failedStmt->execute();

            $failedStmt->close();

        } catch (Throwable $dbError) {

            error_log(
                'Failed to update payment status: ' .
                $dbError->getMessage()
            );
        }
    }

    respond(502, [
        'status' =>
            $requestAccepted ? 'unknown' : 'failed',
        'message' => $requestAccepted
            ? 'Safaricom accepted the request, but the payment record could not be updated. Check payment history before retrying.'
            : 'Unable to initiate STK Push'
    ]);
}
