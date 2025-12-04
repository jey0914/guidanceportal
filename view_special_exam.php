<?php
session_start();
include("db.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];

// Get student info for sidebar
$student_query = "SELECT * FROM students WHERE email = ?";
$student_stmt = $con->prepare($student_query);
$student_stmt->bind_param("s", $email);
$student_stmt->execute();
$student_result = $student_stmt->get_result();
$student = $student_result->fetch_assoc();

// Get special exam requests
$query = "SELECT subject, reason, teacher, status, submitted_at 
          FROM special_exam_requests
          WHERE email = ?
          ORDER BY submitted_at DESC";

$stmt = $con->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Count statuses
$approved_count = 0;
$pending_count = 0;
$rejected_count = 0;

$temp_result = $result->fetch_all(MYSQLI_ASSOC);
$total_requests = count($temp_result);

foreach ($temp_result as $row) {
    switch (strtolower($row['status'])) {
        case 'approved':
            $approved_count++;
            break;
        case 'pending':
            $pending_count++;
            break;
        case 'rejected':
            $rejected_count++;
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Exam Status - Student Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; box-sizing: border-box; }
        .sidebar-link { transition: all 0.3s ease; color: #94a3b8; }
        .sidebar-link:hover { background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; transform: translateX(4px); }
        .sidebar-link.active { background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); }
        .status-badge { transition: all 0.3s ease; }
        .status-badge:hover { transform: scale(1.05); }
        .exam-row:hover { background: linear-gradient(135deg, rgba(59,130,246,0.05), rgba(147,51,234,0.05)); transform: translateX(4px); }
        .pulse-animation { animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1;}50%{opacity:0.7;} }
    </style>
</head>
<body class="bg-gray-50">
<div class="flex h-screen bg-gray-50">
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
        <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-file-medical mr-3 text-blue-600"></i>
                        Special Exam Eligibility
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">Check the status of your special examination requests</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        <?= date('F j, Y') ?>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-7xl mx-auto animate-fade-in">
                <!-- Status Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Requests</p>
                                <p class="text-2xl font-bold text-gray-900"><?= $total_requests ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Approved</p>
                                <p class="text-2xl font-bold text-green-600"><?= $approved_count ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Pending</p>
                                <p class="text-2xl font-bold text-yellow-600"><?= $pending_count ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Rejected</p>
                                <p class="text-2xl font-bold text-red-600"><?= $rejected_count ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($total_requests > 0): ?>
                <!-- Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden card-hover">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900"><i class="fas fa-list-alt mr-2 text-blue-600"></i>Special Exam Requests</h2>
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full"><?= $total_requests ?> Request<?= $total_requests > 1 ? 's' : '' ?></span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><i class="fas fa-book mr-2"></i>Subject</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><i class="fas fa-comment-alt mr-2"></i>Reason</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><i class="fas fa-chalkboard-teacher mr-2"></i>Teacher</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><i class="fas fa-flag mr-2"></i>Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><i class="fas fa-calendar-plus mr-2"></i>Submitted At</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($temp_result as $row): ?>
                                    <tr class="exam-row">
                                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($row['subject']) ?></td>
                                        <td class="px-6 py-4"><?= htmlspecialchars($row['reason']) ?></td>
                                        <td class="px-6 py-4"><?= htmlspecialchars($row['teacher'] ?? 'N/A') ?></td>
                                        <td class="px-6 py-4">
                                            <span class="status-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                <?php 
                                                $status = strtolower($row['status']);
                                                if ($status === 'approved') echo 'bg-green-100 text-green-800';
                                                elseif ($status === 'pending') echo 'bg-yellow-100 text-yellow-800';
                                                elseif ($status === 'rejected') echo 'bg-red-100 text-red-800';
                                                else echo 'bg-gray-100 text-gray-800';
                                                ?>">
                                                <i class="fas fa-<?php 
                                                if ($status === 'approved') echo 'check-circle';
                                                elseif ($status === 'pending') echo 'clock';
                                                elseif ($status === 'rejected') echo 'times-circle';
                                                else echo 'question-circle';
                                                ?> mr-1"></i>
                                                <?= htmlspecialchars($row['status']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900"><?= date("F j, Y", strtotime($row['submitted_at'])) ?></div>
                                            <div class="text-sm text-gray-500"><?= date("g:i A", strtotime($row['submitted_at'])) ?></div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php else: ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center card-hover">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                        <i class="fas fa-file-medical text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Special Exam Requests</h3>
                    <p class="text-gray-600 mb-4">You haven't submitted any special examination requests yet.</p>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>
</body>
</html>
