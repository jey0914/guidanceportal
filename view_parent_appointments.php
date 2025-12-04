<?php
session_start();
include("db.php");

if (!isset($_SESSION['parent_email'])) {
    header("Location: parent_login.php");
    exit();
}

$parent_email = $_SESSION['parent_email'];

// Fetch appointments for this parent
$stmt = $con->prepare("SELECT * FROM parent_appointments WHERE email = ? ORDER BY date ASC, time ASC");
$stmt->bind_param("s", $parent_email);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Use status from DB
        $status = $row['status']; 

        // Set badge class
        $statusClass = 'status-pending';
        if ($status === 'confirmed') {
            $statusClass = 'status-confirmed';
        } elseif ($status === 'cancelled') {
            $statusClass = 'status-cancelled';
        }

        // Automatically mark past appointments as completed (optional)
        $appointmentDate = new DateTime($row['date']);
        $today = new DateTime();
        if ($appointmentDate < $today && $status !== 'cancelled') {
            $status = 'completed';
            $statusClass = 'status-completed';
        }

        $appointments[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'email' => $row['email'],
            'student_id' => $row['student_id'],
            'date' => $row['date'],
            'time' => $row['time'],
            'interest' => $row['interest'],
            'reason' => $row['reason'],
            'status' => $status,
            'status_class' => $statusClass
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Appointments - Parent Portal</title>
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

        /* Enhanced Table Styles */
        .table-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-top: 2rem;
        }

        .table-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .table-responsive {
            border-radius: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            background: white;
        }

        thead {
            background: #f8fafc;
        }

        th {
            padding: 1.25rem 1rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        tbody tr {
            transition: all 0.3s ease;
        }

        tbody tr:hover {
            background: #f8faff;
            transform: scale(1.01);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        /* Status Badges */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
        }

        .status-confirmed {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
        }

        .status-completed {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .action-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-edit {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }

        .btn-edit:hover {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .btn-delete:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
            color: white;
        }

        /* Success Message */
        .success-message {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            border-left: 4px solid #10b981;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
        }

        .success-message::before {
            content: '✓';
            background: #10b981;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #64748b;
        }

        .empty-state i {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }

        .empty-state h4 {
            color: #374151;
            margin-bottom: 1rem;
        }

        .empty-state p {
            margin-bottom: 2rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border: none;
            border-radius: 12px;
            padding: 0.875rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4338ca, #6d28d9);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
        }

        /* Modal Enhancements */
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            border-radius: 20px 20px 0 0;
            border-bottom: 1px solid #e5e7eb;
            padding: 1.5rem 2rem;
        }

        .modal-body {
            padding: 2rem;
            font-size: 1.1rem;
            color: #374151;
        }

        .modal-footer {
            border-top: 1px solid #e5e7eb;
            padding: 1.5rem 2rem;
            border-radius: 0 0 20px 20px;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .btn-secondary {
            background: #6b7280;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }

        .btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-2px);
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

            .table-header {
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .table-responsive {
                font-size: 0.85rem;
            }

            th, td {
                padding: 0.75rem 0.5rem;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.25rem;
            }

            .action-btn {
                font-size: 0.8rem;
                padding: 0.4rem 0.8rem;
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

        /* Appointment Details */
        .appointment-details {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .appointment-details:hover {
            white-space: normal;
            overflow: visible;
        }

        /* Date and Time Styling */
        .date-time {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .date {
            font-weight: 600;
            color: #374151;
        }

        .time {
            font-size: 0.85rem;
            color: #6b7280;
            background: #f3f4f6;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            display: inline-block;
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
                <li><a href="parent_appointments.php" class="active"><i class="fas fa-calendar-check"></i> Appointments</a></li>
                <li><a href="parent_reports.php"><i class="fas fa-file-alt"></i> Reports</a></li>
                <li><a href="parent_settings.php"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="content-header">
            <h1 class="page-title">
                <i class="fas fa-list-alt"></i>
                Your Appointments
            </h1>
            <p class="page-subtitle">View and manage all your submitted appointment requests</p>
        </div>

        <!-- Content -->
        <div class="content fade-in">
            <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
                <div class="success-message">Appointment successfully deleted!</div>
            <?php endif; ?>

            <!-- Appointments Table -->
            <div class="table-container">
                <div class="table-header">
                    <h3><i class="fas fa-calendar-alt"></i> Submitted Appointments</h3>
                    <a href="parent_appointments.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Appointment
                    </a>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th><i class="fas fa-user"></i> Parent Name</th>
                                <th><i class="fas fa-id-card"></i> Student No.</th>
                                <th><i class="fas fa-calendar"></i> Date & Time</th>
                                <th><i class="fas fa-tags"></i> Type</th>
                                <th><i class="fas fa-comment"></i> Reason</th>
                                <th><i class="fas fa-info-circle"></i> Status</th>
                                <th><i class="fas fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
<?php if (!empty($appointments)): ?>
    <?php foreach ($appointments as $appointment): ?>
        <tr>
            <td><strong><?= htmlspecialchars($appointment['name']) ?></strong></td>
            <td><span class="badge bg-secondary"><?= htmlspecialchars($appointment['student_id']) ?></span></td>
            <td>
                <div class="date-time">
                    <span class="date"><?= date('M j, Y', strtotime($appointment['date'])) ?></span>
                    <span class="time"><?= date('g:i A', strtotime($appointment['time'])) ?></span>
                </div>
            </td>
            <td><span class="badge bg-info"><?= htmlspecialchars($appointment['interest']) ?></span></td>
            <td>
                <div class="appointment-details" title="<?= htmlspecialchars($appointment['reason']) ?>">
                    <?= htmlspecialchars(strlen($appointment['reason']) > 50 ? substr($appointment['reason'], 0, 50) . '...' : $appointment['reason']) ?>
                </div>
            </td>
            <td>
                <span class="status-badge <?= $appointment['status_class'] ?>"><?= ucfirst($appointment['status']) ?></span>
            </td>
            <td>
                <div class="action-buttons">
                    <a href="parent_edit_appointment.php?id=<?= $appointment['id'] ?>" class="action-btn btn-edit" title="Edit Appointment">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="#" class="action-btn btn-delete" onclick="showDeleteModal(<?= $appointment['id'] ?>)" title="Delete Appointment">
                        <i class="fas fa-trash"></i> Delete
                    </a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="7">
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h4>No Appointments Found</h4>
                <p>You haven't submitted any appointment requests yet.</p>
                <a href="parent_appointments.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Schedule Your First Appointment
                </a>
            </div>
        </td>
    </tr>
<?php endif; ?>
</tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle"></i> Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fas fa-trash-alt text-danger" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                        <p><strong>Are you sure you want to delete this appointment?</strong></p>
                        <p class="text-muted">This action cannot be undone. The appointment will be permanently removed from your records.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Yes, Delete
                    </a>
                </div>
            </div>
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

        // Delete modal function
        function showDeleteModal(appointmentId) {
            document.getElementById('confirmDeleteBtn').href = 'parent_delete_appointment.php?id=' + appointmentId;
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        // Auto-hide success messages
        setTimeout(() => {
            const messages = document.querySelectorAll('.success-message');
            messages.forEach(message => {
                message.style.transition = 'opacity 0.5s ease';
                message.style.opacity = '0';
                setTimeout(() => message.remove(), 500);
            });
        }, 5000);

        // Enhanced table interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to table rows
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.01)';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });

            // Tooltip for truncated text
            const appointmentDetails = document.querySelectorAll('.appointment-details');
            appointmentDetails.forEach(detail => {
                detail.addEventListener('click', function() {
                    const fullText = this.getAttribute('title');
                    alert('Full Reason:\n\n' + fullText);
                });
            });
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98c45cf186a50dcb',t:'MTc2MDA4MDkwOS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
