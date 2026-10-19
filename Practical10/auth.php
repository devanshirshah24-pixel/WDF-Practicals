
<?php

require_once "session.php";

// Check whether a user is logged in.
function requireLogin()
{
    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }
}

// Check whether a user has the required role.
function requireRole($requiredRole)
{
    requireLogin();

    if ($_SESSION["role"] !== $requiredRole) {

        if ($_SESSION["role"] === "admin") {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: student_dashboard.php");
        }

        exit;
    }
}

?>
