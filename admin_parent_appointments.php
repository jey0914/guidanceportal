<?php
include("db.php");

// Handle AJAX actions for approve/decline
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['appointment_id'])) {
    $action = $_POST['action'];
    $appointment_id = $_POST['appointment_id'];

    $response = ['success' => false, 'message' => 'Something went wrong.'];

    if ($action === 'approve') {
        $stmt = $con->prepare("UPDATE parent_appointments SET status='confirmed' WHERE id=?");
        $stmt->bind_param("i", $appointment_id);
        if ($stmt->execute()) {
            $response = ['success' => true, 'message' => 'Appointment approved successfully.'];
        } else {
            $response['message'] = 'Failed to update appointment.';
        }
        $stmt->close();
    } elseif ($action === 'decline') {
        $stmt = $con->prepare("UPDATE parent_appointments SET status='cancelled' WHERE id=?");
        $stmt->bind_param("i", $appointment_id);
        if ($stmt->execute()) {
            $response = ['success' => true, 'message' => 'Appointment declined successfully.'];
        } else {
            $response['message'] = 'Failed to update appointment.';
        }
        $stmt->close();
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Fetch appointments
$appointments = [];
$sql = "SELECT * FROM parent_appointments ORDER BY created_at DESC";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'email' => $row['email'],
            'student_id' => $row['student_id'],
            'date' => $row['date'],
            'time' => $row['time'],
            'interest' => $row['interest'],
            'reason' => $row['reason'],
            'status' => $row['status'],
            'created_at' => $row['created_at']
        ];
    }
}
$appointments_json = json_encode($appointments);

// Statistics
$totalAppointments = $pendingAppointments = $confirmedAppointments = $cancelledAppointments = 0;

$sqlStats = "SELECT 
                COUNT(*) AS total,
                SUM(status='pending') AS pending,
                SUM(status='confirmed') AS confirmed,
                SUM(status='cancelled') AS cancelled
             FROM parent_appointments";
$resultStats = $con->query($sqlStats);

if ($resultStats->num_rows > 0) {
    $stats = $resultStats->fetch_assoc();
    $totalAppointments = $stats['total'];
    $pendingAppointments = $stats['pending'];
    $confirmedAppointments = $stats['confirmed'];
    $cancelledAppointments = $stats['cancelled'];
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Appointments - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            height: 100%;
        }
        
        .sidebar {
            width: 250px;
            background-color: #fff;
            border-right: 1px solid #dee2e6;
            padding: 1.5rem 0;
            position: fixed;
            height: 100%;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .sidebar .logo {
            padding: 0 1.5rem 2rem;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 1rem;
        }
        
        .sidebar .logo h4 {
            color: #2c3e50;
            font-weight: 600;
            margin: 0;
        }
        
        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar ul li {
            margin: 0;
        }
        
        .sidebar ul li a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: #6c757d;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .sidebar ul li a:hover {
            background-color: #f8f9fa;
            color: #495057;
        }
        
        .sidebar ul li a.active {
            background-color: #007bff;
            color: white;
            border-right: 3px solid #0056b3;
        }
        
        .sidebar ul li a i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
            width: 20px;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            min-height: 100%;
        }
        
        .page-header {
            margin-bottom: 2rem;
        }
        
        .page-header h1 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .page-header p {
            color: #6c757d;
            margin: 0;
        }
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.5rem;
        }
        
        .stat-card.total .stat-icon {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        
        .stat-card.pending .stat-icon {
            background-color: #fff3e0;
            color: #f57c00;
        }
        
        .stat-card.confirmed .stat-icon {
            background-color: #e8f5e8;
            color: #388e3c;
        }
        
        .stat-card.cancelled .stat-icon {
            background-color: #ffebee;
            color: #d32f2f;
        }
        
        .stat-card h3 {
            font-size: 2rem;
            font-weight: 600;
            margin: 0;
            color: #2c3e50;
        }
        
        .stat-card p {
            margin: 0;
            color: #6c757d;
            font-weight: 500;
        }
        
        .appointments-table {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .table-header {
            padding: 1.5rem;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .table-header h5 {
            margin: 0;
            color: #2c3e50;
            font-weight: 600;
        }
        
        .table-controls {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .search-box {
            position: relative;
            min-width: 250px;
        }
        
        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        .search-box input {
            padding-left: 40px;
        }
        
        .filter-select {
            min-width: 150px;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .table {
            margin: 0;
        }
        
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
            padding: 1rem 0.75rem;
        }
        
        .table tbody td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: capitalize;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-confirmed {
            background-color: #d1edff;
            color: #0c5460;
        }
        
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .btn-group .btn {
            margin-right: 0.25rem;
        }
        
        .btn-group .btn:last-child {
            margin-right: 0;
        }
        
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1050;
            display: none;
        }
        
        .modal-dialog {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 90%;
            max-height: 90%;
            overflow-y: auto;
            z-index: 1051;
            display: none;
        }
        
        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }
        
        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6c757d;
        }
        
        .close-btn:hover {
            color: #495057;
        }
        
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        

#confirmBackdrop {
  position: fixed;
  top:0;
  left:0;
  width:100%;
  height:100%;
  background: rgba(0,0,0,0.5);
  z-index: 1050;
}

