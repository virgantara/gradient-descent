<?php 
session_start();
$state = bin2hex(random_bytes(16));
$_SESSION['oauth_state'] = $state;

print_r($_SESSION['oauth_state']);
$clientId = 'b8aaf9d8-5190-4df1-a674-c6552907559b';
$redirectUri = urlencode('http://local.test.com/callback.php');
$scope = urlencode('read write');

$authUrl = "http://local.oauth.com:5001/oauth/login?"
    . "client_id={$clientId}"
    . "&redirect_uri={$redirectUri}"
    . "&response_type=code"
    . "&scope={$scope}"
    . "&grant_type=password"
    . "&state={$state}";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP OAuth2 Client</title>
</head>
<body>
    <h1>Welcome to My Application</h1>
    <p>
        <a href="<?php echo $authUrl; ?>">Login with OAuth2</a>
    </p>
</body>
</html>
