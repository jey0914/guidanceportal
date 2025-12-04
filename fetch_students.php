<?php
include "db.php";

header("Content-Type: application/json");

$query = "SELECT student_no, fname, mname, lname FROM form ORDER BY lname ASC";
$result = $con->query($query);

$students = [];

while ($row = $result->fetch_assoc()) {
    $fullName = trim($row['fname'] . ' ' . ($row['mname'] ? $row['mname'][0] . '. ' : '') . $row['lname']);
    $students[] = [
        'student_no' => $row['student_no'],
        'full_name' => $fullName
    ];
}

echo json_encode($students);
?>
