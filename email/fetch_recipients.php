<?php
include 'db.php';

$category = $_GET['category'] ?? '';
$emails = [];

switch ($category) {
    case 'all_students':
        $query = "SELECT email FROM form WHERE strand_course IS NOT NULL";
        break;
    case 'grade_11':
        $query = "SELECT email FROM form WHERE year_level = '11'";
        break;
    case 'grade_12':
        $query = "SELECT email FROM form WHERE year_level = '12'";
        break;
    case 'bsa':
        $query = "SELECT email FROM form WHERE strand_course LIKE '%BSA%'";
        break;
    case 'bsit':
        $query = "SELECT email FROM form WHERE strand_course LIKE '%BSIT%'";
        break;
    default:
        $query = "";
}

if ($query) {
    $result = mysqli_query($con, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $emails[] = $row['email'];
    }
}

header('Content-Type: application/json');
echo json_encode($emails);
?>
