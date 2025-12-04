<?php
include 'db.php';

$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$date = $_POST['date'];
$time = $_POST['time'];
$grade = $_POST['grade'];
$section = $_POST['section'];
$interest = $_POST['interest'];
$reason = $_POST['reason'];

$sql = "UPDATE appointments SET name=?, email=?, grade=?, section=?, date=?, time=?, interest=?, reason=? WHERE id=?";
$stmt = $con->prepare($sql);
$stmt->bind_param("ssssssssi", $name, $email, $grade, $section, $date, $time, $interest, $reason, $id);
$stmt->execute();

header("Location: view_appointments.php?updated=1");
exit;
?>