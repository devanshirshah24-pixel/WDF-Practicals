<?php
require_once "db.php";

$sql = "SELECT * FROM events";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>StudentHub Events</title>
</head>

<body>

<h1>StudentHub Events</h1>

<?php
if (mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<h2>" . htmlspecialchars($row['event_name']) . "</h2>";
        echo "<p>Date: " . htmlspecialchars($row['event_date']) . "</p>";
        echo "<p>" . htmlspecialchars($row['description']) . "</p>";
        echo "<hr>";
    }

} else {
    echo "<p>No events available.</p>";
}
?>

</body>
</html>