<?php
// Client credentials
$clientId = 'test_client';
$clientSecret = 'test_secret';

// Token request URL
$tokenUrl = 'http://local.test.com/oauth_server.php';

// Make the request using cURL
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $tokenUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'grant_type' => 'client_credentials',
    'client_id' => $clientId,
    'client_secret' => $clientSecret
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

$tokenData = json_decode($response, true);

if (isset($tokenData['access_token'])) {
    echo "Access Token: " . $tokenData['access_token'] . "\n";

    // Use the token to access the protected resource
    $protectedUrl = 'http://local.test.com/oauth_server.php';
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $protectedUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $tokenData['access_token']
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resourceResponse = curl_exec($ch);
    curl_close($ch);

    echo "Protected Resource Response: " . $resourceResponse . "\n";
} else {
    echo "Error: Unable to retrieve access token.\n";
    print_r($tokenData);
}
