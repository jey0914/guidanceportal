<?php
include 'db.php';

$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['add_counseling'])) {
        $stmt = $con->prepare("INSERT INTO counseling_history (email, date, type, status, counselor) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $_POST['email'], $_POST['date'], $_POST['type'], $_POST['status'], $_POST['counselor']);
        $success = $stmt->execute() ? "Counseling report added." : "Error: " . $stmt->error;

    } elseif (isset($_POST['add_incident'])) {
        $stmt = $con->prepare("INSERT INTO incident_reports (student_email, report_date, summary, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $_POST['student_email'], $_POST['report_date'], $_POST['summary'], $_POST['status']);
        $success = $stmt->execute() ? "Incident report added." : "Error: " . $stmt->error;

    } elseif (isset($_POST['add_goodmoral'])) {
        $stmt = $con->prepare("INSERT INTO good_moral (email, request_date, status) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $_POST['email'], $_POST['request_date'], $_POST['status']);
        $success = $stmt->execute() ? "Good Moral request added." : "Error: " . $stmt->error;

    } elseif (isset($_POST['add_exam'])) {
        $stmt = $con->prepare("INSERT INTO approved_special_exam (email, subject, exam_date, exam_time, room, status) VALUES (?, ?, ?, ?, ?, ?)");
        
        if (!$stmt) {
            $error = "SQL error: " . $con->error;
        } else {
            $status = "Approved";
            $stmt->bind_param("ssssss", $_POST['email'], $_POST['subject'], $_POST['exam_date'], $_POST['exam_time'], $_POST['room'], $status);
            $success = $stmt->execute() ? "Special Exam approved." : "Error: " . $stmt->error;
        }

    } elseif (isset($_POST['add_appointment'])) {
        $stmt = $con->prepare("INSERT INTO appointments (email, date, time, interest) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $_POST['email'], $_POST['date'], $_POST['time'], $_POST['interest']);
        $success = $stmt->execute() ? "Appointment scheduled." : "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Reports</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; margin: 0; }
    .dashboard { display: flex; min-height: 100vh; }

    /* Sidebar */
    .sidebar {
      width: 250px; background-color: #fff; border-right: 1px solid #dee2e6;
      padding: 1.5rem 0; position: fixed; height: 100vh; overflow-y: auto;
    }
    .sidebar h2 { padding: 0 1.5rem; margin-bottom: 2rem; font-size: 1.25rem; font-weight: 600; color: #495057; }
    .sidebar ul { list-style: none; padding: 0; margin: 0; }
    .sidebar li { margin-bottom: 0.25rem; }
    .sidebar a {
      display: flex; align-items: center; gap: 0.75rem;
      padding: 0.75rem 1.5rem; color: #6c757d; text-decoration: none; font-weight: 500;
      transition: all 0.2s ease;
    }
    .sidebar a:hover { background-color: #f8f9fa; color: #495057; }
    .sidebar a.active { background-color: #e3f2fd; color: #1976d2; border-right: 3px solid #1976d2; }
    .sidebar a i { font-size: 1.1rem; width: 18px; text-align: center; }

    /* Main content */
    .content { flex: 1; margin-left: 250px; padding: 2rem; }
    .page-header {
      background: white; border-radius: 12px; padding: 2rem; margin-bottom: 2rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-left: 4px solid #0d6efd; text-align: center;
    }
    .page-title { font-size: 2rem; font-weight: 600; color: #495057; display: flex; align-items: center; justify-content: center; gap: 0.75rem; margin-bottom: 0.5rem; }
    .page-subtitle { color: #6c757d; font-size: 1rem; margin: 0; }

    .category-container {
      display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-top: 2rem;
    }

    .category-box {
      background: white; border-radius: 12px; overflow: visible; /* allow dropdown to be visible */
      box-shadow: 0 4px 20px rgba(0,0,0,0.1); transition: all 0.3s ease; position: relative;
    }
    .category-box:hover { transform: translateY(-5px); box-shadow: 0 8px 30px rgba(0,0,0,0.15); }
    .category-box a {
      display: block; padding: 2rem; text-decoration: none; color: #495057;
      font-size: 1.25rem; font-weight: 600; text-align: center; transition: all 0.3s ease;
      position: relative; z-index: 1; /* keep link below dropdown */
    }
    .category-box .category-icon { font-size: 3rem; margin-bottom: 1rem; display: block; }
    .category-box .category-title { font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem; }
    .category-box .category-description { font-size: 0.9rem; color: #6c757d; margin: 0; line-height: 1.5; }

    .category-box.incident { border-left: 4px solid #dc3545; }
    .category-box.exam { border-left: 4px solid #198754; }
    .category-box.interview { border-left: 4px solid #6f42c1; }
    .category-box.counseling { border-left: 4px solid #0d6efd; }

    /* Dropdown menu - right side */
.dropdown-menu {
  display: none;
  position: absolute;
  top: 0;
  left: 100%; /* pop right */
  margin-left: 10px;
  background: white;
  border: 1px solid rgba(0,0,0,0.08);
  border-radius: 10px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.12);
  padding: 8px 0;
  z-index: 9999;
  min-width: 260px;
}

/* Dropdown link */
.dropdown-menu a {
  display: block;
  padding: 10px 14px;
  color: #172554;
  text-decoration: none;
  font-size: 14px;
  font-weight: 600;
}

.dropdown-menu a:hover { background-color: #f1f5f9; }

/* Show dropdown on hover */
.category-box.counseling:hover .dropdown-menu {
  display: block;
}



    @keyframes fadeIn {
      from { opacity: 0; transform: translate(-50%, -6px); }
      to { opacity: 1; transform: translate(-50%, 0); }
    }

    /* small screens: dropdown becomes full width under the card */
    @media (max-width: 768px) {
      .sidebar { width: 100%; height: auto; position: relative; }
      .content { margin-left: 0; padding: 1rem; }
      .category-container { grid-template-columns: 1fr; gap: 1rem; }
      .dropdown-menu { left: 16px; transform: translateX(0); min-width: calc(100% - 32px); top: calc(100% + 10px); }
    }
  </style>
</head>
<body>
  <div class="dashboard">

    <!-- Sidebar -->
    <div class="sidebar">
      <h2><i class="bi bi-speedometer2"></i> Admin Panel</h2>
      <ul>
        <li><a href="admin_dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a></li>
        <li><a href="admin_records.php"><i class="bi bi-people"></i> Student Records</a></li>
        <li><a href="admin_appointments.php"><i class="bi bi-calendar-check"></i> Appointments</a></li>
        <li><a href="admin_reports.php" class="active"><i class="bi bi-graph-up"></i> Reports</a></li>
        <li><a href="admin_settings.php"><i class="bi bi-gear"></i> Settings</a></li>
        <li><a href="admin_announcements.php"><i class="bi bi-megaphone"></i> Announcements</a></li>
        <li><a href="admin_parents.php"><i class="bi bi-person-lines-fill"></i> Parent Accounts</a></li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="content">
      <div class="page-header">
        <div class="page-title">📁 Reports Dashboard</div>
        <div class="page-subtitle">Overview of all report categories</div>
      </div>

      <div class="category-container">
        <!-- Incident Report -->
        <div class="category-box incident">
          <a href="incident_report.php">
            <div class="category-icon">🚨</div>
            <div class="category-title">Incident Report</div>
            <div class="category-description">Physical or disciplinary events</div>
          </a>
        </div>

        <!-- Special Exam -->
        <div class="category-box exam">
          <a href="special exam_report.php">
            <div class="category-icon">🧾</div>
            <div class="category-title">Special Examination Form</div>
            <div class="category-description">Application Form</div>
          </a>
        </div>

        <!-- Exit Interview -->
        <div class="category-box interview">
          <a href="Exit Interview_report.php">
            <div class="category-icon">💬</div>
            <div class="category-title">Exit Interview</div>
            <div class="category-description">Experience Feedback</div>
          </a>
        </div>

        <!-- Counseling Notes (with hover dropdown) -->
        <div class="category-box counseling">
          <a href="admin_counseling_notes.php" class="dropdown-link">
            <div class="category-icon">🧾</div>
            <div class="category-title">Guidance/Counseling Notes ▾</div>
            <div class="category-description">Student Counseling Records</div>
          </a>

          <!-- Dropdown Menu -->
          <div class="dropdown-menu" role="menu" aria-hidden="true">
            <a href="admin_counseling_form.php">
              <span>📋 Counseling Form</span>
              <small>Send session details to student</small>
            </a>
          </div>
        </div>

      </div> <!-- /.category-container -->

    </div> <!-- /.content -->
  </div> <!-- /.dashboard -->

</body>
</html>