#confirmModal {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: #fff;
  border-radius: 8px;
  padding: 20px;
  z-index: 1060; /* higher than backdrop */
  pointer-events: auto;
}

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1060;
        }
        
        .toast {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            padding: 1rem;
            margin-bottom: 0.5rem;
            min-width: 300px;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
        }
        
        .toast.show {
            opacity: 1;
            transform: translateX(0);
        }
        
        .toast.success {
            border-left: 4px solid #28a745;
        }
        
        .toast.error {
            border-left: 4px solid #dc3545;
        }
        
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
                padding: 1rem;
            }
            
            .stats-cards {
                grid-template-columns: 1fr;
            }
            
            .table-header {
                flex-direction: column;
                align-items: stretch;
            }
            
            .table-controls {
                flex-direction: column;
            }
            
            .search-box {
                min-width: auto;
            }
        }
    </style> <!-- Sidebar -->
  <div class="sidebar">
   <div class="logo">
    <h4 id="dashboard_title"><i class="bi bi-mortarboard-fill me-2"></i>Admin Panel</h4>
   </div>
  <ul>
                <li><a href="admin_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li><a href="admin_records.php"><i class="bi bi-people"></i> Student Records</a></li>
                <li><a href="admin_appointments.php"><i class="bi bi-calendar-check"></i> Appointments</a></li>
                <li><a href="admin_reports.php"><i class="bi bi-graph-up"></i> Reports</a></li>
                <li><a href="admin_settings.php"><i class="bi bi-gear"></i> Settings</a></li>
                <li><a href="admin_announcements.php"><i class="bi bi-megaphone"></i> Announcements</a></li>
                <li><a href="admin_student_accounts.php"><i class="bi bi-person-badge"></i> Student Accounts</a></li>
                <li><a href="admin_parents.php"><i class="bi bi-person-lines-fill"></i> Parent Accounts</a></li>
            </ul>
  </div><!-- Main Content -->
  <div class="main-content">
   <div class="page-header">
    <h1><i class="bi bi-calendar-check me-2"></i>Parent Appointments</h1>
    <p>Manage and track all parent-teacher appointment requests</p>
   </div><!-- Statistics Cards -->
   <div class="stats-cards">
    <div class="stat-card total">
     <div class="stat-icon"><i class="bi bi-calendar-event"></i>
     </div>
     <div>
      <h3 id="totalAppointments"><!--?= $totalAppointments ?--></h3>
      <p>Total Appointments</p>
     </div>
    </div>
    <div class="stat-card pending">
     <div class="stat-icon"><i class="bi bi-clock"></i>
     </div>
     <div>
      <h3 id="pendingAppointments"><!--?= $pendingAppointments ?--></h3>
      <p>Pending Approval</p>
     </div>
    </div>
    <div class="stat-card confirmed">
     <div class="stat-icon"><i class="bi bi-check-circle"></i>
     </div>
     <div>
      <h3 id="confirmedAppointments"><!--?= $confirmedAppointments ?--></h3>
      <p>Confirmed</p>
     </div>
    </div>
    <div class="stat-card cancelled">
     <div class="stat-icon"><i class="bi bi-x-circle"></i>
     </div>
     <div>
      <h3 id="cancelledAppointments"><!--?= $cancelledAppointments ?--></h3>
      <p>Cancelled</p>
     </div>
    </div>
   </div><!-- Appointments Table -->
   <div class="appointments-table">
    <div class="table-header">
     <h5>Appointment Requests</h5>
     <div class="table-controls">
      <div class="search-box"><i class="bi bi-search"></i> <input type="text" class="form-control" placeholder="Search appointments..." id="searchInput">
      </div><select class="form-select filter-select" id="statusFilter"> <option value="">All Status</option> <option value="pending">Pending</option> <option value="confirmed">Confirmed</option> <option value="completed">Completed</option> <option value="cancelled">Cancelled</option> </select> <select class="form-select filter-select" id="dateFilter"> <option value="">All Dates</option> <option value="today">Today</option> <option value="week">This Week</option> <option value="month">This Month</option> </select>
     </div>
    </div>
    <div class="table-responsive">
     <table class="table" id="appointmentsTable">
      <thead>
       <tr>
        <th>Parent Name</th>
        <th>Email</th>
        <th>Student No.</th>
        <th>Date &amp; Time</th>
        <th>Interest</th>
        <th>Reason</th>
        <th>Status</th>
        <th>Created</th>
        <th>Actions</th>
       </tr>
      </thead>
      <tbody id="appointmentsTableBody"><!-- Dynamic content will be loaded here -->
      </tbody>
     </table>
    </div>
   </div>
  </div><!-- View Appointment Modal -->
  <div class="modal-backdrop" id="modalBackdrop"></div>
  <div class="modal-dialog" id="viewModal">
   <div class="modal-header">
    <h5>Appointment Details</h5><button class="close-btn" onclick="closeModal()">×</button>
   </div>
   <div class="modal-body" id="modalBody"><!-- Appointment details will be loaded here -->
   </div>
   <div class="modal-footer"><button class="btn btn-secondary" onclick="closeModal()">Close</button>
   </div>
  </div><!-- Toast Container -->
  <div class="toast-container" id="toastContainer"></div>

  <!-- Confirmation Modal -->
