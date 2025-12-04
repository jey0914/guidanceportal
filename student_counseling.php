<?php
session_start();
include("db.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch student info
$stmt = $con->prepare("SELECT * FROM form WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    header("Location: login.php");
    exit();
}

// ✅ FIXED: Changed student_email → email
$stmt = $con->prepare("SELECT * FROM counseling_history WHERE email = ? ORDER BY interview_date DESC");
$stmt->bind_param("s", $email);
$stmt->execute();
$counseling_result = $stmt->get_result();

$counseling_sessions = [];
while ($row = $counseling_result->fetch_assoc()) {
    $counseling_sessions[] = $row;
}

// --- Stats calculation ---
$total_sessions = count($counseling_sessions);
$completed_sessions = 0;
$scheduled_sessions = 0;
$recent_sessions = 0;

$thirty_days_ago = date('Y-m-d', strtotime('-30 days'));

foreach ($counseling_sessions as $session) {
    if (strcasecmp($session['status'], 'Completed') === 0) {
        $completed_sessions++;
    } elseif (strcasecmp($session['status'], 'Scheduled') === 0) {
        $scheduled_sessions++;
    }
    
    if (!empty($session['interview_date']) && $session['interview_date'] >= $thirty_days_ago) {
        $recent_sessions++;
    }
}

// --- Helper functions ---
function getSessionIcon($nature) {
    switch (strtolower(trim($nature))) {
        case 'academic':
        case 'academic counseling':
            return 'fas fa-graduation-cap';
        case 'personal':
        case 'personal counseling':
            return 'fas fa-heart';
        case 'career':
        case 'career guidance':
            return 'fas fa-briefcase';
        case 'crisis':
        case 'crisis intervention':
            return 'fas fa-exclamation-triangle';
        case 'group':
        case 'group counseling':
            return 'fas fa-users';
        default:
            return 'fas fa-comments';
    }
}

function getSessionColor($nature) {
    switch (strtolower(trim($nature))) {
        case 'academic':
        case 'academic counseling':
            return 'blue';
        case 'personal':
        case 'personal counseling':
            return 'green';
        case 'career':
        case 'career guidance':
            return 'purple';
        case 'crisis':
        case 'crisis intervention':
            return 'red';
        case 'group':
        case 'group counseling':
            return 'indigo';
        default:
            return 'gray';
    }
}

function extractKeyTopics($remarks) {
    if (empty($remarks)) return [];

    $topics = [];
    $keywords = [
        'academic performance', 'study habits', 'time management', 'stress management',
        'career planning', 'college preparation', 'relationship issues', 'family problems',
        'anxiety', 'depression', 'self-esteem', 'goal setting', 'decision making'
    ];

    $remarks_lower = strtolower($remarks);
    foreach ($keywords as $keyword) {
        if (strpos($remarks_lower, $keyword) !== false) {
            $topics[] = ucwords($keyword);
        }
    }

    return array_slice($topics, 0, 3); // Limit to 3 topics
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Counseling History - Guidance Portal</title>
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
        
        .session-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        
        .session-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .session-card.academic {
            border-left-color: #3b82f6;
        }
        
        .session-card.personal {
            border-left-color: #10b981;
        }
        
        .session-card.career {
            border-left-color: #8b5cf6;
        }
        
        .session-card.crisis {
            border-left-color: #ef4444;
        }
        
        .session-card.group {
            border-left-color: #6366f1;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .topic-tag {
            display: inline-block;
            background: #f3f4f6;
            color: #374151;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            margin: 0.125rem;
        }
        
        .progress-ring {
            transform: rotate(-90deg);
        }
        
        .progress-ring-circle {
            transition: stroke-dashoffset 0.35s;
            transform-origin: 50% 50%;
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
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 1rem;
            top: 2.5rem;
            bottom: -1rem;
            width: 2px;
            background: #e5e7eb;
        }
        
        .timeline-item:last-child::before {
            display: none;
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
                        <p class="font-medium text-sm"><?= htmlspecialchars($student['fname'] . ' ' . ($student['lname'] ?? '')) ?></p>
                        <p class="text-xs text-slate-400">ID: <?= htmlspecialchars($student['student_no'] ?? $student['id'] ?? '') ?></p>
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
                            <h1 class="text-3xl font-bold mb-2">🧠 My Counseling History — <?= htmlspecialchars($student['fname']) ?></h1>
                            <p class="text-blue-100">Review your past counseling sessions and progress notes</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-blue-100">Total Sessions</p>
                            <p class="text-3xl font-bold"><?= $total_sessions ?></p>
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
                                <p class="text-gray-600 text-sm font-medium">Total Sessions</p>
                                <p class="text-2xl font-bold text-gray-800"><?= $total_sessions ?></p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-comments text-blue-600"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Completed</p>
                                <p class="text-2xl font-bold text-green-600"><?= $completed_sessions ?></p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Scheduled</p>
                                <p class="text-2xl font-bold text-amber-600"><?= $scheduled_sessions ?></p>
                            </div>
                            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-amber-600"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">This Month</p>
                                <p class="text-2xl font-bold text-purple-600"><?= $recent_sessions ?></p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-calendar-week text-purple-600"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="max-w-6xl mx-auto px-8 pb-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Sessions Timeline -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-lg p-6 animate-fade-in">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-xl font-bold text-gray-800">Session History</h2>
                                <div class="flex space-x-2">
                                    <button class="filter-btn px-4 py-2 bg-blue-100 text-blue-600 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors" data-filter="all">
                                        All
                                    </button>
                                    <button class="filter-btn px-4 py-2 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" data-filter="academic">
                                        Academic
                                    </button>
                                    <button class="filter-btn px-4 py-2 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" data-filter="personal">
                                        Personal
                                    </button>
                                    <button class="filter-btn px-4 py-2 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" data-filter="career">
                                        Career
                                    </button>
                                </div>
                            </div>

                            <?php if (count($counseling_sessions) > 0): ?>
                                <div class="space-y-6" id="sessionsList">
                                    <?php foreach ($counseling_sessions as $session): 
                                        $session_color = getSessionColor($session['nature'] ?? $session['type'] ?? 'general');
                                        $key_topics = extractKeyTopics($session['remarks'] ?? '');
                                    ?>
                                        <div class="session-card <?= strtolower($session['nature'] ?? $session['type'] ?? 'general') ?> bg-white border border-gray-200 rounded-xl p-6 shadow-sm timeline-item relative" data-type="<?= strtolower($session['nature'] ?? $session['type'] ?? 'general') ?>">
                                            <!-- Timeline dot -->
                                            <div class="absolute left-4 top-6 w-4 h-4 bg-<?= $session_color ?>-500 rounded-full border-4 border-white shadow-lg z-10"></div>
                                            
                                            <div class="ml-12">
                                                <div class="flex items-start justify-between mb-4">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="w-12 h-12 bg-<?= $session_color ?>-100 rounded-xl flex items-center justify-center">
                                                            <i class="<?= getSessionIcon($session['nature'] ?? $session['type'] ?? 'general') ?> text-<?= $session_color ?>-600"></i>
                                                        </div>
                                                        <div>
                                                            <h3 class="font-semibold text-gray-800"><?= htmlspecialchars($session['nature'] ?? $session['type'] ?? 'Counseling Session') ?></h3>
                                                            <p class="text-sm text-gray-600">with <?= htmlspecialchars($session['counselor'] ?? 'Guidance Counselor') ?></p>
                                                        </div>
                                                    </div>
                                                    <span class="px-3 py-1 bg-<?= $session['status'] === 'Completed' ? 'green' : 'amber' ?>-100 text-<?= $session['status'] === 'Completed' ? 'green' : 'amber' ?>-800 rounded-full text-sm font-medium">
                                                        <?= htmlspecialchars($session['status'] ?? 'Completed') ?>
                                                    </span>
                                                </div>
                                                
                                                <div class="grid grid-cols-2 gap-4 mb-4">
                                                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                                                        <i class="fas fa-calendar-alt text-gray-400"></i>
                                                        <span><?= date('M j, Y', strtotime($session['interview_date'] ?? $session['date'])) ?></span>
                                                    </div>
                                                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                                                        <i class="fas fa-clock text-gray-400"></i>
                                                        <span>
                                                            <?= !empty($session['time_started']) ? date('g:i A', strtotime($session['time_started'])) : '—' ?>
                                                            <?= !empty($session['time_ended']) ? ' - ' . date('g:i A', strtotime($session['time_ended'])) : '' ?>
                                                        </span>
                                                    </div>
                                                    <?php if (!empty($session['grade_section'])): ?>
                                                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                                                        <i class="fas fa-graduation-cap text-gray-400"></i>
                                                        <span><?= htmlspecialchars($session['grade_section']) ?></span>
                                                    </div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($session['program'])): ?>
                                                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                                                        <i class="fas fa-book text-gray-400"></i>
                                                        <span><?= htmlspecialchars($session['program']) ?></span>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <?php if (!empty($key_topics)): ?>
                                                <div class="mb-4">
                                                    <p class="text-sm font-medium text-gray-700 mb-2">Key Topics Discussed:</p>
                                                    <div class="flex flex-wrap">
                                                        <?php foreach ($key_topics as $topic): ?>
                                                            <span class="topic-tag"><?= htmlspecialchars($topic) ?></span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                
                                                <?php if (!empty($session['remarks'])): ?>
                                                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                                    <p class="text-sm font-medium text-gray-700 mb-2">Session Summary:</p>
                                                    <p class="text-sm text-gray-600 leading-relaxed">
                                                        <?= htmlspecialchars(substr($session['remarks'], 0, 200)) ?>
                                                        <?= strlen($session['remarks']) > 200 ? '...' : '' ?>
                                                    </p>
                                                    <?php if (strlen($session['remarks']) > 200): ?>
                                                    <button class="text-blue-600 text-sm font-medium mt-2 hover:text-blue-700" onclick="toggleFullRemarks(this)">
                                                        Read more
                                                    </button>
                                                    <div class="hidden full-remarks">
                                                        <p class="text-sm text-gray-600 leading-relaxed mt-2">
                                                            <?= htmlspecialchars($session['remarks']) ?>
                                                        </p>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php endif; ?>
                                                
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                        <span><i class="fas fa-user-md mr-1"></i> Professional Session</span>
                                                        <span><i class="fas fa-lock mr-1"></i> Confidential</span>
                                                    </div>
                                                    <a href="export_summary.php?id=<?php echo $session['id']; ?>" 
                                                    class="text-blue-600 text-sm font-medium hover:text-blue-700" target="_blank">
                                                    <i class="fas fa-download mr-1"></i> Export Summary
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-12">
                                    <i class="fas fa-comments text-6xl text-gray-300 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-600 mb-2">No Counseling Sessions Yet</h3>
                                    <p class="text-gray-500 mb-6">You haven't had any counseling sessions recorded yet.</p>
                                    <a href="book_appointment.php" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-calendar-plus mr-2"></i>Schedule a Session
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Progress Overview -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 animate-fade-in">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Progress Overview</h3>
                            
                            <?php if ($total_sessions > 0): ?>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-600">Completion Rate</span>
                                        <span class="text-sm font-bold text-gray-800"><?= round(($completed_sessions / $total_sessions) * 100) ?>%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full" style="width: <?= ($completed_sessions / $total_sessions) * 100 ?>%"></div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 mt-6">
                                        <div class="text-center">
                                            <p class="text-2xl font-bold text-blue-600"><?= $completed_sessions ?></p>
                                            <p class="text-xs text-gray-500">Completed</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-2xl font-bold text-amber-600"><?= $scheduled_sessions ?></p>
                                            <p class="text-xs text-gray-500">Scheduled</p>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p class="text-sm text-gray-500">No sessions to track yet.</p>
                            <?php endif; ?>
                        </div>

                        <!-- Session Types -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 animate-fade-in">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Session Types</h3>
                            
                            <?php
                            $session_types = [];
                            foreach ($counseling_sessions as $session) {
                                $type = $session['nature'] ?? $session['type'] ?? 'General';
                                $session_types[$type] = ($session_types[$type] ?? 0) + 1;
                            }
                            ?>
                            
                            <?php if (count($session_types) > 0): ?>
                                <div class="space-y-3">
                                    <?php foreach ($session_types as $type => $count): 
                                        $color = getSessionColor($type);
                                        $percentage = ($count / $total_sessions) * 100;
                                    ?>
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-3 h-3 bg-<?= $color ?>-500 rounded-full"></div>
                                                <span class="text-sm font-medium text-gray-700"><?= htmlspecialchars($type) ?></span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm text-gray-500"><?= $count ?></span>
                                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                                    <div class="bg-<?= $color ?>-500 h-2 rounded-full" style="width: <?= $percentage ?>%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-sm text-gray-500">No session data available.</p>
                            <?php endif; ?>
                        </div>

                        <!-- Quick Actions -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 animate-fade-in">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>
                            <div class="space-y-3">
                                <a href="appointments.php" class="block w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-xl font-medium hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-105 text-center">
                                    <i class="fas fa-calendar-plus mr-2"></i>Schedule Session
                                </a>
                                <a href="export_history.php" target="_blank" 
   class="w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-xl font-medium hover:bg-gray-200 transition-colors block text-center">
   <i class="fas fa-download mr-2"></i>Export History
</a>

                            <!-- Get Help Button -->
<button 
    class="w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-xl font-medium hover:bg-gray-200 transition-colors"
    onclick="document.getElementById('helpModal').classList.remove('hidden')">
    <i class="fas fa-question-circle mr-2"></i>Get Help
</button>

<!-- Modal -->
<div id="helpModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl p-6 w-96 shadow-lg relative">
        <!-- Close button -->
        <button 
            class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-lg"
            onclick="document.getElementById('helpModal').classList.add('hidden')">
            &times;
        </button>

        <h2 class="text-xl font-semibold mb-4 text-gray-800">Need Help?</h2>
        <p class="text-gray-600 text-sm mb-4">
            If you’re having trouble, please contact the Guidance Office or email us at 
            <span class="font-medium text-blue-600">guidance@school.edu</span>.<br><br>
            You can also visit the counseling page for more info.
        </p>

        <div class="text-right">
            <button 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
                onclick="document.getElementById('helpModal').classList.add('hidden')">
                Got it
            </button>
        </div>
    </div>
</div>

                        <!-- Resources -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 animate-fade-in">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Helpful Resources</h3>
                            <div class="space-y-3">
                                <a href="#" class="block p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-book text-blue-600"></i>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">Study Tips Guide</p>
                                            <p class="text-xs text-gray-500">Academic success strategies</p>
                                        </div>
                                    </div>
                                </a>
                                
                                <a href="#" class="block p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-heart text-green-600"></i>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">Wellness Resources</p>
                                            <p class="text-xs text-gray-500">Mental health support</p>
                                        </div>
                                    </div>
                                </a>
                                
                                <a href="#" class="block p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-compass text-purple-600"></i>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">Career Planning</p>
                                            <p class="text-xs text-gray-500">Future pathway guidance</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Filter functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        const sessionCards = document.querySelectorAll('.session-card');

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
                
                // Filter sessions
                sessionCards.forEach(card => {
                    const type = card.getAttribute('data-type');
                    if (filter === 'all' || type.includes(filter)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Toggle full remarks
        function toggleFullRemarks(button) {
            const fullRemarks = button.nextElementSibling;
            const isHidden = fullRemarks.classList.contains('hidden');
            
            if (isHidden) {
                fullRemarks.classList.remove('hidden');
                button.textContent = 'Read less';
            } else {
                fullRemarks.classList.add('hidden');
                button.textContent = 'Read more';
            }
        }

        // Export functionality
        document.querySelectorAll('[data-export]').forEach(button => {
            button.addEventListener('click', function() {
                // Implement export functionality
                console.log('Exporting session data...');
            });
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98bbad39f2f20dcb',t:'MTc1OTk4OTgyNi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
