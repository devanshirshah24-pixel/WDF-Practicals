
<?php
require_once "auth.php";

requireLogin();

if ($_SESSION["role"] === "admin") {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eaf2ff;
            padding: 30px;
        }
        main {
            max-width: 600px;
            margin: auto;
            padding: 25px;
            background: white;
            border-radius: 10px;
        }
        a { color: #0b5ed7; }
    </style>
</head>
<body>
<main>
    <h1>Student Home</h1>
    <p>Welcome, <?= htmlspecialchars($_SESSION["name"], ENT_QUOTES, "UTF-8") ?>.</p>
    <p>Your role is <strong>student</strong>.</p>
    <p>You can view your student home page, but you cannot access the admin dashboard.</p>
    <a href="logout.php">Logout</a>
</main>
</body>
</html>
