<?php
session_start();
include("db.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

$stmt = $con->prepare("SELECT fname FROM form WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Reports</title>
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
    
    .report-card {
      background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
      border: 1px solid #e2e8f0;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .report-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: var(--card-gradient);
    }
    
    .report-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
    }
    
    .report-card:hover .report-icon {
      transform: scale(1.1);
    }
    
    .report-icon {
      transition: transform 0.3s ease;
    }
    
    .academic-card {
      --card-gradient: linear-gradient(90deg, #3b82f6, #1d4ed8);
    }
    
    .administrative-card {
      --card-gradient: linear-gradient(90deg, #ef4444, #dc2626);
    }
    
    .general-card {
      --card-gradient: linear-gradient(90deg, #10b981, #059669);
    }
    
    .special-card {
      --card-gradient: linear-gradient(90deg, #8b5cf6, #7c3aed);
    }
    
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .animate-fadeInUp {
      animation: fadeInUp 0.6s ease-out;
    }
    
    .stats-badge {
      background: rgba(59, 130, 246, 0.1);
      color: #1d4ed8;
      font-size: 0.75rem;
      font-weight: 600;
      padding: 0.25rem 0.5rem;
      border-radius: 9999px;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen">

  <!-- Sidebar -->
  <div class="sidebar fixed left-0 top-0 h-full w-64 text-white z-50">
    <div class="p-6">
      <div class="flex items-center space-x-3 mb-8">
        <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center">
          <i class="fas fa-graduation-cap text-white"></i>
        </div>
        <div>
          <h2 class="text-xl font-bold">Guidance Office</h2>
          <p class="text-sm text-slate-300">Student Portal</p>
        </div>
      </div>
      
      <nav class="space-y-2">
        <a href="dashboard.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl">
          <i class="fas fa-home w-5"></i>
          <span class="font-medium">Dashboard</span>
        </a>
        
        <?php if (stripos($_SESSION['strand_course'], 'SHS') !== false || stripos($_SESSION['year_level'], 'Grade') !== false): ?>
        <a href="student_records.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl">
          <i class="fas fa-clipboard-check w-5"></i>
          <span class="font-medium">Attendance</span>
        </a>
        <?php endif; ?>
        
        <a href="appointments.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl">
          <i class="fas fa-calendar-check w-5"></i>
          <span class="font-medium">Appointments</span>
        </a>
        
        <a href="student_reports.php" class="sidebar-link active flex items-center space-x-3 px-4 py-3 rounded-xl">
          <i class="fas fa-file-alt w-5"></i>
          <span class="font-medium">Reports</span>
        </a>
        
        <a href="settings.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl">
          <i class="fas fa-cog w-5"></i>
          <span class="font-medium">Settings</span>
        </a>
      </nav>
    </div>
  </div>

  <!-- Main Content -->
  <div class="ml-64 min-h-screen">
    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-md shadow-lg sticky top-0 z-40">
      <div class="px-8 py-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-800">📑 My Reports</h1>
            <p class="text-gray-600 mt-1">Access your academic and administrative reports</p>
          </div>
        </div>
      </div>
    </header>

    <div class="p-8">
      <!-- Academic Reports Section -->
      <div class="mb-12">
        <div class="flex items-center space-x-3 mb-6">
          <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
            <i class="fas fa-graduation-cap text-blue-600"></i>
          </div>
          <h2 class="text-2xl font-bold text-gray-800">Academic Reports</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <a href="student_counseling.php" class="block animate-fadeInUp" style="animation-delay: 0.1s;">
            <div class="report-card academic-card p-6 h-full">
              <div class="flex items-start justify-between mb-4">
                <div class="report-icon w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                  <i class="fas fa-brain text-blue-600 text-2xl"></i>
                </div>
                <span class="stats-badge">View History</span>
              </div>
              <h3 class="text-xl font-bold text-gray-800 mb-2">Counseling History</h3>
              <p class="text-gray-600 mb-4">Review your past counseling sessions and progress notes</p>
              <div class="flex items-center text-blue-600 font-medium">
                <span>Access Records</span>
                <i class="fas fa-arrow-right ml-2"></i>
              </div>
            </div>
          </a>

          <?php if (stripos($_SESSION['strand_course'], 'SHS') !== false || stripos($_SESSION['year_level'], 'Grade') !== false): ?>
          <a href="student_attendance.php" class="block animate-fadeInUp" style="animation-delay: 0.2s;">
            <div class="report-card academic-card p-6 h-full">
              <div class="flex items-start justify-between mb-4">
                <div class="report-icon w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                  <i class="fas fa-clock text-blue-600 text-2xl"></i>
                </div>
                <span class="stats-badge">Monthly View</span>
              </div>
              <h3 class="text-xl font-bold text-gray-800 mb-2">Attendance Summary</h3>
              <p class="text-gray-600 mb-4">Track your monthly attendance record and patterns</p>
              <div class="flex items-center text-blue-600 font-medium">
                <span>View Summary</span>
                <i class="fas fa-arrow-right ml-2"></i>
              </div>
            </div>
          </a>
          <?php endif; ?>
        </div>
      </div>

      <!-- Administrative Reports Section -->
      <div class="mb-12">
        <div class="flex items-center space-x-3 mb-6">
          <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
            <i class="fas fa-shield-alt text-red-600"></i>
          </div>
          <h2 class="text-2xl font-bold text-gray-800">Administrative Reports</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <a href="student_incident_reports.php" class="block animate-fadeInUp" style="animation-delay: 0.3s;">
            <div class="report-card administrative-card p-6 h-full">
              <div class="flex items-start justify-between mb-4">
                <div class="report-icon w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center">
                  <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <span class="stats-badge bg-red-100 text-red-700">Check Status</span>
              </div>
              <h3 class="text-xl font-bold text-gray-800 mb-2">Incident Reports</h3>
              <p class="text-gray-600 mb-4">View any incident reports filed regarding your conduct</p>
              <div class="flex items-center text-red-600 font-medium">
                <span>View Reports</span>
                <i class="fas fa-arrow-right ml-2"></i>
              </div>
            </div>
          </a>

          <a href="view_special_exam.php" class="block animate-fadeInUp" style="animation-delay: 0.4s;">
            <div class="report-card special-card p-6 h-full">
              <div class="flex items-start justify-between mb-4">
                <div class="report-icon w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">
                  <i class="fas fa-file-medical text-purple-600 text-2xl"></i>
                </div>
                <span class="stats-badge bg-purple-100 text-purple-700">Check Eligibility</span>
              </div>
              <h3 class="text-xl font-bold text-gray-800 mb-2">Special Exam Requests</h3>
              <p class="text-gray-600 mb-4">Check status of your special examination requests</p>
              <div class="flex items-center text-purple-600 font-medium">
                <span>View Status</span>
                <i class="fas fa-arrow-right ml-2"></i>
              </div>
            </div>
          </a>
        </div>
      </div>

      <!-- General Reports Section -->
      <div class="mb-12">
        <div class="flex items-center space-x-3 mb-6">
          <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
            <i class="fas fa-clipboard-list text-green-600"></i>
          </div>
          <h2 class="text-2xl font-bold text-gray-800">General Reports</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <a href="student_exit_interview.php" class="block animate-fadeInUp" style="animation-delay: 0.5s;">
            <div class="report-card general-card p-6 h-full">
              <div class="flex items-start justify-between mb-4">
                <div class="report-icon w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                  <i class="fas fa-certificate text-green-600 text-2xl"></i>
                </div>
                <span class="stats-badge bg-green-100 text-green-700">Track Requests</span>
              </div>
              <h3 class="text-xl font-bold text-gray-800 mb-2">Exit Interview</h3>
              <p class="text-gray-600 mb-4">Complete exit interview and track certificate requests</p>
              <div class="flex items-center text-green-600 font-medium">
                <span>Start Process</span>
                <i class="fas fa-arrow-right ml-2"></i>
              </div>
            </div>
          </a>

          <a href="student_appointment.php" class="block animate-fadeInUp" style="animation-delay: 0.6s;">
            <div class="report-card general-card p-6 h-full">
              <div class="flex items-start justify-between mb-4">
                <div class="report-icon w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                  <i class="fas fa-calendar-check text-green-600 text-2xl"></i>
                </div>
                <span class="stats-badge bg-green-100 text-green-700">Upcoming</span>
              </div>
              <h3 class="text-xl font-bold text-gray-800 mb-2">Appointments</h3>
              <p class="text-gray-600 mb-4">View your upcoming appointments with guidance office</p>
              <div class="flex items-center text-green-600 font-medium">
                <span>View Schedule</span>
                <i class="fas fa-arrow-right ml-2"></i>
              </div>
            </div>
          </a>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
        <div class="text-center">
          <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-question-circle text-blue-600 text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold text-gray-800 mb-2">Need Help?</h3>
          <p class="text-gray-600 mb-6">Can't find what you're looking for? Contact the guidance office for assistance.</p>
          <div class="flex items-center justify-center space-x-4">
            <a href="appointments.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium transition-colors">
              <i class="fas fa-calendar-plus mr-2"></i>
              Schedule Appointment
            </a>
            <a href="mailto:guidance@school.edu" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-medium transition-colors">
              <i class="fas fa-envelope mr-2"></i>
              Send Email
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Add smooth scroll behavior
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
          behavior: 'smooth'
        });
      });
    });

    // Add loading state for report cards
    document.querySelectorAll('.report-card').forEach(card => {
      card.addEventListener('click', function() {
        const icon = this.querySelector('.report-icon i');
        icon.className = 'fas fa-spinner fa-spin text-2xl';
        
        setTimeout(() => {
          // Reset icon after navigation (in case of back button)
          icon.className = icon.dataset.originalClass || icon.className.replace('fa-spinner fa-spin', 'fa-chart-bar');
        }, 1000);
      });
    });
  </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'987cd0b9e5b80dcd',t:'MTc1OTMzMDY3Ny4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
