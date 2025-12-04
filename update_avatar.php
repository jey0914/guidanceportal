<?php
session_start();
include("db.php");

if (!isset($_SESSION['email']) || !isset($_POST['avatar'])) {
    header("Location: profile.php");
    exit();
}

$avatar = $_POST['avatar'];
$email = $_SESSION['email'];

$stmt = $con->prepare("UPDATE form SET avatar_choice = ? WHERE email = ?");
$stmt->bind_param("ss", $avatar, $email);
$stmt->execute();

header("Location: profile.php");
exit();