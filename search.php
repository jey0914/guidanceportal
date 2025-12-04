<?php
include "db.php"; // connection

if (isset($_GET['query'])) {
    $query = trim($_GET['query']);
    $sql = "SELECT id, student_no, CONCAT(fname, ' ', lname) AS full_name 
            FROM form 
            WHERE student_no LIKE ? 
               OR fname LIKE ? 
               OR lname LIKE ?";

    $stmt = mysqli_prepare($con, $sql);

    if (!$stmt) {
        die("SQL error: " . mysqli_error($con));
    }

    $search = "%$query%";
    mysqli_stmt_bind_param($stmt, "sss", $search, $search, $search);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $students = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $students[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($students);
}
?>
