<?php
session_start();
include("db.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch student info from `form`
$formQuery = $con->prepare("SELECT student_no, fname, mname, lname, year_level, strand_course FROM form WHERE email = ?");
$formQuery->bind_param("s", $email);
$formQuery->execute();
$formResult = $formQuery->get_result();
$formData = $formResult->fetch_assoc();

$studentNo = $formData['student_no'] ?? '';
$fullName = trim($formData['fname'] . ' ' . ($formData['mname'] ? $formData['mname'] . ' ' : '') . $formData['lname']);
$yearLevel = $formData['year_level'] ?? '';
$strandCourse = $formData['strand_course'] ?? '';

// Fetch exit interview info from `exit_interviews`
$exitQuery = $con->prepare("SELECT * FROM exit_interviews WHERE email = ? LIMIT 1");
$exitQuery->bind_param("s", $email);
$exitQuery->execute();
$exitResult = $exitQuery->get_result();
$exitData = $exitResult->fetch_assoc();

$status = $exitData['status'] ?? 'Pending';
$reason = $exitData['reason'] ?? '—';
$preferredDate = $exitData['preferred_date'] ?? '—';
$preferredTime = $exitData['preferred_time'] ?? '—';
$scheduledDate = $exitData['scheduled_date'] ?? '—';
$notes = $exitData['notes'] ?? 'No notes yet';

// Compute days until interview (if scheduled)
if (!empty($exitData['scheduled_date'])) {
    $today = new DateTime();
    $scheduled = new DateTime($exitData['scheduled_date']);
    $interval = $today->diff($scheduled);
    $daysUntil = $interval->invert ? 'Done' : $interval->days . ' day(s)';
} else {
    $daysUntil = 'Not yet scheduled';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exit Interview Report - Student Portal</title>
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
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        
        .status-for-scheduling {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-scheduled {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .status-completed {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .info-card {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-left: 4px solid #3b82f6;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-slide-in {
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
        
        .table-row {
            transition: all 0.3s ease;
        }
        
        .table-row:hover {
            background-color: #f1f5f9;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .notes-tooltip {
            position: relative;
            cursor: help;
        }
        
        .notes-tooltip:hover .tooltip-content {
            opacity: 1;
            visibility: visible;
        }
        
        .tooltip-content {
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #1f2937;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 10;
            max-width: 300px;
            white-space: normal;
        }
        
        .refresh-btn {
            transition: transform 0.3s ease;
        }
        
        .refresh-btn:hover {
            transform: rotate(180deg);
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
                    
                    <a href="student_records.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl">
                        <i class="fas fa-clipboard-check w-5"></i>
                        <span class="font-medium">Attendance</span>
                    </a>
                    
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
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Exit Interview Report</h1>
                        <p class="text-sm text-gray-600 mt-1">Track your exit interview request status and schedule</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button onclick="refreshReport()" class="refresh-btn bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                            <i class="fas fa-sync-alt"></i>
                            <span>Refresh</span>
                        </button>
                        <div class="text-sm text-gray-500">
                           Last updated: <span id="lastUpdated"><?php echo date("F j, Y g:i A"); ?></span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Instructions Card -->
                <div class="info-card p-6 rounded-xl mb-6 animate-fade-in">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">How to Use Your Exit Interview Report</h3>
                            <div class="text-gray-700 space-y-2 text-sm leading-relaxed">
                                <p>This report allows you to track your exit interview request status and stay informed about scheduling from the Guidance Office. <strong>Please check this report regularly</strong> to ensure you don't miss important updates.</p>
                                
                                <div class="mt-4">
                                    <h4 class="font-semibold text-gray-800 mb-2">Status Meanings:</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <div class="flex items-center space-x-2">
                                            <span class="status-badge status-for-scheduling text-xs">For Scheduling</span>
                                            <span class="text-xs text-gray-600">Request received, awaiting schedule</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="status-badge status-scheduled text-xs">Scheduled</span>
                                            <span class="text-xs text-gray-600">Interview approved with date assigned</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="status-badge status-completed text-xs">Completed</span>
                                            <span class="text-xs text-gray-600">Interview conducted successfully</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <p class="mt-3"><strong>Important:</strong> Check the Notes column for special instructions, reminders, or feedback from the Guidance Office. Contact us immediately if you notice any discrepancies in your information.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 animate-fade-in">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Current Status</p>
                                 <p class="text-2xl font-bold text-blue-600"><?php echo htmlspecialchars(ucfirst($status)); ?></p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-calendar-check text-blue-600"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 animate-fade-in">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Scheduled Date</p>
                                <p class="text-2xl font-bold text-green-600"><?php echo htmlspecialchars($scheduledDate); ?></p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-clock text-green-600"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 animate-fade-in">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Days Until Interview</p>
                                   <p class="text-2xl font-bold text-orange-600"><?php echo htmlspecialchars($daysUntil); ?></p>
                            </div>
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-hourglass-half text-orange-600"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Exit Interview Report Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden animate-fade-in">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Your Exit Interview Details</h2>
                        <p class="text-sm text-gray-600 mt-1">Review your submitted information and current status</p>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Information</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Academic Details</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Interview Details</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status & Notes</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr class="table-row">
                                    <td class="px-6 py-4">
                                        <div class="space-y-2">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($fullName); ?></p>
                                    <p class="text-sm text-gray-500">Student No: <?php echo htmlspecialchars($studentNo); ?></p>
                                            </div>
                                            <div>
                                                 <p class="text-sm text-gray-600"><?php echo htmlspecialchars($email); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                             <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($yearLevel); ?></p>
                                <p class="text-sm text-gray-500">Strand/Course: <?php echo htmlspecialchars($strandCourse); ?></p>
                                <p class="text-sm text-gray-500">Reason: <?php echo htmlspecialchars($reason); ?></p>
                            </div>
                        </td>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            <div>
                                                <p class="text-xs text-gray-500">Preferred Date</p>
                                                <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($preferredDate); ?></p>
                                            </div>
                                            <div class="mt-2">
                                                <p class="text-xs text-gray-500">Scheduled Date</p>
                                                 <p class="text-sm font-medium text-blue-600"><?php echo htmlspecialchars($scheduledDate); ?></p>
                                                 <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($preferredTime); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-3">
                                            <span class="status-badge status-scheduled">Scheduled</span>
                                            <div class="notes-tooltip">
                                                <div class="flex items-center space-x-2">
                                                    <i class="fas fa-sticky-note text-gray-400"></i>
                                                    <span class="text-sm text-gray-600 cursor-help">View Notes</span>
                                                </div>
                                                <div class="tooltip-content">
                                                   Interview scheduled for <?php echo htmlspecialchars($scheduledDate . ' ' . $preferredTime); ?>. 
Please bring your clearance form and any required documents. Contact the Guidance Office if you need to reschedule.

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Action Items Card -->
                <div class="mt-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200 animate-fade-in">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-tasks text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Next Steps & Reminders</h3>
                            <div class="space-y-3">
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-blue-600 rounded-full mt-2 flex-shrink-0"></div>
                                    <p class="text-sm text-gray-700">Your exit interview is scheduled for <strong><?php echo htmlspecialchars($scheduledDate . ' ' . $preferredTime); ?></strong></p>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-blue-600 rounded-full mt-2 flex-shrink-0"></div>
                                    <p class="text-sm text-gray-700">Please arrive 10 minutes early at the Guidance Office</p>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-blue-600 rounded-full mt-2 flex-shrink-0"></div>
                                    <p class="text-sm text-gray-700">Bring your clearance form and any required documents</p>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-orange-500 rounded-full mt-2 flex-shrink-0"></div>
                                    <p class="text-sm text-gray-700">Need to reschedule? Contact the Guidance Office at least 24 hours in advance</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="mt-6 bg-white rounded-xl p-6 shadow-sm border border-gray-200 animate-fade-in">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Need Help?</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-phone text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Phone</p>
                                <p class="text-sm text-gray-600">(02) 8123-4567</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-envelope text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Email</p>
                                <p class="text-sm text-gray-600">guidance@school.edu.ph</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Update last updated time
        function updateLastUpdated() {
            const now = new Date();
            const timeString = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            document.getElementById('lastUpdated').textContent = timeString;
        }

        // Refresh report function
        function refreshReport() {
            // Simulate refresh with loading state
            const refreshBtn = document.querySelector('.refresh-btn');
            const originalContent = refreshBtn.innerHTML;
            
            refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Refreshing...</span>';
            refreshBtn.disabled = true;
            
            setTimeout(() => {
                refreshBtn.innerHTML = originalContent;
                refreshBtn.disabled = false;
                updateLastUpdated();
                
                // Show success notification
                showNotification('Report refreshed successfully!', 'success');
            }, 1500);
        }

        // Calculate days until interview
        function calculateDaysUntil() {
            const scheduledDate = new Date('2024-02-16');
            const today = new Date();
            const diffTime = scheduledDate - today;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            const daysElement = document.getElementById('daysUntil');
            if (diffDays > 0) {
                daysElement.textContent = `${diffDays} days`;
                daysElement.className = 'text-2xl font-bold text-orange-600';
            } else if (diffDays === 0) {
                daysElement.textContent = 'Today';
                daysElement.className = 'text-2xl font-bold text-red-600';
            } else {
                daysElement.textContent = 'Past due';
                daysElement.className = 'text-2xl font-bold text-red-600';
            }
        }

        // Show notification
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white transform transition-all duration-300 translate-x-full`;
            
            if (type === 'success') {
                notification.classList.add('bg-green-500');
            } else if (type === 'error') {
                notification.classList.add('bg-red-500');
            }
            
            notification.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="fas fa-check-circle"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Animate out and remove
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            updateLastUpdated();
            calculateDaysUntil();
            
            // Update time every minute
            setInterval(updateLastUpdated, 60000);
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9908392242960dc9',t:'MTc2MDc5MjQ3NC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
