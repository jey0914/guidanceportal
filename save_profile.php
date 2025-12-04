<?php
session_start();
include("db.php"); // <-- make sure this file creates $con

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

// Get data from POST
$firstName  = $_POST['firstName'] ?? '';
$lastName   = $_POST['lastName'] ?? '';
$email      = $_POST['emailInput'] ?? '';
$phone      = $_POST['phoneInput'] ?? '';
$bio        = $_POST['bio'] ?? '';
$department = $_POST['department'] ?? '';
$location   = $_POST['location'] ?? '';

$userEmail = $_SESSION['email']; // currently logged-in user

// Update admin info in DB
$stmt = $con->prepare("UPDATE admins SET fname=?, lname=?, email=?, phone=?, bio=?, department=?, location=? WHERE email=?");
$stmt->bind_param("ssssssss", $firstName, $lastName, $email, $phone, $bio, $department, $location, $userEmail);

if($stmt->execute()){
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully!']);
} else {
    // For debugging: uncomment this line to see the DB error
    // echo json_encode(['success' => false, 'message' => $stmt->error]);
    echo json_encode(['success' => false, 'message' => 'Failed to update profile.']);
}
?>
