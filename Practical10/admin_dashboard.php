
<?php

require_once "auth.php";

requireRole("admin");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - StudentHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-brand">StudentHub Admin</div>

    <div class="nav-right">
        <a href="Home.html">Home</a>

        <form action="logout.php" method="POST" class="logout-form">
            <button type="submit" class="logout-button">Logout</button>
        </form>
    </div>
</nav>

<h1>Administrator Dashboard</h1>

<div class="dashboard-card">
    <h2>
        Welcome, <?= htmlspecialchars($_SESSION["name"]) ?>!
    </h2>

    <p>You have successfully logged in as an administrator.</p>

    <p>
        <strong>Email:</strong>
        <?= htmlspecialchars($_SESSION["email"]) ?>
    </p>

    <p>
        <strong>Role:</strong>
        <?= htmlspecialchars($_SESSION["role"]) ?>
    </p>

    <h3>Administration</h3>

    <ul>
        <li><a href="students.php">View Registered Students</a></li>
        <li><a href="Home.html">Open StudentHub Home</a></li>
    </ul>
</div>

</body>
</html>
