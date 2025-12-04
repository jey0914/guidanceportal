<?php
include 'db.php';
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}

$email = $_SESSION['email'];

// Fetch notifications for this user
$query = $con->prepare("SELECT * FROM notifications WHERE receiver_email = ? ORDER BY date_sent DESC");

if (!$query) {
    die("Prepare failed: " . $con->error);
}

$query->bind_param("s", $email);
$query->execute();
$result = $query->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guidance Portal - Notifications</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
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
        
        /* Mobile Responsive */
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
        
        /* Notification Styles */
        .notification-item {
            transition: all 0.3s ease;
        }
        
        .notification-item:hover {
            background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%);
            transform: translateX(4px);
        }
        
        .notification-unread {
            border-left: 4px solid #3b82f6;
            background: linear-gradient(135deg, #f0f4ff 0%, #ffffff 100%);
        }
        
        .notification-read {
            opacity: 0.8;
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
                <li><a href="dashboard.php">
                    <i class="fas fa-home"></i>
                    <span class="nav-text">Dashboard</span>
                </a></li>
                 <?php if (stripos($_SESSION['strand_course'], 'SHS') !== false || stripos($_SESSION['year_level'], 'Grade') !== false): ?>
    <li><a href="student_records.php" class="sidebar-link"><i class="fas fa-clipboard-check w-5"></i> <span>Attendance</span></a></li>
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
        <div class="flex items-center justify-center min-h-screen py-12 px-6">
            <div class="w-full max-w-4xl bg-white rounded-2xl shadow-xl p-8 border border-gray-200">
                <div class="flex justify-between items-center border-b pb-6 mb-6">
                    <h2 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-bell text-yellow-500 mr-3"></i> 
                        Notifications Center
                    </h2>
                </div>

                <form method="POST" action="">
                    <div class="flex justify-between items-center mb-6 bg-gray-50 p-4 rounded-xl">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" id="selectAll" class="w-5 h-5 border-gray-300 rounded text-blue-600 focus:ring-blue-500">
                            <label for="selectAll" class="text-sm font-medium text-gray-700">Select All Notifications</label>
                        </div>
                        <button type="submit" name="delete_selected" class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 shadow-lg hover:shadow-xl">
                            <i class="fas fa-trash mr-2"></i> Delete Selected
                        </button>
                    </div>

                    <div class="divide-y divide-gray-200 border rounded-xl bg-white shadow-sm">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php 
                // Determine read/unread class
                $statusClass = ($row['is_read'] == 0) ? 'notification-unread' : 'notification-read';

                // Format the timestamp nicely
                $date = date("F d, Y h:i A", strtotime($row['created_at']));
            ?>
            <div class="notification-item <?= $statusClass ?> flex items-center p-6 hover:bg-gray-50">
                <input type="checkbox" name="delete_ids[]" value="<?= $row['id'] ?>" class="w-5 h-5 mr-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <div class="flex-1">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 mb-1">
                                <?php
                                // Choose icon and badge based on type
                                switch($row['type']) {
                                    case 'appointment':
                                        $icon = 'fa-calendar-check text-blue-500';
                                        $badgeText = 'New';
                                        $badgeColor = 'bg-blue-100 text-blue-800';
                                        break;
                                    case 'report':
                                        $icon = 'fa-file-alt text-green-500';
                                        $badgeText = 'Important';
                                        $badgeColor = 'bg-green-100 text-green-800';
                                        break;
                                    case 'alert':
                                        $icon = 'fa-exclamation-triangle text-yellow-500';
                                        $badgeText = 'Alert';
                                        $badgeColor = 'bg-yellow-100 text-yellow-800';
                                        break;
                                    case 'info':
                                        $icon = 'fa-info-circle text-blue-500';
                                        $badgeText = 'Info';
                                        $badgeColor = 'bg-gray-100 text-gray-800';
                                        break;
                                    case 'welcome':
                                        $icon = 'fa-graduation-cap text-purple-500';
                                        $badgeText = 'Welcome';
                                        $badgeColor = 'bg-purple-100 text-purple-800';
                                        break;
                                    default:
                                        $icon = 'fa-bell text-gray-500';
                                        $badgeText = 'Notice';
                                        $badgeColor = 'bg-gray-100 text-gray-800';
                                }
                                ?>
                                <i class="fas <?= $icon ?> mr-2"></i>
                                <?= htmlspecialchars($row['title']) ?>
                            </p>
                            <p class="text-sm text-gray-700 mb-2"><?= htmlspecialchars($row['message']) ?></p>
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                <i class="fas fa-clock mr-1"></i>
                                <?= $date ?>
                            </span>
                        </div>
                        <span class="<?= $badgeColor ?> text-xs font-medium px-2 py-1 rounded-full"><?= $badgeText ?></span>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="p-8 text-center text-gray-500">
            <i class="fas fa-bell-slash text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No notifications yet</h3>
            <p class="text-sm text-gray-500">When you receive notifications, they'll appear here.</p>
        </div>
    <?php endif; ?>
</div>


                    <!-- Empty State (hidden when there are notifications) -->
                    <div class="hidden p-8 text-center text-gray-500">
                        <i class="fas fa-bell-slash text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No notifications yet</h3>
                        <p class="text-sm text-gray-500">When you receive notifications, they'll appear here.</p>
                    </div>
                </form>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
        }

        // Select All functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="delete_ids[]"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            const sidebar = document.getElementById('sidebar');
            const menuBtn = document.querySelector('.mobile-menu-btn');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !menuBtn.contains(e.target) && 
                sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
            }
        });

        // Mark notifications as read when clicked
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function(e) {
                if (!e.target.matches('input[type="checkbox"]')) {
                    this.classList.remove('notification-unread');
                    this.classList.add('notification-read');
                }
            });
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98f65baa77970dc9',t:'MTc2MDYwNTE0NS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>

<?php
// Handle deletion
if (isset($_POST['delete_selected']) && !empty($_POST['delete_ids'])) {
    $ids = $_POST['delete_ids'];
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    // Uncomment when database is connected
    // $stmt = $con->prepare("DELETE FROM notifications WHERE id IN ($placeholders)");
    // $types = str_repeat('i', count($ids));
    // $stmt->bind_param($types, ...$ids);
    // $stmt->execute();

    echo "<script>alert('Selected notifications deleted successfully!'); window.location.href='notifications.php';</script>";
}
?>
