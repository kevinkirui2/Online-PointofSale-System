<?php

/**
 * Query the status of an existing Safaricom STK Push.
 *
 * Returns the Daraja response without modifying the database.
 */

function mpesaDarajaRequest(string $url, array $options): array
{
    $curl = curl_init($url);

    if ($curl === false) {
        throw new RuntimeException('Could not initialize cURL');
    }

    $defaultOptions = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 30
    ];

    curl_setopt_array(
        $curl,
        array_replace($defaultOptions, $options)
    );

    $body = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error = curl_error($curl);

    curl_close($curl);

    if ($body === false) {
        throw new RuntimeException('Daraja network error: ' . $error);
    }

    $decoded = json_decode($body, true);

    return [
        'http_code' => $httpCode,
        'body' => is_array($decoded) ? $decoded : []
    ];
}

function queryMpesaCheckout(string $checkoutId): array
{
    if (
        $checkoutId === '' ||
        strlen($checkoutId) > 120 ||
        str_starts_with($checkoutId, 'INIT_')
    ) {
        throw new RuntimeException('Invalid CheckoutRequestID');
    }

    $environment = getenv('MPESA_ENV');

    if ($environment !== 'sandbox' && $environment !== 'production') {
        throw new RuntimeException('Invalid M-Pesa environment');
    }

    $baseUrl = $environment === 'production'
        ? 'https://api.safaricom.co.ke'
        : 'https://sandbox.safaricom.co.ke';

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
        throw new RuntimeException('Missing Daraja credentials');
    }

    // Obtain OAuth access token.
    $auth = mpesaDarajaRequest(
        $baseUrl . '/oauth/v1/generate?grant_type=client_credentials',
        [
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => $consumerKey . ':' . $consumerSecret
        ]
    );

    $token = $auth['body']['access_token'] ?? null;

    if ($auth['http_code'] !== 200 || !$token) {
        throw new RuntimeException('Daraja authentication failed');
    }

    // Generate timestamp using Nairobi time.
    $timestamp = (new DateTimeImmutable(
        'now',
        new DateTimeZone('Africa/Nairobi')
    ))->format('YmdHis');

    $password = base64_encode(
        $shortcode . $passkey . $timestamp
    );

    $payload = [
        'BusinessShortCode' => $shortcode,
        'Password' => $password,
        'Timestamp' => $timestamp,
        'CheckoutRequestID' => $checkoutId
    ];

    $response = mpesaDarajaRequest(
        $baseUrl . '/mpesa/stkpushquery/v1/query',
        [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode(
                $payload,
                JSON_THROW_ON_ERROR
            ),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ]
        ]
    );

    $data = $response['body'];

    $confirmed = (
        $response['http_code'] === 200 &&
        (string)($data['ResponseCode'] ?? '') === '0' &&
        array_key_exists('ResultCode', $data)
    );

    return [
        'confirmed' => $confirmed,
        'successful' => (
            $confirmed &&
            (string)$data['ResultCode'] === '0'
        ),
        'result_code' => $confirmed
            ? (int)$data['ResultCode']
            : null,
        'result_desc' => $data['ResultDesc'] ?? null,
        'http_code' => $response['http_code'],
        'response' => $data
    ];
}
