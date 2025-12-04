<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

include("db.php");

$student_email = $_SESSION['email'];

// Get student info
$student_query = $con->prepare("SELECT * FROM form WHERE email = ?");
$student_query->bind_param("s", $student_email);
$student_query->execute();
$student_result = $student_query->get_result();
$student = $student_result->fetch_assoc();

if (!$student) {
    header("Location: login.php");
    exit();
}

// Get all appointments
$appointments_query = $con->prepare("SELECT * FROM appointments WHERE email = ? ORDER BY date DESC, time DESC");
$appointments_query->bind_param("s", $student_email);
$appointments_query->execute();
$appointments_result = $appointments_query->get_result();

$appointments = [];
while ($row = $appointments_result->fetch_assoc()) {
    $appointments[] = $row;
}

// Helper functions
function getStatusBadgeClass($status){
    switch(strtolower($status)){
        case 'pending': return 'status-pending';
        case 'confirmed':
        case 'approved': return 'status-confirmed';
        case 'completed': return 'status-completed';
        case 'cancelled': return 'status-cancelled';
        default: return 'status-pending';
    }
}

function formatCategory($category){
    switch($category){
        case 'consultation': return 'General Consultation';
        case 'special_exam': return 'Special Examination';
        case 'exit_interview': return 'Exit Interview';
        default: return ucfirst(str_replace('_',' ',$category));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment History - Enhanced View</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
        .status-confirmed {
            background: #d1fae5;
            color: #065f46;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
        .status-completed {
            background: #e0e7ff;
            color: #3730a3;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
        .filter-btn {
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            border: 2px solid #e5e7eb;
            color: #6b7280;
            font-weight: 500;
        }
        
        .filter-btn:hover {
            border-color: #3b82f6;
            color: #3b82f6;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }
        
        .filter-btn.active {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            border-color: #3b82f6;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .appointment-row {
            transition: all 0.3s ease;
        }
        
        .appointment-row:hover {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            transform: scale(1.01);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .table-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }
        
        .gradient-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .stats-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .search-container {
            position: relative;
        }
        
        .search-input {
            padding: 12px 16px 12px 48px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            width: 100%;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .slide-in {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .no-results {
            text-align: center;
            padding: 40px 20px;
            color: #6b7280;
        }
        
        .category-badge {
            background: #f3f4f6;
            color: #374151;
            padding: 4px 8px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .admin-message {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .tooltip {
            position: relative;
            cursor: help;
        }
        
        .tooltip:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #1f2937;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            white-space: nowrap;
            z-index: 1000;
            margin-bottom: 5px;
        }
        
        .tooltip:hover::before {
            content: '';
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: #1f2937;
            z-index: 1000;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">
                <i class="fas fa-history text-indigo-600 mr-3"></i>
                Appointment History
            </h1>
            <p class="text-gray-600 text-lg">Track and manage all your appointment records</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8" id="statsContainer">
            <!-- Stats will be populated by JavaScript -->
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border mb-8">
            <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                <!-- Search Bar -->
                <div class="search-container w-full lg:w-1/3">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="searchInput" class="search-input" placeholder="Search appointments...">
                </div>
                
                <!-- Filter Buttons -->
                <div class="flex flex-wrap gap-3">
                    <div class="filter-btn active" data-filter="all">
                        <i class="fas fa-list mr-2"></i>All
                    </div>
                    <div class="filter-btn" data-filter="pending">
                        <i class="fas fa-clock mr-2"></i>Pending
                    </div>
                    <div class="filter-btn" data-filter="confirmed">
                        <i class="fas fa-check-circle mr-2"></i>Confirmed
                    </div>
                    <div class="filter-btn" data-filter="completed">
                        <i class="fas fa-check-double mr-2"></i>Completed
                    </div>
                    <div class="filter-btn" data-filter="cancelled">
                        <i class="fas fa-times-circle mr-2"></i>Cancelled
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointments Table -->
        <?php if(count($appointments) > 0): ?>
        <div class="table-container fade-in">
            <div class="overflow-x-auto">
                <table class="min-w-full" id="appointmentsTable">
                    <thead class="gradient-header">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold">
                                <i class="fas fa-calendar-alt mr-2"></i>Date
                            </th>
                            <th class="px-6 py-4 text-left font-semibold">
                                <i class="fas fa-clock mr-2"></i>Time
                            </th>
                            <th class="px-6 py-4 text-left font-semibold">
                                <i class="fas fa-tag mr-2"></i>Category
                            </th>
                            <th class="px-6 py-4 text-left font-semibold">
                                <i class="fas fa-comment mr-2"></i>Reason
                            </th>
                            <th class="px-6 py-4 text-left font-semibold">
                                <i class="fas fa-info-circle mr-2"></i>Status
                            </th>
                            <th class="px-6 py-4 text-left font-semibold">
                                <i class="fas fa-user-shield mr-2"></i>Admin Message
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($appointments as $apt): ?>
                        <tr class="border-b border-gray-100 appointment-row slide-in" data-status="<?= strtolower($apt['status']) === 'approved' ? 'confirmed' : strtolower($apt['status']) ?>">

                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="bg-indigo-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-calendar text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-800"><?= date('M j, Y', strtotime($apt['date'])) ?></div>
                                        <div class="text-sm text-gray-500"><?= date('l', strtotime($apt['date'])) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-clock text-blue-600"></i>
                                    </div>
                                    <span class="font-medium text-gray-800"><?= date('g:i A', strtotime($apt['time'])) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="category-badge"><?= formatCategory($apt['category']) ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <p class="text-gray-800 text-sm leading-relaxed"><?= htmlspecialchars($apt['reason']) ?></p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="<?= getStatusBadgeClass($apt['status']) ?>">
                                    <?php 
                                    $statusIcons = [
                                        'pending' => 'fas fa-clock',
                                        'confirmed' => 'fas fa-check-circle',
                                        'completed' => 'fas fa-check-double',
                                        'cancelled' => 'fas fa-times-circle'
                                    ];
                                    $icon = $statusIcons[strtolower($apt['status'])] ?? 'fas fa-info-circle';
                                    ?>
                                    <i class="<?= $icon ?>"></i>
                                    <?= ucfirst($apt['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php if (!empty($apt['admin_message'])): ?>
                                    <div class="tooltip admin-message" data-tooltip="<?= htmlspecialchars($apt['admin_message']) ?>">
                                        <div class="flex items-center">
                                            <i class="fas fa-comment-dots text-gray-400 mr-2"></i>
                                            <span class="text-gray-600 text-sm"><?= htmlspecialchars($apt['admin_message']) ?></span>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <span class="text-gray-400 text-sm italic">No message</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- No Results Message -->
            <div id="noResults" class="no-results hidden">
                <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No appointments found</h3>
                <p class="text-gray-500">Try adjusting your search or filter criteria</p>
            </div>
        </div>
        <?php else: ?>
            <div class="bg-white rounded-2xl p-12 shadow-lg text-center">
                <i class="fas fa-calendar-times text-6xl text-gray-300 mb-6"></i>
                <h3 class="text-2xl font-bold text-gray-600 mb-4">No Appointment History</h3>
                <p class="text-gray-500 mb-8">You haven't made any appointments yet. Start by booking your first appointment!</p>
                <a href="appointments.php" class="inline-block bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3 rounded-full font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i>Book New Appointment
                </a>
            </div>
        <?php endif; ?>
        
        <!-- Back Button -->
        <div class="mt-8 text-center">
            <a href="student_appointment.php" class="inline-block bg-gradient-to-r from-gray-600 to-gray-700 text-white px-8 py-4 rounded-full font-semibold hover:from-gray-700 hover:to-gray-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                <i class="fas fa-arrow-left mr-2"></i>Back to Appointments
            </a>
        </div>
    </div>

    <script>
        // Enhanced filter and search functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        const appointmentRows = document.querySelectorAll('.appointment-row');
        const searchInput = document.getElementById('searchInput');
        const noResults = document.getElementById('noResults');
        const appointmentsTable = document.getElementById('appointmentsTable');

        // Calculate and display statistics
        function calculateStats() {
            const stats = {
                total: appointmentRows.length,
                pending: 0,
                confirmed: 0,
                completed: 0,
                cancelled: 0
            };

            appointmentRows.forEach(row => {
                const status = row.getAttribute('data-status');
                if (stats.hasOwnProperty(status)) {
                    stats[status]++;
                }
            });

            displayStats(stats);
        }

        function displayStats(stats) {
            const statsContainer = document.getElementById('statsContainer');
            const statsConfig = [
                { key: 'total', label: 'Total Appointments', icon: 'fas fa-calendar-alt', color: 'indigo' },
                { key: 'pending', label: 'Pending', icon: 'fas fa-clock', color: 'yellow' },
                { key: 'confirmed', label: 'Confirmed', icon: 'fas fa-check-circle', color: 'green' },
                { key: 'completed', label: 'Completed', icon: 'fas fa-check-double', color: 'blue' }
            ];

            statsContainer.innerHTML = statsConfig.map(config => `
                <div class="stats-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">${config.label}</p>
                            <p class="text-3xl font-bold text-${config.color}-600">${stats[config.key]}</p>
                        </div>
                        <div class="bg-${config.color}-100 p-3 rounded-full">
                            <i class="${config.icon} text-${config.color}-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Enhanced filter functionality
        function filterAppointments() {
            const activeFilter = document.querySelector('.filter-btn.active').getAttribute('data-filter');
            const searchTerm = searchInput.value.toLowerCase().trim();
            let visibleCount = 0;

            appointmentRows.forEach(row => {
                const status = row.getAttribute('data-status');
                const rowText = row.textContent.toLowerCase();
                
                const matchesFilter = activeFilter === 'all' || status === activeFilter;
                const matchesSearch = searchTerm === '' || rowText.includes(searchTerm);
                
                if (matchesFilter && matchesSearch) {
                    row.style.display = '';
                    row.classList.add('slide-in');
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                    row.classList.remove('slide-in');
                }
            });

            // Show/hide no results message
            if (visibleCount === 0 && appointmentRows.length > 0) {
                noResults.classList.remove('hidden');
                if (appointmentsTable) appointmentsTable.style.display = 'none';
            } else {
                noResults.classList.add('hidden');
                if (appointmentsTable) appointmentsTable.style.display = '';
            }
        }

        // Event listeners
        filterButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                filterButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                filterAppointments();
            });
        });

        searchInput.addEventListener('input', filterAppointments);

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            calculateStats();
            
            // Add staggered animation to rows
            appointmentRows.forEach((row, index) => {
                row.style.animationDelay = `${index * 0.1}s`;
            });
        });

        // Add smooth scrolling for better UX
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98f4b98fe54a0dc9',t:'MTc2MDU4ODAxOS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
