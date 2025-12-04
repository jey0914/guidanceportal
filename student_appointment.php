<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

include("db.php");

// Get student information
$student_email = $_SESSION['email'];

// Debug prepare
$student_query = $con->prepare("SELECT * FROM form WHERE email = ?");
if (!$student_query) {
    die("Prepare failed for student query: " . $con->error);
}

$student_query->bind_param("s", $student_email);
$student_query->execute();
$student_result = $student_query->get_result();
$student = $student_result->fetch_assoc();

if (!$student) {
    header("Location: login.php");
    exit();
}

// Get appointments for this student
$appointments_query = $con->prepare("SELECT * FROM appointments WHERE email = ? ORDER BY date DESC, time DESC");

if (!$appointments_query) {
    die("Prepare failed for appointments query: " . $con->error);
}

$appointments_query->bind_param("s", $student_email);
$appointments_query->execute();
$appointments_result = $appointments_query->get_result();

$appointments = [];
while ($row = $appointments_result->fetch_assoc()) {
    $appointments[] = $row;
}

// Calculate statistics
$total_appointments = count($appointments);
$pending_count = 0;
$confirmed_count = 0;
$completed_count = 0;
$cancelled_count = 0;

foreach ($appointments as $apt) {
    $status = trim(strtolower($apt['status'])); // lowercase at remove spaces

    if ($status === 'pending') $pending_count++;
    elseif ($status === 'confirmed' || $status === 'approved') $confirmed_count++;
    elseif ($status === 'completed') $completed_count++;
    elseif ($status === 'cancelled') $cancelled_count++;
}


// Get upcoming and past appointments
$upcoming_appointments = [];
$past_appointments = [];
$today = date('Y-m-d');

foreach ($appointments as $apt) {
    if ($apt['date'] >= $today) {
        $upcoming_appointments[] = $apt;
    } else {
        $past_appointments[] = $apt;
    }
}

// Helper functions (status badge, card class, category formatting, icons)
function getStatusBadgeClass($status) {
    switch (strtolower($status)) {
        case 'pending': return 'status-pending';
        case 'confirmed': return 'status-confirmed';
        case 'completed': return 'status-completed';
        case 'cancelled': return 'status-cancelled';
        default: return 'status-pending';
    }
}

function getAppointmentCardClass($status) {
    switch (strtolower($status)) {
        case 'pending': return 'pending';
        case 'confirmed': return 'confirmed';
        case 'completed': return 'completed';
        case 'cancelled': return 'cancelled';
        default: return 'pending';
    }
}

function formatCategory($category) {
    switch ($category) {
        case 'consultation': return 'General Consultation';
        case 'special_exam': return 'Special Examination';
        case 'exit_interview': return 'Exit Interview';
        default: return ucfirst(str_replace('_', ' ', $category));
    }
}

