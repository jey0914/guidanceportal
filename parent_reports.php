<?php
session_start();
include("db.php");

// Check if logged in
if (!isset($_SESSION['parent_email'])) {
    header("Location: parent_login.php");
    exit();
}
$email = $_SESSION['parent_email'];


// Fetch student info from the database
$sql = "SELECT f.student_no, f.fname, f.mname, f.lname, f.year_level AS grade, f.strand_course AS program
        FROM form f
        JOIN parents p ON p.student_id = f.student_no
        WHERE p.email = ?
        LIMIT 1";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $_SESSION['parent_email']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Normalize grade to number
    $grade_numeric = (int) filter_var($row['grade'], FILTER_SANITIZE_NUMBER_INT);

    $student_info = [
        'full_name' => $row['fname'] . ' ' . ($row['mname'] ? $row['mname'] . ' ' : '') . $row['lname'],
        'student_no' => $row['student_no'],
        'grade' => $row['grade'],
        'section' => 'A', 
        'program' => $row['program'],
        'school_year' => '2024-2025',
        'is_senior_high' => ($grade_numeric === 11 || $grade_numeric === 12) 
    ];
} else {
    die("Student not found.");
}


// Fetch incident reports
$stmt_incidents = $con->prepare("
    SELECT id, date_reported AS date, incident_type AS type, description, action_taken 
    FROM student_incident_reports 
    WHERE student_no = ? 
    ORDER BY date_reported DESC
");
if (!$stmt_incidents) {
    die("Prepare failed (incidents): " . $con->error);
}
$stmt_incidents->bind_param("s", $student_info['student_no']);
$stmt_incidents->execute();
$result_incidents = $stmt_incidents->get_result();
$incident_reports = $result_incidents->fetch_all(MYSQLI_ASSOC);

// Fetch attendance records
$stmt_attendance = $con->prepare("
    SELECT date, status, time_in, time_out 
    FROM attendance_logs 
    WHERE student_no = ? 
    ORDER BY date DESC
");
if (!$stmt_attendance) {
    die("Prepare failed (attendance): " . $con->error);
}
$stmt_attendance->bind_param("s", $student_info['student_no']);
$stmt_attendance->execute();
$result_attendance = $stmt_attendance->get_result();
$attendance_records = $result_attendance->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Reports - Parent Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .sidebar-nav ul {
            list-style: none;
            padding: 1.5rem 0;
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

        /* Student Info Card */
        .student-info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .student-avatar {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .student-name {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0 0 0.5rem;
        }

        .student-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .detail-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .detail-label {
            font-size: 0.85rem;
            opacity: 0.8;
            margin-bottom: 0.25rem;
        }

        .detail-value {
            font-size: 1.1rem;
            font-weight: 600;
        }

        /* Report Sections */
        .report-section {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .section-header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .section-header h3 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 700;
        }

        .section-body {
            padding: 2rem;
        }

        /* Incident Reports */
        .incident-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .incident-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .incident-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .incident-type {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .incident-minor {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
        }

        .incident-major {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
        }

        .incident-date {
            color: #64748b;
            font-size: 0.9rem;
        }

        .incident-description {
            color: #374151;
            line-height: 1.6;
        }

        /* Attendance Table */
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
        }

        .attendance-table th {
            background: #f8fafc;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }

        .attendance-table td {
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .attendance-table tbody tr:hover {
            background: #f8faff;
        }

        .attendance-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-present {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
        }

        .status-absent {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
        }

        .status-late {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
        }

        /* No Records Message */
        .no-records {
            text-align: center;
            padding: 3rem 2rem;
            color: #64748b;
        }

        .no-records i {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }

        .no-records h4 {
            color: #374151;
            margin-bottom: 0.5rem;
        }

        /* Mobile Responsive */
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

            .student-info-card {
                padding: 1.5rem;
            }

            .student-details {
                grid-template-columns: 1fr;
            }

            .section-body {
                padding: 1.5rem;
            }

            .incident-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .attendance-table {
                font-size: 0.85rem;
            }

            .attendance-table th,
            .attendance-table td {
                padding: 0.75rem 0.5rem;
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

        /* Summary Stats */
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border-left: 4px solid var(--accent-color);
        }

        .stat-card.incidents { --accent-color: #ef4444; }
        .stat-card.attendance { --accent-color: #10b981; }
        .stat-card.performance { --accent-color: #3b82f6; }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.9rem;
            margin: 0.5rem 0 0;
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-graduation-cap"></i> Parent Portal</h3>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="parent_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="child_info.php"><i class="fas fa-child"></i> Child Info</a></li>
                <li><a href="parent_appointments.php"><i class="fas fa-calendar-check"></i> Appointments</a></li>
                <li><a href="parent_reports.php" class="active"><i class="fas fa-file-alt"></i> Reports</a></li>
                <li><a href="parent_settings.php"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="content-header">
            <h1 class="page-title">
                <i class="fas fa-file-alt"></i>
                Student Reports
            </h1>
            <p class="page-subtitle">View your child's academic records, incidents, and attendance</p>
        </div>

        <!-- Content -->
        <div class="content fade-in">
            <!-- Student Information Card -->
            <div class="student-info-card">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <div class="student-avatar">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <h2 class="student-name"><?= htmlspecialchars($student_info['full_name']) ?></h2>
                        <p class="mb-0 opacity-75">Student ID: <?= htmlspecialchars($student_info['student_no']) ?></p>
                        
                        <div class="student-details">
                            <div class="detail-item">
                                <div class="detail-label">Grade Level</div>
                                <div class="detail-value"><?= htmlspecialchars($student_info['grade']) ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Program/Track</div>
                                <div class="detail-value"><?= htmlspecialchars($student_info['program']) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Statistics -->
           <div class="summary-stats">
    <div class="stat-card incidents">
        <h3 class="stat-number"><?= count($incident_reports) ?></h3>
        <p class="stat-label">Total Incidents</p>
    </div>
    <div class="stat-card attendance">
        <h3 class="stat-number"><?= count(array_filter($attendance_records, fn($r) => $r['status'] === 'present')) ?></h3>
        <p class="stat-label">Days Present</p>
    </div>
    <div class="stat-card performance">
        <h3 class="stat-number"><?= !empty($attendance_records) ? round((count(array_filter($attendance_records, fn($r) => $r['status'] === 'present')) / count($attendance_records)) * 100) : 0 ?>%</h3>
        <p class="stat-label">Attendance Rate</p>
    </div>
</div>
            <!-- Incident Reports Section -->
            <div class="report-section">
                <div class="section-header">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Incident Reports</h3>
                </div>
                <div class="section-body">
                    <?php if (!empty($incident_reports)): ?>
                       <?php foreach ($incident_reports as $incident): ?>
    <div class="incident-card">
        <div class="incident-header">
            <div>
                <?php $typeClass = strtolower($incident['type']); ?>
                <span class="incident-type incident-<?= $typeClass ?>">
                    <?= ucfirst($incident['type']) ?>
                </span>
            </div>
            <div class="incident-date">
                <i class="fas fa-calendar"></i>
                <?= date('F j, Y', strtotime($incident['date'])) ?>
            </div>
        </div>
        <div class="incident-description">
            <h6><i class="fas fa-info-circle"></i> Description:</h6>
            <p><?= htmlspecialchars($incident['description']) ?></p>
            
            <h6><i class="fas fa-gavel"></i> Action Taken:</h6>
            <p><?= htmlspecialchars($incident['action_taken']) ?></p>
        </div>
    </div>
<?php endforeach; ?>

                    <?php else: ?>
                        <div class="no-records">
                            <i class="fas fa-check-circle"></i>
                            <h4>No Incident Reports</h4>
                            <p>Great news! Your child has no recorded incidents.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Attendance Section (Only for Senior High School) -->
            <?php if ($student_info['is_senior_high']): ?>
            <div class="report-section">
                <div class="section-header">
                    <i class="fas fa-calendar-check"></i>
                    <h3>Attendance Records</h3>
                </div>
                <div class="section-body">
                    <?php if (!empty($attendance_records)): ?>
                        <div class="table-responsive">
                            <table class="attendance-table">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-calendar"></i> Date</th>
                                        <th><i class="fas fa-info-circle"></i> Status</th>
                                        <th><i class="fas fa-clock"></i> Time In</th>
                                        <th><i class="fas fa-clock"></i> Time Out</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($attendance_records as $record): ?>
                                        <tr>
                                            <td><?= date('F j, Y', strtotime($record['date'])) ?></td>
                                            <td>
                                                <span class="attendance-status status-<?= $record['status'] ?>">
                                                    <?= ucfirst($record['status']) ?>
                                                </span>
                                            </td>
                                            <td><?= $record['time_in'] ? date('g:i A', strtotime($record['time_in'])) : '-' ?></td>
                                            <td><?= $record['time_out'] ? date('g:i A', strtotime($record['time_out'])) : '-' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="no-records">
                            <i class="fas fa-calendar-times"></i>
                            <h4>No Attendance Records</h4>
                            <p>No attendance records found for this student.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php else: ?>
            <!-- Message for College Students -->
            <div class="report-section">
                <div class="section-header">
                    <i class="fas fa-info-circle"></i>
                    <h3>Attendance Information</h3>
                </div>
                <div class="section-body">
                    <div class="no-records">
                        <i class="fas fa-graduation-cap"></i>
                        <h4>College Student</h4>
                        <p>Attendance tracking is not available for college students. Please contact your child's academic advisor for attendance information.</p>
                    </div>
                </div>
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

        // Enhanced interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to incident cards
            const incidentCards = document.querySelectorAll('.incident-card');
            incidentCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                    this.style.boxShadow = '0 15px 35px rgba(0, 0, 0, 0.15)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.1)';
                });
            });

            // Add smooth scrolling for better UX
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98c47b4c91c40dcb',t:'MTc2MDA4MjE1Mi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
