<?php
session_start();

// Check if the access token exists
if (!isset($_SESSION['access_token'])) {
    echo 'You are not logged in.';
    exit();
}

echo '<h2>Welcome to the Homepage!</h2>';
echo '<p>You have successfully logged in.</p>';
echo '<p>Your Access Token: ' . htmlspecialchars($_SESSION['access_token']) . '</p>';
?>

<form action="logout.php" method="POST">
    <button type="submit">Log Out</button>
</form>