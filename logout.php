<?php
// Clear authentication cookies to log the user out
if (session_status() === PHP_SESSION_ACTIVE) {
	// If a session exists, destroy it for safety
	session_unset();
	session_destroy();
}

setcookie('username', '', time() - 3600, '/');
setcookie('user_id', '', time() - 3600, '/');
unset($_COOKIE['username']);
unset($_COOKIE['user_id']);

// Redirect to home
header('Location: ./index.php');
