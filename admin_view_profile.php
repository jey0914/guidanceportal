<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

include("db.php");

if (!isset($_GET['id'])) {
    echo "Invalid student.";
    exit();
}

$student_no = $_GET['id'];
$stmt = $con->prepare("SELECT * FROM form WHERE student_no = ?");
$stmt->bind_param("s", $student_no);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    echo "Student not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Student Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
    <a href="add_student.php" class="btn btn-secondary mb-3">← Back</a>
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Student Profile: <?= htmlspecialchars($student['fname'] . ' ' . $student['lname']) ?></h4>
        </div>
        <div class="card-body">
            <p><strong>Student No:</strong> <?= $student['student_no'] ?></p>
            <p><strong>Email:</strong> <?= $student['email'] ?></p>
            <p><strong>Birthday:</strong> <?= $student['bday'] ?></p>
            <p><strong>Year Level:</strong> <?= $student['year_level'] ?></p>
            <p><strong>Strand/Course:</strong> <?= $student['strand_course'] ?></p>
            <?php if (!empty($student['last_login'])): ?>
                <p><strong>Last Login:</strong> <?= date("F d, Y h:i A", strtotime($student['last_login'])) ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>