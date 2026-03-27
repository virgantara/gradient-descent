<?php
// OAuth2 server endpoint and client details
$oauthServerUrl = 'http://local.oauth.com:5001/oauth/token';
$clientId = 'b8aaf9d8-5190-4df1-a674-c6552907559b';
$clientSecret = 'my_client_secret';

// Hardcoded username and password for demonstration purposes
$username = 'admin_pptik';
$password = 'pp$$tt11kk44';

// Prepare the POST data
$data = http_build_query([
    'grant_type'    => 'password',
    'client_id'     => $clientId,
    'client_secret' => $clientSecret,
    'username'      => $username,
    'password'      => $password
]);

// Send the POST request to the OAuth2 server
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $oauthServerUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

// Decode the JSON response
$tokenData = json_decode($response, true);

echo '<pre>';
print_r($response);
// Store the token in the session and redirect to the callback page
session_start();
if (isset($tokenData['access_token'])) {
    $_SESSION['access_token'] = $tokenData['access_token'];
    header('Location: callback.php');
    exit();
} else {
    echo 'Login failed: ' . htmlspecialchars($tokenData['error_description'] ?? 'Unknown error');
}
?>
