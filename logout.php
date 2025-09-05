<?php
// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destroy all session data
session_unset();
session_destroy();

// Redirect to homepage
header('Location: index.php?logout=success');
exit();
?>