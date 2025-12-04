<?php
session_start();
include("db.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Check if "about" field is sent
if (isset($_POST['about'])) {
    $about = trim($_POST['about']);

    // Sanitize input
    $about = htmlspecialchars($about, ENT_QUOTES, 'UTF-8');

    // Update in database
    $query = $con->prepare("UPDATE form SET about = ? WHERE email = ?");
    $query->bind_param("ss", $about, $email);

    if ($query->execute()) {
        // ✅ Redirect to profile.php after successful update
        header("Location: profile.php?updated=1");
        exit();
    } else {
        // ❌ Redirect with error message
        header("Location: profile.php?error=1");
        exit();
    }

    $query->close();
} else {
    header("Location: profile.php?error=2");
    exit();
}

$con->close();
?>