function getCategoryIcon($category) {
    switch ($category) {
        case 'consultation': return 'fas fa-user-tie';
        case 'special_exam': return 'fas fa-clipboard-check';
        case 'exit_interview': return 'fas fa-door-open';
        default: return 'fas fa-calendar';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments - Guidance Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        .sidebar-link {
            transition: all 0.3s ease;
            color: #94a3b8;
        }
        
        .sidebar-link:hover {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            transform: translateX(4px);
        }
        
        .sidebar-link.active {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }
        
        .appointment-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        
        .appointment-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .appointment-card.pending {
            border-left-color: #f59e0b;
        }
        
        .appointment-card.confirmed {
            border-left-color: #10b981;
        }
        
        .appointment-card.completed {
            border-left-color: #6366f1;
        }
        
        .appointment-card.cancelled {
            border-left-color: #ef4444;
        }
        
        .status-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-confirmed {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-completed {
            background-color: #e0e7ff;
            color: #3730a3;
        }
        
        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .floating-action {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 50;
            transition: all 0.3s ease;
        }
        
        .floating-action:hover {
            transform: scale(1.1);
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.5rem;
        }
        
        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .calendar-day:hover {
            background-color: #e0e7ff;
        }
        
        .calendar-day.has-appointment {
            background-color: #3b82f6;
            color: white;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-slate-800 text-white flex flex-col animate-slide-in">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Guidance Office</h2>
                        <p class="text-sm text-slate-300">Student Portal</p>
                    </div>
                </div>
                
                <nav class="space-y-2">
                    <a href="dashboard.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl">
                        <i class="fas fa-home w-5"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    
                    <?php if (stripos($student['strand_course'], 'SHS') !== false || stripos($student['year_level'], 'Grade') !== false): ?>
                    <a href="student_records.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl">
                        <i class="fas fa-clipboard-check w-5"></i>
                        <span class="font-medium">Attendance</span>
                    </a>
                    <?php endif; ?>
                    
                    <a href="appointments.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl">
                        <i class="fas fa-calendar-check w-5"></i>
                        <span class="font-medium">Appointments</span>
                    </a>
                    
                    <a href="student_reports.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl">
                        <i class="fas fa-file-alt w-5"></i>
                        <span class="font-medium">Reports</span>
                    </a>
                    
                    <a href="settings.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl">
                        <i class="fas fa-cog w-5"></i>
                        <span class="font-medium">Settings</span>
                    </a>
                </nav>
            </div>
            
            <!-- User Profile at Bottom -->
            <div class="mt-auto p-6 border-t border-slate-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="font-medium text-sm"><?= htmlspecialchars($student['fname'] . ' ' . $student['lname']) ?></p>
                        <p class="text-xs text-slate-400">ID: <?= htmlspecialchars($student['student_no']) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Header -->
            <div class="gradient-bg text-white p-8 animate-fade-in">
                <div class="max-w-6xl mx-auto">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">My Appointments</h1>
                            <p class="text-blue-100">Manage your guidance office appointments</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-blue-100">Today</p>
                            <p class="text-xl font-semibold"><?= date('M j, Y') ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="max-w-6xl mx-auto px-8 -mt-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 animate-fade-in">
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Total</p>
                                <p class="text-2xl font-bold text-gray-800"><?= $total_appointments ?></p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-calendar text-blue-600"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Pending</p>
                                <p class="text-2xl font-bold text-amber-600"><?= $pending_count ?></p>
                            </div>
                            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-clock text-amber-600"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Confirmed</p>
                                <p class="text-2xl font-bold text-green-600"><?= $confirmed_count ?></p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Completed</p>
                                <p class="text-2xl font-bold text-purple-600"><?= $completed_count ?></p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-trophy text-purple-600"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="max-w-6xl mx-auto px-8 pb-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Appointments List -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-lg p-6 animate-fade-in">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-xl font-bold text-gray-800">
                                    <?= count($upcoming_appointments) > 0 ? 'Upcoming Appointments' : 'Recent Appointments' ?>
                                </h2>
                                <div class="flex space-x-2">
                                    <button class="filter-btn px-4 py-2 bg-blue-100 text-blue-600 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors" data-filter="all">
                                        All
                                    </button>
                                    <button class="filter-btn px-4 py-2 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" data-filter="pending">
                                        Pending
                                    </button>
                                    <button class="filter-btn px-4 py-2 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" data-filter="confirmed">
                                        Confirmed
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-4" id="appointmentsList">
                                <?php 
                                $display_appointments = count($upcoming_appointments) > 0 ? $upcoming_appointments : array_slice($appointments, 0, 5);
                                
                                if (count($display_appointments) > 0): 
                                    foreach ($display_appointments as $appointment): 
                                ?>
                                    <div class="appointment-card <?= getAppointmentCardClass($appointment['status']) ?> bg-white border border-gray-200 rounded-xl p-6 shadow-sm" data-status="<?= strtolower($appointment['status']) ?>">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-12 h-12 bg-<?= strtolower($appointment['status']) == 'confirmed' ? 'green' : (strtolower($appointment['status']) == 'pending' ? 'amber' : (strtolower($appointment['status']) == 'completed' ? 'purple' : 'red')) ?>-100 rounded-xl flex items-center justify-center">
                                                    <i class="<?= getCategoryIcon($appointment['category']) ?> text-<?= strtolower($appointment['status']) == 'confirmed' ? 'green' : (strtolower($appointment['status']) == 'pending' ? 'amber' : (strtolower($appointment['status']) == 'completed' ? 'purple' : 'red')) ?>-600"></i>
                                                </div>
                                                <div>
                                                    <h3 class="font-semibold text-gray-800"><?= formatCategory($appointment['category']) ?></h3>
                                                    <p class="text-sm text-gray-600">Guidance Office</p>
                                                </div>
                                            </div>
                                            <span class="status-badge <?= getStatusBadgeClass($appointment['status']) ?>"><?= ucfirst($appointment['status']) ?></span>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                                <i class="fas fa-calendar-alt text-gray-400"></i>
                                                <span><?= date('M j, Y', strtotime($appointment['date'])) ?></span>
                                            </div>
                                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                                <i class="fas fa-clock text-gray-400"></i>
                                                <span><?= date('g:i A', strtotime($appointment['time'])) ?></span>
                                            </div>
                                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                                                <span>Guidance Office</span>
                                            </div>
                                            <?php if (!empty($appointment['grade'])): ?>
                                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                                <i class="fas fa-graduation-cap text-gray-400"></i>
                                                <span><?= htmlspecialchars($appointment['grade']) ?></span>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if (!empty($appointment['reason'])): ?>
                                        <p class="text-sm text-gray-600 mb-4"><?= htmlspecialchars(substr($appointment['reason'], 0, 100)) ?><?= strlen($appointment['reason']) > 100 ? '...' : '' ?></p>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($appointment['admin_message'])): ?>
                                        <div class="bg-blue-50 border-l-4 border-blue-400 p-3 mb-4">
                                            <p class="text-sm text-blue-700"><strong>Admin Message:</strong> <?= htmlspecialchars($appointment['admin_message']) ?></p>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <div class="flex space-x-3">
                                            <?php if (strtolower($appointment['status']) == 'confirmed'): ?>
                                                <button class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                                    <i class="fas fa-video mr-2"></i>Ready for Meeting
                                                </button>
                                            <?php elseif (strtolower($appointment['status']) == 'pending'): ?>
                                                <button class="flex-1 bg-gray-100 text-gray-600 py-2 px-4 rounded-lg text-sm font-medium cursor-not-allowed">
                                                    <i class="fas fa-clock mr-2"></i>Awaiting Confirmation
                                                </button>
                                            <?php elseif (strtolower($appointment['status']) == 'completed'): ?>
                                                <button class="flex-1 bg-green-100 text-green-600 py-2 px-4 rounded-lg text-sm font-medium">
                                                    <i class="fas fa-check mr-2"></i>Session Completed
                                                </button>
                                            <?php else: ?>
                                                <button class="flex-1 bg-red-100 text-red-600 py-2 px-4 rounded-lg text-sm font-medium">
                                                    <i class="fas fa-times mr-2"></i>Cancelled
                                                </button>
                                            <?php endif; ?>
                                            
                                            <?php if (strtolower($appointment['status']) == 'pending'): ?>
                                            <button class="px-4 py-2 border border-red-300 text-red-600 rounded-lg text-sm font-medium hover:bg-red-50 transition-colors" onclick="cancelAppointment(<?= $appointment['id'] ?>)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php 
                                    endforeach; 
                                else: 
                                ?>
                                    <div class="text-center py-12">
                                        <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-600 mb-2">No Appointments Found</h3>
                                        <p class="text-gray-500 mb-6">You haven't scheduled any appointments yet.</p>
                                        <a href="book_appointment.php" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-plus mr-2"></i>Book Your First Appointment
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Quick Actions -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 animate-fade-in">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>
                            <div class="space-y-3">
                                <a href="appointments.php" class="block w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-xl font-medium hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-105 text-center">
                                    <i class="fas fa-plus mr-2"></i>Book New Appointment
                                </a>
                                <button class="w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-xl font-medium hover:bg-gray-200 transition-colors"  onclick="window.location.href='view_all_history.php'">
                                    <i class="fas fa-history mr-2"></i>View All History
                                </button>
                            </div>
                        </div>

                        <!-- Mini Calendar -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 animate-fade-in">
                            <h3 class="text-lg font-bold text-gray-800 mb-4"><?= date('F Y') ?></h3>
                            <div class="calendar-grid text-sm">
                                <div class="text-center text-gray-500 font-medium py-2">Sun</div>
                                <div class="text-center text-gray-500 font-medium py-2">Mon</div>
                                <div class="text-center text-gray-500 font-medium py-2">Tue</div>
                                <div class="text-center text-gray-500 font-medium py-2">Wed</div>
                                <div class="text-center text-gray-500 font-medium py-2">Thu</div>
                                <div class="text-center text-gray-500 font-medium py-2">Fri</div>
                                <div class="text-center text-gray-500 font-medium py-2">Sat</div>
                                
                                <?php
                                // Generate calendar days
                                $firstDay = date('Y-m-01');
                                $lastDay = date('Y-m-t');
                                $startDay = date('w', strtotime($firstDay));
                                $totalDays = date('t');
                                $today = date('j');
                                
                                // Get appointment dates for this month
                                $appointment_dates = [];
                                foreach ($appointments as $apt) {
                                    if (date('Y-m', strtotime($apt['date'])) == date('Y-m')) {
                                        $appointment_dates[] = date('j', strtotime($apt['date']));
                                    }
                                }
                                
                                // Empty cells for days before month starts
                                for ($i = 0; $i < $startDay; $i++) {
                                    echo '<div class="calendar-day text-gray-400"></div>';
                                }
                                
                                // Days of the month
                                for ($day = 1; $day <= $totalDays; $day++) {
                                    $classes = 'calendar-day';
                                    if ($day == $today) {
                                        $classes .= ' bg-blue-600 text-white font-bold';
                                    } elseif (in_array($day, $appointment_dates)) {
                                        $classes .= ' has-appointment';
                                    } else {
                                        $classes .= ' text-gray-800';
                                    }
                                    echo "<div class=\"$classes\">$day</div>";
                                }
                                ?>
                            </div>
                            <div class="mt-4 text-xs text-gray-500">
                                <div class="flex items-center space-x-2 mb-1">
                                    <div class="w-3 h-3 bg-blue-600 rounded-full"></div>
                                    <span>Has appointments</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-gray-800 rounded-full"></div>
                                    <span>Today</span>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 animate-fade-in">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Recent Activity</h3>
                            <div class="space-y-3">
                                <?php 
                                $recent_appointments = array_slice($appointments, 0, 3);
                                if (count($recent_appointments) > 0):
                                    foreach ($recent_appointments as $recent): 
                                ?>
                                    <div class="flex items-start space-x-3">
                                        <div class="w-8 h-8 bg-<?= strtolower($recent['status']) == 'confirmed' ? 'green' : (strtolower($recent['status']) == 'pending' ? 'blue' : (strtolower($recent['status']) == 'completed' ? 'purple' : 'red')) ?>-100 rounded-full flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-<?= strtolower($recent['status']) == 'confirmed' ? 'check' : (strtolower($recent['status']) == 'pending' ? 'clock' : (strtolower($recent['status']) == 'completed' ? 'star' : 'times')) ?> text-<?= strtolower($recent['status']) == 'confirmed' ? 'green' : (strtolower($recent['status']) == 'pending' ? 'blue' : (strtolower($recent['status']) == 'completed' ? 'purple' : 'red')) ?>-600 text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800"><?= formatCategory($recent['category']) ?></p>
                                            <p class="text-xs text-gray-500"><?= ucfirst($recent['status']) ?> - <?= date('M j', strtotime($recent['date'])) ?></p>
                                        </div>
                                    </div>
                                <?php 
                                    endforeach;
                                else:
                                ?>
                                    <p class="text-sm text-gray-500">No recent activity</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <a href="book_appointment.php" class="floating-action bg-gradient-to-r from-blue-600 to-purple-600 text-white w-14 h-14 rounded-full shadow-lg hover:shadow-xl flex items-center justify-center">
        <i class="fas fa-plus text-lg"></i>
    </a>

    <script>
        // Filter functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        const appointmentCards = document.querySelectorAll('.appointment-card');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                
                // Update button styles
                filterButtons.forEach(btn => {
                    btn.classList.remove('bg-blue-100', 'text-blue-600');
                    btn.classList.add('text-gray-600');
                });
                this.classList.add('bg-blue-100', 'text-blue-600');
                this.classList.remove('text-gray-600');
                
                // Filter appointments
                appointmentCards.forEach(card => {
                    const status = card.getAttribute('data-status');
                    if (filter === 'all' || status === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Cancel appointment function
        function cancelAppointment(appointmentId) {
            if (confirm('Are you sure you want to cancel this appointment?')) {
                // You can implement AJAX call here to update the database
                fetch('cancel_appointment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        appointment_id: appointmentId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error cancelling appointment: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while cancelling the appointment.');
                });
            }
        }

        // Show all appointments function
        function showAllAppointments() {
            // Reset filter to show all
            document.querySelector('[data-filter="all"]').click();
        }

        // Calendar day click functionality
        const calendarDays = document.querySelectorAll('.calendar-day');
        calendarDays.forEach(day => {
            day.addEventListener('click', function() {
                if (this.textContent.trim() !== '') {
                    console.log('Selected date:', this.textContent);
                    // You can implement date selection functionality here
                }
            });
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98b58da4d5b80dcb',t:'MTc1OTkyNTYxNy4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
