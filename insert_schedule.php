<?php
include("db.php");

$subject = $_POST['subject'];
$date = $_POST['exam_date'];
$time = $_POST['exam_time'];
$room = $_POST['room'];

$sql = "INSERT INTO special_exam_schedule (subject, exam_date, exam_time, room)
        VALUES (?, ?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("ssss", $subject, $date, $time, $room);
$stmt->execute();

header("Location: special_exam_view.php");
exit();
?>