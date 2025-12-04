<?php
session_start();
include("db.php");

// ✅ Check login session
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];

// ✅ Fetch full user info including profile details
$query = $con->prepare("SELECT student_no, fname, strand_course, year_level, profile_picture, avatar_choice 
                        FROM form WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

// ✅ Assign user data with fallbacks
$student_no = $user['student_no'] ?? '';
$fname = $user['fname'] ?? '';
$strand_course = $user['strand_course'] ?? '';
$year_level = $user['year_level'] ?? '';
$profile_picture = $user['profile_picture'] ?? '';
$avatar_choice = $user['avatar_choice'] ?? '';

// ✅ Store in session if needed elsewhere
$_SESSION['student_no'] = $student_no;
$_SESSION['fname'] = $fname;
$_SESSION['strand_course'] = $strand_course;
$_SESSION['year_level'] = $year_level;

// ✅ Determine which profile image to display
if (!empty($profile_picture)) {
    // ✅ If uploaded manually and stored as relative path
    $profileImage = $profile_picture;
} elseif (!empty($avatar_choice)) {
    // ✅ If avatar_choice already contains full path (like uploads/filename.jpg)
    if (str_starts_with($avatar_choice, 'uploads/')) {
        $profileImage = $avatar_choice;
    } else {
        $profileImage = "avatars/" . $avatar_choice;
    }
} else {
    $profileImage = "avatars/default_avatar.png";
}


// ✅ Fetch recent notifications
$notif_q = $con->query("SELECT * FROM notifications WHERE student_no = '$student_no' ORDER BY created_at DESC LIMIT 5");

// ✅ Count unread notifications
$notif_count_q = $con->query("SELECT COUNT(*) AS unread_count FROM notifications WHERE student_no = '$student_no' AND is_read = 0");
$unread_count = $notif_count_q->fetch_assoc()['unread_count'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>GP Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
   /* 🌙 Enhanced Dark Mode Styles */
body.dark-mode {
  background-color: #0f172a;
  color: #e2e8f0;
}

body.dark-mode .sidebar {
  background: linear-gradient(180deg, #111827 0%, #1e293b 100%);
  color: #f1f5f9;
  border-right: 1px solid rgba(255, 255, 255, 0.1);
}

body.dark-mode .sidebar-header {
  background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(147, 51, 234, 0.15) 100%);
}

body.dark-mode .sidebar a {
  color: rgba(255, 255, 255, 0.8);
}

body.dark-mode .sidebar a:hover {
  color: #fff;
  background: rgba(255, 255, 255, 0.05);
}

body.dark-mode .sidebar a.active {
  background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
  box-shadow: 0 0 10px rgba(59, 130, 246, 0.3);
}

/* Main content + cards */
body.dark-mode .main-content {
  background-color: #1e293b;
}

body.dark-mode .module-card {
  background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
  color: #e2e8f0;
  border-color: #334155;
}

body.dark-mode .module-card .module-title h3 {
  color: #f8fafc;
}

body.dark-mode .module-card .module-title p {
  color: #94a3b8;
}

body.dark-mode .stat-item {
  background: #1e293b;
  border-color: #334155;
  color: #f1f5f9;
}

/* Dropdowns, Modals, and Panels */
body.dark-mode .dropdown-content,
body.dark-mode .modal,
body.dark-mode .message-panel,
body.dark-mode .calendar-popup {
  background: #1e293b;
  color: #e2e8f0;
  border-color: #334155;
}

body.dark-mode .modal-header {
  background: linear-gradient(135deg, #334155 0%, #475569 100%);
}

body.dark-mode .dropdown-item:hover {
  background: #334155;
  color: #f8fafc;
}

/* Buttons */
body.dark-mode .btn-primary {
  background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}

body.dark-mode .btn-secondary {
  background-color: #334155;
  color: #f8fafc;
  border-color: #475569;
}

body.dark-mode .btn-secondary:hover {
  background-color: #475569;
}

/* Scrollbar */
body.dark-mode ::-webkit-scrollbar-track {
  background: #1e293b;
}
body.dark-mode ::-webkit-scrollbar-thumb {
  background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
}

/* Message panel header */
body.dark-mode .message-header {
  background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
}

/* Calendar */
body.dark-mode .calendar-popup {
  background: #1e293b;
  color: #f8fafc;
}

/* Links and Texts */
body.dark-mode a,
body.dark-mode .nav-text {
  color: #e2e8f0;
}

    /* Modal Styles */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
    }

    .modal {
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
      width: 90%;
      max-width: 480px;
      max-height: 90vh;
      overflow-y: auto;
    }

    .modal-header {
      background: linear-gradient(135deg, #1d3557 0%, #457b9d 100%);
      color: white;
      padding: 15px 20px;
      border-radius: 8px 8px 0 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .modal-title {
      font-size: 16px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .close-btn {
      background: none;
      border: none;
      color: white;
      font-size: 18px;
      cursor: pointer;
      padding: 0;
      width: 24px;
      height: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .close-btn:hover {
      opacity: 0.8;
    }

    .modal-body {
      padding: 20px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: 600;
      color: #333;
      font-size: 13px;
    }

    .form-group label i {
      margin-right: 5px;
      color: #1d3557;
      width: 12px;
    }

    .form-group input,
    .form-group textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 13px;
      box-sizing: border-box;
      transition: border-color 0.3s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: #1d3557;
      box-shadow: 0 0 0 2px rgba(29, 53, 87, 0.1);
    }

    .form-group textarea {
      resize: vertical;
      height: 80px;
    }

    .file-upload {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .file-upload input[type="file"] {
      display: none;
    }

    .file-upload-btn {
      background-color: #f8f9fa;
      border: 1px dashed #ddd;
      padding: 8px 12px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 12px;
      color: #666;
      display: flex;
      align-items: center;
      gap: 5px;
      transition: all 0.3s ease;
    }

    .file-upload-btn:hover {
      border-color: #1d3557;
      background-color: #f0f4f8;
    }

    .file-name {
      font-size: 12px;
      color: #666;
      font-style: italic;
    }

    .modal-footer {
      padding: 15px 20px;
      border-top: 1px solid #eee;
      display: flex;
      gap: 10px;
      justify-content: flex-end;
    }

    .btn {
      padding: 8px 16px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 13px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 5px;
      transition: all 0.3s ease;
    }

    .btn-primary {
      background-color: #1d3557;
      color: white;
    }

    .btn-primary:hover {
      background-color: #457b9d;
    }

    .btn-secondary {
      background-color: #f8f9fa;
      color: #666;
      border: 1px solid #ddd;
    }

    .btn-secondary:hover {
      background-color: #e9ecef;
    }

    body {
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }

    .send-btn {
    background-color: #2563eb;
    color: white;
    border: none;
    padding: 10px 18px;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s ease;
  }
  .send-btn:hover {
    background-color: #1d4ed8;
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
    
    .sidebar a .nav-text {
      flex: 1;
      font-size: 0.95rem;
    }
    
    .sidebar a .nav-badge {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: white;
      font-size: 0.75rem;
      padding: 2px 8px;
      border-radius: 12px;
      font-weight: 600;
      min-width: 20px;
      text-align: center;
    }

    .sidebar-nav .has-dropdown .dropdown {
  display: none;
  list-style: none;
  padding-left: 15px;
}

.sidebar-nav .has-dropdown:hover .dropdown {
  display: block;
}

.sidebar-nav .has-dropdown a {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
    
    #loadingScreen {
  transition: opacity 0.5s ease; /* 0.5s fade-out duration */
  }

    /* Main content adjustment */
    .main-content {
      margin-left: 300px;
      min-height: 100vh;
    }
    
    /* Module Cards Styling */
    .module-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 24px;
      margin-bottom: 32px;
    }
    
    .module-card {
      background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
      border-radius: 20px;
      padding: 24px;
      border: 1px solid #e2e8f0;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }
    
    .module-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .module-card:hover::before {
      opacity: 1;
    }
    
    .module-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }
    
    .module-header {
      display: flex;
      align-items: center;
      margin-bottom: 16px;
    }
    
    .module-icon {
      width: 48px;
      height: 48px;
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 16px;
      font-size: 1.5rem;
      color: white;
    }
    
    .module-title {
      flex: 1;
    }
    
    .module-title h3 {
      margin: 0 0 4px 0;
      font-size: 1.25rem;
      font-weight: 700;
      color: #1f2937;
    }
    
    .module-title p {
      margin: 0;
      font-size: 0.875rem;
      color: #6b7280;
    }
    
    .module-content {
      margin-bottom: 20px;
    }
    
    .module-stats {
      display: flex;
      gap: 16px;
      margin-bottom: 16px;
    }
    
    .stat-item {
      flex: 1;
      text-align: center;
      padding: 12px;
      background: #f8fafc;
      border-radius: 12px;
      border: 1px solid #e2e8f0;
    }
    
    .stat-number {
      display: block;
      font-size: 1.5rem;
      font-weight: 700;
      color: #1f2937;
    }
    
    .stat-label {
      font-size: 0.75rem;
      color: #6b7280;
      text-transform: uppercase;
      font-weight: 600;
      letter-spacing: 0.5px;
    }
    
    .module-actions {
      display: flex;
      gap: 12px;
    }
    
    .module-btn {
      flex: 1;
      padding: 12px 16px;
      border-radius: 12px;
      font-weight: 600;
      font-size: 0.875rem;
      text-decoration: none;
      text-align: center;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
    }
    
    .module-btn.primary {
      background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
      color: white;
    }
    
    .module-btn.primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
    }
    
    .module-btn.secondary {
      background: #f1f5f9;
      color: #475569;
      border: 1px solid #e2e8f0;
    }
    
    .module-btn.secondary:hover {
      background: #e2e8f0;
      transform: translateY(-2px);
    }

     /* Highlight today */
  .today {
    background-color: #3b82f6; /* blue */
    color: white;
    border-radius: 50%;
  }
    
    /* Mobile responsiveness */
    @media (max-width: 768px) {
      .sidebar {
        width: 250px;
      }
      
      .main-content {
        margin-left: 250px;
      }
      
      /* Mobile layout adjustments */
      .flex.gap-8 {
        flex-direction: column;
        gap: 24px;
      }
      
      .w-80 {
        width: 100%;
      }
      
      .module-grid {
        grid-template-columns: 1fr;
      }
    }
    
    @media (max-width: 1024px) {
      .flex.gap-8 {
        gap: 24px;
      }
      
      .w-80 {
        width: 300px;
      }
    }
    
    /* Custom animations */
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
    
    @keyframes slideInRight {
      from {
        opacity: 0;
        transform: translateX(30px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }
    
    @keyframes pulse {
      0%, 100% {
        transform: scale(1);
      }
      50% {
        transform: scale(1.05);
      }
    }
    
    .animate-fadeInUp {
      animation: fadeInUp 0.6s ease-out;
    }
    
    .animate-slideInRight {
      animation: slideInRight 0.6s ease-out;
    }
    
    .animate-pulse-custom {
      animation: pulse 2s ease-in-out infinite;
    }
    
    /* Gradient backgrounds */
    .gradient-bg {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .gradient-card {
      background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    }
    
    .announcement-gradient {
      background: linear-gradient(135deg, #e0f2fe 0%, #f3e5f5 100%);
    }
    
    /* Glass morphism effect */
    .glass {
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.18);
    }
    #notificationPanel {
  transform-origin: top right;
  transition: all 0.2s ease-in-out;
}
#notificationPanel.show {
  display: block;
  transform: scale(1);
  opacity: 1;
}

    
    /* Profile dropdown styling */
    .profile-dropdown {
      position: relative;
    }
    
    .profile-dropdown .dropdown-content {
      display: none;
      position: absolute;
      right: 0;
      top: 45px;
      background: white;
      min-width: 250px;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      border: 1px solid #e5e7eb;
      z-index: 1000;
    }
    
    .profile-dropdown .dropdown-content.show {
      display: block;
      animation: fadeInUp 0.3s ease-out;
    }
    
    .dropdown-item {
      display: block;
      padding: 12px 16px;
      color: #374151;
      text-decoration: none;
      transition: all 0.2s ease;
      border-radius: 8px;
      margin: 4px 8px;
    }
    
    .dropdown-item:hover {
      background: #f3f4f6;
      color: #1f2937;
    }
    
    .profile-info {
      padding: 16px;
      border-bottom: 1px solid #e5e7eb;
      background: linear-gradient(135deg, #f8fafc 0%, #e5e7eb 100%);
      border-radius: 12px 12px 0 0;
    }
    
    /* Message panel styling */
    .message-panel {
      position: fixed;
      top: 0;
      right: -350px;
      width: 350px;
      height: 100vh;
      background: white;
      box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
      transition: right 0.3s ease;
      z-index: 1000;
      border-left: 1px solid #e5e7eb;
    }
    
    .message-panel.show {
      right: 0;
    }
    
    .message-header {
      padding: 20px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .message-body {
      padding: 20px;
    }
    
    .message-link {
      display: block;
      padding: 15px;
      margin-bottom: 10px;
      background: #f8fafc;
      border-radius: 8px;
      text-decoration: none;
      color: #374151;
      transition: all 0.2s ease;
      border: 1px solid #e5e7eb;
    }
    
    .message-link:hover {
      background: #e5e7eb;
      transform: translateY(-2px);
    }
    
    /* Calendar styling */
    .mini-calendar {
      display: none;
      position: fixed;
      top: 70px;
      right: 20px;
      z-index: 1000;
    }
    
    .calendar-popup {
      background: white;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      border: 1px solid #e5e7eb;
      overflow: hidden;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }
    
    ::-webkit-scrollbar-track {
      background: #f1f5f9;
    }
    
    ::-webkit-scrollbar-thumb {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-radius: 4px;
    }
    
    /* Hover effects */
    .hover-lift {
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .hover-lift:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    /* Icon animations */
    .icon-bounce:hover {
      animation: pulse 0.6s ease-in-out;
    }
    @keyframes waveAnimation {
    0%, 40%, 100% {
      transform: scaleY(0.4);
    }
    20% {
      transform: scaleY(1);
    }
  }

  .wave {
    animation: waveAnimation 1.2s ease-in-out infinite;
  }

  .wave:nth-child(1) { animation-delay: -1.2s; }
  .wave:nth-child(2) { animation-delay: -1.1s; }
  .wave:nth-child(3) { animation-delay: -1.0s; }
  .wave:nth-child(4) { animation-delay: -0.9s; }
  .wave:nth-child(5) { animation-delay: -0.8s; }
    
    /* Responsive design */
    @media (max-width: 768px) {
      .message-panel {
        width: 100%;
        right: -100%;
      }
      
      .mini-calendar {
        right: 10px;
        left: 10px;
      }
    }
  </style>
</head>
<body class="bg-gray-50 min-h-screen">


  <!-- Loading Spinner (Top of body, above sidebar and main content) -->
  <div id="loadingScreen" class="fixed inset-0 bg-white flex justify-center items-center z-50">
  <div class="flex space-x-2">
    <div class="wave bg-blue-500 h-8 w-2 rounded"></div>
    <div class="wave bg-blue-500 h-8 w-2 rounded"></div>
    <div class="wave bg-blue-500 h-8 w-2 rounded"></div>
    <div class="wave bg-blue-500 h-8 w-2 rounded"></div>
    <div class="wave bg-blue-500 h-8 w-2 rounded"></div>
  </div>
</div>

 <!-- Enhanced Sidebar -->
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
      <li>
        <a href="dashboard.php" class="active">
          <i class="fas fa-home"></i>
          <span class="nav-text">Dashboard</span>
        </a>
      </li>

      <?php if (stripos($_SESSION['strand_course'], 'SHS') !== false || stripos($_SESSION['year_level'], 'Grade') !== false): ?>
      <li>
        <a href="student_records.php">
          <i class="fas fa-clipboard-check"></i>
          <span class="nav-text">Attendance</span>
        </a>
      </li>
      <?php endif; ?>

      <!-- Appointments with Dropdown -->
      <li class="has-dropdown">
        <a href="appointments.php">
          <i class="fas fa-calendar-check"></i>
          <span class="nav-text">Appointments</span>
          <i class="fas fa-chevron-down dropdown-icon"></i>
        </a>
        <ul class="dropdown">
          <li><a href="exit_interview_form.php">Exit Interview Form</a></li>
          <!-- Add more forms here if needed -->
        </ul>
      </li>

      <li>
        <a href="student_reports.php">
          <i class="fas fa-file-alt"></i>
          <span class="nav-text">Reports</span>
        </a>
      </li>
      <li>
        <a href="settings.php">
          <i class="fas fa-cog"></i>
          <span class="nav-text">Settings</span>
        </a>
      </li>
      <li>
        <a href="help.php">
          <i class="fas fa-question-circle"></i>
          <span class="nav-text">Help & Support</span>
        </a>
      </li>
    </ul>
  </div>
      </div>


  <!-- Main Content Wrapper -->
  <div class="main-content" id="mainContent">

  <!-- Header -->
  <header class="gradient-bg shadow-lg sticky top-0 z-40">
    <div class="container mx-auto px-6 py-4">
      <div class="flex items-center justify-between">
        
        <!-- Logo and Title -->
        <div class="flex items-center space-x-4 animate-fadeInUp">
          <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
            <i class="fas fa-graduation-cap text-white text-xl"></i>
          </div>
          <div>
            <h1 class="text-2xl font-bold text-white">Guidance Portal</h1>
            <p class="text-white/80 text-sm">Student Dashboard</p>
          </div>
        </div>

        <!-- Header Actions -->
        <div class="flex items-center space-x-4 animate-slideInRight">

        <!-- Search Bar -->
<div class="relative">
  <input
    type="text"
    placeholder="Search..."
    id="searchInput"
    class="pl-10 pr-4 py-2 w-64 rounded-full bg-white/10 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition"
  >
  <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-white/70"></i>

  <!-- Search Results Panel -->
  <div id="searchResults" class="absolute mt-2 w-64 bg-white rounded-xl shadow-lg hidden z-50">
    <ul id="resultsList" class="divide-y divide-gray-200"></ul>
  </div>
</div>

          <!-- Notifications -->
<div class="relative">
  <button onclick="toggleNotifications()" class="relative p-3 bg-white/10 rounded-xl hover:bg-white/20 transition-colors icon-bounce">
    <i class="fas fa-bell text-white text-lg"></i>
    <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-xs text-white font-bold">
  <?= $unread_count ?>
</span>

  </button>

  <!-- Notification Popup -->
  <div id="notificationPanel"
       class="hidden absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-gray-200 z-50">
    <div class="p-4 border-b border-gray-100 font-semibold text-gray-800">
      Notifications
    </div>

   <ul class="max-h-60 overflow-y-auto divide-y divide-gray-100">
  <?php if($notif_q && $notif_q->num_rows > 0): ?>
    <?php while($row = $notif_q->fetch_assoc()): ?>
      <li class="p-3 hover:bg-gray-50 transition">
        <p class="text-sm text-gray-700"><?= htmlspecialchars($row['message']) ?></p>
        <span class="text-xs text-gray-400"><?= date("F d, Y h:i A", strtotime($row['created_at'])) ?></span>
      </li>
    <?php endwhile; ?>
  <?php else: ?>
      <li class="p-3 text-gray-500">No notifications</li>
  <?php endif; ?>
</ul>


    <!-- Footer -->
    <div class="flex justify-between items-center px-4 py-2 border-t border-gray-100 text-sm">
      <a href="notifications.php" class="text-blue-600 font-medium hover:underline">See all</a>
      <button class="text-gray-500 hover:text-blue-600 font-medium" onclick="markAllRead()">Mark as read</button>
    </div>
  </div>
</div>

          <!-- Messages -->
          <button onclick="toggleMessagePanel()" class="p-3 bg-white/10 rounded-xl hover:bg-white/20 transition-colors icon-bounce">
            <i class="fas fa-envelope text-white text-lg"></i>
          </button>

          <!-- Calendar -->
          <button id="calendarIcon" onclick="toggleCalendar()" class="p-3 bg-white/10 rounded-xl hover:bg-white/20 transition-colors icon-bounce">
            <i class="fas fa-calendar-alt text-white text-lg"></i>
          </button>

          <!-- Profile Dropdown -->
          <div class="profile-dropdown">
            <button onclick="toggleProfileDropdown()" class="flex items-center space-x-3 p-2 bg-white/10 rounded-xl hover:bg-white/20 transition-colors">
             <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white/30">
  <img src="<?= htmlspecialchars($profileImage) ?>" alt="Profile" class="w-full h-full object-cover">
</div>

              <div class="hidden md:block text-left">
                <p class="text-white font-medium text-sm"><?= htmlspecialchars($fname) ?></p>
                <p class="text-white/70 text-xs">Student</p>
              </div>
              <i class="fas fa-chevron-down text-white/70 text-sm"></i>
            </button>
            
            <div id="profileDropdown" class="dropdown-content">
              <div class="profile-info">
                <div class="flex items-center space-x-3 mb-2">
                  <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-gray-300">
  <img src="<?= htmlspecialchars($profileImage) ?>" alt="Profile" class="w-full h-full object-cover">
</div>

                  <div>
                    <strong class="text-gray-800"><?= htmlspecialchars($fname) ?></strong><br>
                    <small class="text-gray-600"><?= htmlspecialchars($_SESSION['email']) ?></small>
                  </div>
                </div>
              </div>
              <a href="profile.php" class="dropdown-item">
                <i class="fas fa-user-circle mr-3 text-blue-500"></i>
                View Profile
              </a>
              <a href="settings.php" class="dropdown-item">
                <i class="fas fa-cog mr-3 text-gray-500"></i>
                Settings
              </a>

                <!-- 🌙 Dark Mode Toggle -->
              <a href="#" id="toggleDarkMode" class="dropdown-item">
              <i class="fas fa-moon mr-3 text-gray-500"></i>
              <span id="themeText">Dark Mode</span>
              </a>

              <a href="#" onclick="showLogoutModal()" class="dropdown-item text-red-600">
                <i class="fas fa-sign-out-alt mr-3"></i>
                Logout
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="container mx-auto px-6 py-8">
    
    <!-- Welcome Section -->
    <div class="mb-8 animate-fadeInUp">
      <div class="gradient-card rounded-2xl p-8 shadow-lg border border-gray-200 hover-lift">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">
              Welcome back, <?= htmlspecialchars($fname); ?>! 👋
            </h2>
            <p class="text-gray-600 text-lg">Ready to continue your guidance journey today?</p>
          </div>
          <div class="hidden md:block">
            <div class="w-20 h-20 gradient-bg rounded-2xl flex items-center justify-center animate-pulse-custom">
              <i class="fas fa-heart text-white text-2xl"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Chatbot Floating Button -->
<div id="chatHead" 
  class="fixed bottom-6 right-6 bg-gradient-to-r from-blue-500 to-purple-600 text-white 
  w-14 h-14 rounded-full flex items-center justify-center shadow-lg cursor-pointer 
  hover:scale-110 transition-transform duration-300 z-50">
  <i class="fas fa-comments text-2xl"></i>
</div>

<!-- Chatbot Chat Window -->
<div id="chatBox" 
  class="fixed bottom-24 right-6 bg-white w-96 h-[450px] rounded-2xl shadow-2xl border border-gray-200 
  hidden flex flex-col overflow-hidden z-50">

  <!-- Header -->
  <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-4 flex justify-between items-center">
    <h3 class="font-semibold text-sm">Guidy</h3>
    <button id="closeChat" class="text-white hover:text-gray-200">
      <i class="fas fa-times"></i>
    </button>
  </div>

 <!-- Messages -->
<div id="chatMessages" class="flex-1 p-3 overflow-y-auto space-y-2 text-sm">
    <?php
    $firstName = strtok($fname, ' '); // kunin ang string bago first space
    ?>
    <div class="bg-gray-100 p-2 rounded-lg text-gray-800 max-w-[80%]">
      👋 Hello <?= htmlspecialchars($firstName); ?>! What's up today?
    </div>
</div>


  <!-- Input -->
  <div class="p-3 border-t border-gray-200 flex items-center">
    <input id="chatInput" 
      type="text" 
      placeholder="Type a message..." 
      class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
    <button id="sendBtn" 
      class="ml-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg px-4 py-2 text-sm hover:opacity-90">
      <i class="fas fa-paper-plane"></i>
    </button>
  </div>
</div>

<!-- Motivation Image Banner -->
<div class="mb-8 animate-fadeInUp" style="animation-delay: 0.1s;">
  <div class="relative bg-gray-200 rounded-2xl shadow-xl p-6 h-[540px] flex items-center justify-center">

    <!-- Rectangle for Images -->
    <img id="motivationImage" src="image/inspire1.jpg" alt="Motivational Image"
     class="max-h-[540px] w-auto object-cover transition-opacity duration-1000">

    <!-- Prev/Next Buttons -->
    <button id="prevBtn" 
      class="absolute left-6 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-purple-600 px-4 py-2 rounded-full shadow-lg">
      ◀
    </button>
    <button id="nextBtn" 
      class="absolute right-6 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-purple-600 px-4 py-2 rounded-full shadow-lg">
      ▶
    </button>
  </div>
</div>

    <!-- Dashboard Layout with Left Content and Right Sidebar -->
    <div class="flex gap-8">
      <!-- Left Content Area -->
      <div class="flex-1">
        <!-- Mental Health & Wellness Modules -->
        <div class="animate-fadeInUp" style="animation-delay: 0.2s;">
          <div class="mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-2">🧠 Mental Health & Wellness</h3>
            <p class="text-gray-600 mb-6">Take care of your mental health with our comprehensive tools</p>
            
            <div class="module-grid">
              
              <!-- Mental Health Assessment -->
              <div class="module-card">
                <div class="module-header">
                  <div class="module-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                    <i class="fas fa-brain"></i>
                  </div>
                  <div class="module-title">
                    <h3>Mental Health Assessment</h3>
                    <p>Evaluate your current mental wellness state</p>
                  </div>
                </div>
                <div class="module-content">
                  <div class="module-stats">
                    <div class="stat-item">
                      <span class="stat-number">3</span>
                      <span class="stat-label">Tests Available</span>
                    </div>
                    <div class="stat-item">
                      <span class="stat-number">15min</span>
                      <span class="stat-label">Duration</span>
                    </div>
                  </div>
                </div>
                <div class="module-actions">
                  <a href="mental_health_test.php" class="module-btn primary">
                    <i class="fas fa-clipboard-check mr-2"></i>Take Test
                  </a>
                  <a href="test_history.php" class="module-btn secondary">
                    <i class="fas fa-history mr-2"></i>Results
                  </a>
                </div>
              </div>

              <!-- Wellness Library -->
              <div class="module-card">
                <div class="module-header">
                  <div class="module-icon" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                    <i class="fas fa-book-open"></i>
                  </div>
                  <div class="module-title">
                    <h3>Wellness Library</h3>
                    <p>Educational articles and reading materials</p>
                  </div>
                </div>
                <div class="module-content">
                  <div class="module-stats">
                    <div class="stat-item">
                      <span class="stat-number">45</span>
                      <span class="stat-label">Articles</span>
                    </div>
                    <div class="stat-item">
                      <span class="stat-number">12</span>
                      <span class="stat-label">Categories</span>
                    </div>
                  </div>
                </div>
                <div class="module-actions">
                  <a href="wellness_library.php" class="module-btn primary">
                    <i class="fas fa-book mr-2"></i>Browse Library
                  </a>
                  <a href="bookmarks.php" class="module-btn secondary">
                    <i class="fas fa-bookmark mr-2"></i>Saved Articles
                  </a>
                </div>
              </div>

              <!-- Daily Tips & Insights -->
              <div class="module-card">
                <div class="module-header">
                  <div class="module-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <i class="fas fa-lightbulb"></i>
                  </div>
                  <div class="module-title">
                    <h3>Daily Tips & Insights</h3>
                    <p>Practical advice for mental wellness</p>
                  </div>
                </div>
                <div class="module-content">
                  <div class="module-stats">
                    <div class="stat-item">
                      <span class="stat-number">New</span>
                      <span class="stat-label">Daily Tips</span>
                    </div>
                    <div class="stat-item">
                      <span class="stat-number">2min</span>
                      <span class="stat-label">Read Time</span>
                    </div>
                  </div>
                </div>
                <div class="module-actions">
                  <a href="daily_tips.php" class="module-btn primary">
                    <i class="fas fa-calendar-day mr-2"></i>Today's Tip
                  </a>
                  <a href="tip_archive.php" class="module-btn secondary">
                    <i class="fas fa-archive mr-2"></i>All Tips
                  </a>
                </div>
              </div>

              <!-- Stress Management Guide -->
              <div class="module-card">
                <div class="module-header">
                  <div class="module-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <i class="fas fa-leaf"></i>
                  </div>
                  <div class="module-title">
                    <h3>Stress Management Guide</h3>
                    <p>Comprehensive stress relief techniques</p>
                  </div>
                </div>
                <div class="module-content">
                  <div class="module-stats">
                    <div class="stat-item">
                      <span class="stat-number">8</span>
                      <span class="stat-label">Techniques</span>
                    </div>
                    <div class="stat-item">
                      <span class="stat-number">5min</span>
                      <span class="stat-label">Quick Relief</span>
                    </div>
                  </div>
                </div>
                <div class="module-actions">
                  <a href="stress_guide.php" class="module-btn primary">
                    <i class="fas fa-spa mr-2"></i>Learn Techniques
                  </a>
                  <a href="stress_test.php" class="module-btn secondary">
                    <i class="fas fa-heartbeat mr-2"></i>Stress Test
                  </a>
                </div>
              </div>

              <!-- Mindfulness & Meditation -->
              <div class="module-card">
                <div class="module-header">
                  <div class="module-icon" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);">
                    <i class="fas fa-om"></i>
                  </div>
                  <div class="module-title">
                    <h3>Mindfulness & Meditation</h3>
                    <p>Guided practices for inner peace</p>
                  </div>
                </div>
                <div class="module-content">
                  <div class="module-stats">
                    <div class="stat-item">
                      <span class="stat-number">20</span>
                      <span class="stat-label">Exercises</span>
                    </div>
                    <div class="stat-item">
                      <span class="stat-number">3-30min</span>
                      <span class="stat-label">Sessions</span>
                    </div>
                  </div>
                </div>
                <div class="module-actions">
                  <a href="meditation_guide.php" class="module-btn primary">
                    <i class="fas fa-play mr-2"></i>Start Session
                  </a>
                  <a href="mindfulness_tips.php" class="module-btn secondary">
                    <i class="fas fa-brain mr-2"></i>Learn More
                  </a>
                </div>
              </div>

              <!-- Coping Strategies Hub -->
              <div class="module-card">
                <div class="module-header">
                  <div class="module-icon" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                    <i class="fas fa-hands-helping"></i>
                  </div>
                  <div class="module-title">
                    <h3>Coping Strategies</h3>
                    <p>Practical tools for difficult situations</p>
                  </div>
                </div>
                <div class="module-content">
                  <div class="module-stats">
                    <div class="stat-item">
                      <span class="stat-number">15</span>
                      <span class="stat-label">Strategies</span>
                    </div>
                    <div class="stat-item">
                      <span class="stat-number">Step-by-Step</span>
                      <span class="stat-label">Guides</span>
                    </div>
                  </div>
                </div>
                <div class="module-actions">
                  <a href="coping_strategies.php" class="module-btn primary">
                    <i class="fas fa-toolbox mr-2"></i>View Strategies
                  </a>
                  <a href="emergency_coping.php" class="module-btn secondary">
                    <i class="fas fa-first-aid mr-2"></i>Quick Help
                  </a>
                </div>
              </div>

              <!-- Self-Care Planner -->
              <div class="module-card">
                <div class="module-header">
                  <div class="module-icon" style="background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);">
                    <i class="fas fa-heart"></i>
                  </div>
                  <div class="module-title">
                    <h3>Self-Care Planner</h3>
                    <p>Personalized wellness activities</p>
                  </div>
                </div>
                <div class="module-content">
                  <div class="module-stats">
                    <div class="stat-item">
                      <span class="stat-number">25</span>
                      <span class="stat-label">Activities</span>
                    </div>
                    <div class="stat-item">
                      <span class="stat-number">Custom</span>
                      <span class="stat-label">Plans</span>
                    </div>
                  </div>
                </div>
                <div class="module-actions">
                  <a href="self_care_plan.php" class="module-btn primary">
                    <i class="fas fa-calendar-plus mr-2"></i>Create Plan
                  </a>
                  <a href="self_care_ideas.php" class="module-btn secondary">
                    <i class="fas fa-lightbulb mr-2"></i>Get Ideas
                  </a>
                </div>
              </div>

              <!-- Mental Health Resources -->
              <div class="module-card">
                <div class="module-header">
                  <div class="module-icon" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
                    <i class="fas fa-shield-heart"></i>
                  </div>
                  <div class="module-title">
                    <h3>Mental Health Resources</h3>
                    <p>Comprehensive support materials</p>
                  </div>
                </div>
                <div class="module-content">
                  <div class="module-stats">
                    <div class="stat-item">
                      <span class="stat-number">30+</span>
                      <span class="stat-label">Resources</span>
                    </div>
                    <div class="stat-item">
                      <span class="stat-number">24/7</span>
                      <span class="stat-label">Access</span>
                    </div>
                  </div>
                </div>
                <div class="module-actions">
                  <a href="mental_health_resources.php" class="module-btn primary">
                    <i class="fas fa-folder-open mr-2"></i>Browse All
                  </a>
                  <a href="emergency_resources.php" class="module-btn secondary">
                    <i class="fas fa-phone mr-2"></i>Emergency
                  </a>
                </div>
              </div>

              <!-- Crisis Support -->
              <div class="module-card">
                <div class="module-header">
                  <div class="module-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                    <i class="fas fa-phone-alt"></i>
                  </div>
                  <div class="module-title">
                    <h3>Crisis Support</h3>
                    <p>Immediate help when you need it most</p>
                  </div>
                </div>
                <div class="module-content">
                  <div class="module-stats">
                    <div class="stat-item">
                      <span class="stat-number">24/7</span>
                      <span class="stat-label">Hotline</span>
                    </div>
                    <div class="stat-item">
                      <span class="stat-number">Instant</span>
                      <span class="stat-label">Response</span>
                    </div>
                  </div>
                </div>
                <div class="module-actions">
                  <a href="tel:988" class="module-btn primary" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                    <i class="fas fa-phone mr-2"></i>Call Now
                  </a>
                  <a href="crisis_resources.php" class="module-btn secondary">
                    <i class="fas fa-info-circle mr-2"></i>Resources
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Sidebar -->
      <div class="w-80 space-y-6">


        <!-- Quick Announcements -->
        <div class="animate-fadeInUp" style="animation-delay: 0.4s;">
          <div class="gradient-card rounded-2xl p-6 shadow-lg border border-gray-200 hover-lift">
            <div class="flex items-center space-x-3 mb-4">
              <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                <i class="fas fa-bullhorn text-white"></i>
              </div>
              <div>
                <h3 class="text-lg font-bold text-gray-800">📢 Quick Updates</h3>
                <p class="text-sm text-gray-600">Latest announcements</p>
              </div>
            </div>
            
            
    <div class="space-y-3">
      <?php
        $announcement_q = $con->query("SELECT * FROM exam_announcements ORDER BY created_at DESC LIMIT 5");
        if ($announcement_q && $announcement_q->num_rows > 0):
          while ($row = $announcement_q->fetch_assoc()):
      ?>
        <div class="p-3 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg border-l-4 border-blue-500">
          <h4 class="text-sm font-semibold text-gray-800 mb-1">
            <?= htmlspecialchars($row['title']) ?>
          </h4>
          <p class="text-xs text-gray-600 mb-2">
            <?= nl2br(htmlspecialchars($row['message'])) ?>
          </p>
          <span class="text-xs text-blue-600 font-medium">
            <?= date("F d, Y h:i A", strtotime($row['created_at'])) ?>
          </span>

  
        </div>
      <?php 
          endwhile; 
        else: 
      ?>
        <p class="text-gray-500 text-sm">No announcements yet.</p>
      <?php endif; ?>
    </div>
            
   <a
  href="announcements.php"
  class="w-full mt-4 bg-gradient-to-r from-orange-500 to-red-500 text-white py-2 px-4 rounded-lg font-medium hover:from-orange-600 hover:to-red-600 transition-all duration-300 text-sm flex items-center justify-center"
>
  <i class="fas fa-eye mr-2"></i> View All Announcements
</a>

          </div>
        </div>


  <!-- Mini Calendar Widget -->
<div class="animate-fadeInUp" style="animation-delay: 0.6s;">
  <div class="gradient-card rounded-2xl p-6 shadow-lg border border-gray-200 hover-lift">
       
  <!-- Top Widget Title with Icon -->
    <div class="flex items-center mb-4">
      <i class="widget-icon fa-kit fa-calendar-day text-xl text-blue-600 mr-2"></i>
      <h2 class="text-lg font-bold text-gray-800">Calendar</h2>
    </div>

        <!-- Month Navigation -->
    <div class="calendar-header flex justify-between items-center mb-4">
      <button onclick="changeMonth(-1)" class="text-gray-500 font-bold">&lt;</button>
      <h2 id="smallMonthName" class="small-month-name text-lg font-bold text-gray-800"></h2>
      <button onclick="changeMonth(1)" class="text-gray-500 font-bold">&gt;</button>
    </div>
    <div class="calendar-container">
      <table class="w-full text-center text-sm">
        <thead>
          <tr>
            <th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th>
          </tr>
        </thead>
        <tbody id="calendarBody">
          
          <!-- Dates will be injected here -->
        </tbody>
      </table>
    </div>

    <!-- Small Widget Links -->
    <div class="small_widget_links mt-4 flex justify-between text-sm">
      <a href="full_calendar.php" class="text-blue-600 hover:underline">Full Calendar</a>
      <a href="#" onclick="widget_show_hide(this); return false;" class="text-gray-500 hover:underline">Hide</a>
    </div>
  </div>
</div>


        <!-- Quick Actions -->
        <div class="animate-fadeInUp" style="animation-delay: 0.5s;">
          <div class="gradient-card rounded-2xl p-6 shadow-lg border border-gray-200 hover-lift">
            <div class="flex items-center space-x-3 mb-4">
              <div class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center">
                <i class="fas fa-bolt text-white"></i>
              </div>
              <div>
                <h3 class="text-lg font-bold text-gray-800">⚡ Quick Actions</h3>
                <p class="text-sm text-gray-600">Fast access to key features</p>
              </div>
            </div>
            
            <div class="space-y-3">
              <button onclick="window.location.href='appointments.php';" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:from-blue-600 hover:to-blue-700 transition-all duration-300 text-sm flex items-center justify-center">
            <i class="fas fa-calendar-plus mr-2"></i>Book Appointment
            </button>

              
              <button onclick="window.location.href='mental_health_test.php';" class="w-full bg-gradient-to-r from-purple-500 to-purple-600 text-white py-3 px-4 rounded-lg font-medium hover:from-purple-600 hover:to-purple-700 transition-all duration-300 text-sm flex items-center justify-center">
                <i class="fas fa-brain mr-2"></i>Take Assessment
              </button>
              
              <button onclick="window.location.href='emergency_resources.php';" class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-3 px-4 rounded-lg font-medium hover:from-green-600 hover:to-green-700 transition-all duration-300 text-sm flex items-center justify-center">
                <i class="fas fa-phone mr-2"></i>Emergency Support
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>


  </main>

<!-- Compose Message Modal -->
<div id="composeModal" class="modal-overlay" style="display: none;">
  <div class="modal">
    <!-- Modal Header -->
    <div class="modal-header">
      <div class="modal-title">
        <i class="fas fa-paper-plane"></i>
        Compose Message
      </div>
      <button class="close-btn" onclick="closeModal()">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <!-- Modal Body -->
    <div class="modal-body">
      <form id="composeForm">
        <!-- To Field -->
        <div class="form-group">
          <label for="to"><i class="fas fa-user"></i> To:</label>
          <input type="text" id="to" name="to" placeholder="Enter recipient..." required>
        </div>

        <!-- Subject Field -->
        <div class="form-group">
          <label for="subject"><i class="fas fa-heading"></i> Subject:</label>
          <input type="text" id="subject" name="subject" placeholder="Enter subject..." required>
        </div>

        <!-- Message Field -->
        <div class="form-group">
          <label for="message"><i class="fas fa-edit"></i> Message:</label>
          <textarea id="message" name="message" placeholder="Write your message..." required></textarea>
        </div>

        <!-- Attachment Field -->
        <div class="form-group">
          <label><i class="fas fa-paperclip"></i> Attachment (Optional):</label>
          <div class="file-upload">
            <input type="file" id="attachment" name="attachment">
            <label for="attachment" class="file-upload-btn">
              <i class="fas fa-upload"></i>
              Choose File
            </label>
            <span class="file-name" id="fileName">No file selected</span>
          </div>
        </div>

        
        <!-- ✅ Send Button -->
        <div class="form-actions" style="text-align: right; margin-top: 20px;">
          <button type="submit" class="send-btn">
            <i class="fas fa-paper-plane mr-2"></i> Send
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
      


<!-- ✅ Message Panel -->
<div id="messagePanel" class="message-panel">
  <div class="message-header">
    <h3><i class="fas fa-envelope mr-2"></i>Message Center</h3>
    <button onclick="toggleMessagePanel()" class="text-white hover:text-gray-200 transition-colors">
      <i class="fas fa-times text-xl"></i>
    </button>
  </div>
  <div class="message-body">
    <a href="inbox.php" class="message-link">
      <i class="fas fa-inbox mr-3 text-blue-500"></i>
      <span class="font-medium">Inbox</span>
      <span class="ml-auto bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">5</span>
    </a>
    <a href="compose_message.php" class="message-link">
      <i class="fas fa-edit mr-3 text-green-500"></i>
      <span class="font-medium">Compose Message</span>
    </a>
    <a href="sent_messages.php" class="message-link">
      <i class="fas fa-paper-plane mr-3 text-purple-500"></i>
      <span class="font-medium">Sent Messages</span>
    </a>
  </div>
</div>


  <!-- Calendar -->
  <div id="mini-calendar" class="mini-calendar">
    <div class="calendar-popup">
      <div id="calendarContainer"></div>
    </div>
  </div>

  <!-- Logout Modal -->
  <div id="logoutModal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div class="bg-white rounded-2xl p-8 text-center max-w-sm mx-4 shadow-2xl">
      <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-sign-out-alt text-red-500 text-xl"></i>
      </div>
      <h3 class="text-xl font-bold text-gray-800 mb-2">Confirm Logout</h3>
      <p class="text-gray-600 mb-6">Are you sure you want to logout from your account?</p>
      <div class="flex space-x-3">
        <button onclick="window.location.href='logout.php'" class="flex-1 bg-red-500 text-white py-3 px-4 rounded-xl font-medium hover:bg-red-600 transition-colors">
          <i class="fas fa-check mr-2"></i>Yes, Logout
        </button>
        <button onclick="hideLogoutModal()" class="flex-1 bg-gray-200 text-gray-800 py-3 px-4 rounded-xl font-medium hover:bg-gray-300 transition-colors">
          <i class="fas fa-times mr-2"></i>Cancel
        </button>
      </div>
    </div>
  </div>

  </div> <!-- End Main Content Wrapper -->

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  // -------------------- 🔔 Notification Popup --------------------
function toggleNotifications() {
  const panel = document.getElementById("notificationPanel");
  panel.classList.toggle("hidden");
}

// Close notification panel when clicking outside
window.addEventListener('click', function(e) {
  const panel = document.getElementById('notificationPanel');
  const bell = e.target.closest('button[onclick="toggleNotifications()"]');
  if (!panel.contains(e.target) && !bell) {
    panel.classList.add('hidden');
  }
});

// Mark all notifications as read
function markAllRead() {
  const badge = document.querySelector('.fa-bell + span');
  if (badge) badge.style.display = 'none';
  alert('All notifications marked as read.');
}

// -------------------- Chatbot Script --------------------
const chatHead = document.getElementById('chatHead');
const chatBox = document.getElementById('chatBox');
const closeChat = document.getElementById('closeChat');
const chatMessages = document.getElementById('chatMessages');
const chatInput = document.getElementById('chatInput');
const sendBtn = document.getElementById('sendBtn');

let botRole = "neutral"; // default bot mood

// -------------------- Helper Functions --------------------

// Update bot role based on user input
function updateBotRole(userText) {
  const text = userText.toLowerCase();

  if (text.includes("hahaha") || text.includes("lol") || text.includes("hehe")) {
    botRole = "friendly"; // happy/funny mode
  } else if (text.includes("sad") || text.includes("unhappy") || text.includes("miss")) {
    botRole = "empathetic"; // sad/serious mode
  } else if (text.includes("angry") || text.includes("frustrated")) {
    botRole = "stern"; // serious/angry mode
  } else {
    botRole = "neutral"; // default
  }
}

// Add reaction emoji based on user message
function getBotReaction(userText) {
  const text = userText.toLowerCase();

  if (text.includes("hahaha") || text.includes("lol") || text.includes("hehe")) {
    return "😂"; 
  } else if (text.includes("sad") || text.includes("unhappy") || text.includes("miss")) {
    return "😢"; 
  } else if (text.includes("angry") || text.includes("frustrated")) {
    return "😡"; 
  } else if (text.includes("love") || text.includes("like")) {
    return "❤️"; 
  } else if (text.includes("wow") || text.includes("amazing") || text.includes("surprise")) {
    return "😲"; 
  } else {
    return ""; 
  }
}

// -------------------- Event Listeners --------------------

// Toggle chatbot visibility
chatHead.addEventListener('click', () => {
  chatBox.classList.toggle('hidden');
  if (!chatBox.classList.contains('hidden')) chatInput.focus();
});

closeChat.addEventListener('click', () => chatBox.classList.add('hidden'));

// Send message
sendBtn.addEventListener('click', sendChatMessage);
chatInput.addEventListener('keypress', e => {
  if (e.key === 'Enter') sendChatMessage();
});

// -------------------- Send Chat Message --------------------
async function sendChatMessage() {
  const text = chatInput.value.trim();
  if (!text) return;

  const userMsg = document.createElement('div');
  userMsg.className = 'bg-blue-500 text-white p-2 rounded-lg ml-auto max-w-[80%]';
  userMsg.textContent = text;
  chatMessages.appendChild(userMsg);
  chatMessages.scrollTop = chatMessages.scrollHeight;

  chatInput.value = '';

  updateBotRole(text);
  
    
 // Prepare prompt for Gemini
let userPrompt = "Please respond naturally in Taglish and friendly tone: " + text;

// Check if there's already a bot message being typed
let botMsg = chatMessages.querySelector('.bot-typing');
if (!botMsg) {
    botMsg = document.createElement('div');
    botMsg.className = 'bg-gray-100 p-2 rounded-lg text-gray-800 max-w-[80%] italic bot-typing';
    chatMessages.appendChild(botMsg);
}

// Set initial 'Typing...' text
botMsg.textContent = 'Typing';
let dotCount = 0;
let typingInterval = setInterval(() => {
    dotCount = (dotCount + 1) % 4; // cycles 0,1,2,3
    botMsg.textContent = 'Typing' + '.'.repeat(dotCount);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}, 500); // every 500ms

// Fetch bot reply
let reply = await getBotReply(text);

// Stop typing animation before starting letter-by-letter
clearInterval(typingInterval);

// Sanitize reply
reply = reply.replace(/As an AI language model,.*?/, "").replace(/\n/g, " ").trim();
const reaction = getBotReaction(text);
if (reaction) reply += " " + reaction;
if (botRole === "friendly") reply = "😊 " + reply;
if (botRole === "empathetic") reply = "💛 " + reply;
if (botRole === "stern") reply = "😠 " + reply;

// Clear 'Typing...' text before letter-by-letter animation
botMsg.textContent = '';
let i = 0;

// Letter-by-letter animation
let interval = setInterval(() => {
    botMsg.textContent += reply[i];
    i++;
    chatMessages.scrollTop = chatMessages.scrollHeight;
    if (i >= reply.length) {
        clearInterval(interval);
        botMsg.classList.remove('bot-typing', 'italic');
    }
}, 50);

}

// -------------------- GPT Reply --------------------
async function getBotReply(text) {
  try {
    const response = await fetch("gemini.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "prompt=" + encodeURIComponent(text)
    });

    const reply = await response.text();
    return reply || "🤖 Sorry, no response received.";
  } catch (err) {
    console.error(err);
    return "⚠️ Sorry, I’m having trouble connecting to the chatbot server right now.";
  }
}

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeModal();
});

window.addEventListener('load', function() {
  setTimeout(function() {
    document.getElementById('loadingScreen').style.display = 'none';
  }, 1500);
});

function toggleMessagePanel() {
  const panel = document.getElementById("messagePanel");
  panel.classList.toggle("show");
}

function toggleCalendar() {
  const cal = document.getElementById("mini-calendar");
  cal.style.display = (cal.style.display === "block") ? "none" : "block";
}

document.addEventListener("click", function (event) {
  const calendar = document.getElementById("mini-calendar");
  const icon = document.getElementById("calendarIcon");
  if (!calendar.contains(event.target) && !icon.contains(event.target)) {
    calendar.style.display = "none";
  }
});

function toggleProfileDropdown() {
  const dropdown = document.getElementById("profileDropdown");
  dropdown.classList.toggle("show");
}

document.addEventListener("click", function(e) {
  const dropdown = document.getElementById("profileDropdown");
  const profileBtn = dropdown.parentElement.querySelector("button");
  if (!dropdown.contains(e.target) && !profileBtn.contains(e.target)) {
    dropdown.classList.remove("show");
  }
});

flatpickr("#calendarContainer", {
  inline: true,
  defaultDate: "today"
});

function showLogoutModal() {
  document.getElementById("logoutModal").style.display = "flex";
}

function hideLogoutModal() {
  document.getElementById("logoutModal").style.display = "none";
}

document.addEventListener("click", function(e) {
  const panel = document.getElementById("messagePanel");
  const messageBtn = document.querySelector("button[onclick='toggleMessagePanel()']");
  if (!panel.contains(e.target) && !messageBtn.contains(e.target)) {
    panel.classList.remove("show");
  }
});

const images = [
  "image/inspire1.jpg",
  "image/inspire2.jpg",
  "image/inspire3.jpg",
  "image/inspire4.jpg",
  "image/inspire5.jpg"
];
let currentIndex = 0;

const img = document.getElementById("motivationImage");
const prevBtn = document.getElementById("prevBtn");
const nextBtn = document.getElementById("nextBtn");

function showImage(index) {
  img.style.opacity = 0;
  setTimeout(() => {
    img.src = images[index];
    img.style.opacity = 1;
  }, 300);
}

setInterval(() => {
  currentIndex = (currentIndex + 1) % images.length;
  showImage(currentIndex);
}, 5000);

nextBtn.addEventListener("click", () => {
  currentIndex = (currentIndex + 1) % images.length;
  showImage(currentIndex);
});

prevBtn.addEventListener("click", () => {
  currentIndex = (currentIndex - 1 + images.length) % images.length;
  showImage(currentIndex);
});

const searchInput = document.getElementById("searchInput");
const resultsBox = document.getElementById("searchResults");
const resultsList = document.getElementById("resultsList");

searchInput.addEventListener("input", function () {
  let query = this.value.trim();
  if (query.length < 2) {
    resultsBox.classList.add("hidden");
    return;
  }

  fetch("search.php?query=" + encodeURIComponent(query))
    .then(res => res.json())
    .then(data => {
      resultsList.innerHTML = "";
      if (data.length === 0) {
        resultsList.innerHTML = "<li class='p-2 text-gray-500'>No results found</li>";
      } else {
        data.forEach(student => {
          let li = document.createElement("li");
          li.className = "p-2 hover:bg-gray-100 cursor-pointer";
          li.innerHTML = `
            <strong>${student.full_name}</strong><br>
            <small>${student.student_no}</small>
          `;
          li.addEventListener("click", () => {
            window.location.href = `profile.php?student_no=${student.student_no}`;
          });
          resultsList.appendChild(li);
        });
      }
      resultsBox.classList.remove("hidden");
    });
});

let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();

function changeMonth(delta) {
  currentMonth += delta;
  if (currentMonth < 0) {
    currentMonth = 11;
    currentYear--;
  } else if (currentMonth > 11) {
    currentMonth = 0;
    currentYear++;
  }
  updateCalendar();
}

function updateCalendar() {
  const monthNames = [
    "January","February","March","April","May","June",
    "July","August","September","October","November","December"
  ];
  document.getElementById('smallMonthName').innerText = `${monthNames[currentMonth]} ${currentYear}`;

  const tbody = document.getElementById('calendarBody');
  tbody.innerHTML = "";

  const firstDay = new Date(currentYear, currentMonth, 1).getDay();
  const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
  const today = new Date();

  let date = 1;

  for (let i = 0; i < 6; i++) {
    let row = document.createElement("tr");
    for (let j = 0; j < 7; j++) {
      let cell = document.createElement("td");
      if (i === 0 && j < firstDay) {
        cell.innerHTML = "";
      } else if (date > daysInMonth) {
        cell.innerHTML = "";
      } else {
        cell.innerHTML = date;
        if (date === today.getDate() && currentMonth === today.getMonth() && currentYear === today.getFullYear()) {
          cell.classList.add("today");
        }
        date++;
      }
      row.appendChild(cell);
    }
    tbody.appendChild(row);
  }
}

updateCalendar();

document.addEventListener("click", (e) => {
  if (!resultsBox.contains(e.target) && e.target !== searchInput) {
    resultsBox.classList.add("hidden");
  }
});

// -------------------- 🌙 Dark Mode Toggle --------------------
document.addEventListener('DOMContentLoaded', () => {
  const toggleDarkMode = document.getElementById('toggleDarkMode');
  const themeText = document.getElementById('themeText');

  if (!toggleDarkMode) return; // in case dropdown not yet rendered

  if (localStorage.getItem('theme') === 'dark') {
    document.body.classList.add('dark-mode');
    themeText.textContent = 'Light Mode';
    toggleDarkMode.querySelector('i').classList.replace('fa-moon', 'fa-sun');
  }

  toggleDarkMode.addEventListener('click', (e) => {
    e.preventDefault();
    document.body.classList.toggle('dark-mode');
    if (document.body.classList.contains('dark-mode')) {
      localStorage.setItem('theme', 'dark');
      themeText.textContent = 'Light Mode';
      toggleDarkMode.querySelector('i').classList.replace('fa-moon', 'fa-sun');
    } else {
      localStorage.setItem('theme', 'light');
      themeText.textContent = 'Dark Mode';
      toggleDarkMode.querySelector('i').classList.replace('fa-sun', 'fa-moon');
    }
  });
});
</script>
