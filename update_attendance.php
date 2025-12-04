<?php
include("db.php");

$rfid = $_POST['rfid'] ?? '';

if ($rfid == '') {
    echo "NO RFID";
    exit;
}

// 1. Find student number using RFID
$sql = "SELECT student_no FROM rfid_map WHERE rfid_no = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $rfid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "CARD NOT REGISTERED";
    exit;
}

$row = $result->fetch_assoc();
$student_no = $row['student_no'];

// 2. Get student data from register table
$sql2 = "SELECT * FROM register WHERE student_no = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("s", $student_no);
$stmt2->execute();
$res2 = $stmt2->get_result();

if ($res2->num_rows == 0) {
    echo "NO STUDENT FOUND";
    exit;
}

$student = $res2->fetch_assoc();
$full_name = $student['fname'] . ' ' . ($student['mname'] ?? '') . ' ' . $student['lname'];
$section = $student['strand_course'] ?? 'N/A';

// 3. Insert attendance (TIME IN only)
$now_date = date("Y-m-d");
$now_time = date("H:i:s");

$sql3 = "INSERT INTO attendance_logs (student_no, name, section, date, subject, time_in, status)
         VALUES (?, ?, ?, ?, 'Advisory', ?, 'Present')";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("sssss", $student_no, $full_name, $section, $now_date, $now_time);
$stmt3->execute();

echo "OK";
?>
