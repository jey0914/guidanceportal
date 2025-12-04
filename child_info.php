<?php
session_start();
include("db.php");

if (!isset($_SESSION['parent_email'])) {
    header("Location: parent_login.php");
    exit();
}

$parent_email = $_SESSION['parent_email'];

// kunin parent fullname at student ID
$stmt = $con->prepare("SELECT fullname, student_id FROM parents WHERE email = ?");
$stmt->bind_param("s", $parent_email);
$stmt->execute();
$result = $stmt->get_result();
$parent = $result->fetch_assoc();

$fullname = $parent['fullname'];
$student_no = $parent['student_id'];

// kunin child details
$student_stmt = $con->prepare("SELECT * FROM form WHERE student_no = ?");
$student_stmt->bind_param("s", $student_no);
$student_stmt->execute();
$student_result = $student_stmt->get_result();
$student = $student_result->fetch_assoc();

// kunin guardian info
$guardian_stmt = $con->prepare("SELECT fullname, relationship, contact FROM parents WHERE student_id = ?");
$guardian_stmt->bind_param("s", $student_no);
$guardian_stmt->execute();
$guardian_result = $guardian_stmt->get_result();
$guardian = $guardian_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Child Information - Parent Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #334155;
        }

        html, body {
            height: 100%;
        }

        /* Enhanced Sidebar */
        .sidebar {
            width: 280px;
            background: #2c3e50;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
            transition: all 0.3s ease;
        }
        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar-header h3 {
            color: white;
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .sidebar-nav {
            padding: 1.5rem 0;
        }

        .sidebar-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-nav li {
            margin: 0.5rem 1rem;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar-nav a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .sidebar-nav a.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .sidebar-nav i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            background: #f8fafc;
        }

        /* Header */
        .content-header {
            background: white;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid #e2e8f0;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-subtitle {
            color: #64748b;
            margin: 0.5rem 0 0;
            font-size: 1rem;
        }

        /* Content Area */
        .content {
            padding: 2rem;
        }

        /* Student Cover Section */
        .student-cover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 3rem 2rem;
            color: white;
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .student-cover::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="60" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
            opacity: 0.3;
        }

        .student-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: 700;
            margin: 0 auto 1.5rem;
            border: 4px solid rgba(255, 255, 255, 0.3);
            position: relative;
            z-index: 1;
        }

        .student-name {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0 0 0.5rem;
            position: relative;
            z-index: 1;
        }

        .student-id {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        /* Tabs */
        .nav-tabs {
            border: none;
            margin-bottom: 2rem;
        }

        .nav-tabs .nav-link {
            border: none;
            background: white;
            color: #64748b;
            font-weight: 600;
            padding: 1rem 2rem;
            margin-right: 0.5rem;
            border-radius: 12px 12px 0 0;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link:hover {
            background: #f1f5f9;
            color: #4f46e5;
        }

        .nav-tabs .nav-link.active {
            background: #4f46e5;
            color: white;
            border-color: transparent;
        }

        /* Tab Content */
        .tab-content {
            background: white;
            border-radius: 0 20px 20px 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .tab-pane {
            padding: 2rem;
        }

        /* Info Cards */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 1.5rem;
            border-left: 4px solid #4f46e5;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .info-card h6 {
            color: #4f46e5;
            font-size: 0.9rem;
            font-weight: 600;
            margin: 0 0 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-card .value {
            color: #1e293b;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }

        /* Academic Progress */
        .progress-section {
            margin-top: 2rem;
        }

        .progress-item {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #10b981;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .subject-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 1.1rem;
        }

        .grade-badge {
            background: #10b981;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .progress-bar {
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981, #059669);
            border-radius: 4px;
            transition: width 0.8s ease;
        }

        /* Emergency Contact */
        .emergency-contact {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-radius: 16px;
            padding: 1.5rem;
            border-left: 4px solid #f59e0b;
            margin-top: 1.5rem;
        }

        .emergency-contact h6 {
            color: #92400e;
            font-weight: 700;
            margin: 0 0 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #92400e;
            font-weight: 500;
        }

        /* Alert Styles */
        .alert {
            border-radius: 16px;
            border: none;
            padding: 1.5rem;
            margin: 2rem 0;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .content-header {
                padding: 1.5rem;
            }

            .content {
                padding: 1rem;
            }

            .student-cover {
                padding: 2rem 1rem;
            }

            .student-name {
                font-size: 2rem;
            }

            .student-avatar {
                width: 100px;
                height: 100px;
                font-size: 2.5rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .nav-tabs .nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }
        }

        /* Mobile Menu Toggle */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: #4f46e5;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.75rem;
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3><i class="bi bi-mortarboard"></i> Parent Portal</h3>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="parent_dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a></li>
                <li><a href="child_info.php" class="active"><i class="bi bi-person"></i> Child Info</a></li>
                <li><a href="parent_appointments.php"><i class="bi bi-calendar-check"></i> Appointments</a></li>
                <li><a href="parent_reports.php"><i class="bi bi-file-text"></i> Reports</a></li>
                <li><a href="parent_settings.php"><i class="bi bi-gear"></i> Settings</a></li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="content-header">
            <h1 class="page-title">
                <i class="bi bi-person-circle"></i>
                Child Information
            </h1>
            <p class="page-subtitle">View and manage your child's academic and personal information</p>
        </div>

       <!-- Content -->
<div class="content fade-in">
    <?php if ($student): ?>
        <!-- Student Cover -->
        <div class="student-cover">
            <div class="student-avatar">
                <?= strtoupper(substr($student['fname'], 0, 1) . substr($student['lname'], 0, 1)) ?>
            </div>
            <h1 class="student-name">
                <?= htmlspecialchars($student['fname'] . ' ' . ($student['mname'] ? $student['mname'] . ' ' : '') . $student['lname']) ?>
            </h1>
            <p class="student-id">Student No: <?= htmlspecialchars($student['student_no']) ?></p>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs" id="studentTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">
                    <i class="bi bi-person-fill me-2"></i>Personal Information
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab">
                    <i class="bi bi-telephone-fill me-2"></i>Contact & Emergency
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="studentTabContent">
            <!-- Personal Information Tab -->
            <div class="tab-pane fade show active" id="personal" role="tabpanel">
                <h5 class="mb-4"><i class="bi bi-person-badge me-2"></i>Personal Details</h5>
                
                <div class="info-grid">
                    <div class="info-card">
                        <h6>Full Name</h6>
                        <p class="value"><?= htmlspecialchars($student['fname'] . ' ' . ($student['mname'] ? $student['mname'] . ' ' : '') . $student['lname']) ?></p>
                    </div>
                    
                    <div class="info-card">
                        <h6>Student Number</h6>
                        <p class="value"><?= htmlspecialchars($student['student_no']) ?></p>
                    </div>
                    
                    <div class="info-card">
                        <h6>Date of Birth</h6>
                        <p class="value"><?= date('F j, Y', strtotime($student['bday'])) ?></p>
                    </div>
                    
                    <div class="info-card">
                        <h6>Age</h6>
                        <p class="value"><?= date('Y') - date('Y', strtotime($student['bday'])) ?> years old</p>
                    </div>
                    
                    
                    <div class="info-card">
                        <h6>Guardian</h6>
                        <p class="value"><?= htmlspecialchars($guardian['relationship'] ?? 'N/A') ?></p>
                    </div>
                </div>
            </div>
                    

                    <!-- Contact & Emergency Tab -->
                    <div class="tab-pane fade" id="contact" role="tabpanel">
                        <h5 class="mb-4"><i class="bi bi-telephone me-2"></i>Contact Information</h5>
                            
                            <div class="info-card">
                                <h6>School Email</h6>
                                <p class="value">    <?= strtolower(($student['lname'] ?? 'lastname') . '.' . ($student['student_no'] ?? '0000')) ?>@guidanceportal.rosario.sti.edu.ph</p>
                            </div>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <div class="alert alert-warning text-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>No Student Record Found</strong><br>
                    No student record found linked to your account. Please contact the school administration for assistance.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-toggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });

        // Animate progress bars when tab is shown
        document.addEventListener('DOMContentLoaded', function() {
            const academicTab = document.getElementById('academic-tab');
            
            academicTab.addEventListener('shown.bs.tab', function() {
                const progressBars = document.querySelectorAll('.progress-fill');
                progressBars.forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0%';
                    setTimeout(() => {
                        bar.style.width = width;
                    }, 100);
                });
            });
        });

        // Add fade-in animation to tab content
        document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(event) {
                const targetPane = document.querySelector(event.target.getAttribute('data-bs-target'));
                targetPane.classList.add('fade-in');
                
                setTimeout(() => {
                    targetPane.classList.remove('fade-in');
                }, 600);
            });
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98c411b4b4310dcb',t:'MTc2MDA3NzgyNy4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
