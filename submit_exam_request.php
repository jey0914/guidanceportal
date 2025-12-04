<?php
session_start();
include("db.php");

$uploadDir = "uploads/";

// Create uploads folder if it doesn't exist
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$uploadStatus = true;
$proofFilename = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fname = trim($_POST["fname"]);
    $mname = trim($_POST["mname"]);
    $lname = trim($_POST["lname"]);
    $email = trim($_POST["email"]);
    $subject = trim($_POST["subject"]);
    $teacher = trim($_POST["teacher"]);
    $reason = trim($_POST["reason"]);
    $year_level = $_POST["grade"];
    $strand_course = $_POST["section"];

    // Handle file upload
    if (isset($_FILES["proof"]) && $_FILES["proof"]["error"] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES["proof"]["tmp_name"];
        $fileName = basename($_FILES["proof"]["name"]);
        $fileType = mime_content_type($fileTmpPath);
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];

        if (in_array($fileType, $allowedTypes)) {
            $proofFilename = uniqid() . "_" . $fileName;
            $destination = $uploadDir . $proofFilename;

            if (!move_uploaded_file($fileTmpPath, $destination)) {
                $uploadStatus = false;
                $_SESSION['error'] = "Failed to upload file.";
            }
        } else {
            $uploadStatus = false;
            $_SESSION['error'] = "Invalid file type. Only JPG, PNG, and PDF allowed.";
        }
    }

    // Insert into DB
    if ($uploadStatus) {
        $stmt = $con->prepare("INSERT INTO special_exam_requests 
            (fname, mname, lname, email, subject, teacher, reason, proof_filename, year_level, strand_course, submitted_at, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'Pending')");
        
        if (!$stmt) {
            $_SESSION['error'] = "Database error: " . $con->error;
            header("Location: special_exam.php?success=0");
            exit();
        }

        $stmt->bind_param("ssssssssss", 
            $fname, $mname, $lname, $email, $subject, $teacher, $reason, $proofFilename, $year_level, $strand_course);

        if ($stmt->execute()) {
            header("Location: special_exam.php?success=1");
            exit();
        } else {
            $_SESSION['error'] = "Failed to save request: " . $stmt->error;
        }
    }

    header("Location: special_exam.php?success=0");
    exit();
} else {
    header("Location: special_exam.php");
    exit();
}
?>
