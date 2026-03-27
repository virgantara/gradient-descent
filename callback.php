<?php
session_start();

if (!isset($_GET['code'])) {
    echo "<h2>Login Failed</h2>";
    echo "<p>No authorization code received.</p>";
    exit;
}

// Validate the state parameter to protect against CSRF attacks
if (!isset($_SESSION['oauth_state'])) {
    echo "<h2>Session State Not Set</h2>";
    exit;
}

$authCode = $_GET['code'];
$clientId = 'b8aaf9d8-5190-4df1-a674-c6552907559b';
$clientSecret = 'my_client_secret';
$tokenUrl = 'http://local.oauth.com:5001/oauth/token';
$redirectUri = 'http://local.test.com/callback.php';

// Exchange the authorization code for an access token
$data = http_build_query([
    'grant_type'    => 'authorization_code',
    'code'          => $authCode,
    'client_id'     => $clientId,
    'client_secret' => $clientSecret,
    'redirect_uri'  => $redirectUri,
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $tokenUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

$tokenData = json_decode($response, true);
print_r($tokenData);exit;
if (isset($tokenData['access_token'])) {
    echo "<h2>Access Token Received</h2>";
    echo "<p>Your Access Token: <strong>" . htmlspecialchars($tokenData['access_token']) . "</strong></p>";

    // Optionally store the access token in the session or localStorage
    $_SESSION['access_token'] = $tokenData['access_token'];
} else {
    echo "<h2>Token Exchange Failed</h2>";
    echo "<p>Error: " . htmlspecialchars($tokenData['error'] ?? 'Unknown error') . "</p>";
}
?>
