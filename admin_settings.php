<?php
session_start();
include("db.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Settings</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8f9fa;
      margin: 0;
    }

    .dashboard { display: flex; min-height: 100vh; }

    /* Sidebar */
    .sidebar {
      width: 250px;
      background-color: #fff;
      border-right: 1px solid #dee2e6;
      padding: 1.5rem 0;
      position: fixed;
      height: 100vh;
      overflow-y: auto;
    }

    .sidebar h2 {
      padding: 0 1.5rem;
      margin-bottom: 2rem;
      font-size: 1.25rem;
      font-weight: 600;
      color: #495057;
    }

    .sidebar ul { list-style: none; padding: 0; margin: 0; }
    .sidebar li { margin-bottom: 0.25rem; }
    .sidebar a {
      display: flex; align-items: center; gap: 0.75rem;
      padding: 0.75rem 1.5rem;
      color: #6c757d; text-decoration: none; font-weight: 500;
      transition: all 0.2s ease;
    }
    .sidebar a:hover { background-color: #f8f9fa; color: #495057; }
    .sidebar a.active { background-color: #e3f2fd; color: #1976d2; border-right: 3px solid #1976d2; }
    .sidebar a i { font-size: 1.1rem; width: 18px; text-align: center; }

    /* Main content */
    .content {
      flex: 1; margin-left: 250px; padding: 2rem;
    }

    .page-header {
      background: white; border-radius: 12px;
      padding: 2rem; margin-bottom: 2rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      border-left: 4px solid #0d6efd;
    }

    .page-title {
      font-size: 2rem; font-weight: 600;
      color: #495057; margin: 0; display: flex; align-items: center; gap: 0.75rem;
    }

    .settings-section {
      background: white; border-radius: 12px;
      padding: 2rem; margin-bottom: 2rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .settings-section h3 {
      font-size: 1.25rem; font-weight: 600;
      color: #495057; margin-bottom: 1rem;
    }

    .form-control, .form-select {
      border-radius: 8px;
    }

    .btn-primary { border-radius: 8px; }

    @media (max-width: 768px) {
      .sidebar { width: 100%; height: auto; position: relative; }
      .content { margin-left: 0; padding: 1rem; }
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
        <li><a href="admin_reports.php"><i class="bi bi-graph-up"></i> Reports</a></li>
        <li><a href="admin_settings.php" class="active"><i class="bi bi-gear"></i> Settings</a></li>
        <li><a href="admin_announcements.php"><i class="bi bi-megaphone"></i> Announcements</a></li>
        <li><a href="admin_parents.php"><i class="bi bi-person-lines-fill"></i> Parent Accounts</a></li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="content">
      <div class="page-header">
        <div class="page-title"><i class="bi bi-gear"></i> Admin Settings</div>
      </div>

      <!-- Profile Settings -->
      <div class="settings-section">
        <h3>Profile Settings</h3>
        <form>
          <div class="mb-3">
            <label for="adminName" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="adminName" placeholder="Enter your name">
          </div>
          <div class="mb-3">
            <label for="adminEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="adminEmail" placeholder="Enter your email">
          </div>
          <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
      </div>

      <!-- Password Update -->
      <div class="settings-section">
        <h3>Change Password</h3>
        <form>
          <div class="mb-3">
            <label for="currentPassword" class="form-label">Current Password</label>
            <input type="password" class="form-control" id="currentPassword" placeholder="Enter current password">
          </div>
          <div class="mb-3">
            <label for="newPassword" class="form-label">New Password</label>
            <input type="password" class="form-control" id="newPassword" placeholder="Enter new password">
          </div>
          <div class="mb-3">
            <label for="confirmPassword" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm new password">
          </div>
          <button type="submit" class="btn btn-primary">Update Password</button>
        </form>
      </div>

      <!-- Notifications -->
      <div class="settings-section">
        <h3>Notification Preferences</h3>
        <form>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="notifyEmail">
            <label class="form-check-label" for="notifyEmail">
              Email Notifications
            </label>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="notifySMS">
            <label class="form-check-label" for="notifySMS">
              SMS Notifications
            </label>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="notifyApp">
            <label class="form-check-label" for="notifyApp">
              App Notifications
            </label>
          </div>
          <button type="submit" class="btn btn-primary mt-2">Save Preferences</button>
        </form>
      </div>
    </div>

  </div>
</body>
</html>
