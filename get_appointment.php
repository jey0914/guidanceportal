<?php
include 'db_connect.php'; 

$sql = "SELECT * FROM appointments ORDER BY date, time";
$result = $con->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
    echo "<td>" . htmlspecialchars($row['date']) . "</td>";
    echo "<td>" . htmlspecialchars($row['time']) . "</td>";
    echo "<td>" . htmlspecialchars($row['grade']) . "</td>";
    echo "<td>" . htmlspecialchars($row['section']) . "</td>";
    echo "<td>" . htmlspecialchars($row['interest']) . "</td>";
    echo "<td>" . htmlspecialchars($row['reason']) . "</td>";
    echo "<td><a href='delete_appointment.php?id=" . $row['id'] . "'>Delete</a></td>";
    echo "</tr>";
  }
} else {
  echo "<tr><td colspan='9'>No appointments found</td></tr>";
}
?>
