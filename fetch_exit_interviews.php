<?php
include "db.php";

// Fetch all exit interviews (latest first)
$query = "SELECT * FROM exit_interviews ORDER BY id DESC";
$result = $conn->query($query);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        "id" => $row["id"],
        "student_no" => $row["student_no"],
        "full_name" => $row["full_name"],
        "email" => $row["email"],
        "year_level" => $row["year_level"],
        "strand_course" => $row["strand_course"],
        "reason" => $row["reason"],
        "preferred_date" => $row["preferred_date"],
        "preferred_time" => $row["preferred_time"],
        "scheduled_date" => $row["scheduled_date"],
        "status" => $row["status"],
        "notes" => $row["notes"],
        "created_at" => $row["created_at"]
    ];
}

header("Content-Type: application/json");
echo json_encode($data);
?>
