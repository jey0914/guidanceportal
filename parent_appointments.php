<?php
session_start();
include("db.php");

// Redirect if not logged in
if (!isset($_SESSION['parent_email'])) {
    header("Location: parent_login.php");
    exit();
}

$parent_email = $_SESSION['parent_email'];
$successMsg = "";
$errorMsg = "";

// Get parent info
$stmt = $con->prepare("SELECT fullname, student_id FROM parents WHERE email = ? LIMIT 1");
if (!$stmt) {
    die("Prepare failed: (" . $con->errno . ") " . $con->error);
}
$stmt->bind_param("s", $parent_email);
$stmt->execute();
$result = $stmt->get_result();
$parentData = $result->fetch_assoc();

$student_id = $parentData['student_id'] ?? ""; // always define it
$fullname = $parentData['fullname'] ?? "";

// Fetch appointment counts for this parent
$pendingCount = $confirmedCount = $completedCount = 0;

$sqlCounts = "SELECT status, COUNT(*) as count 
              FROM parent_appointments 
              WHERE email = ? 
              GROUP BY status";
$stmtCounts = $con->prepare($sqlCounts);
$stmtCounts->bind_param("s", $parent_email);
$stmtCounts->execute();
$resultCounts = $stmtCounts->get_result();

while ($row = $resultCounts->fetch_assoc()) {
    switch ($row['status']) {
        case 'Pending':
            $pendingCount = $row['count'];
            break;
        case 'confirmed':
            $confirmedCount = $row['count'];
            break;
        case 'Completed':
            $completedCount = $row['count'];
            break;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? $fullname; // fallback to parent name if empty
    $student_id = $_POST['student_no'] ?? $student_id; // fallback to parent table value
    $date = $_POST['date'];
    $time = $_POST['time'];
    $interest = $_POST['interest'];
    $reason = $_POST['reason'];
    $status = 'Pending'; // set default status

    // Validate appointment time
    if ($time < "08:00" || $time > "17:00") {
        $errorMsg = "Appointments are allowed from 8:00 AM to 5:00 PM only.";
    } elseif ($time >= "12:00" && $time < "13:00") {
        $errorMsg = "Appointments are not allowed during lunch break (12:00 PM – 1:00 PM).";
    } else {
        $stmt = $con->prepare(
            "INSERT INTO parent_appointments (name, email, student_id, date, time, interest, reason, status) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        if (!$stmt) {
            die("Prepare failed: (" . $con->errno . ") " . $con->error);
        }

        $stmt->bind_param("ssssssss", $name, $parent_email, $student_id, $date, $time, $interest, $reason, $status);

        if ($stmt->execute()) {
            header("Location: parent_appointments.php?success=1");
            exit();
        } else {
            $errorMsg = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments - Parent Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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

        /* Enhanced Form Styles */
        .appointment-form-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .form-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .form-header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .form-body {
            padding: 2.5rem;
        }

        /* Enhanced Form Controls */
        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-control, .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f9fafb;
        }

        .form-control:focus, .form-select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            background: white;
        }

        .form-control[readonly] {
            background: #f1f5f9;
            color: #64748b;
            border-color: #d1d5db;
        }

        /* Success and Error Messages */
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

        .error-message {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            border-left: 4px solid #ef4444;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
        }

        .error-message::before {
            content: '⚠';
            background: #ef4444;
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

        /* Action Buttons */
        .action-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .btn {
            border-radius: 12px;
            padding: 0.875rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4338ca, #6d28d9);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
        }

        .btn-outline-primary {
            background: white;
            color: #4f46e5;
            border: 2px solid #4f46e5;
        }

        .btn-outline-primary:hover {
            background: #4f46e5;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }

        /* Quick Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05);
            border-left: 4px solid var(--accent-color);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .stat-card.pending { --accent-color: #f59e0b; }
        .stat-card.confirmed { --accent-color: #10b981; }
        .stat-card.completed { --accent-color: #6366f1; }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stat-card.pending .stat-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .stat-card.confirmed .stat-icon { background: linear-gradient(135deg, #10b981, #059669); }
        .stat-card.completed .stat-icon { background: linear-gradient(135deg, #6366f1, #4f46e5); }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.9rem;
            font-weight: 500;
            margin: 0.5rem 0 0;
        }

        /* Time Slots */
        .time-slots {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .time-slot {
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            font-weight: 500;
        }

        .time-slot:hover {
            border-color: #4f46e5;
            background: #f8faff;
        }

        .time-slot.selected {
            background: #4f46e5;
            color: white;
            border-color: #4f46e5;
        }

        .time-slot.unavailable {
            background: #f3f4f6;
            color: #9ca3af;
            cursor: not-allowed;
            opacity: 0.6;
        }

        /* Form Sections */
        .form-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 12px;
            border-left: 4px solid #4f46e5;
        }

        .form-section h6 {
            color: #4f46e5;
            font-weight: 700;
            margin: 0 0 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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

            .form-body {
                padding: 1.5rem;
            }

            .action-buttons {
                flex-direction: column;
                align-items: stretch;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .time-slots {
                grid-template-columns: repeat(2, 1fr);
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

        /* Loading States */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #4f46e5;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
                <i class="fas fa-calendar-check"></i>
                Appointments
            </h1>
            <p class="page-subtitle">Schedule and manage your consultation appointments</p>
        </div>

        <!-- Content -->
        <div class="content fade-in">
            <!-- Quick Stats -->
            <div class="stats-grid">
                <div class="stat-card pending">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value"><?= $pendingCount ?></div>
                            <p class="stat-label">Pending Appointments</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card confirmed">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value"><?= $confirmedCount ?></div>
                            <p class="stat-label">Confirmed Appointments</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card completed">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value"><?= $completedCount ?></div>
                            <p class="stat-label">Completed Sessions</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <div>
                    <h4 class="mb-0">📅 Schedule New Appointment</h4>
                    <small class="text-muted">Book a consultation with your child's guidance counselor</small>
                </div>
                <a href="view_parent_appointments.php" class="btn btn-outline-primary">
                    <i class="fas fa-list"></i> View All Appointments
                </a>
            </div>

            <!-- Success/Error Messages -->
            <?php if (!empty($successMsg) || (isset($_GET['success']) && $_GET['success'] == 1)): ?>
                <div class="success-message">Appointment successfully submitted!</div>
            <?php endif; ?>

            <?php if (!empty($errorMsg)): ?>
                <div class="error-message"><?= $errorMsg ?></div>
            <?php endif; ?>

            <!-- Appointment Form -->
            <div class="appointment-form-container">
                <div class="form-header">
                    <h2><i class="fas fa-calendar-plus"></i> New Appointment Request</h2>
                </div>
                
                <div class="form-body">
                    <form method="POST" class="row g-4" id="appointmentForm">
                        <!-- Parent Information Section -->
                        <div class="col-12">
                            <div class="form-section">
                                <h6><i class="fas fa-user"></i> Parent Information</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="fas fa-user-circle"></i> Parent Name
                                        </label>
                                        <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($fullname) ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="fas fa-id-card"></i> Student Number
                                        </label>
                                       <input type="text" class="form-control" name="student_no" value="<?= htmlspecialchars($student_id) ?>" readonly>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Appointment Details Section -->
                        <div class="col-12">
                            <div class="form-section">
                                <h6><i class="fas fa-calendar-alt"></i> Appointment Details</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="fas fa-calendar"></i> Preferred Date
                                        </label>
                                        <input type="date" class="form-control" name="date" required id="appointmentDate">
                                        <small class="text-muted">Select a date at least 2 days from today</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="fas fa-clock"></i> Preferred Time
                                        </label>
                                        <input type="time" class="form-control" name="time" required min="08:00" max="17:00" id="appointmentTime">
                                        <small class="text-muted">Available hours: 8:00 AM - 5:00 PM</small>
                                    </div>
                                </div>

                                <!-- Quick Time Slots -->
                                <div class="mt-3">
                                    <label class="form-label">
                                        <i class="fas fa-clock"></i> Quick Time Selection
                                    </label>
                                    <div class="time-slots">
                                        <div class="time-slot" data-time="09:00">9:00 AM</div>
                                        <div class="time-slot" data-time="10:00">10:00 AM</div>
                                        <div class="time-slot" data-time="11:00">11:00 AM</div>
                                        <div class="time-slot" data-time="13:00">1:00 PM</div>
                                        <div class="time-slot" data-time="14:00">2:00 PM</div>
                                        <div class="time-slot" data-time="15:00">3:00 PM</div>
                                        <div class="time-slot" data-time="16:00">4:00 PM</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Consultation Details Section -->
                        <div class="col-12">
                            <div class="form-section">
                                <h6><i class="fas fa-comments"></i> Consultation Details</h6>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label">
                                            <i class="fas fa-tags"></i> Consultation Interest
                                        </label>
                                        <select class="form-select" name="interest" required>
                                            <option value="">-- Select Consultation Type --</option>
                                            <option value="Academic">📚 Academic Performance</option>
                                            <option value="Behavioral">👥 Behavioral Concerns</option>
                                            <option value="Personal">🧠 Personal Development</option>
                                            <option value="Concern about child">⚠️ General Concerns about Child</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">
                                            <i class="fas fa-edit"></i> Detailed Reason/Description
                                        </label>
                                        <textarea class="form-control" name="reason" rows="5" required 
                                                placeholder="Please provide detailed information about your concerns or what you'd like to discuss during the consultation..."></textarea>
                                        <small class="text-muted">Be as specific as possible to help the counselor prepare for your session</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-success w-100" id="submitBtn">
                                <i class="fas fa-paper-plane"></i> Submit Appointment Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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

        // Initialize date picker with restrictions
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('appointmentDate');
            const today = new Date();
            const minDate = new Date(today.getTime() + (2 * 24 * 60 * 60 * 1000)); // 2 days from today
            
            // Set minimum date
            dateInput.min = minDate.toISOString().split('T')[0];
            
            // Initialize Flatpickr for better date selection
            flatpickr(dateInput, {
                minDate: minDate,
                disable: [
                    function(date) {
                        // Disable weekends (Saturday = 6, Sunday = 0)
                        return (date.getDay() === 0 || date.getDay() === 6);
                    }
                ],
                dateFormat: "Y-m-d",
                theme: "material_blue"
            });
        });

        // Time slot selection
        document.querySelectorAll('.time-slot').forEach(slot => {
            slot.addEventListener('click', function() {
                if (!this.classList.contains('unavailable')) {
                    // Remove active class from all slots
                    document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
                    
                    // Add active class to clicked slot
                    this.classList.add('selected');
                    
                    // Update time input
                    document.getElementById('appointmentTime').value = this.dataset.time;
                }
            });
        });

        // Form validation and submission
        document.getElementById('appointmentForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<span class="spinner"></span> Submitting...';
            submitBtn.disabled = true;
            
            // Simulate processing time (remove this in production)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        });

        // Auto-hide success/error messages
        setTimeout(() => {
            const messages = document.querySelectorAll('.success-message, .error-message');
            messages.forEach(message => {
                message.style.transition = 'opacity 0.5s ease';
                message.style.opacity = '0';
                setTimeout(() => message.remove(), 500);
            });
        }, 5000);

        // Form field validation feedback
        document.querySelectorAll('input[required], select[required], textarea[required]').forEach(field => {
            field.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.style.borderColor = '#ef4444';
                } else {
                    this.style.borderColor = '#10b981';
                }
            });
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98c4472eb5530dcb',t:'MTc2MDA4MDAxOC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
