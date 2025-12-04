<?php
session_start();
include("db.php");

if (!isset($_SESSION['admin_email'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'No data received']);
    exit();
}

// Sanitize inputs
$student_id = $con->real_escape_string($data['studentId']);
$date = $con->real_escape_string($data['date']);
$time = $con->real_escape_string($data['time']);
$type = $con->real_escape_string($data['type']);
$location = $con->real_escape_string($data['location']);
$description = $con->real_escape_string($data['description']);
$witnesses = $con->real_escape_string($data['witnesses']);
$action = $con->real_escape_string($data['action']);
$status = $con->real_escape_string($data['status']);

// Get parent's email for the student
$parentEmail = '';
$stmtParent = $con->prepare("SELECT parent_email FROM students WHERE student_no = ? LIMIT 1");
$stmtParent->bind_param("s", $student_id);
$stmtParent->execute();
$resultParent = $stmtParent->get_result();
if ($row = $resultParent->fetch_assoc()) {
    $parentEmail = $row['parent_email'];
}

// Insert into student_incident_reports
$stmt = $con->prepare("INSERT INTO student_incident_reports (student_no, date_reported, time_reported, incident_type, location, description, witnesses, action_taken, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssss", $student_id, $date, $time, $type, $location, $description, $witnesses, $action, $status);

if ($stmt->execute()) {
    $insertId = $stmt->insert_id;

    // Also insert into parent view table if using separate table OR just notify parent
    if ($parentEmail) {
        $stmtParentReport = $con->prepare("INSERT INTO parent_incident_reports (student_no, parent_email, date_reported, time_reported, incident_type, location, description, witnesses, action_taken, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtParentReport->bind_param("ssssssssss", $student_id, $parentEmail, $date, $time, $type, $location, $description, $witnesses, $action, $status);
        $stmtParentReport->execute();
    }

    echo json_encode(['status' => 'success', 'message' => 'Report submitted successfully', 'id' => $insertId]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to submit report']);
}
?>
