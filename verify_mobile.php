<?php

require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function accessProtectedRoute($protectedUrl, $token) {
    $ch = curl_init($protectedUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,

        // -- token for local
        // 'x-access-token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjbGllbnRJZCI6ImFkbWluIiwiY2xpZW50U2VjcmV0IjoiYWRtaW4iLCJpYXQiOjE2NTA3OTk3MzR9.OWf-mEyxftYjqrYwqODSLYixG8AKcTXFy11DRCUHKKk',
        
        // ---token for remote
        'x-access-token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjbGllbnRJZCI6IjE5MjYyMDE0IiwiY2xpZW50U2VjcmV0IjoiYmlzbWlsbGFoIiwiaWF0IjoxNTc5MzE4NzA3fQ.5Z43hVcfu8d-0Sqn46r9eC6XEq97cMc-CrZBJFEs5tA',
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch) . "\n";
        curl_close($ch);
        return;
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $responseData = json_decode($response, true);
    curl_close($ch);

    if ($httpCode === 200) {

        print_r($responseData);
    } else {
        print_r($responseData);
        echo "Access failed: " . ($responseData['msg'] ?? 'Unknown error') . "\n";
    }
}

// Node.js server endpoints
// $verifyUrl = 'http://api.unida.gontor.ac.id:1926/mobile/verify';
$verifyUrl = 'http://local.api.com:1926/mobile/verify';
// $verifyUrlSsl = 'https://local.api.com:1988/mobile/verify';
$verifyUrlSsl = 'https://api.unida.gontor.ac.id:1988/mobile/verify';
// User credentials for login
$token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VybmFtZSI6IjM5MjAxODYxMTAyOTgiLCJyb2xlIjoiTWFoYXNpc3dhIiwiaWF0IjoxNzMzODM2NDc5LCJleHAiOjE3MzM4NDAwNzl9.zgnrxJg8IX12bFbeJFmwncWJmNTmI7HuLy1zfwrgYIE';

// echo '<pre>';
// echo $jwt;
// echo '<br>';
// // Execute the login and access the protected route
// $res = accessProtectedRoute($verifyUrl, $token);
$res = accessProtectedRoute($verifyUrlSsl, $token);
print_r($res);
// if ($token) {
//     // accessProtectedRoute($protectedUrl, $token);
// }

?>