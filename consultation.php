<?php
include 'db.php';
session_start();

if (!isset($_SESSION['admin_email'])) {
  header("Location: admin_login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation Appointments - Admin Panel</title>
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
        }
        
        .page-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: #495057;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .page-subtitle {
            color: #6c757d;
            margin: 0.5rem 0 0 0;
            font-size: 1rem;
        }
        
        .stats-cards {
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
        }
        
        .stat-card i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .stat-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0.5rem 0;
        }
        
        .stat-card p {
            color: #6c757d;
            margin: 0;
            font-size: 0.9rem;
        }
        
        .academic { color: #0d6efd; }
        .career { color: #198754; }
        .personal { color: #6f42c1; }
        .behavioral { color: #fd7e14; }
        .others { color: #dc3545; }
        
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .filter-row {
            display: flex;
            gap: 1rem;
            align-items: end;
        }
        
        .filter-group {
            flex: 1;
        }
        
        .filter-group label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .form-control, .form-select {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 0.75rem;
            width: 100%;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
            outline: none;
        }
        
        .btn-filter {
            background: #0d6efd;
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
        }
        
        .btn-filter:hover {
            background: #0b5ed7;
        }
        
        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .table-header {
            background: #f8f9fa;
            padding: 1.5rem;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .table-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #495057;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .appointment-count {
            background: #0d6efd;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            font-size: 0.9rem;
        }
        
        table thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
            padding: 1rem 0.75rem;
            text-align: center;
            border-top: none;
        }
        
        table tbody td {
            padding: 1rem 0.75rem;
            text-align: center;
            vertical-align: middle;
            border-bottom: 1px solid #f8f9fa;
        }
        
        table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .interest-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            color: white;
        }
        
        .interest-academic { background: #0d6efd; }
        .interest-career { background: #198754; }
        .interest-personal { background: #6f42c1; }
        .interest-behavioral { background: #fd7e14; }
        .interest-others { background: #dc3545; }
        
        .btn-manage {
            background: #0d6efd;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-manage:hover {
            background: #0b5ed7;
            transform: translateY(-1px);
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from { 
                opacity: 0;
                transform: translateY(-50px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .modal-header {
            background: #0d6efd;
            color: white;
            padding: 1.5rem;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .close {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0.25rem;
            border-radius: 4px;
            transition: background 0.2s ease;
        }
        
        .close:hover {
            background: rgba(255,255,255,0.2);
        }
        
        .modal-body {
            padding: 2rem;
        }
        
        .modal-body form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .modal-body label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .modal-body select,
        .modal-body textarea {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 0.75rem;
            font-size: 1rem;
            transition: border-color 0.2s ease;
        }
        
        .modal-body select:focus,
        .modal-body textarea:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
            outline: none;
        }
        
        .modal-body textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .modal-body button {
            background: #198754;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .modal-body button:hover {
            background: #157347;
            transform: translateY(-1px);
        }
        
        .no-appointments {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        
        .no-appointments i {
            font-size: 3rem;
            color: #dee2e6;
            margin-bottom: 1rem;
            display: block;
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
            
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }
            
            .stats-cards {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .page-header {
                padding: 1.5rem;
            }
            
            .table-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            table {
                font-size: 0.8rem;
            }
            
            table thead th,
            table tbody td {
                padding: 0.5rem 0.25rem;
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
        <li><a href="admin_appointments.php"><i class="bi bi-calendar-check"></i> Appointments</a></li>
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
          <i class="bi bi-chat-dots"></i>
          Consultation Appointments
        </h1>
        <p class="page-subtitle">Manage and review all consultation requests from students</p>
      </div>

      <!-- Statistics Cards -->
      <div class="stats-cards">
        <div class="stat-card">
          <i class="bi bi-mortarboard academic"></i>
          <h3 id="academicCount">0</h3>
          <p>Academic</p>
        </div>
        <div class="stat-card">
          <i class="bi bi-briefcase career"></i>
          <h3 id="careerCount">0</h3>
          <p>Career</p>
        </div>
        <div class="stat-card">
          <i class="bi bi-person-heart personal"></i>
          <h3 id="personalCount">0</h3>
          <p>Personal</p>
        </div>
        <div class="stat-card">
          <i class="bi bi-exclamation-triangle behavioral"></i>
          <h3 id="behavioralCount">0</h3>
          <p>Behavioral</p>
        </div>
        <div class="stat-card">
          <i class="bi bi-three-dots others"></i>
          <h3 id="othersCount">0</h3>
          <p>Others</p>
        </div>
      </div>

      <!-- Filter Section -->
      <div class="filter-section">
        <div class="filter-row">
          <div class="filter-group">
            <label for="searchName">Search by Name</label>
            <input type="text" id="searchName" class="form-control" placeholder="Enter student name...">
          </div>
          <div class="filter-group">
            <label for="filterInterest">Filter by Interest</label>
            <select id="filterInterest" class="form-select">
              <option value="">All Interests</option>
              <option value="Academic">Academic</option>
              <option value="Career">Career</option>
              <option value="Personal">Personal</option>
              <option value="Behavioral">Behavioral</option>
              <option value="Others">Others</option>
            </select>
          </div>
          <div class="filter-group">
            <label for="filterDate">Filter by Date</label>
            <input type="date" id="filterDate" class="form-control">
          </div>
          <div>
            <button class="btn-filter" onclick="filterAppointments()">
              <i class="bi bi-funnel"></i> Filter
            </button>
          </div>
        </div>
      </div>

      <!-- Table Container -->
      <div class="table-container">
        <div class="table-header">
          <h3 class="table-title">
            <i class="bi bi-list-ul"></i>
            Consultation List
          </h3>
          <span class="appointment-count" id="appointmentCount">
            Loading...
          </span>
        </div>

        <div class="table-responsive">
          <table id="consultationTable">
            <thead>
              <tr>
                <th><i class="bi bi-person"></i> Full Name</th>
                <th><i class="bi bi-envelope"></i> Email</th>
                <th><i class="bi bi-mortarboard"></i> Grade / Year</th>
                <th><i class="bi bi-book"></i> Strand / Course</th>
                <th><i class="bi bi-calendar"></i> Date</th>
                <th><i class="bi bi-clock"></i> Time</th>
                <th><i class="bi bi-tag"></i> Interest</th>
                <th><i class="bi bi-chat-text"></i> Specific Concern</th>
                <th><i class="bi bi-chat-text"></i> Teacher</th>
                <th><i class="bi bi-file-text"></i> Reason</th>
                <th><i class="bi bi-gear"></i> Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $result = $con->query("SELECT * FROM appointments WHERE interest IN ('Academic', 'Career', 'Personal', 'Behavioral', 'Others') ORDER BY date ASC, time ASC");
              if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $interestClass = 'interest-' . strtolower($row['interest']);
                  echo "<tr>
                          <td><strong>" . htmlspecialchars($row['name']) . "</strong></td>
                          <td>" . htmlspecialchars($row['email']) . "</td>
                          <td>" . htmlspecialchars($row['grade']) . "</td>
                          <td>" . htmlspecialchars($row['section']) . "</td>
                          <td>" . htmlspecialchars($row['date']) . "</td>
                          <td>" . htmlspecialchars($row['time']) . "</td>
                          <td><span class='interest-badge $interestClass'>" . htmlspecialchars($row['interest']) . "</span></td>
                          <td><span title='" . htmlspecialchars($row['specific_concern']) . "'>" . 
                          (strlen($row['specific_concern']) > 20 ? substr(htmlspecialchars($row['specific_concern']), 0, 20) . '...' : htmlspecialchars($row['specific_concern'])) . "</span></td>
                          <td>" . htmlspecialchars($row['teacher']) . "</td>
                          <td><span title='" . htmlspecialchars($row['reason']) . "'>" . 
                          (strlen($row['reason']) > 30 ? substr(htmlspecialchars($row['reason']), 0, 30) . '...' : htmlspecialchars($row['reason'])) . "</span></td>
                          <td><button class='btn-manage' onclick=\"openModal('{$row['id']}')\"><i class='bi bi-gear'></i> Manage</button></td>
                        </tr>";
                }
              } else {
                echo "<tr><td colspan='10' class='no-appointments'>
                        <i class='bi bi-calendar-x'></i>
                        <h4>No Consultation Appointments Found</h4>
                        <p>There are currently no consultation appointments to display.</p>
                      </td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

 <!-- Spinner + Feedback -->
<div id="processingOverlay" style="position: fixed; top:0; left:0; width:100%; height:100%; 
     background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 2000; flex-direction: column; color: white; font-size: 1.2rem;">
  <div class="spinner-border text-light" role="status" style="width: 4rem; height: 4rem;">
    <span class="visually-hidden">Loading...</span>
  </div>
  <div id="overlayMessage" style="margin-top: 20px;"></div>
</div>


  <!-- Manage Modal -->
  <div id="manageModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3><i class="bi bi-gear"></i> Manage Appointment</h3>
        <span class="close" onclick="closeModal()">&times;</span>
      </div>
      <div class="modal-body">
       <form id="decisionForm" action="update_status.php" method="POST" onsubmit="return showConfirmModal(event)">
  <input type="hidden" name="appointment_id" id="appointmentId">
  
  <div>
    <label for="status">Status:</label>
    <select name="status" id="status" required>
      <option value="">--Select Status--</option>
      <option value="Approved">✅ Approved</option>
      <option value="Declined">❌ Declined</option>
    </select>
    
    <label>Message to Student:</label>
    <textarea name="admin_message" placeholder="Your message..."></textarea>
  </div>

  <button type="submit">
    <i class="bi bi-check-circle"></i> Submit Decision
  </button>
</form>

<!-- Confirmation Modal -->
<div id="confirmModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h3>Confirm Submission</h3>
      <span class="close" onclick="closeConfirmModal()">&times;</span>
    </div>
    <div class="modal-body">
      <p>Are you sure you want to submit this decision?</p>
      <div style="text-align: right;">
        <button onclick="submitForm()" class="btn btn-primary">Yes</button>
        <button onclick="closeConfirmModal()" class="btn btn-secondary">No</button>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>

    // Confirmation before submitting the form
  function confirmSubmission() {
      return confirm("Are you sure you want to submit this decision?");
  }

  // Show confirmation modal
function showConfirmModal(event) {
  event.preventDefault(); // Stop the form from submitting
  document.getElementById('confirmModal').style.display = 'block';
  return false;
}

// Close confirmation modal
function closeConfirmModal() {
  document.getElementById('confirmModal').style.display = 'none';
}

// Submit the form when "Yes" is clicked
function submitForm() {
  const status = document.getElementById('status').value;

  if (!status) {
    alert("Please select a status first!");
    return;
  }

  // Close confirm modal
  closeConfirmModal();

  // Show overlay spinner
  const overlay = document.getElementById('processingOverlay');
  const overlayMsg = document.getElementById('overlayMessage');
  overlayMsg.textContent = ""; // clear previous message
  overlay.style.display = "flex";

  // After 1.5 seconds, hide spinner and show message
  setTimeout(() => {
    overlayMsg.textContent = status === "Approved" ? "Appointment Approved ✅" : "Appointment Declined ❌";
    
    // Optional: change spinner color or hide it
    const spinner = overlay.querySelector('.spinner-border');
    spinner.style.display = "none";

    // After another 1 second, submit form
    setTimeout(() => {
      overlay.style.display = "none";
      document.getElementById('decisionForm').submit();
    }, 1000);

  }, 1500);
}


// Close modal when clicking outside
window.addEventListener('click', function(event) {
  const confirmModal = document.getElementById('confirmModal');
  if (event.target === confirmModal) {
    closeConfirmModal();
  }
});

    // Count appointments by interest
    function updateStats() {
      const rows = document.querySelectorAll('#consultationTable tbody tr');
      const stats = {
        academic: 0,
        career: 0,
        personal: 0,
        behavioral: 0,
        others: 0
      };
      
      let totalCount = 0;
      
      rows.forEach(row => {
        if (row.cells.length > 1) { // Skip "no appointments" row
          const interest = row.cells[6].textContent.trim().toLowerCase();
          if (stats.hasOwnProperty(interest)) {
            stats[interest]++;
          }
          totalCount++;
        }
      });
      
      document.getElementById('academicCount').textContent = stats.academic;
      document.getElementById('careerCount').textContent = stats.career;
      document.getElementById('personalCount').textContent = stats.personal;
      document.getElementById('behavioralCount').textContent = stats.behavioral;
      document.getElementById('othersCount').textContent = stats.others;
      document.getElementById('appointmentCount').textContent = totalCount + ' appointments';
    }

    // Filter appointments
    function filterAppointments() {
      const searchName = document.getElementById('searchName').value.toLowerCase();
      const filterInterest = document.getElementById('filterInterest').value;
      const filterDate = document.getElementById('filterDate').value;
      const table = document.getElementById('consultationTable');
      const rows = table.getElementsByTagName('tr');
      let visibleCount = 0;

      for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        if (row.cells.length <= 1) continue; // Skip "no appointments" row
        
        const name = row.cells[0].textContent.toLowerCase();
        const interest = row.cells[6].textContent.trim();
        const date = row.cells[4].textContent;

        let showRow = true;

        if (searchName && !name.includes(searchName)) {
          showRow = false;
        }

        if (filterInterest && interest !== filterInterest) {
          showRow = false;
        }

        if (filterDate && date !== filterDate) {
          showRow = false;
        }

        if (showRow) {
          row.style.display = '';
          visibleCount++;
        } else {
          row.style.display = 'none';
        }
      }

      document.getElementById('appointmentCount').textContent = visibleCount + ' appointments';
    }

    // Modal functions
    function openModal(appointmentId) {
      document.getElementById('appointmentId').value = appointmentId;
      document.getElementById('manageModal').style.display = 'block';
    }

    function closeModal() {
      document.getElementById('manageModal').style.display = 'none';
    }

    // Real-time search
    document.getElementById('searchName').addEventListener('input', filterAppointments);
    document.getElementById('filterInterest').addEventListener('change', filterAppointments);
    document.getElementById('filterDate').addEventListener('change', filterAppointments);

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
      const modal = document.getElementById('manageModal');
      if (event.target === modal) {
        closeModal();
      }
    });

    // Initialize stats on page load
    document.addEventListener('DOMContentLoaded', function() {
      updateStats();
    });
  </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98a37f0565c40dc9',t:'MTc1OTczNjI3NS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
