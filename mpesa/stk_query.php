<?php

// STK Push status query - CLI only.
// Does not initiate payment or modify the database.

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Forbidden');
}

if (getenv('MPESA_ENV') !== 'sandbox') {
    exit("Error: Sandbox environment required.\n");
}

$paymentId = filter_var(
    $argv[1] ?? null,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

if ($paymentId === false || $paymentId === null) {
    exit("Usage: php stk_query.php PAYMENT_ID\n");
}

require_once __DIR__ . '/../server/connection.php';


function darajaRequest(
    string $url,
    array $options
): array {

    $curl = curl_init($url);

    // Default cURL settings
    $defaultOptions = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 30
    ];

    // Merge options while preserving numeric cURL keys
    $allOptions = array_replace(
        $defaultOptions,
        $options
    );

    curl_setopt_array($curl, $allOptions);

    $body = curl_exec($curl);

    $httpCode = curl_getinfo(
        $curl,
        CURLINFO_HTTP_CODE
    );

    $error = curl_error($curl);

    curl_close($curl);

    if ($body === false) {
        throw new RuntimeException(
            'Network error: ' . $error
        );
    }

    $decoded = json_decode($body, true);

    return [
        'http_code' => $httpCode,
        'body' => is_array($decoded) ? $decoded : []
    ];
}


try {

    // Find existing payment
    $stmt = $db->prepare(
        "SELECT id, checkout_request_id, amount, status
         FROM mpesa_payments
         WHERE id = ?
         LIMIT 1"
    );

    $stmt->bind_param('i', $paymentId);
    $stmt->execute();

    $result = $stmt->get_result();
    $payment = $result->fetch_assoc();

    $result->free();
    $stmt->close();

    if (!$payment) {
        throw new RuntimeException(
            'Payment not found in database'
        );
    }

    $checkoutId = $payment['checkout_request_id'];

    if (
        !$checkoutId ||
        str_starts_with($checkoutId, 'INIT_')
    ) {
        throw new RuntimeException(
            'Payment has no valid CheckoutRequestID'
        );
    }

    echo "Payment ID: {$payment['id']}\n";
    echo "Amount: KSh {$payment['amount']}\n";
    echo "Database status: {$payment['status']}\n";
    echo "Checkout ID: {$checkoutId}\n\n";

    // Read Daraja credentials from Docker environment
    $consumerKey = getenv('MPESA_CONSUMER_KEY');
    $consumerSecret = getenv('MPESA_CONSUMER_SECRET');
    $shortcode = getenv('MPESA_SHORTCODE');
    $passkey = getenv('MPESA_PASSKEY');

    if (
        !$consumerKey ||
        !$consumerSecret ||
        !$shortcode ||
        !$passkey
    ) {
        throw new RuntimeException(
            'Missing Daraja credentials'
        );
    }

    // Get OAuth access token
    $auth = darajaRequest(
        'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials',
        [
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD =>
            $consumerKey . ':' . $consumerSecret
        ]
    );

    $token = $auth['body']['access_token'] ?? null;

    if ($auth['http_code'] !== 200 || !$token) {
        throw new RuntimeException(
            'Daraja authentication failed: ' .
                ($auth['body']['errorMessage'] ?? 'Unknown error')
        );
    }

    // Generate query password
    date_default_timezone_set('Africa/Nairobi');

    $timestamp = date('YmdHis');

    $password = base64_encode(
        $shortcode . $passkey . $timestamp
    );

    // Query the existing STK request
    $payload = [
        'BusinessShortCode' => $shortcode,
        'Password' => $password,
        'Timestamp' => $timestamp,
        'CheckoutRequestID' => $checkoutId
    ];

    $response = darajaRequest(
        'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query',
        [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS =>
            json_encode($payload, JSON_THROW_ON_ERROR),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ]
        ]
    );

    $data = $response['body'];

    echo "Daraja HTTP status: {$response['http_code']}\n";

    if (isset($data['ResponseCode'])) {
        echo "Response Code: {$data['ResponseCode']}\n";
    }

    if (isset($data['ResultCode'])) {
        echo "Result Code: {$data['ResultCode']}\n";
    }

    if (isset($data['ResultDesc'])) {
        echo "Result Description: {$data['ResultDesc']}\n";
    }

    if (isset($data['errorMessage'])) {
        echo "Daraja Error: {$data['errorMessage']}\n";
    }

    echo "\n";

    // Only a completed query result is meaningful.
    // ResponseCode 0 alone does not mean paid.
    if (
        $response['http_code'] === 200 &&
        (string)($data['ResponseCode'] ?? '') === '0' &&
        array_key_exists('ResultCode', $data)
    ) {

        switch ((string)$data['ResultCode']) {
            case '0':
                echo "RESULT: Safaricom reports SUCCESS.\n";
                echo "No database changes were made.\n";
                break;

            case '1032':
                echo "RESULT: Customer cancelled payment.\n";
                break;

            case '1037':
                echo "RESULT: Payment request timed out.\n";
                break;

            default:
                echo "RESULT: Payment was not successful.\n";
                echo "Result code: {$data['ResultCode']}\n";
        }
    } else {
        echo "RESULT: Payment status not confirmed by this query.\n";
        echo "Check the Daraja response before taking action.\n";
    }
} catch (Throwable $e) {

    fwrite(
        STDERR,
        "Query error: " . $e->getMessage() . "\n"
    );

    exit(1);
}
