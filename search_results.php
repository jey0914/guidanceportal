<?php
include "db.php";
session_start();

// ✅ Set timezone to Philippines
date_default_timezone_set('Asia/Manila');

// Check if admin
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

// Track student last active (sa student side dapat, pero puwede testing dito kung gusto mo)
if(isset($_SESSION['email'])){
    $email_student = $_SESSION['email'];
    $now = date("Y-m-d H:i:s");
    $stmt_update = $con->prepare("UPDATE form SET last_activity = ? WHERE email = ?");
    $stmt_update->bind_param("ss", $now, $email_student);
    $stmt_update->execute();
}

// Search term
$searchTerm = isset($_GET['query']) ? trim($_GET['query']) : '';
$result = null;

if ($searchTerm !== '') {
    $sql = "SELECT id, student_no, fname, lname, email, strand_course, year_level, last_activity, profile_picture
            FROM form
            WHERE fname LIKE ? OR lname LIKE ? OR student_no LIKE ? OR CONCAT(fname, ' ', lname) LIKE ?";
    $stmt = $con->prepare($sql);

    if(!$stmt){
        die("SQL Error: " . $con->error);
    }

    $searchWildcard = "%".$searchTerm."%";
    $stmt->bind_param("ssss", $searchWildcard, $searchWildcard, $searchWildcard, $searchWildcard);
    $stmt->execute();
    $result = $stmt->get_result();
}

