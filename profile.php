<?php
session_start();
include("db.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// ✅ Update last_activity ng naka-login user
$update = $con->prepare("UPDATE form SET last_activity = NOW() WHERE email = ?");
$update->bind_param("s", $_SESSION['email']);
$update->execute();

if (isset($_GET['student_no']) && !empty($_GET['student_no'])) {
    // Gamitin yung student number mula sa search
    $student_no = $_GET['student_no'];
    $query = $con->prepare("SELECT fname, lname, student_no, year_level, strand_course, avatar_choice, email, about, last_activity 
                            FROM form 
                            WHERE student_no = ?");
    $query->bind_param("s", $student_no);
} else {
    // Default: naka-login user
    $email = $_SESSION['email'];
    $query = $con->prepare("SELECT fname, lname, student_no, year_level, strand_course, avatar_choice, email, about, last_activity 
                            FROM form 
                            WHERE email = ?");
    if (!$query) {
        die("Query failed: " . $con->error);
    }
    $query->bind_param("s", $email);
}

$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();
$about = !empty($user['about']) ? htmlspecialchars($user['about']) : "There is currently no information about this member.";

// Safety check kung walang nakita
if (!$user) {
    die("Student not found.");
}

// ✅ Determine online status
$onlineThreshold = 300; // 5 minutes in seconds
$isOnline = false;
if (!empty($user['last_activity'])) {
    $lastActivity = strtotime($user['last_activity']);
    $isOnline = (time() - $lastActivity) <= $onlineThreshold;
}

// Build portal email
$raw_lname = strtolower(str_replace(' ', '', $user['lname'] ?? ''));
$short_no = ltrim($user['student_no'], '0');
$short_no = substr($short_no, -6);
$portal_email = $raw_lname . "." . $short_no . "@guidanceportal.rosario.sti.edu.ph";

// Check kung sariling profile ba
$is_own_profile = (!isset($student_no) || $_SESSION['email'] === $user['email']);

// Variables for display
$fullName   = htmlspecialchars($user['fname'] . ' ' . ucfirst($user['lname']));
$studentNo  = $is_own_profile ? htmlspecialchars($user['student_no'] ?? 'Not set') : '';
$year       = $is_own_profile ? htmlspecialchars($user['year_level'] ?? 'Not set') : '';
$strand     = $is_own_profile ? htmlspecialchars($user['strand_course'] ?? 'Not set') : '';
$avatarFile = htmlspecialchars($user['avatar_choice'] ?? 'default_avatar.png');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
 <style>
    body {
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }
    
    /* Enhanced Sidebar styling */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 300px;
      height: 100vh;
      background: linear-gradient(180deg, #0f172a 0%, #1e293b 50%, #334155 100%);
      color: white;
      z-index: 1000;
      box-shadow: 8px 0 32px rgba(0, 0, 0, 0.3);
      border-right: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .sidebar-header {
      padding: 32px 24px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(147, 51, 234, 0.1) 100%);
      position: relative;
      overflow: hidden;
    }
    
    .sidebar-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.05), transparent);
      animation: shimmer 3s ease-in-out infinite;
    }
    
    @keyframes shimmer {
      0% { transform: translateX(-100%); }
      100% { transform: translateX(100%); }
    }
    
    .sidebar-header h2 {
      margin: 0;
      font-size: 1.75rem;
      font-weight: 800;
      display: flex;
      align-items: center;
      gap: 16px;
      position: relative;
      z-index: 1;
    }
    
    .sidebar-header .logo-icon {
      width: 48px;
      height: 48px;
      background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 8px 32px rgba(59, 130, 246, 0.3);
      animation: pulse 2s ease-in-out infinite;
    }
    
    .sidebar-header .subtitle {
      font-size: 0.875rem;
      color: rgba(255, 255, 255, 0.7);
      margin-top: 8px;
      font-weight: 500;
      position: relative;
      z-index: 1;
    }
    
    .sidebar-nav {
      padding: 24px 0;
      height: calc(100vh - 140px);
      overflow-y: auto;
    }
    
    .sidebar ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    
    .sidebar li {
      margin-bottom: 8px;
      padding: 0 16px;
    }
    
    .sidebar a {
      display: flex;
      align-items: center;
      padding: 16px 20px;
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      border-radius: 16px;
      position: relative;
      font-weight: 500;
      overflow: hidden;
    }
    
    .sidebar a::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .sidebar a:hover::before {
      opacity: 1;
    }
    
    .sidebar a:hover {
      color: white;
      transform: translateX(8px) scale(1.02);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    }
    
    .sidebar a.active {
      background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
      color: white;
      box-shadow: 0 8px 32px rgba(59, 130, 246, 0.4);
      transform: translateX(4px);
    }
    
    .sidebar a.active::after {
      content: '';
      position: absolute;
      right: -16px;
      top: 50%;
      transform: translateY(-50%);
      width: 4px;
      height: 24px;
      background: linear-gradient(180deg, #3b82f6 0%, #8b5cf6 100%);
      border-radius: 2px;
    }
    
    .sidebar a i {
      width: 24px;
      margin-right: 16px;
      font-size: 1.2rem;
      text-align: center;
    }
    
    .content {
      margin-left: 280px;
      min-height: 100vh;
      background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }
    
    .profile-header {
      position: relative;
    }

    .profile-edit-btn {
      position: absolute;
      top: 50%;
      right: 40px;
      transform: translateY(-50%);
      background: white;
      color: #3b82f6;
      border: none;
      padding: 10px 20px;
      border-radius: 9999px;
      font-weight: 600;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 6px;
      cursor: pointer;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
      transition: all 0.3s ease;
    }

    .profile-edit-btn:hover {
      background: #3b82f6;
      color: white;
      transform: translateY(-50%) scale(1.05);
    }

    .profile-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      padding: 3rem 2rem;
      color: white;
      position: relative;
      overflow: visible;
    }
    
    .profile-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><radialGradient id="a" cx="50%" cy="0%" r="100%"><stop offset="0%" stop-color="rgba(255,255,255,0.1)"/><stop offset="100%" stop-color="rgba(255,255,255,0)"/></radialGradient></defs><rect width="100" height="20" fill="url(%23a)"/></svg>');
      opacity: 0.3;
    }
    .profile-avatar-container {
      position: relative;
      display: inline-block;
      overflow: visible;
      z-index: 60;
    }
    
    .profile-avatar {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      border: 4px solid rgba(255, 255, 255, 0.3);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
      cursor: pointer;
      transition: all 0.3s ease;
      object-fit: cover;
    }
    .profile-avatar:hover {
      transform: scale(1.05);
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
      border-color: rgba(255, 255, 255, 0.5);
    }
    
    .avatar-badge {
      position: absolute;
      bottom: 8px;
      right: 8px;
      width: 32px;
      height: 32px;
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 3px solid white;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    .profile-info h2 {
      font-size: 2.5rem;
      font-weight: 700;
      margin: 0 0 0.5rem 0;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .profile-status {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
      padding: 8px 16px;
      border-radius: 20px;
      font-size: 0.9rem;
      font-weight: 500;
    }
    
    .avatar-popup {
      position: absolute;
      top: 100%;
      left: 80%;
      transform: translateX(-50%);
      background: white;
      border-radius: 20px;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
      padding: 2rem;
      min-width: 320px;
      z-index: 1000;
      display: none;
      border: 1px solid rgba(0, 0, 0, 0.1);
    }
    
    .avatar-popup::before {
      content: '';
      position: absolute;
      top: -8px;
      left: 50%;
      transform: translateX(-50%);
      width: 16px;
      height: 16px;
      background: white;
      border: 1px solid rgba(0, 0, 0, 0.1);
      border-bottom: none;
      border-right: none;
      transform: translateX(-50%) rotate(45deg);
    }
    
    .avatar-popup h3 {
      color: #1f2937;
      font-size: 1.25rem;
      font-weight: 600;
      margin: 0 0 1.5rem 0;
      text-align: center;
    }
    
    .avatar-options {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1rem;
      margin-bottom: 1.5rem;
    }
    
    .avatar-option {
      position: relative;
      cursor: pointer;
      border-radius: 12px;
      overflow: hidden;
      transition: all 0.3s ease;
    }
    
    .avatar-option:hover {
      transform: scale(1.05);
    }
    
    .avatar-option img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 12px;
      border: 3px solid transparent;
      transition: all 0.3s ease;
    }
    
    .avatar-option input:checked + img {
      border-color: #3b82f6;
      box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }

    .avatar-badge {
      pointer-event: none;
    }
    
    .save-avatar-btn {
      width: 100%;
      background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 12px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .save-avatar-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
    }
    
    .profile-body {
      padding: 2rem;
    }
    
    .profile-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .tab-navigation {
      display: flex;
      background: #f8fafc;
      border-radius: 16px;
      padding: 6px;
      margin: 2rem;
      margin-bottom: 0;
    }
    
    .tab-button {
      flex: 1;
      padding: 12px 24px;
      border: none;
      background: transparent;
      border-radius: 12px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      color: #64748b;
    }
    
    .tab-button.active {
      background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
      color: white;
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .tab-button:not(.active):hover {
      background: rgba(59, 130, 246, 0.1);
      color: #3b82f6;
    }
    
    .tab-content {
      padding: 2rem;
    }
    .tab-pane {
      display: none;
      animation: fadeInUp 0.4s ease-out;
    }
    
    .tab-pane.active {
      display: block;
    }
    
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .info-section h5 {
      color: #1f2937;
      font-size: 1.5rem;
      font-weight: 700;
      margin: 0 0 1.5rem 0;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .info-grid {
      display: grid;
      gap: 1.5rem;
    }
    
    .info-item {
      background: #f8fafc;
      padding: 1.5rem;
      border-radius: 16px;
      border: 1px solid #e2e8f0;
      transition: all 0.3s ease;
    }
    
    .info-item:hover {
      background: #f1f5f9;
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .info-label {
      font-weight: 600;
      color: #64748b;
      font-size: 0.875rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 0.5rem;
    }
    
    .info-value {
      color: #1f2937;
      font-size: 1.125rem;
      font-weight: 500;
    }
    
    .floating-particles {
      position: absolute;
      width: 100%;
      height: 100%;
      overflow: hidden;
      pointer-events: none;
    }
    
    .particle {
      position: absolute;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      animation: float 15s infinite linear;
    }
    
    @keyframes float {
      0% {
        transform: translateY(100vh) rotate(0deg);
        opacity: 0;
      }
      10% {
        opacity: 1;
      }
      90% {
        opacity: 1;
      }
      100% {
        transform: translateY(-100px) rotate(360deg);
        opacity: 0;
      }
    }

    /* Miscellaneous Section Styling */
    .misc-section {
      background: white;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      border: 1px solid rgba(0, 0, 0, 0.05);
      padding: 1.5rem;
      margin-top: 1.5rem;
    }

    .misc-title {
      color: #1f2937;
      font-size: 1.25rem;
      font-weight: 700;
      margin: 0 0 1rem 0;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .misc-stats {
      display: flex;
      flex-wrap: wrap;
      gap: 1.5rem;
      color: #64748b;
      font-size: 0.95rem;
    }

    .misc-stat {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    /* Right Sidebar Widget */
    .account-widget {
      background: white;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      border: 1px solid rgba(0, 0, 0, 0.05);
      padding: 1.5rem;
      position: sticky;
      top: 2rem;
    }

    .widget-header {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid #e2e8f0;
    }

    .widget-icon {
      width: 40px;
      height: 40px;
      background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.1rem;
    }

    .widget-title {
      color: #1f2937;
      font-size: 1.1rem;
      font-weight: 600;
      margin: 0;
    }

    .widget-info {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .widget-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .widget-label {
      color: #64748b;
      font-size: 0.875rem;
      font-weight: 500;
    }

    .widget-value {
      color: #1f2937;
      font-size: 0.875rem;
      font-weight: 600;
    }
  </style>
</head>
<body class="bg-gray-50">

<!-- Sidebar -->
<div class="sidebar">
  <div class="sidebar-header">
    <div class="sidebar-logo">
      <i class="fas fa-graduation-cap text-white"></i>
    </div>
    <div>
      <h2 class="text-xl font-bold">Guidance Office</h2>
      <p class="text-sm text-slate-300">Student Portal</p>
    </div>
  </div>
  
  <ul class="sidebar-nav">
    <li><a href="dashboard.php" class="sidebar-link"><i class="fas fa-home w-5"></i> <span>Dashboard</span></a></li>
    <?php if (stripos($_SESSION['strand_course'], 'SHS') !== false || stripos($_SESSION['year_level'], 'Grade') !== false): ?>
    <li><a href="student_records.php" class="sidebar-link"><i class="fas fa-clipboard-check w-5"></i> <span>Attendance</span></a></li>
    <?php endif; ?>
    <li><a href="appointments.php" class="sidebar-link"><i class="fas fa-calendar-check w-5"></i> <span>Appointments</span></a></li>
    <li><a href="student_reports.php" class="sidebar-link"><i class="fas fa-file-alt w-5"></i> <span>Reports</span></a></li>
    <li><a href="settings.php" class="sidebar-link"><i class="fas fa-cog w-5"></i> <span>Settings</span></a></li>
  </ul>
</div>

<!-- Main Content -->
<div class="content">
  <!-- Floating Particles -->
  <div class="floating-particles">
    <!-- Particles will be generated by JavaScript -->
  </div>

  <!-- Profile Header -->
  <div class="profile-header">
    <div class="max-w-6xl mx-auto">
      <?php if ($is_own_profile): ?>
      <!-- Edit Button (inside blue rectangle, right side) -->
      <button class="profile-edit-btn" onclick="openEditModal()">
        <i class="fas fa-edit mr-2"></i> Edit
      </button>
      <?php endif; ?>

      <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
        <div class="profile-avatar-container">
          <img src="<?= strpos($avatarFile, 'uploads/') !== false ? $avatarFile : 'avatars/' . $avatarFile ?>" 
               alt="Avatar" class="profile-avatar" id="profilePic"
               <?php if ($is_own_profile): ?> onclick="togglePopup()" <?php endif; ?>>
          <div class="avatar-badge">
            <i class="fas fa-camera text-white text-sm"></i>
          </div>

          <?php if ($is_own_profile): ?>
          <!-- Avatar Popup -->
          <div class="avatar-popup" id="avatarPopup">
            <h3>🎨 Choose Your Avatar</h3>
            <form method="POST" action="update_avatar.php">
              <div class="avatar-options">
                <label class="avatar-option">
                  <input type="radio" name="avatar" value="avatar1.png" hidden <?= $avatarFile == 'avatar1.png' ? 'checked' : '' ?>>
                  <img src="avatars/avatar1.png" alt="Avatar 1">
                </label>
                <label class="avatar-option">
                  <input type="radio" name="avatar" value="avatar2.png" hidden <?= $avatarFile == 'avatar2.png' ? 'checked' : '' ?>>
                  <img src="avatars/avatar2.png" alt="Avatar 2">
                </label>
                <label class="avatar-option">
                  <input type="radio" name="avatar" value="avatar3.png" hidden <?= $avatarFile == 'avatar3.png' ? 'checked' : '' ?>>
                  <img src="avatars/avatar3.png" alt="Avatar 3">
                </label>
                <label class="avatar-option">
                  <input type="radio" name="avatar" value="default_avatar.png" hidden <?= $avatarFile == 'default_avatar.png' ? 'checked' : '' ?>>
                  <img src="avatars/default_avatar.png" alt="Default Avatar">
                </label>
              </div>
              <button type="submit" class="save-avatar-btn">
                <i class="fas fa-save mr-2"></i> Save Avatar
              </button>
            </form>

            <!-- Upload Profile Picture -->
            <?php if (isset($_GET['upload']) && $_GET['upload'] == 'success'): ?>
            <div class="bg-green-100 text-green-800 p-3 rounded-md text-center font-medium shadow mb-3">
              ✅ Profile picture updated successfully!
            </div>
            <?php endif; ?>

            <form action="upload_profile.php" method="POST" enctype="multipart/form-data" class="mt-4 border-t pt-3">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Upload your own picture:</label>
              <input type="file" name="profile_picture" accept="image/*" class="w-full border rounded-lg p-2 mb-3">
              <button type="submit" class="save-avatar-btn bg-green-500 hover:bg-green-600">
                <i class="fas fa-upload mr-2"></i> Upload Picture
              </button>
            </form>
          </div>
          <?php endif; ?>
        </div>

        <div class="profile-info text-center md:text-left">
          <h2><?= htmlspecialchars($fullName) ?></h2>
          <div class="profile-status inline-flex items-center gap-2">
            <i class="fas fa-circle <?= $isOnline ? 'text-green-500' : 'text-gray-400' ?> text-xs"></i>
            <span><?= $isOnline ? 'Online' : 'Offline' ?></span>
          </div>

          <p class="mt-4 text-lg opacity-90">
            Welcome to your student portal. Manage your academic journey and stay connected with the guidance office.
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Profile Body -->
  <div class="profile-body">
    <div class="max-w-6xl mx-auto">
      <div class="profile-card">
        <!-- Tab Navigation -->
        <div class="tab-navigation">
          <?php if ($is_own_profile): ?>
          <button class="tab-button active" onclick="switchTab('info', this)">
            <i class="fas fa-info-circle mr-2"></i> Student Information
          </button>
          <button class="tab-button" onclick="switchTab('contact', this)">
            <i class="fas fa-envelope mr-2"></i> Contact Details
          </button>
          <?php endif; ?>
          <button class="tab-button <?= $is_own_profile ? '' : 'active' ?>" 
                  <?php if ($is_own_profile): ?> onclick="switchTab('about', this)" <?php endif; ?>>
            <i class="fas fa-graduation-cap mr-2"></i> About
          </button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
          <?php if ($is_own_profile): ?>
          <!-- Student Information Tab -->
          <div class="tab-pane active" id="info">
            <div class="info-section">
              <h5>
                <i class="fas fa-user-graduate text-blue-600"></i> Student Information
              </h5>
              <div class="info-grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                <div class="info-item">
                  <div class="info-label">Student Number</div>
                  <div class="info-value"><?= htmlspecialchars($studentNo) ?></div>
                </div>
                <div class="info-item">
                  <div class="info-label">Year Level</div>
                  <div class="info-value"><?= htmlspecialchars($year) ?></div>
                </div>
                <div class="info-item">
                  <div class="info-label">Strand / Course</div>
                  <div class="info-value"><?= htmlspecialchars($strand) ?></div>
                </div>
                <div class="info-item">
                  <div class="info-label">Enrollment Status</div>
                  <div class="info-value">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                      <i class="fas fa-check-circle mr-1"></i> Active
                    </span>
                  </div>
                </div>
                <div class="info-item">
                  <div class="info-label">Academic Year</div>
                  <div class="info-value">2023-2024</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Contact Information Tab -->
          <div class="tab-pane" id="contact">
            <div class="info-section">
              <h5>
                <i class="fas fa-address-book text-green-600"></i> Contact Information
              </h5>
              <div class="info-grid grid-cols-1 md:grid-cols-2">
                <div class="info-item">
                  <div class="info-label">Email Address</div>
                  <div class="info-value">
                    <a href="mailto:<?= htmlspecialchars($portal_email) ?>" class="text-blue-600 hover:text-blue-800 transition-colors">
                      <i class="fas fa-envelope mr-2"></i> <?= htmlspecialchars($portal_email) ?>
                    </a>
                  </div>
                </div>
                <div class="info-item">
                  <div class="info-label">Phone Number</div>
                  <div class="info-value">
                    <i class="fas fa-phone mr-2 text-gray-400"></i> Not provided
                  </div>
                </div>
                <div class="info-item">
                  <div class="info-label">Emergency Contact</div>
                  <div class="info-value">
                    <i class="fas fa-user-friends mr-2 text-gray-400"></i> Not provided
                  </div>
                </div>
                <div class="info-item">
                  <div class="info-label">Address</div>
                  <div class="info-value">
                    <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i> Not provided
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php endif; ?>

          <!-- About Tab -->
          <div class="tab-pane <?= $is_own_profile ? '' : 'active' ?>" id="about">
            <div class="info-section">
              <h5>
                <i class="fas fa-user text-purple-600"></i> About
              </h5>
              <div class="info-item">
                <div class="info-value text-gray-700 leading-relaxed">
                  <?= nl2br($about) ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php if ($is_own_profile): ?>
  <!-- Edit Modal -->
  <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-[2000]">
    <div class="bg-white rounded-2xl shadow-xl w-96 p-6 relative">
      <button onclick="closeEditModal()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700">
        <i class="fas fa-times"></i>
      </button>
      <h2 class="text-lg font-semibold mb-4">Edit Profile</h2>
      <div class="flex flex-col space-y-3">
        <button onclick="openAboutPopup()" class="flex items-center justify-between bg-gray-100 p-3 rounded-xl hover:bg-gray-200">
          <span><i class="fas fa-info-circle mr-2 text-blue-600"></i> About</span>
          <i class="fas fa-chevron-right"></i>
        </button>
        <button onclick="openAvatarPopup()" class="flex items-center justify-between bg-gray-100 p-3 rounded-xl hover:bg-gray-200">
          <span><i class="fas fa-user-circle mr-2 text-purple-600"></i> Profile Avatar</span>
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- About Popup -->
  <div id="aboutPopup" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-[3000]">
    <div class="bg-white rounded-2xl shadow-xl w-96 p-6 relative">
      <button onclick="closeAboutPopup()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700">
        <i class="fas fa-times"></i>
      </button>
      <h2 class="text-lg font-semibold mb-4">Edit About Description</h2>
      <form method="POST" action="update_about.php">
        <textarea name="about" rows="5" class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300" placeholder="Write something about yourself..."></textarea>
        <button type="submit" class="mt-3 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">Save</button>
      </form>
    </div>
  </div>
  <?php endif; ?>

</div>
</body>
</html>


<script>
function openEditModal() {
  document.getElementById("editModal").classList.remove("hidden");
}

function closeEditModal() {
  document.getElementById("editModal").classList.add("hidden");
}

function openAboutPopup() {
  document.getElementById("editModal").classList.add("hidden");
  document.getElementById("aboutPopup").classList.remove("hidden");
}

function closeAboutPopup() {
  document.getElementById("aboutPopup").classList.add("hidden");
  document.getElementById("editModal").classList.remove("hidden");
}

// ✅ Auto-close popup and redirect after About save
document.addEventListener("DOMContentLoaded", function () {
  const aboutForm = document.querySelector('#aboutPopup form');
  if (aboutForm) {
    aboutForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(aboutForm);

      fetch("update_about.php", {
        method: "POST",
        body: formData
      })
        .then(res => res.text())
        .then(() => {
          // Close popup
          document.getElementById("aboutPopup").classList.add("hidden");
          // Show toast notification
          showToast("✅ About section updated successfully!");
          // Redirect after a short delay
          setTimeout(() => {
            window.location.href = "profile.php";
          }, 1200);
        })
        .catch(() => {
          showToast("⚠️ Something went wrong. Please try again.");
        });
    });
  }
});

// ✨ Floating toast message
function showToast(message) {
  const toast = document.createElement("div");
  toast.textContent = message;
  toast.className = "fixed bottom-5 right-5 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg fade-in";
  document.body.appendChild(toast);
  setTimeout(() => toast.remove(), 2500);
}

// Create floating particles
function createParticles() {
  const particleContainer = document.querySelector('.floating-particles');
  for (let i = 0; i < 8; i++) {
    const particle = document.createElement('div');
    particle.className = 'particle';
    particle.style.left = Math.random() * 100 + '%';
    particle.style.width = particle.style.height = Math.random() * 8 + 4 + 'px';
    particle.style.animationDelay = Math.random() * 15 + 's';
    particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
    particleContainer.appendChild(particle);
  }
}

// Tab switching functionality
function switchTab(tabId, button) {
  document.querySelectorAll('.tab-pane').forEach(pane => {
    pane.classList.remove('active');
  });
  document.querySelectorAll('.tab-button').forEach(btn => {
    btn.classList.remove('active');
  });
  document.getElementById(tabId).classList.add('active');
  button.classList.add('active');
}

// Avatar popup functionality
function togglePopup() {
  const popup = document.getElementById("avatarPopup");
  popup.style.display = popup.style.display === "block" ? "none" : "block";
}

// Close popup when clicking outside
document.addEventListener("click", function(event) {
  const popup = document.getElementById("avatarPopup");
  const pic = document.getElementById("profilePic");
  if (!popup.contains(event.target) && !pic.contains(event.target)) {
    popup.style.display = "none";
  }
});

// Initialize particles when page loads
document.addEventListener('DOMContentLoaded', function() {
  createParticles();
});

// Smooth scrolling
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
});
</script>
