
<?php

require_once "auth.php";

requireRole("student");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - StudentHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-brand">StudentHub</div>

    <div class="nav-right">
        <a href="Home.html">Home</a>

        <form action="logout.php" method="POST" class="logout-form">
            <button type="submit" class="logout-button">Logout</button>
        </form>
    </div>
</nav>

<h1>Student Dashboard</h1>

<div class="dashboard-card">
    <h2>Welcome, <?= htmlspecialchars($_SESSION["name"]) ?>!</h2>

    <p>You have successfully logged in as a student.</p>

    <p>
        <strong>Email:</strong>
        <?= htmlspecialchars($_SESSION["email"]) ?>
    </p>

    <p>
        <strong>Role:</strong>
        <?= htmlspecialchars($_SESSION["role"]) ?>
    </p>

    <h3>Student Services</h3>

    <ul>
        <li><a href="Courses.html">View Courses</a></li>
        <li><a href="Assignment.html">View Assignments</a></li>
        <li><a href="Attendance.html">View Attendance</a></li>
        <li><a href="Result.html">View Results</a></li>
        <li><a href="Settings.html">Settings</a></li>
    </ul>
</div>

</body>
</html>