// Helper function for online/offline status
function getStatus($last_active){
    $last = strtotime($last_active);
    $now = time();
    if(!$last) return ['Offline','offline']; // fallback
    if($now - $last <= 300){ // 5 minutes
        return ['Online','online'];
    } else {
        return ['Offline','offline'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Search Results - Admin Dashboard</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <style>
        body {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            min-height: 100vh;
        }
        
        .dashboard {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background-color: #fff;
            border-right: 1px solid #dee2e6;
            padding: 1.5rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .sidebar.show {
            transform: translateX(0);
        }
        
        .sidebar h2 {
            padding: 0 1.5rem;
            margin-bottom: 2rem;
            font-size: 1.25rem;
            font-weight: 600;
            color: #495057;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar li {
            margin-bottom: 0.25rem;
        }
        
        .sidebar a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: #6c757d;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .sidebar a:hover {
            background-color: #f8f9fa;
            color: #495057;
        }
        
        .sidebar a.active {
            background-color: #e3f2fd;
            color: #1976d2;
            border-right: 3px solid #1976d2;
        }
        
        .sidebar a i {
            font-size: 1.1rem;
            width: 18px;
            text-align: center;
        }
        
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 2rem;
            background: #f8f9fa;
            min-height: 100vh;
        }
        
        .results-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }
        
        .student-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
            cursor: pointer;
        }
        
        .student-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }
        
        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(25px, -25px);
        }
        
        .student-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }
        
        .profile-picture {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.3);
            object-fit: cover;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        
        .student-name {
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }
        
        .student-id {
            font-size: 0.9rem;
            opacity: 0.9;
            font-weight: 500;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 1;
        }
        
        .status-badge small {
            margin-left: 8px;
            opacity: 0.8;
            font-size: 0.75rem;
        }
        
        .status-online {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        
        .status-offline {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1rem;
            padding: 12px;
            background: #f8fafc;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .info-item:hover {
            background: #f1f5f9;
            transform: translateX(4px);
        }
        
        .info-item:last-child {
            margin-bottom: 0;
        }
        
        .info-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        
        .info-content {
            flex: 1;
        }
        
        .info-label {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 600;
            margin: 0 0 2px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-value {
            font-size: 0.9rem;
            color: #1e293b;
            font-weight: 500;
            margin: 0;
            line-height: 1.3;
        }
        
        .no-results {
            text-align: center;
            padding: 4rem 2rem;
            color: #64748b;
        }
        
        .no-results i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .mobile-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1100;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            border: none;
            border-radius: 12px;
            padding: 12px;
            color: white;
            box-shadow: 0 8px 32px rgba(59, 130, 246, 0.3);
            transition: all 0.3s ease;
        }
        
        .mobile-toggle:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 40px rgba(59, 130, 246, 0.4);
        }
        
        /* Profile Modal */
        .profile-modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }
        
        .profile-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 0;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            animation: modalSlideIn 0.3s ease;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 20px 20px 0 0;
            position: relative;
            overflow: hidden;
        }
        
        .modal-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(30px, -30px);
        }
        
        .close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 1.5rem;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 1;
        }
        
        .close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }
        
        .modal-profile {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            position: relative;
            z-index: 1;
        }
        
        .modal-profile-picture {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            object-fit: cover;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
        }
        
        .modal-student-info h2 {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0 0 8px 0;
        }
        
        .modal-student-info .student-id {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        .modal-body {
            padding: 2rem;
        }
        
        .modal-info-grid {
            display: grid;
            gap: 1.5rem;
        }
        
        .modal-info-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .modal-info-item:hover {
            background: #f1f5f9;
            transform: translateX(5px);
        }
        
        .modal-info-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        
        .modal-info-content {
            flex: 1;
        }
        
        .modal-info-label {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 600;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .modal-info-value {
            font-size: 1rem;
            color: #1e293b;
            font-weight: 500;
            margin: 0;
            line-height: 1.4;
        }
        
        @media (min-width: 769px) {
            .sidebar {
                transform: translateX(0);
            }
            .mobile-toggle {
                display: none;
            }
        }
        
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            
            .results-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

   <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2><i class="bi bi-speedometer2"></i> Admin Panel</h2>
            <ul>
                <li><a href="admin_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li><a href="admin_records.php"><i class="bi bi-people"></i> Student Records</a></li>
                <li><a href="admin_appointments.php"><i class="bi bi-calendar-check"></i> Appointments</a></li>
                <li><a href="admin_reports.php"><i class="bi bi-graph-up"></i> Reports</a></li>
                <li><a href="admin_settings.php"><i class="bi bi-gear"></i> Settings</a></li>
                <li><a href="admin_announcements.php"><i class="bi bi-megaphone"></i> Announcements</a></li>
                <li><a href="admin_parents.php"><i class="bi bi-person-lines-fill"></i> Parent Accounts</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <?php if ($result && $result->num_rows > 0): ?>
                <!-- Results Grid -->
                <div class="results-container">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="student-card" onclick="openProfile(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                            <div class="card-header">
                                <div class="student-profile">
                                    <?php if (!empty($row['profile_picture']) && file_exists($row['profile_picture'])): ?>
                                        <img src="<?php echo htmlspecialchars($row['profile_picture']); ?>" alt="Profile Picture" class="profile-picture">
                                    <?php else: ?>
                                        <div class="profile-picture">
                                            <i class="bi bi-person-circle"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div>
                                        <h2 class="student-name">
                                            <?php echo htmlspecialchars($row['fname'] . ' ' . $row['lname']); ?>
                                        </h2>
                                        <div class="student-id">ID: <?php echo htmlspecialchars($row['student_no']); ?></div>
                                    </div>
                                </div>

                                <?php 
                                list($status, $status_class) = getStatus($row['last_activity']); 
                                ?>
                                <div class="status-badge status-<?php echo $status_class; ?>">
                                    <i class="bi bi-circle-fill"></i>
                                    <?php echo $status; ?>
                                    <small>(<?php echo date('M j, g:i A', strtotime($row['last_activity'])); ?>)</small>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <div class="info-content">
                                        <p class="info-label">Email Address</p>
                                        <p class="info-value"><?php echo htmlspecialchars($row['email']); ?></p>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="bi bi-book"></i>
                                    </div>
                                    <div class="info-content">
                                        <p class="info-label">Course/Strand</p>
                                        <p class="info-value"><?php echo htmlspecialchars($row['strand_course']); ?></p>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="bi bi-mortarboard"></i>
                                    </div>
                                    <div class="info-content">
                                        <p class="info-label">Year Level</p>
                                        <p class="info-value"><?php echo htmlspecialchars($row['year_level']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="no-results">
                    <i class="bi bi-search"></i>
                    <h3>No Results Found</h3>
                    <p>No students found matching your search term "<strong><?php echo htmlspecialchars($searchTerm); ?></strong>"</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Profile Modal -->
    <div id="profileModal" class="profile-modal">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close-btn" onclick="closeProfile()">
                    <i class="bi bi-x"></i>
                </button>
                <div class="modal-profile">
                    <div id="modalProfilePicture" class="modal-profile-picture">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <div class="modal-student-info">
                        <h2 id="modalStudentName">Student Name</h2>
                        <div class="student-id" id="modalStudentId">ID: </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="modal-info-grid">
                    <div class="modal-info-item">
                        <div class="modal-info-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="modal-info-content">
                            <p class="modal-info-label">Email Address</p>
                            <p class="modal-info-value" id="modalEmail">email@example.com</p>
                        </div>
                    </div>
                    <div class="modal-info-item">
                        <div class="modal-info-icon">
                            <i class="bi bi-book"></i>
                        </div>
                        <div class="modal-info-content">
                            <p class="modal-info-label">Course/Strand</p>
                            <p class="modal-info-value" id="modalCourse">Course Name</p>
                        </div>
                    </div>
                    <div class="modal-info-item">
                        <div class="modal-info-icon">
                            <i class="bi bi-mortarboard"></i>
                        </div>
                        <div class="modal-info-content">
                            <p class="modal-info-label">Year Level</p>
                            <p class="modal-info-value" id="modalYearLevel">Year Level</p>
                        </div>
                    </div>
                    <div class="modal-info-item">
                        <div class="modal-info-icon">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="modal-info-content">
                            <p class="modal-info-label">Last Activity</p>
                            <p class="modal-info-value" id="modalLastActivity">Last seen</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Toggle -->
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.querySelector('.mobile-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !toggleBtn.contains(e.target) && 
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });

        // Profile modal functions
        function openProfile(studentData) {
            const modal = document.getElementById('profileModal');
            const profilePicture = document.getElementById('modalProfilePicture');
            
            // Update modal content
            document.getElementById('modalStudentName').textContent = studentData.fname + ' ' + studentData.lname;
            document.getElementById('modalStudentId').textContent = 'ID: ' + studentData.student_no;
            document.getElementById('modalEmail').textContent = studentData.email;
            document.getElementById('modalCourse').textContent = studentData.strand_course;
            document.getElementById('modalYearLevel').textContent = studentData.year_level;
            
            // Format last activity
            const lastActivity = new Date(studentData.last_activity);
            document.getElementById('modalLastActivity').textContent = lastActivity.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
            
            // Update profile picture
            if (studentData.profile_picture) {
                profilePicture.innerHTML = `<img src="${studentData.profile_picture}" alt="Profile Picture" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">`;
            } else {
                profilePicture.innerHTML = '<i class="bi bi-person-circle"></i>';
            }
            
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeProfile() {
            const modal = document.getElementById('profileModal');
            modal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('profileModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeProfile();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeProfile();
            }
        });
    </script>

<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9948355a06f40dcb',t:'MTc2MTQ2MzQwOC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
