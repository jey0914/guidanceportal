<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['appointment_id'];
    $status = $_POST['status'];
    $admin_message = $_POST['admin_message'];

    $stmt = $con->prepare("UPDATE appointments SET status = ?, admin_message = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $admin_message, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: consultation.php?updated=1");
    exit();
}
?>