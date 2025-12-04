<?php
include 'db.php';
session_start();

// Prevent browser caching
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Check session
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Get user info (ensure user exists)
$stmt = $con->prepare("SELECT fname FROM form WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    // User not found, force logout
    session_destroy();
    header("Location: login.php");
    exit();
}
$user = $result->fetch_assoc();
$fname = $user['fname'];

// Get appointments, ensure only appointments belonging to this email
$stmt2 = $con->prepare("
    SELECT a.* 
    FROM appointments a
    INNER JOIN form f ON a.email = f.email
    WHERE a.email = ?
    ORDER BY a.date ASC, a.time ASC
");
$stmt2->bind_param("s", $email);
$stmt2->execute();
$appointments = $stmt2->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Appointments</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    body {
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }
    
    .sidebar {
      background: linear-gradient(180deg, #1e293b 0%, #334155 100%);
      box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
    }
    
    .sidebar-link {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }
    
    .sidebar-link::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
      transition: left 0.5s ease;
    }
    
    .sidebar-link:hover::before {
      left: 100%;
    }
    
    .sidebar-link:hover {
      background: rgba(255, 255, 255, 0.1);
      transform: translateX(4px);
    }
    
    .sidebar-link.active {
      background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .appointment-card {
      background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
      border: 1px solid #e2e8f0;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .appointment-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 4px;
      height: 100%;
      background: linear-gradient(180deg, #3b82f6, #1d4ed8);
    }
    
    .appointment-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
    }
    
    .status-badge {
      display: inline-flex;
      align-items: center;
      padding: 0.25rem 0.75rem;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 600;
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
      background-color: #dbeafe;
      color: #1e40af;
    }
    
    .status-cancelled {
      background-color: #fee2e2;
      color: #991b1b;
    }
    
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .animate-fadeInUp {
      animation: fadeInUp 0.6s ease-out;
    }
    
    .notification-toast {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
      transform: translateX(400px);
      transition: transform 0.3s ease;
    }
    
    .notification-toast.show {
      transform: translateX(0);
    }
    
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(4px);
      z-index: 9999;
      display: none;
      align-items: center;
      justify-content: center;
    }
    
    .modal-overlay.show {
      display: flex;
    }
    
    .modal-content {
      background: white;
      border-radius: 20px;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
      max-width: 500px;
      width: 90%;
      transform: scale(0.9);
      transition: transform 0.3s ease;
    }
    
    .modal-overlay.show .modal-content {
      transform: scale(1);
    }
    
    .empty-state {
      background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
      border-radius: 20px;
      border: 2px dashed #cbd5e1;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen">

  <!-- Sidebar -->
  <div class="sidebar fixed left-0 top-0 h-full w-64 text-white z-50">
    <div class="p-6">
      <div class="flex items-center space-x-3 mb-8">
        <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center">
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
        
        <?php if (stripos($_SESSION['strand_course'], 'SHS') !== false || stripos($_SESSION['year_level'], 'Grade') !== false): ?>
        <a href="student_records.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl">
          <i class="fas fa-clipboard-check w-5"></i>
          <span class="font-medium">Attendance</span>
        </a>
        <?php endif; ?>
        
        <a href="appointments.php" class="sidebar-link active flex items-center space-x-3 px-4 py-3 rounded-xl">
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
  <div class="ml-64 min-h-screen">
    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-md shadow-lg sticky top-0 z-40">
      <div class="px-8 py-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-800">📋 My Appointments</h1>
            <p class="text-gray-600 mt-1">View and manage your scheduled appointments</p>
          </div>
          
          <div class="flex items-center space-x-4">
            <a href="appointments.php" class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-xl font-medium transition-colors">
              <i class="fas fa-plus mr-2"></i>
              New Appointment
            </a>
            
            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
              <i class="fas fa-user text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </header>

    <div class="p-8">
      <!-- Success/Error Messages -->
      <?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
        <div class="notification-toast show bg-green-500 text-white px-6 py-4 rounded-xl shadow-2xl mb-8" id="successMessage">
          <div class="flex items-center space-x-3">
            <i class="fas fa-check-circle text-xl"></i>
            <div>
              <div class="font-semibold">Success!</div>
              <div class="text-sm opacity-90">Appointment successfully updated!</div>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
        <div class="notification-toast show bg-red-500 text-white px-6 py-4 rounded-xl shadow-2xl mb-8" id="deleteMessage">
          <div class="flex items-center space-x-3">
            <i class="fas fa-trash-alt text-xl"></i>
            <div>
              <div class="font-semibold">Deleted!</div>
              <div class="text-sm opacity-90">Appointment successfully deleted.</div>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <!-- Appointments List -->
      <?php if ($appointments && $appointments->num_rows > 0): ?>
        <div class="grid gap-6">
          <?php $index = 0; ?>
          <?php while ($row = $appointments->fetch_assoc()): ?>
            <div class="appointment-card p-6 animate-fadeInUp" style="animation-delay: <?= $index * 0.1 ?>s;">
              <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-3">
                  <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                  </div>
                  <div>
                    <h3 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($row['name']) ?></h3>
                    <p class="text-gray-600"><?= htmlspecialchars($row['email']) ?></p>
                  </div>
                </div>
                
                <div class="flex items-center space-x-2">
                  <?php
                  // Determine status based on date/time or admin response
                  $appointmentDate = strtotime($row['date'] . ' ' . $row['time']);
                  $currentTime = time();
                  $status = 'pending';
                  $statusText = 'Pending';
                  $statusIcon = 'clock';
                  
                  if (isset($row['status'])) {
                    $status = strtolower($row['status']);
                    $statusText = ucfirst($row['status']);
                  } elseif ($appointmentDate < $currentTime) {
                    $status = 'completed';
                    $statusText = 'Completed';
                    $statusIcon = 'check';
                  }
                  ?>
                  <span class="status-badge status-<?= $status ?>">
                    <i class="fas fa-<?= $statusIcon ?> mr-1"></i>
                    <?= $statusText ?>
                  </span>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-50 rounded-lg p-4">
                  <div class="flex items-center space-x-2 mb-2">
                    <i class="fas fa-graduation-cap text-gray-600"></i>
                    <span class="text-sm font-medium text-gray-700">Academic Info</span>
                  </div>
                  <p class="text-gray-800 font-semibold"><?= htmlspecialchars($row['grade']) ?></p>
                  <p class="text-gray-600 text-sm"><?= htmlspecialchars($row['section']) ?></p>
                </div>

                <div class="bg-blue-50 rounded-lg p-4">
                  <div class="flex items-center space-x-2 mb-2">
                    <i class="fas fa-calendar text-blue-600"></i>
                    <span class="text-sm font-medium text-blue-700">Date & Time</span>
                  </div>
                  <p class="text-blue-800 font-semibold"><?= date('M d, Y', strtotime($row['date'])) ?></p>
                  <p class="text-blue-600 text-sm"><?= date('g:i A', strtotime($row['time'])) ?></p>
                </div>

                <div class="bg-purple-50 rounded-lg p-4">
                  <div class="flex items-center space-x-2 mb-2">
                    <i class="fas fa-tag text-purple-600"></i>
                    <span class="text-sm font-medium text-purple-700">Category</span>
                  </div>
                  <p class="text-purple-800 font-semibold"><?= htmlspecialchars($row['interest']) ?></p>
                  <p class="text-purple-600 text-sm"><?= htmlspecialchars($row['specific_concern']) ?></p>
                </div>
              </div>

              <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="flex items-center space-x-2 mb-2">
                  <i class="fas fa-comment-alt text-gray-600"></i>
                  <span class="text-sm font-medium text-gray-700">Reason for Consultation</span>
                </div>
                <p class="text-gray-800"><?= htmlspecialchars($row['reason']) ?></p>
              </div>

              <?php if (isset($row['admin_message']) && !empty($row['admin_message'])): ?>
                <div class="bg-green-50 rounded-lg p-4 mb-6 border-l-4 border-green-500">
                  <div class="flex items-center space-x-2 mb-2">
                    <i class="fas fa-envelope text-green-600"></i>
                    <span class="text-sm font-medium text-green-700">Message from Guidance Office</span>
                  </div>
                  <p class="text-green-800"><?= htmlspecialchars($row['admin_message']) ?></p>
                </div>
              <?php endif; ?>

              <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <div class="text-sm text-gray-500">
                  <i class="fas fa-clock mr-1"></i>
                  Submitted: <?= isset($row['created_at']) ? date('M d, Y g:i A', strtotime($row['created_at'])) : 'N/A' ?>
                </div>
                
                <div class="flex items-center space-x-3">
                  <?php if (!isset($row['status']) || strtolower($row['status']) != 'approved'): ?>
  <a href="edit_appointment.php?id=<?= $row['id'] ?>" 
     class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg font-medium transition-colors">
    <i class="fas fa-edit mr-1"></i>
    Edit
  </a>
<?php else: ?>
  <span class="bg-gray-200 text-gray-500 px-4 py-2 rounded-lg font-medium cursor-not-allowed flex items-center">
    <i class="fas fa-lock mr-1"></i>
    Approved
  </span>
<?php endif; ?>

                  <button onclick="showDeleteModal(<?= $row['id'] ?>)" 
                          class="bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-trash mr-1"></i>
                    Delete
                  </button>
                </div>
              </div>
            </div>
            <?php $index++; ?>
          <?php endwhile; ?>
        </div>
      <?php else: ?>
        <!-- Empty State -->
        <div class="empty-state p-12 text-center animate-fadeInUp">
          <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-calendar-times text-gray-400 text-3xl"></i>
          </div>
          <h3 class="text-2xl font-bold text-gray-800 mb-4">No Appointments Found</h3>
          <p class="text-gray-600 mb-8 max-w-md mx-auto">
            You haven't scheduled any appointments yet. Book your first consultation with our guidance counselors.
          </p>
          <a href="appointments.php" 
             class="bg-gradient-to-r from-blue-600 to-purple-700 text-white px-8 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-800 transition-all duration-300 transform hover:scale-105 shadow-lg inline-block">
            <i class="fas fa-plus mr-2"></i>
            Schedule Appointment
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal-overlay" id="deleteModal">
    <div class="modal-content">
      <div class="bg-red-500 text-white p-6 rounded-t-xl">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
            <i class="fas fa-exclamation-triangle text-white"></i>
          </div>
          <div>
            <h3 class="text-xl font-bold">Confirm Deletion</h3>
            <p class="text-red-100">This action cannot be undone</p>
          </div>
        </div>
      </div>
      
      <div class="p-6">
        <p class="text-gray-700 mb-6">Are you sure you want to delete this appointment? This will permanently remove the appointment from your records.</p>
        
        <div class="flex items-center justify-end space-x-3">
          <button onclick="hideDeleteModal()" 
                  class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-medium transition-colors">
            Cancel
          </button>
          <a href="#" id="confirmDeleteBtn" 
             class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
            <i class="fas fa-trash mr-1"></i>
            Delete Appointment
          </a>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Handle notification messages
    setTimeout(() => {
      const notifications = document.querySelectorAll('.notification-toast');
      notifications.forEach(notification => {
        notification.classList.remove('show');
      });
    }, 5000);

    // Delete modal functions
    function showDeleteModal(appointmentId) {
      document.getElementById('confirmDeleteBtn').href = 'delete_appointment.php?id=' + appointmentId;
      document.getElementById('deleteModal').classList.add('show');
      document.body.style.overflow = 'hidden';
    }

    function hideDeleteModal() {
      document.getElementById('deleteModal').classList.remove('show');
      document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
      if (e.target === this) {
        hideDeleteModal();
      }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        hideDeleteModal();
      }
    });
  </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'987cb7d6640a0dcd',t:'MTc1OTMyOTY1Ny4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
