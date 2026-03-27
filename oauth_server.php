<?php
require 'vendor/autoload.php';

use OAuth2\Storage\Pdo;
use OAuth2\Server;
use OAuth2\GrantType\ClientCredentials;
use OAuth2\GrantType\AuthorizationCode;

// Database connection
$dsn = 'mysql:host=localhost;dbname=oauth2_db';
$username = 'root';
$password = '4Dm1n_2022';
$storage = new Pdo(['dsn' => $dsn, 'username' => $username, 'password' => $password]);

// Create OAuth2 server
$server = new Server($storage);

// Enable Client Credentials grant type
$server->addGrantType(new ClientCredentials($storage));

// Enable Authorization Code grant type
$server->addGrantType(new AuthorizationCode($storage));

// Handle the request
$request = OAuth2\Request::createFromGlobals();
$response = new OAuth2\Response();

if (!$server->handleTokenRequest($request, $response)->send()) {
    exit('Invalid Token Request');
}

// Validate access token
if (!$server->verifyResourceRequest($request, $response)) {
    $response->send();
    die;
}

// Protected resource
echo json_encode(['success' => true, 'message' => 'Access granted']);
