<?php
session_start();

// Check if the access token exists in the session
if (isset($_SESSION['access_token'])) {
    $accessToken = $_SESSION['access_token'];

    // Send a POST request to the Node.js server to delete the access token
    $ch = curl_init('http://localhost:3000/auth/logout');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['access_token' => $accessToken]));

    $response = curl_exec($ch);
    curl_close($ch);

    // Log the response from the Node.js server (for debugging purposes)
    error_log($response);
}

// Destroy the session
session_unset();
session_destroy();

// Redirect to the login page or a logout confirmation page
header('Location: loginsso.php');
exit();
?>
