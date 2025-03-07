<?php
// logout.php - Terminate Session and Redirect to Login Page
require 'config.php'; // Include centralized configuration

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: index.php");
exit;
?>