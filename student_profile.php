<?php
include "db.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT *, CONCAT(fname, ' ', lname) AS full_name FROM form WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $student = mysqli_fetch_assoc($result);
}
?>

<?php if (!empty($student)): ?>
    <h2><?= htmlspecialchars($student['full_name']) ?></h2>
    <p>Student No: <?= htmlspecialchars($student['student_no']) ?></p>
    <p>Email: <?= htmlspecialchars($student['email']) ?></p>
    <p>Course/Strand: <?= htmlspecialchars($student['strand_course']) ?></p>
<?php else: ?>
    <p>No student found.</p>
<?php endif; ?>
