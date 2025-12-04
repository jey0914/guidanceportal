<?php
session_start();
include("db.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$sender_email = $_SESSION['email'];

// Get sender name from form table
$query = $con->prepare("SELECT fname, lname FROM form WHERE email = ?");
$query->bind_param("s", $sender_email);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();
$sender_name = $user ? $user['fname'] . ' ' . $user['lname'] : 'Unknown User';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiver_email = $_POST['receiver_email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $date_sent = date("Y-m-d H:i:s");

    // Get receiver name from DB
    $rquery = $con->prepare("SELECT fname, lname FROM form WHERE email = ?");
    $rquery->bind_param("s", $receiver_email);
    $rquery->execute();
    $rresult = $rquery->get_result();
    $ruser = $rresult->fetch_assoc();
    $receiver_name = $ruser ? $ruser['fname'] . ' ' . $ruser['lname'] : 'Unknown Receiver';

    // Handle attachment upload
    $attachment = null;
    if (!empty($_FILES['attachment']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir);
        $fileName = time() . "_" . basename($_FILES['attachment']['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetFile)) {
            $attachment = $targetFile;
        }
    }

    // Insert message into DB
    $insert = $con->prepare("
        INSERT INTO messages (sender_email, sender_name, receiver_email, receiver_name, subject, message, attachment, date_sent)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $insert->bind_param("ssssssss", $sender_email, $sender_name, $receiver_email, $receiver_name, $subject, $message, $attachment, $date_sent);

    if ($insert->execute()) {
        echo "<script>alert('Message sent successfully!'); window.location.href='inbox.php';</script>";
    } else {
        echo "<script>alert('Error sending message.');</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GP Dashboard - Compose Message</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
        
        /* Main Content Area */
        .main-content {
            margin-left: 300px;
            min-height: 100vh;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
       .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            transition: border-color 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
        }
        
        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-success {
            background: #10b981;
            color: white;
        }
        
        .btn-success:hover {
            background: #059669;
        }
        
        .btn-secondary {
            background: #f1f5f9;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }
        
        .btn-secondary:hover {
            background: #e2e8f0;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-menu-btn {
                display: block;
            }
        }
        
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px;
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

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

    <!-- Main Content -->
    <div class="main-content">
        <div class="p-6">
            <div class="max-w-2xl mx-auto">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-edit text-blue-600 mr-2"></i>
                    Compose Message
                </h1>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">To (Receiver Email)</label>
                            <input type="email" name="receiver_email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="message" rows="5" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Attachment (optional)</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-success">Send</button>
                        <a href="inbox.php" class="btn btn-secondary">Back to Inbox</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
        }
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98f6146ce5370dc9',t:'MTc2MDYwMjIyNy4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>