<?php
include 'db.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $sql = "DELETE FROM appointments WHERE id=?";
  $stmt = $con->prepare($sql);
  $stmt->bind_param("i", $id);
  $stmt->execute();

  header("Location: view_appointments.php?deleted=1");
  exit();
} else {
  echo "Invalid request.";
}
?>