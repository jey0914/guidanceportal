<?php
session_start();
include("db.php");

if (!isset($_SESSION['parent_email'])) {
    header("Location: parent_login.php");
    exit;
}

$email = $_SESSION['parent_email'];

// Fetch parent's full name
$stmt = $con->prepare("SELECT fullname FROM parents WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$fullname = $user['fullname'];

// Get current date and time
$now = date("Y-m-d H:i:s");

// Fetch upcoming appointments count
$appt_stmt = $con->prepare("
    SELECT COUNT(*) AS total 
    FROM parent_appointments 
    WHERE email = ? 
      AND CONCAT(date, ' ', time) >= ? 
      AND status = 'pending'
");
$appt_stmt->bind_param("ss", $email, $now);
$appt_stmt->execute();
$appt_result = $appt_stmt->get_result();
$appt_count = $appt_result->fetch_assoc()['total'];

// Fetch upcoming appointments list
$appt_list_stmt = $con->prepare("
    SELECT * 
    FROM parent_appointments 
    WHERE email = ? 
      AND CONCAT(date, ' ', time) >= ? 
      AND status = 'pending'
    ORDER BY date ASC, time ASC
    LIMIT 5
");
$appt_list_stmt->bind_param("ss", $email, $now);
$appt_list_stmt->execute();
$appt_list_result = $appt_list_stmt->get_result();

// Fetch reports count for this parent using student_incident_reports
$report_stmt = $con->prepare("
    SELECT COUNT(*) AS total
    FROM student_incident_reports
    WHERE student_no = ? 
");
$report_stmt->bind_param("s", $student_no);
$report_stmt->execute();
$report_result = $report_stmt->get_result();
$report_count = $report_result->fetch_assoc()['total'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard - Guidance Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        body {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        html, body {
            height: 100%;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
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
            text-align: center;
            padding: 2rem 1rem;
            border-bottom: 1px solid #34495e;
            margin-bottom: 0;
            background: #34495e;
        }

        .sidebar-header h2 {
            color: #ffffff;
            font-size: 1.4rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .sidebar-header h2::before {
            content: "🎓";
            font-size: 1.6rem;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar li {
            margin: 0;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            color: #bdc3c7;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
            border-left: 3px solid transparent;
        }

        .sidebar a:hover {
            background: #34495e;
            color: #ffffff;
            border-left-color: #3498db;
        }

        .sidebar a.active {
            background: #34495e;
            color: #ffffff;
            border-left-color: #3498db;
        }

        .sidebar a i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        /* Enhanced Content Area */
        .content {
            flex: 1;
            margin-left: 280px;
            padding: 0;
            background: #f8f9fa;
            min-height: 100vh;
        }

        /* Modern Top Bar */
        .top-bar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 1.5rem;
            padding: 1rem 2rem;
            background: #ffffff;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 0;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .top-bar i {
            font-size: 1.3rem;
            color: #64748b;
            cursor: pointer;
            padding: 0.75rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            position: relative;
        }

        .top-bar i:hover {
            background: #f1f5f9;
            color: #4f46e5;
            transform: translateY(-2px);
        }

        .top-bar i::after {
            content: attr(title);
            position: absolute;
            bottom: -35px;
            left: 50%;
            transform: translateX(-50%);
            background: #1f2937;
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            z-index: 1000;
        }

        .top-bar i:hover::after {
            opacity: 1;
        }

        /* Profile Dropdown Enhancement */
        .profile-dropdown {
            position: relative;
        }

        .profile-icon {
            font-size: 2rem !important;
            color: #4f46e5 !important;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .profile-icon:hover {
            transform: scale(1.1);
            color: #7c3aed !important;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            min-width: 250px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border-radius: 15px;
            padding: 1rem 0;
            z-index: 1000;
            border: 1px solid #e5e7eb;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-info {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 0.5rem;
        }

        .profile-info strong {
            color: #1f2937;
            font-size: 1.1rem;
        }

        .profile-info small {
            color: #64748b;
            font-size: 0.9rem;
        }

        .dropdown-content a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: #374151;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .dropdown-content a:hover {
            background: #f8fafc;
            color: #4f46e5;
            padding-left: 2rem;
        }

        /* Enhanced Welcome Section */
        .welcome-section {
            background: #ffffff;
            border-radius: 8px;
            padding: 2rem;
            margin: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: 1px solid #dee2e6;
        }

        .welcome-section h1 {
            color: #2c3e50;
            font-size: 2.25rem;
            font-weight: 600;
            margin: 0 0 1rem;
        }

        /* Formal Motivation Banner */
        .motivation-banner {
            background: #0d6efd;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 6px;
            margin: 1.5rem 0;
            text-align: center;
            border-left: 4px solid #0b5ed7;
        }

        .motivation-banner p {
            font-size: 1rem;
            font-weight: 500;
            margin: 0;
        }

        /* Formal Announcements */
        .announcements-section {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 2rem;
            margin: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .announcements-section h3 {
            color: #1e293b;
            font-size: 1.3rem;
            font-weight: 600;
            margin: 0 0 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 0.75rem;
        }

        .announcement-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: all 0.2s ease;
            position: relative;
        }

        .announcement-box::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 3px;
            height: 100%;
            background: #3b82f6;
        }

        .announcement-box:hover {
            border-color: #3b82f6;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .announcement-box strong {
            color: #1e293b;
            font-size: 1rem;
            font-weight: 600;
        }

        .announcement-box small {
            color: #64748b;
            font-size: 0.85rem;
        }

        .announcement-box p {
            color: #475569;
            margin: 0.75rem 0 0;
            line-height: 1.5;
        }

        /* Enhanced Message Panel */
        .message-panel {
            position: fixed;
            top: 0;
            right: -400px;
            width: 350px;
            height: 100vh;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: -4px 0 20px rgba(0, 0, 0, 0.15);
            transition: right 0.4s ease;
            z-index: 1000;
            border-left: 1px solid rgba(255, 255, 255, 0.2);
        }

        .message-panel.show {
            right: 0;
        }

        .message-header {
            padding: 2rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
        }

        .message-header h3 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
        }

        .message-header button {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .message-header button:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(90deg);
        }

        .message-body {
            padding: 2rem;
        }

        .message-link {
            display: block;
            padding: 1rem 1.5rem;
            color: #374151;
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
            font-weight: 500;
        }

        .message-link:hover {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            transform: translateX(5px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
        }

        /* Enhanced Calendar */
        .mini-calendar {
            position: absolute;
            top: 70px;
            right: 50px;
            z-index: 1000;
            animation: slideDown 0.3s ease;
        }

        .calendar-popup {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            padding: 1rem;
            border: 1px solid #e5e7eb;
        }

        /* Enhanced Logout Modal */
        .logout-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        .logout-modal-content {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            text-align: center;
            max-width: 400px;
            margin: auto;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            animation: slideUp 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logout-modal-content h3 {
            color: #1f2937;
            font-size: 1.5rem;
            margin: 0 0 1rem;
        }

        .logout-modal-content p {
            color: #64748b;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .modal-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .modal-btn {
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #64748b;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        /* Formal Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin: 0 2rem 2rem;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
            position: relative;
        }

        .stat-card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            border-color: #0d6efd;
        }

        .stat-icon {
            font-size: 1.8rem;
            color: #0d6efd;
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            margin: 0.5rem 0 0;
            font-weight: 500;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .sidebar {
                width: 240px;
            }
            
            .content {
                margin-left: 240px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                transform: translateX(-100%);
                position: fixed;
            }
            
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            
            .content {
                margin-left: 0;
            }
            
            .top-bar {
                padding: 1rem;
                gap: 1rem;
            }
            
            .welcome-section {
                padding: 1.5rem;
            }
            
            .welcome-section h1 {
                font-size: 1.75rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                margin: 1rem;
            }
            
            .announcements-section {
                margin: 1rem;
                padding: 1.5rem;
            }
            
            .message-panel {
                width: 100%;
                right: -100%;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #3730a3, #6b21a8);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Enhanced Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2>Guidance Portal</h2>
            </div>
            <ul>
                <li><a href="parent_dashboard.php" class="active"><i class="bi bi-house-door"></i> Dashboard</a></li>
                <li><a href="child_info.php"><i class="bi bi-person"></i> Child Info</a></li>
                <li><a href="parent_appointments.php"><i class="bi bi-calendar-check"></i> Appointments</a></li>
                <li><a href="parent_reports.php"><i class="bi bi-file-text"></i> Reports</a></li>
                <li><a href="parent_settings.php"><i class="bi bi-gear"></i> Settings</a></li>
            </ul>
        </div>

        <div class="content">
            <!-- Enhanced Top Bar -->
            <div class="top-bar">
                <i class="bi bi-list mobile-menu" onclick="toggleSidebar()" title="Menu" style="display: none;"></i>
                <i class="bi bi-envelope" onclick="toggleMessagePanel()" title="Inbox"></i>
                <i class="bi bi-bell" title="Notifications"></i>
                <i class="bi bi-calendar3" id="calendarIcon" onclick="toggleCalendar()" title="Calendar"></i>

                <!-- Enhanced Profile Dropdown -->
                <div class="profile-dropdown">
                    <i class="bi bi-person-circle profile-icon" onclick="toggleProfileDropdown()"></i>
                    <div id="profileDropdown" class="dropdown-content">
                        <div class="profile-info">
                            <strong><?= htmlspecialchars($fullname) ?></strong><br>
                            <small><?= htmlspecialchars($_SESSION['parent_email']) ?></small>
                        </div>
                        <a href="parent_profile.php"><i class="bi bi-person"></i> View Profile</a>
                        <a href="#" onclick="showLogoutModal()"><i class="bi bi-box-arrow-right"></i> Logout</a>
                    </div>
                </div>
            </div>

            <!-- Enhanced Welcome Section -->
            <div class="welcome-section">
                <h1>Welcome, <?= htmlspecialchars($fullname); ?> 👋</h1>
                
                <div class="motivation-banner">
                    <p>"Your presence as a parent in your child's school journey means more than you know."</p>
                </div>

                <!-- Dashboard Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                       <div class="stat-number"><?= $appt_count ?></div>
                        <div class="stat-label">Upcoming Appointments</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-file-text"></i>
                        </div>
                        <div class="stat-number"><?= $report_count ?></div>
                        <div class="stat-label">Reports Available</div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Announcements -->
           <div class="announcements-section">
    <h3><i class="bi bi-megaphone"></i> Latest Announcements</h3>
    <?php
        $announcement_q = $con->query("SELECT * FROM exam_announcements ORDER BY created_at DESC LIMIT 5");
    ?>
    <?php if ($announcement_q && $announcement_q->num_rows > 0): ?>
        <?php while ($row = $announcement_q->fetch_assoc()): ?>
            <div class="announcement-box">
                <strong><?= htmlspecialchars($row['title']) ?></strong><br>
                <small>Posted on <?= date("F d, Y h:i A", strtotime($row['created_at'])) ?></small>
                <p><?= nl2br(htmlspecialchars($row['message'])) ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="announcement-box">
            <p class="text-muted">No announcements yet. Check back later for updates!</p>
        </div>
    <?php endif; ?>
</div>

<!-- Upcoming Appointments Section -->
<div class="announcements-section">
    <h3><i class="bi bi-calendar-check"></i> Upcoming Appointments</h3>
    <?php
        $now = date("Y-m-d H:i:s");
        $appt_list_stmt = $con->prepare("
            SELECT * FROM parent_appointments 
            WHERE email = ? 
              AND CONCAT(date, ' ', time) >= ? 
              AND status = 'pending'
            ORDER BY date ASC, time ASC
            LIMIT 5
        ");
        $appt_list_stmt->bind_param("ss", $email, $now);
        $appt_list_stmt->execute();
        $appt_list_result = $appt_list_stmt->get_result();
    ?>
    <?php if ($appt_list_result->num_rows > 0): ?>
        <?php while ($row = $appt_list_result->fetch_assoc()): ?>
            <div class="announcement-box">
                <strong><?= htmlspecialchars($row['interest']) ?></strong><br>
                <small>Scheduled on <?= date("F d, Y", strtotime($row['date'])) ?> at <?= date("h:i A", strtotime($row['time'])) ?></small>
                <p><?= nl2br(htmlspecialchars($row['reason'])) ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="announcement-box">
            <p class="text-muted">No upcoming appointments.</p>
        </div>
    <?php endif; ?>
</div>

    <!-- Enhanced Message Panel -->
    <div id="messagePanel" class="message-panel">
        <div class="message-header">
            <h3><i class="bi bi-envelope"></i> Message Center</h3>
            <button onclick="toggleMessagePanel()">✖</button>
        </div>
        <div class="message-body">
            <a href="parent_inbox.php" class="message-link">
                <i class="bi bi-inbox"></i> Inbox
            </a>
            <a href="parent_compose.php" class="message-link">
                <i class="bi bi-pencil-square"></i> Compose Message
            </a>
        </div>
    </div>

    <!-- Enhanced Calendar -->
    <div id="mini-calendar" class="mini-calendar" style="display:none;">
        <div class="calendar-popup">
            <div id="calendarContainer"></div>
        </div>
    </div>

    <!-- Enhanced Logout Modal -->
    <div id="logoutModal" class="logout-modal" style="display:none;">
        <div class="logout-modal-content">
            <h3><i class="fas fa-sign-out-alt"></i> Confirm Logout</h3>
            <p>Are you sure you want to logout from your account?</p>
            <div class="modal-buttons">
                <button class="modal-btn btn-secondary" onclick="hideLogoutModal()">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button class="modal-btn btn-danger" onclick="window.location.href='parent_logout.php'">
                    <i class="fas fa-sign-out-alt"></i> Yes, Logout
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        function toggleMessagePanel() {
            const panel = document.getElementById("messagePanel");
            panel.classList.toggle("show");
        }

        function toggleCalendar() {
            const cal = document.getElementById("mini-calendar");
            cal.style.display = (cal.style.display === "block") ? "none" : "block";
        }

        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("mobile-open");
        }

        // Close calendar when clicking outside
        document.addEventListener("click", function (event) {
            const calendar = document.getElementById("mini-calendar");
            const icon = document.getElementById("calendarIcon");

            if (!calendar.contains(event.target) && !icon.contains(event.target)) {
                calendar.style.display = "none";
            }
        });

        function toggleProfileDropdown() {
            const dropdown = document.getElementById("profileDropdown");
            dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
        }

        // Close profile dropdown when clicking outside
        document.addEventListener("click", function(e) {
            const dropdown = document.getElementById("profileDropdown");
            const icon = document.querySelector(".profile-icon");
            if (!dropdown.contains(e.target) && !icon.contains(e.target)) {
                dropdown.style.display = "none";
            }
        });

        // Initialize calendar
        flatpickr("#calendarContainer", {
            inline: true,
            defaultDate: "today",
            theme: "material_blue"
        });

        function showLogoutModal() {
            document.getElementById("logoutModal").style.display = "flex";
            // Close profile dropdown
            document.getElementById("profileDropdown").style.display = "none";
        }

        function hideLogoutModal() {
            document.getElementById("logoutModal").style.display = "none";
        }

        // Mobile responsiveness
        function checkMobile() {
            const mobileMenu = document.querySelector('.mobile-menu');
            if (window.innerWidth <= 768) {
                mobileMenu.style.display = 'block';
            } else {
                mobileMenu.style.display = 'none';
                document.getElementById("sidebar").classList.remove("mobile-open");
            }
        }

        window.addEventListener('resize', checkMobile);
        checkMobile();

        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add loading animation for stats
        document.addEventListener('DOMContentLoaded', function() {
            const statNumbers = document.querySelectorAll('.stat-number');
            statNumbers.forEach((stat, index) => {
                stat.style.opacity = '0';
                stat.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    stat.style.transition = 'all 0.6s ease';
                    stat.style.opacity = '1';
                    stat.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98bed1bf92a00dcb',t:'MTc2MDAyMjc3OS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
