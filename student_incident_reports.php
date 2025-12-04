<?php
session_start();
include("db.php");

// Make sure student is logged in
if (!isset($_SESSION['email']) || !isset($_SESSION['student_no'])) {
    header("Location: login.php");
    exit();
}

$student_no = $_SESSION['student_no']; // use student_no for querying

// Fetch reports for the logged-in student
$sql = "SELECT date_reported, incident_type, description, status 
        FROM student_incident_reports 
        WHERE student_no = ? 
        ORDER BY date_reported DESC";

$stmt = $con->prepare($sql);

if (!$stmt) {
    die("SQL prepare failed: " . $con->error);
}

$stmt->bind_param("s", $student_no);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Incident Reports - Student Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            box-sizing: border-box; 
        }
        .sidebar-link { 
            transition: all 0.3s ease; 
            color: #94a3b8; 
        }
        .sidebar-link:hover { 
            background: linear-gradient(135deg, #3b82f6, #1d4ed8); 
            color: white; 
            transform: translateX(4px); 
        }
        .sidebar-link.active { 
            background: linear-gradient(135deg, #3b82f6, #1d4ed8); 
            color: white; 
        }
        .card-hover { 
            transition: all 0.3s ease; 
        }
        .card-hover:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); 
        }
        .status-badge { 
            transition: all 0.3s ease; 
        }
        .status-badge:hover { 
            transform: scale(1.05); 
        }
        .exam-row:hover { 
            background: linear-gradient(135deg, rgba(59,130,246,0.05), rgba(147,51,234,0.05)); 
            transform: translateX(4px); 
        }
        .pulse-animation { 
            animation: pulse 2s infinite; 
        }
        @keyframes pulse { 
            0%,100%{opacity:1;}
            50%{opacity:0.7;} 
        }
        .animate-slide-in {
            animation: slideIn 0.5s ease-out;
        }
        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
        .incident-row {
            transition: all 0.3s ease;
        }
        .incident-row:hover {
            background: linear-gradient(135deg, rgba(59,130,246,0.03), rgba(147,51,234,0.03));
            transform: translateX(2px);
        }
        .privacy-notice {
            background: linear-gradient(135deg, rgba(59,130,246,0.1), rgba(147,51,234,0.1));
            border-left: 4px solid #3b82f6;
        }
        .no-reports-card {
            background: linear-gradient(135deg, rgba(34,197,94,0.05), rgba(59,130,246,0.05));
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen bg-gray-50">
        <!-- Sidebar -->
        <div class="w-64 bg-slate-800 text-white flex flex-col animate-slide-in">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
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
        
        <a href="student_reports.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl">
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
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">My Incident Reports</h1>
                        <p class="text-sm text-gray-600 mt-1">View any incident reports filed regarding your conduct</p>
                    </div>
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-calendar-alt mr-2"></i><?= date('F j, Y') ?>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <!-- Privacy Notice -->
                    <div class="privacy-notice rounded-xl p-4 mb-6">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-shield-alt text-blue-600 text-lg mt-0.5"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 mb-1">Privacy Protection Notice</h3>
                                <p class="text-sm text-gray-700">
                                    This table shows only incidents involving you personally. Details about other students are protected for privacy and will not be displayed here. 
                                    If you need more information about any incident, please contact the guidance office.
                                </p>
                            </div>
                       
                    </div>

                    <?php if ($result && $result->num_rows > 0): ?>
                        <!-- Reports Table -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden card-hover">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-lg font-semibold text-gray-900">
                                        <i class="fas fa-list-alt mr-2 text-blue-600"></i>
                                        Your Incident Reports
                                    </h2>
                                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                                        <i class="fas fa-info-circle"></i>
                                        <span><?= $result->num_rows ?> report<?= $result->num_rows !== 1 ? 's' : '' ?> found</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <i class="fas fa-calendar mr-2"></i>Date Reported
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <i class="fas fa-tag mr-2"></i>Incident Type
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <i class="fas fa-file-text mr-2"></i>Description
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <i class="fas fa-clipboard-check mr-2"></i>Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr class="incident-row">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 w-2 h-2 bg-blue-600 rounded-full mr-3"></div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <?= htmlspecialchars(date("F j, Y", strtotime($row['date_reported']))) ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                        <?php 
                                                            $type = strtolower($row['incident_type']);
                                                            if (strpos($type, 'minor') !== false || strpos($type, 'injury') !== false) {
                                                                echo 'bg-yellow-100 text-yellow-800';
                                                            } elseif (strpos($type, 'behavioral') !== false) {
                                                                echo 'bg-purple-100 text-purple-800';
                                                            } elseif (strpos($type, 'property') !== false || strpos($type, 'damage') !== false) {
                                                                echo 'bg-red-100 text-red-800';
                                                            } elseif (strpos($type, 'medical') !== false) {
                                                                echo 'bg-blue-100 text-blue-800';
                                                            } else {
                                                                echo 'bg-gray-100 text-gray-800';
                                                            }
                                                        ?>">
                                                        <i class="fas fa-circle text-xs mr-1"></i>
                                                        <?= htmlspecialchars($row['incident_type']) ?>
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-900 max-w-xs">
                                                        <?= htmlspecialchars($row['description']) ?>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="status-badge px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    <?= $row['status'] === 'Resolved' ? 'bg-green-100 text-green-800' :
                                                       ($row['status'] === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                       ($row['status'] === 'Follow-up Required' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800')) ?>">
                                                        <i class="fas <?= $row['status'] === 'Resolved' ? 'fa-check-circle' : 
                                                                      ($row['status'] === 'Pending' ? 'fa-clock' : 
                                                                      ($row['status'] === 'Follow-up Required' ? 'fa-exclamation-circle' : 'fa-question-circle')) ?> mr-1"></i>
                                                        <?= htmlspecialchars($row['status']) ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover">
                                <div class="flex items-center mb-4">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-info-circle text-blue-600"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">Need Help?</h3>
                                </div>
                                <p class="text-sm text-gray-600 mb-4">
                                    If you have questions about any incident report or need to discuss the details, please contact the guidance office.
                                </p>
                                <a href="appointments.php" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500">
                                    <i class="fas fa-calendar-plus mr-2"></i>
                                    Schedule an Appointment
                                </a>
                            </div>

                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover">
                                <div class="flex items-center mb-4">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-shield-alt text-green-600"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">Privacy Assured</h3>
                                </div>
                                <p class="text-sm text-gray-600">
                                    Your incident reports are confidential and only accessible to you and authorized school personnel. 
                                    We protect your privacy while ensuring your safety and well-being.
                                </p>
                            </div>
                        </div>

                    <?php else: ?>
                        <!-- No Reports State -->
                        <div class="no-reports-card bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center card-hover">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-check-circle text-4xl text-green-600"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Great news!</h3>
                            <p class="text-gray-600 mb-6">You have no incident reports at the moment.</p>
                            
                            <div class="bg-gray-50 rounded-lg p-4 max-w-md mx-auto">
                                <div class="flex items-center justify-center mb-2">
                                    <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                                    <span class="text-sm font-medium text-gray-700">Keep it up!</span>
                                </div>
                                <p class="text-xs text-gray-600">
                                    Continue following school guidelines and maintaining good conduct. 
                                    If you ever need support, the guidance office is here to help.
                                </p>
                            </div>
                            
                            <div class="mt-6 flex justify-center space-x-4">
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Add smooth animations and interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Animate table rows on load
            const rows = document.querySelectorAll('.incident-row');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    row.style.transition = 'all 0.5s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Add click effect to status badges
            const statusBadges = document.querySelectorAll('.status-badge');
            statusBadges.forEach(badge => {
                badge.addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1.05)';
                    }, 100);
                });
            });

            // Smooth scroll for navigation links
            const navLinks = document.querySelectorAll('.sidebar-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Add ripple effect
                    const ripple = document.createElement('span');
                    ripple.style.cssText = `
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255,255,255,0.3);
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        pointer-events: none;
                    `;
                    this.style.position = 'relative';
                    this.appendChild(ripple);
                    
                    setTimeout(() => ripple.remove(), 600);
                });
            });
        });

        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98ff745c20870dc9',t:'MTc2MDcwMDUyOC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>