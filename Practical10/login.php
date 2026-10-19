
<?php

require_once "session.php";
require_once "db.php";

// If already logged in, open the correct dashboard.
if (isset($_SESSION["user_id"])) {

    if ($_SESSION["role"] === "admin") {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: student_dashboard.php");
    }

    exit;
}

$message = "";

if (isset($_GET["timeout"])) {
    $message = "Your session expired. Please log in again.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {

        $message = "Please enter your email and password.";

    } else {

        $sql = "SELECT id, name, email, password, role
                FROM students
                WHERE email = ?
                LIMIT 1";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user["password"])) {

            // Generate a new session ID after successful login.
            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["role"] = $user["role"];
            $_SESSION["last_activity"] = time();

            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            // Redirect according to role.
            if ($user["role"] === "admin") {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: student_dashboard.php");
            }

            exit;

        } else {

            // Do not reveal whether the email exists.
            $message = "Invalid email or password.";
        }

        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Login - StudentHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-brand">StudentHub</div>
    <div class="nav-right">
        <a href="Home.html">Home</a>
    </div>
</nav>

<h1>Welcome to StudentHub</h1>

<fieldset>
    <h3>Login Page</h3>

    <form action="login.php" method="POST">

        <label for="email">Email:</label>
        <input type="email"
               id="email"
               name="email"
               placeholder="Enter your registered email"
               autocomplete="username"
               required>

        <label for="password">Password:</label>
        <input type="password"
               id="password"
               name="password"
               placeholder="Enter your password"
               autocomplete="current-password"
               required>

        <input type="submit" value="Login">

        <?php if ($message !== ""): ?>
            <p class="auth-message">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php endif; ?>

    </form>

    <p>Don't have an account?
        <a href="Sign%20up.php">Sign Up</a>
    </p>
</fieldset>

</body>
</html>
