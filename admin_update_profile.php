<?php
session_start();
include 'db.php'; 

$admin_id = $_SESSION['admin_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $bio = $_POST['bio'];
    $department = $_POST['department'];
    $location = $_POST['location'];

    $stmt = $con->prepare("UPDATE admin SET first_name=?, last_name=?, email=?, phone=?, bio=?, department=?, location=? WHERE id=?");
    $stmt->bind_param("sssssssi", $first, $last, $email, $phone, $bio, $department, $location, $admin_id);
    if($stmt->execute()){
        echo "success";
    } else {
        echo "error";
    }
}
?>
