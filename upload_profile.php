<?php
session_start();
include("db.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
    $email = $_SESSION['email'];
    $target_dir = "uploads/";
    $file_name = basename($_FILES["profile_picture"]["name"]);
    $target_file = $target_dir . time() . "_" . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // ✅ Allow only image files
    $allowed_types = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_types)) {
        die("Error: Only JPG, JPEG, PNG & GIF files are allowed.");
    }

    // ✅ Create uploads directory if missing
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // ✅ Upload and update database
    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        $stmt = $con->prepare("UPDATE form SET avatar_choice = ? WHERE email = ?");
        $stmt->bind_param("ss", $target_file, $email);
        if ($stmt->execute()) {
            header("Location: profile.php?upload=success");
            exit();
        } else {
            echo "Database update failed.";
        }
    } else {
        echo "Failed to upload file.";
    }
} else {
    echo "No file uploaded.";
}
?>
