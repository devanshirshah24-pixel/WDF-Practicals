<?php
require_once "db.php";

$sql = "SELECT * FROM students";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Students</title>
</head>

<body>

<h1>Student List</h1>

<table border="1" cellpadding="10">

<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Gender</th>
</tr>

<?php

while ($row = mysqli_fetch_assoc($result)) {

    echo "<tr>";

    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
    echo "<td>" . htmlspecialchars($row['gender']) . "</td>";

    echo "</tr>";
}

?>

</table>

</body>
</html>