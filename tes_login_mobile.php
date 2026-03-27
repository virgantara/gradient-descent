<?php

require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Step 1: Login to obtain a Bearer token
function login($loginUrl, $jwt) {
    

    $ch = curl_init($loginUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'x-access-token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjbGllbnRJZCI6IjE5MjYyMDE0IiwiY2xpZW50U2VjcmV0IjoiYmlzbWlsbGFoIiwiaWF0IjoxNTc5MzE4NzA3fQ.5Z43hVcfu8d-0Sqn46r9eC6XEq97cMc-CrZBJFEs5tA',
        // 'x-access-token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjbGllbnRJZCI6ImFkbWluIiwiY2xpZW50U2VjcmV0IjoiYWRtaW4iLCJpYXQiOjE2NTA3OTk3MzR9.OWf-mEyxftYjqrYwqODSLYixG8AKcTXFy11DRCUHKKk',
        'Authorization: Bearer '.$jwt
    ]);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch) . "\n";
        curl_close($ch);
        return null;
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    $responseData = json_decode($response, true);
    // print_r($httpCode);
    // print_r($responseData);

    curl_close($ch);

    if ($httpCode === 200 && isset($responseData['values']['token'])) {
        // echo "Login successful. Token obtained.\n";
        return $responseData['values']['token'];
    } else {
        print_r($responseData);
        echo "Login failed: " . ($responseData['values']['msg'] ?? 'Unknown error') . "\n";
        return null;
    }
}


// Node.js server endpoints
$loginUrl = 'http://api.unida.gontor.ac.id:1926/u/login/m';
// $loginUrl = 'http://localhost:1926/u/login/m';
$protectedUrl = 'http://localhost:3000/api/protected';

// User credentials for login
$username = '3920186110298';
$password = '123456789';

$key = 'x0[0gjMZt$%EKs7';

$options = ['cost' => 13];
// $hashedPassword = password_hash($password, PASSWORD_BCRYPT, $options);
$issuedAt = time();
// jwt valid for 60 days (60 seconds * 60 minutes * 24 hours * 60 days)
$expirationTime = $issuedAt + 60 * 60 * 24 * 60;

$payload = [
    'username' => $username,
    'password_hash' => $password,
    'role' => 'Mahasiswa',
    'iat' => $issuedAt,
    'exp' => $expirationTime
];

/**
 * IMPORTANT:
 * You must specify supported algorithms for your application. See
 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
 * for a list of spec-compliant algorithms.
 */
$jwt = JWT::encode($payload, $key, 'HS256');
// echo '<pre>';
// echo $jwt;
// echo '<br>';
// // Execute the login and access the protected route
$token = login($loginUrl, $jwt);

print_r($token);
// if ($token) {
//     // accessProtectedRoute($protectedUrl, $token);
// }

?>