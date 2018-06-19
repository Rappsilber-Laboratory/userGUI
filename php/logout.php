<?php
ob_start();
// http://stackoverflow.com/questions/18188716/warning-session-destroy-trying-to-destroy-uninitialized-session
session_start();
// http://stackoverflow.com/questions/3512507/proper-way-to-logout-from-a-session-in-php

// Unset all of the session variables.
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();

header("location:../../xiNET_website/login.php");
ob_end_flush();
?>
