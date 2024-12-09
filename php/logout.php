<?php
session_start();

// Clear all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Delete the user cookie
if (isset($_COOKIE['user'])) {
    setcookie('user', '', time() - 3600, "/"); // Expire the cookie
}

// Redirect to the login page with a logout message
header("Location: ../auth.php");
exit();
?>
