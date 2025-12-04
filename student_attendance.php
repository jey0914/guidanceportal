<?php
session_start();
include("db.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Get student info
$stmt = $con->prepare("SELECT fname, strand_course, student_no FROM form WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$fname = $user['fname'];
$strand = $user['strand_course'];
$student_no = $user['student_no'];

// Show only for SHS
$year_level = $_SESSION['year_level'] ?? '';

if (stripos($year_level, 'Grade') === false) {
    echo "<h3 style='padding:40px;'>Attendance summary is only available for SHS students.</h3>";
    exit();
}

// Fetch attendance records
$attendance_q = $con->prepare("SELECT date, subject, time_in, time_out, status, remarks FROM attendance_logs WHERE student_no = ? ORDER BY date DESC");
$attendance_q->bind_param("s", $student_no);
$attendance_q->execute();
$records = $attendance_q->get_result();

$monthly_summary = [];
while ($row = $records->fetch_assoc()) {
    $month = date("F Y", strtotime($row['date']));
    $monthly_summary[$month][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Attendance Summary</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
      padding: 40px;
    }
    h2 {
      color: #1d3557;
      margin-bottom: 20px;
    }
    .month-header {
      background-color: #1d3557;
      color: white;
      padding: 10px;
      border-radius: 6px;
      margin-top: 30px;
    }
    .table {
      margin-top: 15px;
      background: white;
      border-radius: 6px;
    }
    .badge {
      font-size: 0.9em;
    }
  </style>
</head>
<body>

  <h2>🕒 Attendance Summary for <?= htmlspecialchars($fname); ?></h2>

  <?php if (!empty($monthly_summary)): ?>
    <?php foreach ($monthly_summary as $month => $entries): ?>
      <div class="month-header"><?= $month ?></div>
      <table class="table table-bordered table-striped">
        <thead class="table-light">
          <tr>
            <th>Date</th>
            <th>Subject</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Status</th>
            <th>Remarks</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $count = ['Present' => 0, 'Late' => 0, 'Absent' => 0];
            foreach ($entries as $entry):
              $count[$entry['status']]++;
          ?>
          <tr>
            <td><?= htmlspecialchars($entry['date']) ?></td>
            <td><?= htmlspecialchars($entry['subject']) ?></td>
            <td><?= htmlspecialchars($entry['time_in']) ?></td>
            <td><?= htmlspecialchars($entry['time_out']) ?></td>
            <td>
              <span class="badge bg-<?= 
                $entry['status'] == 'Present' ? 'success' : 
                ($entry['status'] == 'Late' ? 'warning' : 'danger') ?>">
                <?= htmlspecialchars($entry['status']) ?>
              </span>
            </td>
            <td><?= htmlspecialchars($entry['remarks']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="mb-4">
        <strong>Total:</strong>
        ✅ Present: <?= $count['Present'] ?> |
        ⚠️ Late: <?= $count['Late'] ?> |
        ❌ Absent: <?= $count['Absent'] ?>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No attendance records found.</p>
  <?php endif; ?>

</body>
</html>