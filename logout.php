<?php
session_start();

// Destroy session
session_destroy();

// Set success message
$_SESSION = array();

// Redirect to login with success message
header("Location: login.php?logout=success");
exit();
?>
