<?php
include "db.php";

$id = $_POST['id'];
$status = $_POST['status'];
$scheduled_date = $_POST['scheduled_date'] ?? null;
$scheduled_time = $_POST['scheduled_time'] ?? null;
$notes = $_POST['notes'] ?? null;

$query = "UPDATE exit_interviews 
          SET status=?, scheduled_date=?, notes=? 
          WHERE id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssi", $status, $scheduled_date, $notes, $id);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}
?>
