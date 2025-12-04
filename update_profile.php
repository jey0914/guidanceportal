<?php
session_start();
include("db.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$about = $_POST['about'] ?? '';
$avatar_choice = $_POST['avatar_choice'] ?? 'default_avatar.png';

// Update the record
$stmt = $con->prepare("UPDATE form SET about = ?, avatar_choice = ? WHERE email = ?");
$stmt->bind_param("sss", $about, $avatar_choice, $email);
$stmt->execute();

header("Location: profile.php");
exit();
?>
