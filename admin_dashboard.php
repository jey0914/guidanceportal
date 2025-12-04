<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

include("db.php");

$today = date("Y-m-d");

$today_sql = "SELECT COUNT(*) as count_today FROM appointments WHERE date = ?";
$stmt1 = $con->prepare($today_sql);
$stmt1->bind_param("s", $today);
$stmt1->execute();
$today_result = $stmt1->get_result()->fetch_assoc();
$count_today = $today_result['count_today'];

$total_sql = "SELECT COUNT(*) as count_all FROM appointments";
$total_result = $con->query($total_sql)->fetch_assoc();
$count_all = $total_result['count_all'];

$student_sql = "SELECT COUNT(DISTINCT email) as count_students FROM appointments";
$student_result = $con->query($student_sql)->fetch_assoc();
$count_students = $student_result['count_students'];

$parent_sql = "SELECT COUNT(*) as count_parents FROM parents";
$parent_result = $con->query($parent_sql)->fetch_assoc();
$count_parents = $parent_result['count_parents'];



// 🔍 Search Bar Logic
$searchTerm = '';
$result = null;

if (isset($_GET['query'])) {
    $searchTerm = trim($_GET['query']);

    $sql = "
        SELECT * 
        FROM form 
        WHERE 
            fname LIKE ? 
            OR mname LIKE ? 
            OR lname LIKE ? 
            OR CONCAT(fname, ' ', lname) LIKE ? 
            OR student_no LIKE ?
    ";

    $stmt = $con->prepare($sql);
    $searchWildcard = "%".$searchTerm."%";
    $stmt->bind_param("sssss", $searchWildcard, $searchWildcard, $searchWildcard, $searchWildcard, $searchWildcard);
    $stmt->execute();
    $result = $stmt->get_result();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - GuidanceHub</title>
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
        
        .content {
            flex: 1;
            margin-left: 250px;
            padding: 2rem;
        }
        
        .top-bar {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .welcome-text {
            font-size: 1.25rem;
            font-weight: 600;
            color: #495057;
            margin: 0;
        }
        
        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .notification-btn, .message-btn {
            position: relative;
            background: none;
            border: none;
            color: #6c757d;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
        }
        
        .notification-btn:hover, .message-btn:hover {
            color: #495057;
        }
        
        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .logout-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
        }
        
        .logout-btn:hover {
            background-color: #c82333;
            color: white;
        }
        
        .dashboard-header {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .dashboard-title {
            font-size: 2rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .dashboard-subtitle {
            color: #6c757d;
            font-size: 1rem;
            margin: 0;
        }
        
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .card {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            transition: box-shadow 0.2s ease;
        }
        
        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .card i {
            font-size: 2rem;
            margin-bottom: 1rem;
            display: block;
        }
        
        .card h3 {
            font-size: 1rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .card p {
            font-size: 1.75rem;
            font-weight: 600;
            margin: 0;
        }
        
        .card.appointments i { color: #0d6efd; }
        .card.appointments p { color: #0d6efd; }
        
        .card.total-appointments i { color: #198754; }
        .card.total-appointments p { color: #198754; }
        
        .card.parents i { color: #6f42c1; }
        .card.parents p { color: #6f42c1; }
        
        .card.add-student-card {
            border: 2px dashed #6c757d;
            cursor: pointer;
        }
        .card.add-student-card:hover {
            border-color: #495057;
        }
        .card.add-student-card i { color: #fd7e14; }
        .card.add-student-card p { color: #fd7e14; }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .action-card {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .action-card:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        
        .action-icon {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
            font-size: 1.25rem;
            color: white;
        }
        
        .action-card h4 {
            font-size: 0.9rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.25rem;
        }
        
        .action-card p {
            font-size: 0.8rem;
            color: #6c757d;
            margin: 0;
        }
        
        .recent-activity {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1.5rem;
        }
        
        .activity-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 1.5rem;
        }
        
        .activity-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 0.75rem;
            background-color: #f8f9fa;
        }
        
        .activity-icon {
            width: 36px;
            height: 36px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.9rem;
        }
        
        .activity-content h5 {
            font-size: 0.9rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.25rem;
        }
        
        .activity-content p {
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
        }
        
        .activity-content small {
            font-size: 0.75rem;
            color: #adb5bd;
        }
        
        .email-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            width: 280px;
            z-index: 1000;
            display: none;
        }
        
        .dropdown-header {
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .dropdown-header span {
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 4px;
            color: #6c757d;
        }
        
        .dropdown-header span:hover {
            background-color: #f8f9fa;
            color: #495057;
        }
        
        .message-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f8f9fa;
            font-size: 0.85rem;
        }
        
        .message-item:last-child {
            border-bottom: none;
        }
        
        .modal-content {
            border-radius: 8px;
        }
        
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .content {
                margin-left: 0;
                padding: 1rem;
            }
            
            .summary-cards {
                grid-template-columns: 1fr;
            }
            
            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
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
        
        <!-- Content -->
        <div class="content">
            <div class="top-bar d-flex justify-content-between align-items-center">
    <!-- Welcome Text -->
    <h1 class="welcome-text mb-0">Admin Dashboard</h1>

    <!-- Right-side: search + icons + profile -->
    <div class="d-flex align-items-center gap-2">
<!-- 🔍 Search Bar -->
<div class="position-relative" style="width: 280px;">
  <form class="d-flex align-items-center position-relative" role="search" method="GET" action="search_results.php" style="width: 100%;">
    <i class="fas fa-search position-absolute" style="left: 10px; color: #888;"></i>
    <input 
      id="searchInput"
      class="form-control ps-4"
      type="text"
      name="query"
      placeholder="Search students..."
      aria-label="Search"
      autocomplete="off"
      style="border-radius: 25px; padding-left: 35px;"
    >
  </form>

  <!-- 🔽 Suggestions dropdown -->
  <div id="suggestions" 
       class="list-group position-absolute w-100 shadow-sm bg-white" 
       style="z-index: 1000; display: none; border-radius: 10px;">
  </div>
</div>

<div id="searchResults"></div>
<?php if ($result): ?>
    <div class="search-results mt-3">
        <?php if ($result->num_rows > 0): ?>
            <h5>Search Results:</h5>
            <ul class="list-group">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li class="list-group-item">
                        <strong><?php echo htmlspecialchars($row['fname'] . ' ' . $row['lname']); ?></strong>
                        <span class="text-muted">(<?php echo htmlspecialchars($row['student_no']); ?>)</span>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p class="text-muted mt-2">No students found.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>


        <!-- Notifications -->
        <button class="notification-btn position-relative" title="Notifications">
            <i class="bi bi-bell-fill"></i>
        </button>

        <!-- Messages -->
        <div class="position-relative">
            <button class="message-btn" id="emailDropdownBtn" title="Messages">
                <i class="bi bi-envelope-fill"></i>
                <span class="notification-badge">5</span>
            </button>
            <div id="emailDropdown" class="email-dropdown">
                <div class="dropdown-header">
                    <span id="newMessageBtn" title="New Message"><i class="bi bi-plus-circle"></i></span>
                    <span id="seeAllBtn" title="See All"><i class="bi bi-list"></i></span>
                </div>
                <div id="messagesList"></div>
            </div>
        </div>

        <!-- Profile Dropdown -->
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="profileDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdownBtn">
                <li><a class="dropdown-item" href="admin_profile.php"><i class="bi bi-person"></i> Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</div>

            <!-- Dashboard Header -->
            <div class="dashboard-header">
                <h1 class="dashboard-title">Dashboard Overview</h1>
                <p class="dashboard-subtitle">Manage appointments, students, and parent communications</p>
            </div>

            <!-- Summary Cards -->
            <div class="summary-cards">
                <div class="card appointments">
                    <i class="bi bi-calendar-event"></i>
                    <h3>Today's Appointments</h3>
                    <p><?= $count_today ?></p>
                </div>
                <div class="card total-appointments">
                    <i class="bi bi-journal-check"></i>
                    <h3>Total Appointments</h3>
                    <p><?= $count_all ?></p>
                </div>
                <div class="card parents">
                    <i class="bi bi-people-fill"></i>
                    <h3>Total Registered Parents</h3>
                    <p><?= $count_parents ?></p>
                </div>
                <div class="card add-student-card" onclick="window.location.href='add_student.php'">
                    <i class="bi bi-person-plus-fill"></i>
                    <h3>Add Student</h3>
                    <p>Click to register</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <div class="action-card" onclick="window.location.href='admin_appointments.php'">
                    <div class="action-icon" style="background-color: #0d6efd;">
                        <i class="bi bi-calendar-plus"></i>
                    </div>
                    <h4>Schedule Appointment</h4>
                    <p>Book new counseling session</p>
                </div>
                
                <div class="action-card" onclick="window.location.href='admin_records.php'">
                    <div class="action-icon" style="background-color: #198754;">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <h4>View Students</h4>
                    <p>Manage student records</p>
                </div>
                
                <div class="action-card" onclick="window.location.href='admin_reports.php'">
                    <div class="action-icon" style="background-color: #6f42c1;">
                        <i class="bi bi-bar-chart"></i>
                    </div>
                    <h4>Generate Reports</h4>
                    <p>View analytics and data</p>
                </div>
                
                <div class="action-card" onclick="window.location.href='admin_announcements.php'">
                    <div class="action-icon" style="background-color: #fd7e14;">
                        <i class="bi bi-megaphone"></i>
                    </div>
                    <h4>Send Announcement</h4>
                    <p>Notify students and parents</p>
                </div>
            </div>

<!-- Recent Activity -->
<div class="recent-activity">
    <h3 class="activity-title">Recent Activity</h3>
    
    <!-- Dynamic activity list will be loaded here -->
    <div id="recent-activity-list">
        <div class="activity-item">
            <div class="activity-icon" style="background-color: #0d6efd;">
                <i class="bi bi-calendar-plus"></i>
            </div>
            <div class="activity-content">
                <h5>Waiting for new activity...</h5>
                <p>No recent appointments yet.</p>
                <small>--</small>
            </div>
        </div>
    </div>
</div>
                
                <div class="activity-item">
                    <div class="activity-icon" style="background-color: #198754;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="activity-content">
                        <h5>Session completed</h5>
                        <p>Michael Chen - Follow-up session</p>
                        <small>1 hour ago</small>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon" style="background-color: #6f42c1;">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div class="activity-content">
                        <h5>Parent message received</h5>
                        <p>Mrs. Davis regarding Emma's progress</p>
                        <small>3 hours ago</small>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon" style="background-color: #dc3545;">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div class="activity-content">
                        <h5>Urgent appointment request</h5>
                        <p>Student needs immediate counseling</p>
                        <small>5 hours ago</small>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Logout</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">Are you sure you want to logout?</div>
                <div class="modal-footer">
                    <a href="logout_admin.php" class="btn btn-danger">Yes, Logout</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>


  <!-- 🔍 Search Bar -->
<div class="position-relative" style="width: 280px;">
  <form class="d-flex align-items-center position-relative" role="search" method="GET" action="search_results.php" style="width: 100%;">
    <i class="fas fa-search position-absolute" style="left: 10px; color: #888;"></i>
    <input 
      id="searchInput"
      class="form-control ps-4"
      type="text"
      name="query"
      placeholder="Search students..."
      aria-label="Search"
      autocomplete="off"
      style="border-radius: 25px; padding-left: 35px;"
    >
  </form>

  <!-- 🔽 Suggestions dropdown -->
  <div id="suggestions" 
       class="list-group position-absolute w-100 shadow-sm bg-white" 
       style="z-index: 1000; display: none; border-radius: 10px;">
  </div>
</div>

<!-- 🔍 Live Search Script -->
<script>
const searchInput = document.getElementById("searchInput");
const suggestionsBox = document.getElementById("suggestions");

searchInput.addEventListener("keyup", function() {
  const query = this.value.trim();

  if (query.length > 0) {
    fetch(`search_suggestions.php?query=${encodeURIComponent(query)}`)
      .then(response => response.json())
      .then(data => {
        suggestionsBox.innerHTML = "";
        if (data.length > 0) {
          data.forEach(student => {
            const item = document.createElement("a");
            item.href = `search_results.php?query=${encodeURIComponent(student.name)}`;
            item.classList.add("list-group-item", "list-group-item-action");
            item.textContent = student.name;
            suggestionsBox.appendChild(item);
          });
          suggestionsBox.style.display = "block";
        } else {
          suggestionsBox.style.display = "none";
        }
      })
      .catch(() => (suggestionsBox.style.display = "none"));
  } else {
    suggestionsBox.style.display = "none";
  }
});

// Optional: hide suggestions when clicking outside
document.addEventListener("click", function(e) {
  if (!e.target.closest("#searchInput") && !e.target.closest("#suggestions")) {
    suggestionsBox.style.display = "none";
  }
});
</script> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// =========================
// 📌 Recent Activity Script
// =========================
function loadActivity() {
    fetch("recent_activity.php") 
    .then(res => res.text())
    .then(data => {
        document.getElementById("recent-activity-list").innerHTML = data;
    });
}

// auto-refresh every 5 seconds
setInterval(loadActivity, 5000);
// load agad once page opens
loadActivity();


// =========================
// 📌 Email Dropdown & Modal
// =========================
const emailBtn = document.getElementById('emailDropdownBtn');
const emailDropdown = document.getElementById('emailDropdown');

emailBtn.addEventListener('click', function(e) {
    e.preventDefault();
    emailDropdown.style.display = emailDropdown.style.display === 'block' ? 'none' : 'block';
});

document.addEventListener('click', function(e) {
    if (!emailDropdown.contains(e.target) && !emailBtn.contains(e.target)) {
        emailDropdown.style.display = 'none';
    }
});

// Show modal
document.getElementById('newMessageBtn').addEventListener('click', function() {
    window.location.href = 'email/admin_compose_message.php';
});


// See All
document.getElementById('seeAllBtn').addEventListener('click', function() {
    window.location.href = 'admin_messege.php';
});

// Load messages
function loadMessages() {
    document.getElementById('messagesList').innerHTML = `
        <div class="message-item">
            <strong>Sarah Johnson</strong><br>
            <small>Appointment request for next week</small>
        </div>
        <div class="message-item">
            <strong>Mrs. Davis</strong><br>
            <small>Question about Emma's progress</small>
        </div>
        <div class="message-item">
            <strong>System</strong><br>
            <small>3 more messages...</small>
        </div>
    `;
}

loadMessages();
</script>

<!-- Cloudflare script (hayaan lang, auto-generated security) -->
<script>
(function(){
    function c(){
        var b=a.contentDocument||a.contentWindow.document;
        if(b){
            var d=b.createElement('script');
            d.innerHTML="window.__CF$cv$params={r:'98a33423e2f90dc9',t:'MTc1OTczMzIwOC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";
            b.getElementsByTagName('head')[0].appendChild(d)
        }
    }
    if(document.body){
        var a=document.createElement('iframe');
        a.height=1;
        a.width=1;
        a.style.position='absolute';
        a.style.top=0;
        a.style.left=0;
        a.style.border='none';
        a.style.visibility='hidden';
        document.body.appendChild(a);
        if('loading'!==document.readyState)c();
        else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);
        else{
            var e=document.onreadystatechange||function(){};
            document.onreadystatechange=function(b){
                e(b);
                'loading'!==document.readyState&&(document.onreadystatechange=e,c())
            }
        }
    }
})();
</script>
</body>
</html>
