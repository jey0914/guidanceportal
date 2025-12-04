<?php
session_start();
include("db.php");

if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$session_email = $_SESSION['email'];

// Get student info from the form table
$stmt = $con->prepare("SELECT fname, mname, lname FROM form WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $session_email);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    echo json_encode(['status' => 'error', 'message' => 'Student not found']);
    exit;
}

// Build full name
$full_name = trim($student['fname'] . ' ' . ($student['mname'] ? $student['mname'] . ' ' : '') . $student['lname']);

// Get POST data
$student_no = $_POST['student_no'] ?? '';
$year_level = $_POST['year_level'] ?? '';
$strand_course = $_POST['strand_course'] ?? '';
$exit_reason = $_POST['exit_reason'] ?? '';
$other_reason = $_POST['other_reason'] ?? '';
$preferred_date = $_POST['preferred_date'] ?? '';
$preferred_time = $_POST['preferred_time'] ?? '';
$comments = $_POST['comments'] ?? '';

// Determine reason
$reason = ($exit_reason === 'Others') ? $other_reason : $exit_reason;

// ✅ Insert into exit_interviews table (matches your actual DB structure)
$stmt = $con->prepare("INSERT INTO exit_interviews 
    (student_no, full_name, email, year_level, strand_course, reason, preferred_date, preferred_time, status, notes, created_at) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'For Scheduling', ?, NOW())");

$stmt->bind_param(
    "sssssssss",
    $student_no,
    $full_name,
    $session_email,
    $year_level,
    $strand_course,
    $reason,
    $preferred_date,
    $preferred_time,
    $comments
);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Exit interview submitted successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to submit interview: ' . $stmt->error]);
}
?>
