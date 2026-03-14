<?php
session_start();
session_unset();    // Remove all session variables
session_destroy();  // Destroy the actual session
header("Location: ../pages/home.php"); // Redirect to your login page
exit();
?>