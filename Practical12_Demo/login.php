
<?php
session_start();

if (!empty($_SESSION["admin_logged_in"])) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";

    // Demo credentials only; replace with database-backed authentication
    // and password_hash/password_verify in a real application.
    if ($username === "admin" && $password === "Demo@123") {
        session_regenerate_id(true);
        $_SESSION["admin_logged_in"] = true;
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));

        header("Location: index.php");
        exit;
    }

    $error = "Invalid username or password.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eaf2ff;
            padding: 40px 15px;
        }
        .box {
            max-width: 380px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }
        input, button {
            width: 100%;
            padding: 11px;
            margin: 8px 0;
            box-sizing: border-box;
        }
        button {
            background: #0b5ed7;
            color: white;
            border: 0;
            cursor: pointer;
        }
        .error { color: #b02a37; }
    </style>
</head>
<body>
<div class="box">
    <h2>Event Admin Login</h2>

    <?php if ($error !== ""): ?>
        <p class="error">
            <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
        </p>
    <?php endif; ?>

    <form method="post">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <p><small>Demo username: admin</small></p>
    <p><small>Demo password: Demo@123</small></p>
</div>
</body>
</html>
