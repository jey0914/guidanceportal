<?php
session_start();
include("db.php");

if (!isset($_SESSION['parent_email'])) {
    header("Location: parent_login.php");
    exit();
}

$parent_email = $_SESSION['parent_email'];

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $con->prepare("DELETE FROM parent_appointments WHERE id = ? AND email = ?");
    $stmt->bind_param("is", $id, $parent_email);
    $stmt->execute();

    header("Location: view_parent_appointments.php?deleted=1");
    exit();
} else {
    header("Location: view_parent_appointments.php");
    exit();
}
?>
