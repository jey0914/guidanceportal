<?php
require('fpdf.php');
include('db.php');

// Query all counseling history records
$query = "
    SELECT 
        ch.id, ch.student_no, ch.email, ch.counselor, ch.nature, ch.status, 
        ch.interview_date, ch.time_started, ch.time_ended, ch.remarks,
        f.fname, f.mname, f.lname
    FROM counseling_history ch
    JOIN form f ON ch.email = f.email
    ORDER BY ch.interview_date DESC
";

$result = $con->query($query);

if ($result->num_rows == 0) {
    die("No counseling history records found.");
}

// Create PDF
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Counseling History Report', 0, 1, 'C');
$pdf->Ln(5);

// Table header
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(10, 8, '#', 1);
$pdf->Cell(35, 8, 'Student No', 1);
$pdf->Cell(50, 8, 'Student Name', 1);
$pdf->Cell(40, 8, 'Counselor', 1);
$pdf->Cell(35, 8, 'Date', 1);
$pdf->Cell(30, 8, 'Status', 1);
$pdf->Cell(60, 8, 'Nature of Counseling', 1);
$pdf->Ln();

// Table data
$pdf->SetFont('Arial', '', 10);
$count = 1;
while ($row = $result->fetch_assoc()) {
    $student_name = $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname'];

    $pdf->Cell(10, 8, $count++, 1);
    $pdf->Cell(35, 8, $row['student_no'], 1);
    $pdf->Cell(50, 8, $student_name, 1);
    $pdf->Cell(40, 8, $row['counselor'], 1);
    $pdf->Cell(35, 8, $row['interview_date'], 1);
    $pdf->Cell(30, 8, $row['status'], 1);
    $pdf->Cell(60, 8, $row['nature'], 1);
    $pdf->Ln();
}

$pdf->Output('I', 'Counseling_History.pdf');
?>
