<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

include("db.php");

$category = isset($_GET['category']) ? $_GET['category'] : '';
$stmt = $con->prepare("SELECT * FROM appointments WHERE interest = ? ORDER BY date ASC, time ASC");
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($category) ?> Appointments</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .content { padding: 30px; }
    table { margin-top: 20px; }
    th, td { text-align: center; }
  </style>
</head>
<body>
  <div class="content">
    <h2><?= htmlspecialchars($category) ?> Appointments</h2>
    <a href="admin_appointments.php" class="btn btn-secondary mb-3">⬅ Back to Categories</a>

    <?php if ($result->num_rows > 0): ?>
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>Full Name</th>
            <th>Email</th>
            <th>Grade/Year</th>
            <th>Strand/Course</th>
            <th>Date</th>
            <th>Time</th>
            <th>Reason</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['grade']) ?></td>
            <td><?= htmlspecialchars($row['section']) ?></td>
            <td><?= htmlspecialchars($row['date']) ?></td>
            <td><?= htmlspecialchars($row['time']) ?></td>
            <td><?= htmlspecialchars($row['reason']) ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No appointments found for this category.</p>
    <?php endif; ?>
  </div>
</body>
</html>