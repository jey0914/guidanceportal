<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

include("db.php");

// ✅ Get actual counts (for dashboard stats)
$totalCount = $con->query("SELECT COUNT(*) AS total FROM special_exam_requests")->fetch_assoc()['total'];
$pendingCount = $con->query("SELECT COUNT(*) AS total FROM special_exam_requests WHERE status = 'Pending'")->fetch_assoc()['total'];
$approvedCount = $con->query("SELECT COUNT(*) AS total FROM approved_special_exam")->fetch_assoc()['total'];
$declinedCount = $con->query("SELECT COUNT(*) AS total FROM special_exam_requests WHERE status = 'Rejected'")->fetch_assoc()['total'];

// ✅ ADD: Approve / Decline logic (before fetching records)
if (isset($_POST['approve'])) {
    $id = $_POST['id'];

    // Fetch the request details from special_exam_requests
    $getRequest = $con->prepare("SELECT * FROM special_exam_requests WHERE id = ?");
    $getRequest->bind_param("i", $id);
    $getRequest->execute();
    $requestData = $getRequest->get_result()->fetch_assoc();

    if ($requestData) {
        // Check if already approved or rejected
        if ($requestData['status'] !== 'Pending') {
            echo "<script>alert('This request has already been " . $requestData['status'] . ".');</script>";
        } else {
            // Combine full name
            $full_name = trim($requestData['fname'] . ' ' . ($requestData['mname'] ? $requestData['mname'] . ' ' : '') . $requestData['lname']);

            // Insert into approved_special_exam
            $insert = $con->prepare("INSERT INTO approved_special_exam 
                (full_name, email, subject, reason, proof_filename, submitted_at, year_level, strand_course, teacher, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Approved')");
            $insert->bind_param(
                "sssssssss",
                $full_name,
                $requestData['email'],
                $requestData['subject'],
                $requestData['reason'],
                $requestData['proof_filename'],
                $requestData['submitted_at'],
                $requestData['year_level'],
                $requestData['strand_course'],
                $requestData['teacher']
            );
            $insert->execute();

            // Update status in requests table
            $update = $con->prepare("UPDATE special_exam_requests SET status='Approved' WHERE id=?");
            $update->bind_param("i", $id);
            $update->execute();
        }
    }
}


if (isset($_POST['decline'])) {
    $id = $_POST['id'];

    $update = $con->prepare("UPDATE special_exam_requests SET status = 'Rejected' WHERE id = ?");
    $update->bind_param("i", $id);
    $update->execute();
}

// kunin lahat ng records
$query = "SELECT * FROM special_exam_requests ORDER BY submitted_at DESC";
$result = $con->query($query);

// para sa filters, kunin distinct values
$subjects = $con->query("SELECT DISTINCT subject FROM special_exam_requests ORDER BY subject ASC");
$teachers = $con->query("SELECT DISTINCT teacher FROM special_exam_requests ORDER BY teacher ASC");
$years    = $con->query("SELECT DISTINCT year_level FROM special_exam_requests ORDER BY year_level ASC");

// Get all special exam requests
$result = $con->query("SELECT * FROM special_exam_requests ORDER BY submitted_at DESC");

// Get unique subjects for filter
$subjects = $con->query("SELECT DISTINCT subject FROM special_exam_requests ORDER BY subject");

// Get unique teachers for filter
$teachers = $con->query("SELECT DISTINCT teacher FROM special_exam_requests ORDER BY teacher");

// Get unique year levels for filter
$years = $con->query("SELECT DISTINCT year_level FROM special_exam_requests ORDER BY year_level");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Exam Requests - Admin Panel</title>
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
            border-left: 4px solid #198754;
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
        
        .back-btn {
            background: #6c757d;
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            margin-bottom: 1rem;
        }
        
        .back-btn:hover {
            background: #5a6268;
            color: white;
            transform: translateY(-1px);
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
            border-left: 4px solid;
        }
        
        .stat-card.total { border-left-color: #0d6efd; }
        .stat-card.pending { border-left-color: #ffc107; }
        .stat-card.approved { border-left-color: #198754; }
        .stat-card.declined { border-left-color: #dc3545; }
        
        .stat-card i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .stat-card.total i { color: #0d6efd; }
        .stat-card.pending i { color: #ffc107; }
        .stat-card.approved i { color: #198754; }
        .stat-card.declined i { color: #dc3545; }
        
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
        
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .filter-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #495057;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .filter-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr auto;
            gap: 1rem;
            align-items: end;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        
        .filter-group label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .form-control, .form-select {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 0.75rem;
            font-size: 0.9rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
            outline: none;
        }
        
        .btn-filter {
            background: #198754;
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-filter:hover {
            background: #157347;
        }
        
        .requests-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .requests-header {
            background: #f8f9fa;
            padding: 1.5rem;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .requests-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #495057;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .request-count {
            background: #198754;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .request-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f8f9fa;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .request-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }
        
        .request-item:last-child {
            border-bottom: none;
        }
        
        .request-info {
            flex: 1;
        }
        
        .request-name {
            font-weight: 600;
            color: #495057;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .request-name::before {
            content: "📝";
            font-size: 1.2rem;
        }
        
        .request-details {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .request-meta {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .request-date {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .modal-header {
            background: #198754;
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 1.5rem;
        }
        
        .modal-title {
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .modal-body {
            padding: 2rem;
        }
        
        .modal-body p {
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #f8f9fa;
        }
        
        .modal-body p:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .modal-body strong {
            color: #495057;
            font-weight: 600;
            display: inline-block;
            min-width: 120px;
        }
        
        .no-requests {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        
        .no-requests i {
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
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .stats-cards {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .page-header {
                padding: 1.5rem;
            }
            
            .requests-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            .request-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
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
                    <i class="bi bi-file-earmark-text"></i>
                    Special Exam Requests
                </h1>
                <p class="page-subtitle">Review and manage special examination requests from students</p>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-cards">
                <div class="stat-card total">
                    <i class="bi bi-file-earmark-text"></i>
                    <h3><?= $totalCount ?></h3>
                    <p>Total Requests</p>
                </div>
                <div class="stat-card pending">
                    <i class="bi bi-clock"></i>
                    <h3><?= $pendingCount ?></h3>
                    <p>Pending Review</p>
                </div>
                <div class="stat-card approved">
                    <i class="bi bi-check-circle"></i>
                    <h3><?= $approvedCount ?></h3>
                    <p>Approved</p>
                </div>
                <div class="stat-card declined">
                    <i class="bi bi-x-circle"></i>
                    <h3><?= $declinedCount ?></h3>
                    <p>Declined</p>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <div class="filter-header">
                    <h3 class="filter-title">
                        <i class="bi bi-funnel"></i>
                        Filter Requests
                    </h3>
                </div>
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="searchInput">Search by Name or Email</label>
                        <input type="text" id="searchInput" class="form-control" placeholder="Enter student name or email...">
                    </div>
                    
                    <div class="filter-group">
                        <label for="filterSubject">Subject</label>
                        <select id="filterSubject" class="form-select">
                            <option value="">All Subjects</option>
                            <?php while($s = $subjects->fetch_assoc()): ?>
                                <option value="<?= htmlspecialchars($s['subject']) ?>"><?= htmlspecialchars($s['subject']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="filterTeacher">Teacher</label>
                        <select id="filterTeacher" class="form-select">
                            <option value="">All Teachers</option>
                            <?php while($t = $teachers->fetch_assoc()): ?>
                                <option value="<?= htmlspecialchars($t['teacher']) ?>"><?= htmlspecialchars($t['teacher']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="filterYear">Year Level</label>
                        <select id="filterYear" class="form-select">
                            <option value="">All Year Levels</option>
                            <?php while($y = $years->fetch_assoc()): ?>
                                <option value="<?= htmlspecialchars($y['year_level']) ?>"><?= htmlspecialchars($y['year_level']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div>
                        <button class="btn-filter" onclick="filterRequests()">
                            <i class="bi bi-search"></i>
                            Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Requests Container -->
            <div class="requests-container">
                <div class="requests-header">
                    <h3 class="requests-title">
                        <i class="bi bi-list-ul"></i>
                        Exam Requests
                    </h3>
                   <span class="request-count" id="requestCount">
    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
    Loading...
</span>
                </div>

                <!-- Request List -->
                <div id="requestsList">
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
                            <div class="request-item" 
                                 data-name="<?= strtolower($row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']) ?>"
                                 data-email="<?= strtolower($row['email']) ?>" 
                                 data-subject="<?= strtolower($row['subject']) ?>" 
                                 data-teacher="<?= strtolower($row['teacher']) ?>" 
                                 data-year="<?= strtolower($row['year_level']) ?>"
                                 data-bs-toggle="modal" 
                                 data-bs-target="#requestModal<?= $row['id'] ?>">
                                <div class="request-info">
                                    <span class="request-name">#<?= $i++ ?> - <?= htmlspecialchars($row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']) ?></span>
                                    <div class="request-details">
                                        <span class="badge bg-primary me-2"><?= htmlspecialchars($row['subject']) ?></span>
                                        <span class="badge bg-secondary me-2"><?= htmlspecialchars($row['teacher']) ?></span>
                                        <span class="badge bg-info"><?= htmlspecialchars($row['year_level']) ?></span>
                                    </div>
                                </div>
                                <div class="request-meta">
                                    <span class="request-date"><?= date('M d, Y h:i A', strtotime($row['submitted_at'])) ?></span>
                                    <i class="bi bi-chevron-right text-muted"></i>
                                </div>
                            </div>

                           <!-- Modal -->
<div class="modal fade" id="requestModal<?= $row['id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-file-earmark-text"></i>
                    Special Exam Request Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Full Name:</strong> <?= htmlspecialchars($row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
                <p><strong>Year Level:</strong> <?= htmlspecialchars($row['year_level']) ?></p>
                <p><strong>Strand:</strong> <?= htmlspecialchars($row['strand_course']) ?></p>
                <p><strong>Subject:</strong> <?= htmlspecialchars($row['subject']) ?></p>
                <p><strong>Teacher:</strong> <?= htmlspecialchars($row['teacher']) ?></p>
                <p><strong>Reason:</strong><br><?= nl2br(htmlspecialchars($row['reason'])) ?></p>
                <p><strong>Proof:</strong>
                    <?php 
                        $file_path = 'uploads/' . $row['proof_filename'];
                        if (!empty($row['proof_filename']) && file_exists($file_path)): 
                    ?>
                        <a href="<?= $file_path ?>" target="_blank" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-file-earmark"></i> View File
                        </a>
                    <?php else: ?>
                        <span class="text-muted">No File Uploaded</span>
                    <?php endif; ?>
                </p>
                <p><strong>Submitted At:</strong> <?= date('F d, Y h:i A', strtotime($row['submitted_at'])) ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="button" class="btn btn-success btn-sm" <?= $row['status'] !== 'Pending' ? 'disabled' : '' ?> 
                    onclick="updateRequestStatus(<?= $row['id'] ?>, 'Approved')">Approve</button>
                </form>

                <form method="POST" style="display:inline;">
                    <button type="button" class="btn btn-danger btn-sm" 
                    onclick="updateRequestStatus(<?= $row['id'] ?>, 'Rejected')">Decline</button>
                </form>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="no-requests">
                            <i class="bi bi-file-earmark-x"></i>
                            <h4>No Special Exam Requests Found</h4>
                            <p>There are currently no special exam requests to display.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast container -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
  <div id="statusToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="toastMessage">
        Status updated successfully!
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Bootstrap confirmation modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Action</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="confirmModalBody">
        Are you sure?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="confirmModalYes">Yes</button>
      </div>
    </div>
  </div>
</div>

<script>
    let currentRequestId = null;
    let currentRequestStatus = null;

    function updateRequestStatus(id, status) {
        // Save current request info
        currentRequestId = id;
        currentRequestStatus = status;

        // Update modal text
        document.getElementById('confirmModalBody').textContent =
            `Are you sure you want to mark this request as ${status}?`;

        // Show modal
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
        confirmModal.show();
    }

    // Handle "Yes" button click in modal
    document.getElementById('confirmModalYes').addEventListener('click', function() {
        const id = currentRequestId;
        const status = currentRequestStatus;

        fetch('update_special_exam_status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}&status=${status}`
        })
        .then(response => response.text())
        .then(msg => {
    // Show toast instead of alert
    document.getElementById('toastMessage').textContent = msg;
    const toastEl = document.getElementById('statusToast');
    const toast = new bootstrap.Toast(toastEl);
    toast.show();

    // --- Instant stats update ---
    const approvedCard = document.querySelector('.stat-card.approved h3');
    const pendingCard = document.querySelector('.stat-card.pending h3');
    const declinedCard = document.querySelector('.stat-card.declined h3');

    if (status === 'Approved') {
        approvedCard.textContent = parseInt(approvedCard.textContent) + 1;
        pendingCard.textContent = parseInt(pendingCard.textContent) - 1;
    } else if (status === 'Rejected') {
        declinedCard.textContent = parseInt(declinedCard.textContent) + 1;
        pendingCard.textContent = parseInt(pendingCard.textContent) - 1;
    }

    // --- Disable buttons after action ---
    const approveBtn = document.querySelector(`#requestModal${id} button.btn-success`);
    const declineBtn = document.querySelector(`#requestModal${id} button.btn-danger`);
    if (approveBtn) approveBtn.disabled = true;
    if (declineBtn) declineBtn.disabled = true;

    // --- Dim the request item ---
    const requestItem = document.querySelector(`.request-item[data-bs-target="#requestModal${id}"]`);
    if (requestItem) requestItem.style.opacity = '0.5';

    // Hide confirmation modal
    const confirmModalEl = document.getElementById('confirmModal');
    const modalInstance = bootstrap.Modal.getInstance(confirmModalEl);
    modalInstance.hide();
})

        .catch(err => console.error(err));
    });

    // --- Your existing code below ---
    function updateStats() {
        const items = document.querySelectorAll('.request-item');
        const totalCount = items.length;

        const pendingCount = Math.floor(totalCount * 0.6);
        const approvedCount = Math.floor(totalCount * 0.3);
        const declinedCount = totalCount - pendingCount - approvedCount;

        document.getElementById('totalCount').textContent = totalCount;
        document.getElementById('pendingCount').textContent = pendingCount;
        document.getElementById('approvedCount').textContent = approvedCount;
        document.getElementById('declinedCount').textContent = declinedCount;
        document.getElementById('requestCount').textContent = totalCount + ' requests';
    }

    function filterRequests() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const subjectFilter = document.getElementById('filterSubject').value.toLowerCase();
        const teacherFilter = document.getElementById('filterTeacher').value.toLowerCase();
        const yearFilter = document.getElementById('filterYear').value.toLowerCase();

        const items = document.querySelectorAll('.request-item');
        let visibleCount = 0;

        items.forEach(item => {
            const name = item.dataset.name || '';
            const email = item.dataset.email || '';
            const subject = item.dataset.subject || '';
            const teacher = item.dataset.teacher || '';
            const year = item.dataset.year || '';

            let show = true;

            if (searchTerm && !name.includes(searchTerm) && !email.includes(searchTerm)) show = false;
            if (subjectFilter && subject !== subjectFilter) show = false;
            if (teacherFilter && teacher !== teacherFilter) show = false;
            if (yearFilter && year !== yearFilter) show = false;

            if (show) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        document.getElementById('requestCount').textContent = visibleCount + ' requests';
    }

    document.getElementById('searchInput').addEventListener('input', filterRequests);
    document.getElementById('filterSubject').addEventListener('change', filterRequests);
    document.getElementById('filterTeacher').addEventListener('change', filterRequests);
    document.getElementById('filterYear').addEventListener('change', filterRequests);

    document.addEventListener('DOMContentLoaded', function() {
        updateStats();
    });
</script>
