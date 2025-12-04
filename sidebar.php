<!-- sidebar.php -->
<div class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <h2>
      <div class="logo-icon">
        <i class="fas fa-graduation-cap"></i>
      </div>
      Guidance Portal
    </h2>
    <div class="subtitle">Student Portal • Dashboard</div>
  </div>
  <div class="sidebar-nav">
    <ul>
      <li><a href="dashboard.php" class="active">
        <i class="fas fa-home"></i>
        <span class="nav-text">Dashboard</span>
      </a></li>
      <?php if (stripos($_SESSION['strand_course'], 'SHS') !== false || stripos($_SESSION['year_level'], 'Grade') !== false): ?>
      <li><a href="student_records.php">
        <i class="fas fa-clipboard-check"></i>
        <span class="nav-text">Attendance</span>
      </a></li>
      <?php endif; ?>
      <li><a href="appointments.php">
        <i class="fas fa-calendar-check"></i>
        <span class="nav-text">Appointments</span>
        <span class="nav-badge">2</span>
      </a></li>
      <li><a href="student_reports.php">
        <i class="fas fa-file-alt"></i>
        <span class="nav-text">Reports</span>
      </a></li>
      <li><a href="settings.php">
        <i class="fas fa-cog"></i>
        <span class="nav-text">Settings</span>
      </a></li>
      <li><a href="help.php">
        <i class="fas fa-question-circle"></i>
        <span class="nav-text">Help & Support</span>
      </a></li>
    </ul>
  </div>
</div>
