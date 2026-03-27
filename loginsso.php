<?php
// Define the OAuth2 authorization URL and the callback URL
$authUrl = 'http://localhost:3000/auth/authorize';
$redirectUri = urlencode('http://local.test.com/callback.php');
$homepageUrl = urlencode('http://local.test.com/homepage.php');

// Redirect to the OAuth2 server
header("Location: $authUrl?redirectUri=$redirectUri&homepageUrl=$homepageUrl");
exit();
?>
