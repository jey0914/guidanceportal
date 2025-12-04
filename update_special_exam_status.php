<?php
session_start();
include("db.php");

if (!isset($_SESSION['admin_email'])) {
    exit('Unauthorized');
}

$id = $_POST['id'] ?? null;
$status = $_POST['status'] ?? null;

if ($id && $status) {
    if ($status === 'Approved') {
        $request = $con->query("SELECT * FROM special_exam_requests WHERE id=$id")->fetch_assoc();
        if ($request && $request['status'] === 'Pending') {
            $full_name = trim($request['fname'] . ' ' . ($request['mname'] ? $request['mname'] . ' ' : '') . $request['lname']);
            $stmt = $con->prepare("INSERT INTO approved_special_exam (full_name,email,subject,reason,proof_filename,submitted_at,year_level,strand_course,teacher,status) VALUES (?,?,?,?,?,?,?,?,?,'Approved')");
            $stmt->bind_param("sssssssss", $full_name, $request['email'], $request['subject'], $request['reason'], $request['proof_filename'], $request['submitted_at'], $request['year_level'], $request['strand_course'], $request['teacher']);
            $stmt->execute();

            $con->query("UPDATE special_exam_requests SET status='Approved' WHERE id=$id");
            echo 'Approved successfully';
        } else {
            echo 'Already processed';
        }
    } elseif ($status === 'Rejected') {
        $con->query("UPDATE special_exam_requests SET status='Rejected' WHERE id=$id");
        echo 'Rejected successfully';
    }
} else {
    echo 'Invalid request';
}
