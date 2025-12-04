<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Email Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <style>
        body {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
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
        }
        
        .sidebar h2 {
            padding: 0 1.5rem;
            margin-bottom: 2rem;
            font-size: 1.25rem;
            font-weight: 600;
            color: #495057;
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
            margin-left: 250px;
            flex: 1;
            padding: 2rem;
        }
        
        .header {
            background-color: #fff;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #dee2e6;
            margin: -2rem -2rem 2rem -2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .email-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .email-trigger {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6c757d;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .email-trigger:hover {
            background-color: #f8f9fa;
            color: #495057;
        }
        
        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            min-width: 200px;
            z-index: 1000;
            display: none;
            padding: 0.5rem 0;
        }
        
        .dropdown-menu.show {
            display: block;
        }
        
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: #495057;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #1976d2;
        }
        
        .dropdown-item i {
            font-size: 1rem;
            width: 16px;
            text-align: center;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background-color: #fff;
            border-radius: 0.5rem;
            padding: 1.5rem;
            border: 1px solid #dee2e6;
            transition: all 0.2s ease;
        }
        
        .stat-card:hover {
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        }
        
        .stat-card h3 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1976d2;
        }
        
        .stat-card p {
            color: #6c757d;
            margin: 0;
            font-weight: 500;
        }
        
        .recent-activity {
            background-color: #fff;
            border-radius: 0.5rem;
            padding: 1.5rem;
            border: 1px solid #dee2e6;
        }
        
        .activity-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid #f8f9fa;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }
        
        .activity-icon.inbox {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        
        .activity-icon.sent {
            background-color: #e8f5e8;
            color: #2e7d32;
        }
        
        .activity-icon.draft {
            background-color: #fff3e0;
            color: #f57c00;
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
            <!-- Header with Email Dropdown -->
            <div class="header">
                <h1>Dashboard Overview</h1>
                <div class="email-dropdown">
                    <button class="email-trigger" onclick="toggleEmailDropdown()">
                        <i class="bi bi-envelope"></i>
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="dropdown-menu" id="emailDropdown">
                        <a href="email/inbox.php" class="dropdown-item">
                            <i class="bi bi-inbox"></i>
                            Inbox
                            <span class="badge bg-primary ms-auto">12</span>
                        </a>
                        <a href="email/admin_compose_message.php" class="dropdown-item">
                            <i class="bi bi-send"></i>
                            Sent Messages
                        </a>
                        <a href="email/drafts.php" class="dropdown-item">
                            <i class="bi bi-file-earmark-text"></i>
                            Drafts
                            <span class="badge bg-secondary ms-auto">3</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Email Activity -->
            <div class="recent-activity">
                <h4 class="mb-3"><i class="bi bi-clock-history me-2"></i>Recent Email Activity</h4>
                
                <div class="activity-item">
                    <div class="activity-icon inbox">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">New message from Parent - John Smith</h6>
                        <p class="text-muted mb-0 small">Regarding student attendance inquiry</p>
                        <small class="text-muted">2 minutes ago</small>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon sent">
                        <i class="bi bi-send"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">Sent grade report to Maria Garcia</h6>
                        <p class="text-muted mb-0 small">Monthly progress report delivered</p>
                        <small class="text-muted">15 minutes ago</small>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon draft">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">Draft saved: Weekly newsletter</h6>
                        <p class="text-muted mb-0 small">School announcements and updates</p>
                        <small class="text-muted">1 hour ago</small>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon inbox">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">Appointment request from Lisa Johnson</h6>
                        <p class="text-muted mb-0 small">Requesting counseling session for student</p>
                        <small class="text-muted">3 hours ago</small>
                    </div>
                </div>
            </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleEmailDropdown() {
            const dropdown = document.getElementById('emailDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('emailDropdown');
            const trigger = document.querySelector('.email-trigger');
            
            if (!trigger.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Simulate real-time updates
        function updateNotifications() {
            const badges = document.querySelectorAll('.badge');
            badges.forEach(badge => {
                if (badge.textContent && !isNaN(badge.textContent)) {
                    const currentCount = parseInt(badge.textContent);
                    // Randomly update counts for demo
                    if (Math.random() > 0.8) {
                        badge.textContent = currentCount + Math.floor(Math.random() * 3);
                    }
                }
            });
        }

        // Update notifications every 30 seconds
        setInterval(updateNotifications, 30000);
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98fd790662a60dc9',t:'MTc2MDY3OTc0Ny4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
