<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

include("db.php"); // Make sure your DB connection is included

// Get pending counts dynamically

// Consultation pending
$consultationQuery = $con->query("SELECT COUNT(*) AS total FROM appointments WHERE status='Pending'");
$consultationPending = $consultationQuery->fetch_assoc()['total'];

// Special Exam pending
$examQuery = $con->query("SELECT COUNT(*) AS total FROM special_exam_requests WHERE status='Pending'");
$examPending = $examQuery->fetch_assoc()['total'];

// Exit Interview pending
$interviewQuery = $con->query("SELECT COUNT(*) AS total FROM exit_interviews WHERE status='Pending'");
$interviewPending = $interviewQuery->fetch_assoc()['total'];

// Incident pending
$incidentQuery = $con->query("SELECT COUNT(*) AS total FROM student_incident_reports WHERE status='Pending'");
$incidentPending = $incidentQuery->fetch_assoc()['total'];

// Parent Appointment pending
$parentQuery = $con->query("SELECT COUNT(*) AS total FROM parent_appointments WHERE status='Pending'");
$parentPending = $parentQuery->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Categories - Admin Panel</title>
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
        
        .page-header {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #0d6efd;
            text-align: center;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 600;
            color: #495057;
            margin: 0 0 0.5rem 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }
        
        .page-subtitle {
            color: #6c757d;
            margin: 0;
            font-size: 1rem;
        }
        
        .stats-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            border-left: 4px solid;
        }
        
        .stat-card.consultation { border-left-color: #0d6efd; }
        .stat-card.exam { border-left-color: #198754; }
        .stat-card.interview { border-left-color: #6f42c1; }
        .stat-card.incident { border-left-color: #dc3545; }
        
        .stat-card i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .stat-card.consultation i { color: #0d6efd; }
        .stat-card.exam i { color: #198754; }
        .stat-card.interview i { color: #6f42c1; }
        .stat-card.incident i { color: #dc3545; }
        
        .stat-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0.5rem 0;
            color: #495057;
        }
        
        .stat-card p {
            color: #6c757d;
            margin: 0;
            font-size: 0.9rem;
        }
        
        .category-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .category-box {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .category-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }
        
        .category-box a {
            display: block;
            padding: 2rem;
            text-decoration: none;
            color: #495057;
            font-size: 1.25rem;
            font-weight: 600;
            text-align: center;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .category-box.consultation {
            border-left: 4px solid #0d6efd;
        }
        
        .category-box.consultation a:hover {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            color: #1976d2;
        }
        
        .category-box.exam {
            border-left: 4px solid #198754;
        }
        
        .category-box.exam a:hover {
            background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
            color: #2e7d32;
        }
        
        .category-box.interview {
            border-left: 4px solid #6f42c1;
        }
        
        .category-box.interview a:hover {
            background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
            color: #7b1fa2;
        }
        
        .category-box.incident {
            border-left: 4px solid #dc3545;
        }
        
        .category-box.incident a:hover {
            background: linear-gradient(135deg, #fce4ec 0%, #f8bbd9 100%);
            color: #c2185b;
        }
        
        .category-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
        }
        
        .category-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .category-description {
            font-size: 0.9rem;
            color: #6c757d;
            margin: 0;
            line-height: 1.5;
        }
        
        .category-stats {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(0,0,0,0.1);
            color: #495057;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .quick-actions {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .quick-actions h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .action-btn {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #495057;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .action-btn:hover {
            background: #0d6efd;
            color: white;
            border-color: #0d6efd;
            transform: translateY(-1px);
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
            
            .category-container {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .stats-overview {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .page-header {
                padding: 1.5rem;
            }
            
            .page-title {
                font-size: 1.5rem;
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .action-buttons {
                flex-direction: column;
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
      <li><a href="admin_dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a></li>
      <li><a href="admin_records.php"><i class="bi bi-people"></i> Student Records</a></li>
      <li><a href="admin_appointments.php" class="active"><i class="bi bi-calendar-check"></i> Appointments</a></li>
      <li><a href="admin_reports.php"><i class="bi bi-graph-up"></i> Reports</a></li>
      <li><a href="admin_settings.php"><i class="bi bi-gear"></i> Settings</a></li>
      <li><a href="admin_announcements.php"><i class="bi bi-megaphone"></i> Announcements</a></li>
      <li><a href="admin_parents.php"><i class="bi bi-person-lines-fill"></i> Parent Accounts</a></li>
    </ul>
  </div>

    <!-- Content -->
    <div class="content">
      <!-- Page Header -->
      <div class="page-header">
        <h1 class="page-title">
          <i class="bi bi-calendar-check"></i>
          Appointment Management
        </h1>
        <p class="page-subtitle">Manage all types of student appointments and requests</p>
      </div>

      <!-- Category Container -->
      <div class="category-container">
        <div class="category-box consultation">
          <div class="category-stats" id="consultationStat"><?php echo $consultationPending; ?> pending</div>
          <a href="consultation.php">
            <div class="category-icon">💬</div>
            <div class="category-title">Consultation</div>
            <p class="category-description">Academic, career, personal, and behavioral counseling sessions</p>
          </a>
        </div>
        
        <div class="category-box exam">
          <div class="category-stats" id="examStat"><?php echo $examPending; ?> pending</div>
          <a href="special_exam_view.php">
            <div class="category-icon">📝</div>
            <div class="category-title">Special Exam</div>
            <p class="category-description">Make-up exams, special testing accommodations, and exam scheduling</p>
          </a>
        </div>
        
        <div class="category-box interview">
          <div class="category-stats" id="interviewStat"><?php echo $interviewPending; ?> pending</div>
          <a href="exit_interview.php">
            <div class="category-icon">🚪</div>
            <div class="category-title">Exit Interview</div>
            <p class="category-description">Student departure interviews and feedback collection</p>
          </a>
        </div>

        <div class="category-box interview">
            <div class="category-stats" id="parentStat"><?php echo $parentPending; ?> pending</div>
          <a href="admin_parent_appointments.php">
            <div class="category-icon">📌</div>
            <div class="category-title">Parent Appointment</div>
            <p class="category-description">Schedule a parent-admin meeting to discuss your child’s school matters.</p>
          </a>
        </div>
        
        <div class="category-box incident">
          <div class="category-stats" id="incidentStat"><?php echo $incidentPending; ?> pending</div>
          <a href="incident.php">
            <div class="category-icon">⚠️</div>
            <div class="category-title">Incident Report</div>
            <p class="category-description">Disciplinary issues, conflicts, and behavioral incidents</p>
          </a>
        </div>
      </div>


  <script>
    // Simulate loading appointment counts (replace with actual PHP/AJAX calls)
    document.addEventListener('DOMContentLoaded', function() {
      // These would typically be loaded from your database
      const stats = {
        consultation: 24,
        exam: 8,
        interview: 3,
        incident: 5
      };
      
      document.getElementById('consultationCount').textContent = stats.consultation;
      document.getElementById('examCount').textContent = stats.exam;
      document.getElementById('interviewCount').textContent = stats.interview;
      document.getElementById('incidentCount').textContent = stats.incident;
      
      document.getElementById('consultationStat').textContent = Math.floor(stats.consultation * 0.6) + ' pending';
      document.getElementById('examStat').textContent = Math.floor(stats.exam * 0.5) + ' pending';
      document.getElementById('interviewStat').textContent = Math.floor(stats.interview * 0.3) + ' pending';
      document.getElementById('incidentStat').textContent = Math.floor(stats.incident * 0.8) + ' pending';
    });

    // Add hover effects and animations
    document.querySelectorAll('.category-box').forEach(box => {
      box.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px) scale(1.02)';
      });
      
      box.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
      });
    });
  </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98a3936ce0c80dc9',t:'MTc1OTczNzExMC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