<div class="modal-backdrop" id="confirmBackdrop" style="display:none;"></div>
<div class="modal-dialog" id="confirmModal" style="display:none; max-width:400px;">
  <div class="modal-header">
    <h5 id="confirmTitle">Confirm Action</h5>
    <button class="close-btn" onclick="closeConfirmModal()">×</button>
  </div>
  <div class="modal-body" id="confirmBody">Are you sure?</div>
  <div class="modal-footer">
    <button class="btn btn-secondary" onclick="closeConfirmModal()">No</button>
    <button class="btn btn-primary" id="confirmYesBtn">Yes</button>
  </div>
</div>

 <script>
    // Configuration
    const defaultConfig = {
        dashboard_title: "Admin Panel",
        school_name: "School Management System",
        primary_color: "#007bff",
        success_color: "#28a745",
        warning_color: "#ffc107",
        danger_color: "#dc3545",
        background_color: "#f8f9fa"
    };

    // Global variables
    let appointments = [];
    let filteredAppointments = [];
    let isLoading = false;

    // Confirm modal callback
    let confirmCallback = null;

    function showConfirmModal(message, callback) {
        document.getElementById('confirmBody').textContent = message;
        document.getElementById('confirmBackdrop').style.display = 'block';
        document.getElementById('confirmModal').style.display = 'block';
        confirmCallback = callback;
    }

    function closeConfirmModal() {
        document.getElementById('confirmBackdrop').style.display = 'none';
        document.getElementById('confirmModal').style.display = 'none';
        confirmCallback = null;
    }

    document.getElementById('confirmYesBtn').addEventListener('click', () => {
        if(confirmCallback) confirmCallback();
        closeConfirmModal();
    });

    // Load appointments from PHP
    const phpAppointments = <?= $appointments_json ?>;

    // Initialize appointments from PHP data
    function initializeAppointments() {
        appointments = phpAppointments;
        filteredAppointments = [...appointments];
        renderAppointments();
        updateStatistics();
    }

    // Render appointments table
    function renderAppointments() {
        const tbody = document.getElementById('appointmentsTableBody');
        
        if (filteredAppointments.length === 0) {
            tbody.innerHTML = '<tr><td colspan="9" class="text-center">No appointments found</td></tr>';
            return;
        }

        tbody.innerHTML = filteredAppointments.map(appointment => `
            <tr>
                <td>${escapeHtml(appointment.name)}</td>
                <td>${escapeHtml(appointment.email)}</td>
                <td>${escapeHtml(appointment.student_id)}</td>
                <td>${appointment.date} ${appointment.time}</td>
                <td>${escapeHtml(appointment.interest)}</td>
                <td>${escapeHtml(appointment.reason.substring(0, 50))}${appointment.reason.length > 50 ? '...' : ''}</td>
                <td><span class="status-badge status-${appointment.status}">${appointment.status}</span></td>
                <td>${formatDate(appointment.created_at)}</td>
                <td>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-info" onclick="viewAppointment('${appointment.id}')" title="View Details">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-success" onclick="approveAppointment('${appointment.id}')" title="Approve" ${appointment.status === 'confirmed' ? 'disabled' : ''}>
                            <i class="bi bi-check-lg"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="declineAppointment('${appointment.id}')" title="Decline" ${appointment.status === 'cancelled' ? 'disabled' : ''}>
                            <i class="bi bi-x-lg"></i>
                        </button>
                        <button class="btn btn-sm btn-primary" onclick="editAppointment('${appointment.id}')" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    // Update statistics
    function updateStatistics() {
        const total = appointments.length;
        const pending = appointments.filter(a => a.status === 'pending').length;
        const confirmed = appointments.filter(a => a.status === 'confirmed').length;
        const cancelled = appointments.filter(a => a.status === 'cancelled').length;

        document.getElementById('totalAppointments').textContent = total;
        document.getElementById('pendingAppointments').textContent = pending;
        document.getElementById('confirmedAppointments').textContent = confirmed;
        document.getElementById('cancelledAppointments').textContent = cancelled;
    }

    // View appointment details
    function viewAppointment(id) {
        const appointment = appointments.find(a => a.id === id);
        if (!appointment) return;

        const modalBody = document.getElementById('modalBody');
        modalBody.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Parent Information</h6>
                    <p><strong>Name:</strong> ${escapeHtml(appointment.name)}</p>
                    <p><strong>Email:</strong> ${escapeHtml(appointment.email)}</p>
                    <p><strong>Student ID:</strong> ${escapeHtml(appointment.student_id)}</p>
                </div>
                <div class="col-md-6">
                    <h6>Appointment Details</h6>
                    <p><strong>Date:</strong> ${appointment.date}</p>
                    <p><strong>Time:</strong> ${appointment.time}</p>
                    <p><strong>Status:</strong> <span class="status-badge status-${appointment.status}">${appointment.status}</span></p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <h6>Interest & Reason</h6>
                    <p><strong>Interest:</strong> ${escapeHtml(appointment.interest)}</p>
                    <p><strong>Reason:</strong> ${escapeHtml(appointment.reason)}</p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <p><strong>Created:</strong> ${formatDate(appointment.created_at)}</p>
                </div>
            </div>
        `;

        showModal();
    }

    // Approve appointment
    function approveAppointment(id) {
        showConfirmModal("Are you sure you want to approve this appointment?", async () => {
            if (isLoading) return;
            
            const appointment = appointments.find(a => a.id === id);
            if (!appointment || appointment.status === 'confirmed') return;

            isLoading = true;
            showLoadingToast('Approving appointment...');

            try {
                const formData = new FormData();
                formData.append('action', 'approve');
                formData.append('appointment_id', id);

                const response = await fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    const index = appointments.findIndex(a => a.id === id);
                    if (index !== -1) {
                        appointments[index].status = 'confirmed';
                        filteredAppointments = [...appointments];
                        renderAppointments();
                        updateStatistics();
                    }
                    showSuccessToast(result.message);
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                showErrorToast('Failed to approve appointment. Please try again.');
                console.error('Error:', error);
            } finally {
                isLoading = false;
            }
        });
    }

    // Decline appointment
    function declineAppointment(id) {
        showConfirmModal("Are you sure you want to decline this appointment?", async () => {
            if (isLoading) return;
            
            const appointment = appointments.find(a => a.id === id);
            if (!appointment || appointment.status === 'cancelled') return;

            isLoading = true;
            showLoadingToast('Declining appointment...');

            try {
                const formData = new FormData();
                formData.append('action', 'decline');
                formData.append('appointment_id', id);

                const response = await fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    const index = appointments.findIndex(a => a.id === id);
                    if (index !== -1) {
                        appointments[index].status = 'cancelled';
                        filteredAppointments = [...appointments];
                        renderAppointments();
                        updateStatistics();
                    }
                    showSuccessToast(result.message);
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                showErrorToast('Failed to decline appointment. Please try again.');
                console.error('Error:', error);
            } finally {
                isLoading = false;
            }
        });
    }

        // Edit appointment (placeholder)
    function editAppointment(id) {
        showInfoToast('Edit functionality would open an edit form here.');
    }

    // Modal functions
    function showModal() {
        document.getElementById('modalBackdrop').style.display = 'block';
        document.getElementById('viewModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('modalBackdrop').style.display = 'none';
        document.getElementById('viewModal').style.display = 'none';
    }

    // Toast functions
    function showToast(message, type = 'info') {
        const toastContainer = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="bi bi-${getToastIcon(type)} me-2"></i>
                <span>${message}</span>
            </div>
        `;

        toastContainer.appendChild(toast);
        
        setTimeout(() => toast.classList.add('show'), 100);
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                if (toastContainer.contains(toast)) {
                    toastContainer.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }

    function showSuccessToast(message) {
        showToast(message, 'success');
    }

    function showErrorToast(message) {
        showToast(message, 'error');
    }

    function showInfoToast(message) {
        showToast(message, 'info');
    }

    function showLoadingToast(message) {
        showToast(`<span class="loading-spinner me-2"></span>${message}`, 'info');
    }

    function getToastIcon(type) {
        switch (type) {
            case 'success': return 'check-circle';
            case 'error': return 'exclamation-circle';
            case 'warning': return 'exclamation-triangle';
            default: return 'info-circle';
        }
    }

    // Search and filter functions
    function setupFilters() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const dateFilter = document.getElementById('dateFilter');

        searchInput.addEventListener('input', filterAppointments);
        statusFilter.addEventListener('change', filterAppointments);
        dateFilter.addEventListener('change', filterAppointments);
    }

    function filterAppointments() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const dateFilter = document.getElementById('dateFilter').value;

        filteredAppointments = appointments.filter(appointment => {
            const matchesSearch = !searchTerm || 
                appointment.name.toLowerCase().includes(searchTerm) ||
                appointment.email.toLowerCase().includes(searchTerm) ||
                appointment.student_id.toLowerCase().includes(searchTerm) ||
                appointment.interest.toLowerCase().includes(searchTerm);

            const matchesStatus = !statusFilter || appointment.status === statusFilter;

            const matchesDate = !dateFilter || checkDateFilter(appointment.date, dateFilter);

            return matchesSearch && matchesStatus && matchesDate;
        });

        renderAppointments();
    }

    function checkDateFilter(appointmentDate, filter) {
        const today = new Date();
        const appDate = new Date(appointmentDate);

        switch (filter) {
            case 'today':
                return appDate.toDateString() === today.toDateString();
            case 'week':
                const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                return appDate >= weekAgo && appDate <= today;
            case 'month':
                const monthAgo = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
                return appDate >= monthAgo && appDate <= today;
            default:
                return true;
        }
    }

    // Utility functions
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Element SDK Configuration
    async function onConfigChange(config) {
        document.getElementById('dashboard_title').innerHTML = `<i class="bi bi-mortarboard-fill me-2"></i>${config.dashboard_title || defaultConfig.dashboard_title}`;
        
        const primaryColor = config.primary_color || defaultConfig.primary_color;
        const successColor = config.success_color || defaultConfig.success_color;
        const backgroundColor = config.background_color || defaultConfig.background_color;
        
        document.body.style.backgroundColor = backgroundColor;
    }

    // Initialize Element SDK
    if (window.elementSdk) {
        window.elementSdk.init({
            defaultConfig,
            onConfigChange,
            mapToCapabilities: (config) => ({
                recolorables: [
                    {
                        get: () => config.primary_color || defaultConfig.primary_color,
                        set: (value) => {
                            config.primary_color = value;
                            window.elementSdk.setConfig({ primary_color: value });
                        }
                    },
                    {
                        get: () => config.success_color || defaultConfig.success_color,
                        set: (value) => {
                            config.success_color = value;
                            window.elementSdk.setConfig({ success_color: value });
                        }
                    },
                    {
                        get: () => config.background_color || defaultConfig.background_color,
                        set: (value) => {
                            config.background_color = value;
                            window.elementSdk.setConfig({ background_color: value });
                        }
                    }
                ],
                borderables: [],
                fontEditable: undefined,
                fontSizeable: undefined
            }),
            mapToEditPanelValues: (config) => new Map([
                ["dashboard_title", config.dashboard_title || defaultConfig.dashboard_title],
                ["school_name", config.school_name || defaultConfig.school_name]
            ])
        });
    }

    // Initialize application
    document.addEventListener('DOMContentLoaded', function() {
        initializeAppointments();
        setupFilters();
        
        // Close modal when clicking backdrop
        document.getElementById('modalBackdrop').addEventListener('click', closeModal);
    });
</script>




