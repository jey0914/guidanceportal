<?php
session_start();
include("db.php");

if (!isset($_SESSION['parent_email'])) {
    header("Location: parent_login.php");
    exit();
}

$currentEmail = $_SESSION['parent_email'];

// Get new data from form
$newEmail = $_POST['email'] ?? $currentEmail;
$newContact = $_POST['contact'] ?? null; // <- match sa form name

// Prepare SQL to update only email and contact
$stmt = $con->prepare("UPDATE parents SET email = ?, contact = ? WHERE email = ?");
$stmt->bind_param("sss", $newEmail, $newContact, $currentEmail);

if ($stmt->execute()) {
    // Update session email if it changed
    if ($newEmail !== $currentEmail) {
        $_SESSION['parent_email'] = $newEmail;
    }
    header("Location: parent_profile.php?update=success");
    exit();
} else {
    echo "Error updating profile: " . $stmt->error;
}
?>
