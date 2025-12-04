<?php
session_start();
include("db.php");

if (!isset($_SESSION['parent_email'])) {
    header("Location: parent_login.php");
    exit();
}

$email = $_SESSION['parent_email'];

// Get form data
$currentPassword = $_POST['current_password'] ?? '';
$newPassword     = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

// Empty field check
if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
    header("Location: parent_settings.php?security=empty");
    exit();
}

// Check if new passwords match
if ($newPassword !== $confirmPassword) {
    header("Location: parent_settings.php?security=nomatch");
    exit();
}

// Fetch current password hash
$stmt = $con->prepare("SELECT password FROM parents WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    header("Location: parent_settings.php?security=error");
    exit();
}

$stmt->bind_result($hashedPassword);
$stmt->fetch();

// Verify current password
if (!password_verify($currentPassword, $hashedPassword)) {
    header("Location: parent_settings.php?security=wrongcurrent");
    exit();
}

// Hash the new password
$newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// Update password
$update = $con->prepare("UPDATE parents SET password = ? WHERE email = ?");
$update->bind_param("ss", $newHashedPassword, $email);

if ($update->execute()) {
    header("Location: parent_settings.php?security=success");
    exit();
} else {
    header("Location: parent_settings.php?security=error");
    exit();
}
?>
