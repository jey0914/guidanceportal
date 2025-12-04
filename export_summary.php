<?php
require('fpdf.php');
include('db.php');

if (!isset($_GET['id'])) {
    die("No session ID provided.");
}

$session_id = intval($_GET['id']);

// Fetch counseling session details
$stmt = $con->prepare("
    SELECT 
        ch.student_no, ch.email, ch.counselor, ch.nature, ch.status, 
        ch.interview_date, ch.time_started, ch.time_ended, 
        ch.year_level, ch.strand_course, ch.remarks,
        f.fname, f.mname, f.lname
    FROM counseling_history ch
    JOIN form f ON ch.email = f.email
    WHERE ch.id = ?
    LIMIT 1
");
$stmt->bind_param("i", $session_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("No record found.");
}

$data = $result->fetch_assoc();

// Format student name
$student_name = $data['fname'] . ' ' . $data['mname'] . ' ' . $data['lname'];

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();

// Header
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Counseling Session Summary', 0, 1, 'C');
$pdf->Ln(5);

// Student Info
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Student Information', 0, 1);

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(50, 8, 'Student No:', 0, 0);
$pdf->Cell(100, 8, $data['student_no'], 0, 1);
$pdf->Cell(50, 8, 'Name:', 0, 0);
$pdf->Cell(100, 8, $student_name, 0, 1);
$pdf->Cell(50, 8, 'Year Level:', 0, 0);
$pdf->Cell(100, 8, $data['year_level'], 0, 1);
$pdf->Cell(50, 8, 'Strand / Course:', 0, 0);
$pdf->Cell(100, 8, $data['strand_course'], 0, 1);
$pdf->Ln(5);

// Session Details
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Session Details', 0, 1);

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(50, 8, 'Counselor:', 0, 0);
$pdf->Cell(100, 8, $data['counselor'], 0, 1);
$pdf->Cell(50, 8, 'Nature of Counseling:', 0, 0);
$pdf->Cell(100, 8, $data['nature'], 0, 1);
$pdf->Cell(50, 8, 'Status:', 0, 0);
$pdf->Cell(100, 8, $data['status'], 0, 1);
$pdf->Cell(50, 8, 'Interview Date:', 0, 0);
$pdf->Cell(100, 8, $data['interview_date'], 0, 1);
$pdf->Cell(50, 8, 'Time Started:', 0, 0);
$pdf->Cell(100, 8, $data['time_started'], 0, 1);
$pdf->Cell(50, 8, 'Time Ended:', 0, 0);
$pdf->Cell(100, 8, $data['time_ended'], 0, 1);
$pdf->Ln(5);

// Remarks
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Counselor Remarks', 0, 1);

$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(0, 8, $data['remarks'] ? $data['remarks'] : 'No remarks provided.');
$pdf->Ln(10);

// Footer Note
$pdf->SetFont('Arial', 'I', 9);
$pdf->MultiCell(0, 6, "Note: This document is confidential and intended only for authorized use by the student and the Guidance Office.", 0, 'C');

$pdf->Output('I', 'Counseling_Summary.pdf');
?>
