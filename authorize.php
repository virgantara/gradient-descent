<?php
$authorizeUrl = 'http://local.oauth.com:5001/oauth/authorize';
$clientId = 'b8aaf9d8-5190-4df1-a674-c6552907559b';
$redirectUri = 'http://local.test.com/callback.php';
$scope = 'read write';
$state = bin2hex(random_bytes(16)); // CSRF protection token

// Building the URL with parameters
$queryParams = http_build_query([
    'response_type' => 'code',
    'client_id' => $clientId,
    'redirect_uri' => $redirectUri,
    'scope' => $scope,
    'state' => $state,
]);

$authUrl = $authorizeUrl . '?' . $queryParams;

// Redirect to the OAuth2 server for authorization
header("Location: $authUrl");
exit;

?>
