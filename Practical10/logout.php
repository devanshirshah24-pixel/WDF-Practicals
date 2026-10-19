
<?php

require_once "session.php";

// Accept logout requests through POST only.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit;
}

// Remove all session variables.
$_SESSION = [];

// Remove the session cookie from the browser.
if (ini_get("session.use_cookies")) {

    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        "",
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destroy the server-side session.
session_destroy();

// Return to login.
header("Location: login.php");
exit;

?>
