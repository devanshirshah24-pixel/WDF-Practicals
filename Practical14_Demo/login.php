
<?php
session_start();
require_once "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === "") {
        $error = "Enter a valid email and password.";
    } else {
        $stmt = $conn->prepare(
            "SELECT id, name, email, password, role
             FROM demo_users WHERE email = ?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($password, $user["password"])) {
            session_regenerate_id(true);

            $_SESSION["user_id"] = (int)$user["id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["role"] = $user["role"];

            $id = (int)$user["id"];
            $action = "LOGIN";
            $details = "User logged in";

            $log = $conn->prepare(
                "INSERT INTO pr14_audit_logs (user_id, action, details)
                 VALUES (?, ?, ?)"
            );
            $log->bind_param("iss", $id, $action, $details);
            $log->execute();

            if ($user["role"] === "admin") {
                header("Location: index.php");
            } else {
                header("Location: student.php");
            }
            exit;
        }

        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eaf2ff;
            padding: 40px 15px;
        }
        .login {
            max-width: 380px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }
        input, button {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            margin: 8px 0;
        }
        button {
            color: white;
            background: #0b5ed7;
            border: 0;
            cursor: pointer;
        }
        .error { color: #b02a37; }
    </style>
</head>
<body>
<div class="login">
    <center><h2>Login</h2></center>

    <?php if ($error !== ""): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>
