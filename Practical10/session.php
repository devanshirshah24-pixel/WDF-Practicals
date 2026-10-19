
<?php

// Configure the session cookie before starting the session.
$isHttps = !empty($_SERVER["HTTPS"])
    && $_SERVER["HTTPS"] !== "off";

session_set_cookie_params([
    "lifetime" => 0,
    "path" => "/",
    "secure" => $isHttps,
    "httponly" => true,
    "samesite" => "Lax"
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Set the inactivity timeout to 15 minutes.
$timeout = 10;

if (isset($_SESSION["last_activity"])) {

    if (time() - $_SESSION["last_activity"] > $timeout) {

        $_SESSION = [];

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

        session_destroy();

        header("Location: login.php?timeout=1");
        exit;
    }
}

// Update the activity time.
if (isset($_SESSION["user_id"])) {
    $_SESSION["last_activity"] = time();
}

?>
