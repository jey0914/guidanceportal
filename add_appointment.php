<?php
include 'db.php';
session_start();  // Don't forget this if you need to check login/session

// Sanitize inputs
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$grade = trim($_POST['grade']);
$section = trim($_POST['section']);
$date = $_POST['date'];
$time = $_POST['time'];
$specific_concern = trim($_POST['specific_concern']);
$reason = trim($_POST['reason']);
$interest = trim($_POST['interest']);

// Prepare the SQL query
$sql = "INSERT INTO appointments 
(name, email, grade, section, date, time, specific_concern, reason, interest)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $con->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sssssssss", $name, $email, $grade, $section, $date, $time, $specific_concern, $reason, $interest);
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: appointments.php?success=1");
        exit;
    } else {
        die("Execute error: " . $stmt->error);
    }
} else {
    die("Prepare failed: " . $con->error);
}
?>