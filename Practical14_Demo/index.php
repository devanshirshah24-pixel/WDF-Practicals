
<?php
require_once "db.php";
require_once "auth.php";

requireAdmin();

logAction($conn, "VIEW_DASHBOARD", "Admin opened dashboard");

function countRows(mysqli $conn, string $table): int {
    // Table names are fixed constants defined below, never user input.
    $allowed = [
        "demo_users",
        "demo_students",
        "demo_events",
        "demo_registrations"
    ];

    if (!in_array($table, $allowed, true)) {
        throw new InvalidArgumentException("Invalid table.");
    }

    $result = $conn->query("SELECT COUNT(*) AS total FROM `$table`");
    return (int)$result->fetch_assoc()["total"];
}

$userCount = countRows($conn, "demo_users");
$studentCount = countRows($conn, "demo_students");
$eventCount = countRows($conn, "demo_events");
$registrationCount = countRows($conn, "demo_registrations");

$registrations = $conn->query(
    "SELECT id, student_name, event_title, registered_at
     FROM demo_registrations
     ORDER BY registered_at DESC, id DESC
     LIMIT 10"
);

$logs = $conn->query(
    "SELECT l.action, l.details, l.created_at, u.name
     FROM pr14_audit_logs l
     LEFT JOIN demo_users u ON l.user_id = u.id
     ORDER BY l.id DESC
     LIMIT 10"
);

function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - Practical 14</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f2f5fa;
            color: #222;
        }

        header {
            background: #0b5ed7;
            color: white;
            padding: 18px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        nav {
            background: #fff;
            padding: 15px 25px;
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
        }

        nav a {
            color: #0b5ed7;
            text-decoration: none;
            font-weight: bold;
        }

        main { padding: 25px; }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, minmax(150px, 1fr));
            gap: 16px;
        }

        .card, section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px #0000000d;
        }

        .card h3 { margin-top: 0; color: #555; }
        .number { font-size: 30px; color: #0b5ed7; }

        section { margin-top: 25px; }

        .table-wrap { overflow-x: auto; }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 12px;
        }

        th, td {
            padding: 11px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th { background: #e3f2fd; }

        .logout {
            background: white;
            color: #0b5ed7;
            padding: 9px 14px;
            text-decoration: none;
            border-radius: 5px;
        }

        @media (max-width: 800px) {
            .cards { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 480px) {
            main { padding: 12px; }
            .cards { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<header>
    <div>
        <h2>Admin Dashboard</h2>
        <p>Welcome, <?= e($_SESSION["name"]) ?> |
           Role: <?= e($_SESSION["role"]) ?></p>
    </div>
    <a class="logout" href="logout.php">Logout</a>
</header>

<nav>
    <?php if ($_SESSION["role"] === "admin"): ?>
        <a href="#dashboard">Dashboard</a>
        <a href="#students">Students</a>
        <a href="#events">Events</a>
        <a href="#registrations">Registrations</a>
        <a href="#audit">Audit Log</a>
    <?php endif; ?>
</nav>

<main>
    <section id="dashboard">
        <h2>System Statistics</h2>

        <div class="cards">
            <div class="card">
                <h3>Total Users</h3>
                <div class="number"><?= $userCount ?></div>
            </div>

            <div class="card" id="students">
                <h3>Total Students</h3>
                <div class="number"><?= $studentCount ?></div>
            </div>

            <div class="card" id="events">
                <h3>Total Events</h3>
                <div class="number"><?= $eventCount ?></div>
            </div>

            <div class="card" id="registrations">
                <h3>Total Registrations</h3>
                <div class="number"><?= $registrationCount ?></div>
            </div>
        </div>
    </section>

    <section>
        <h2>Latest Registrations</h2>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Event</th>
                    <th>Registered At</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $registrations->fetch_assoc()): ?>
                    <tr>
                        <td><?= e($row["id"]) ?></td>
                        <td><?= e($row["student_name"]) ?></td>
                        <td><?= e($row["event_title"]) ?></td>
                        <td><?= e($row["registered_at"]) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section id="audit">
        <h2>Recent Audit Logs</h2>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Administrator</th>
                    <th>Action</th>
                    <th>Details</th>
                    <th>Date and Time</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $logs->fetch_assoc()): ?>
                    <tr>
                        <td><?= e($row["name"] ?? "Unknown user") ?></td>
                        <td><?= e($row["action"]) ?></td>
                        <td><?= e($row["details"]) ?></td>
                        <td><?= e($row["created_at"]) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

</body>
</html>
