<?php
session_start();
include("db.php");

if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Special Exam Request - Guidance Portal</title>
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
    
    .form-card {
      background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
      border: 1px solid #e2e8f0;
      transition: all 0.3s ease;
    }
    
    .form-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
    }
    
    .form-input {
      transition: all 0.3s ease;
      border: 2px solid #e2e8f0;
    }
    
    .form-input:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
      outline: none;
    }
    
    .reason-card {
      background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
      border-radius: 16px;
      border: 2px solid transparent;
      transition: all 0.3s ease;
      cursor: pointer;
    }
    
    .reason-card:hover {
      border-color: #3b82f6;
      transform: translateY(-2px);
      box-shadow: 0 12px 40px rgba(59, 130, 246, 0.15);
    }
    
    .reason-card.selected {
      border-color: #3b82f6;
      background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    }
    
    .file-upload-area {
      border: 2px dashed #cbd5e1;
      border-radius: 12px;
      transition: all 0.3s ease;
    }
    
    .file-upload-area:hover {
      border-color: #3b82f6;
      background: #f8fafc;
    }
    
    .file-upload-area.dragover {
      border-color: #3b82f6;
      background: #eff6ff;
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
          <h2 class="text-xl font-bold">Guidance Portal</h2>
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
            <h1 class="text-3xl font-bold text-gray-800">📄 Special Exam Request</h1>
            <p class="text-gray-600 mt-1">Request for special examination arrangements</p>
          </div>
          
          <div class="flex items-center space-x-4">
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
      <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="notification-toast show bg-green-500 text-white px-6 py-4 rounded-xl shadow-2xl mb-8" id="successMessage">
          <div class="flex items-center space-x-3">
            <i class="fas fa-check-circle text-xl"></i>
            <div>
              <div class="font-semibold">Request Submitted!</div>
              <div class="text-sm opacity-90">Your special exam request has been submitted successfully.</div>
            </div>
          </div>
        </div>
      <?php elseif (isset($_GET['success']) && $_GET['success'] == 0): ?>
        <div class="notification-toast show bg-red-500 text-white px-6 py-4 rounded-xl shadow-2xl mb-8" id="errorMessage">
          <div class="flex items-center space-x-3">
            <i class="fas fa-exclamation-circle text-xl"></i>
            <div>
              <div class="font-semibold">Submission Failed</div>
              <div class="text-sm opacity-90">Failed to submit your request. Please try again.</div>
            </div>
          </div>
        </div>
      <?php elseif (isset($_SESSION['error'])): ?>
        <div class="notification-toast show bg-yellow-500 text-white px-6 py-4 rounded-xl shadow-2xl mb-8" id="warningMessage">
          <div class="flex items-center space-x-3">
            <i class="fas fa-exclamation-triangle text-xl"></i>
            <div>
              <div class="font-semibold">Warning</div>
              <div class="text-sm opacity-90"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <!-- Form -->
      <div class="max-w-4xl mx-auto">
        <div class="form-card p-8 animate-fadeInUp">
          <div class="flex items-center space-x-3 mb-8">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
              <i class="fas fa-file-medical text-blue-600 text-xl"></i>
            </div>
            <div>
              <h2 class="text-2xl font-bold text-gray-800">Special Exam Request Form</h2>
              <p class="text-gray-600">Please fill out all required information</p>
            </div>
          </div>

          <form action="submit_exam_request.php" method="POST" enctype="multipart/form-data" class="space-y-8">
            
            <!-- Student Information -->
            <div class="bg-gray-50 rounded-xl p-6">
              <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-user mr-2 text-blue-600"></i>
                Student Information
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                   <input type="text" name="full_name" class="form-input w-full px-4 py-3 rounded-xl bg-white"
value="<?= htmlspecialchars(($_SESSION['fname'] ?? '') . ' ' . ($_SESSION['mname'] ?? '') . ' ' . ($_SESSION['lname'] ?? '')) ?>" readonly>
<!-- hidden inputs to avoid PHP error -->
  <input type="hidden" name="fname" value="<?= htmlspecialchars($_SESSION['fname'] ?? '') ?>">
  <input type="hidden" name="mname" value="<?= htmlspecialchars($_SESSION['mname'] ?? '') ?>">
  <input type="hidden" name="lname" value="<?= htmlspecialchars($_SESSION['lname'] ?? '') ?>">
</div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Student Email</label>
             <input type="email" name="email" class="form-input w-full px-4 py-3 rounded-xl bg-white"value="<?= htmlspecialchars($_SESSION['email']) ?>" readonly>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Year Level</label>
                   <input type="text" name="grade" class="form-input w-full px-4 py-3 rounded-xl bg-white"value="<?= htmlspecialchars($_SESSION['year_level']) ?>" readonly>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Strand / Course</label>
                  <input type="text" name="section" class="form-input w-full px-4 py-3 rounded-xl bg-white" 
                    value="<?= htmlspecialchars($_SESSION['strand_course']) ?>" readonly>
                </div>
              </div>
            </div>

            <!-- Exam Details -->
            <div class="bg-blue-50 rounded-xl p-6">
              <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-book mr-2 text-blue-600"></i>
                Exam Details
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                  <input type="text" name="subject" class="form-input w-full px-4 py-3 rounded-xl bg-white" 
                    placeholder="Enter subject name" required>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Subject Teacher</label>
                  <input type="text" name="teacher" class="form-input w-full px-4 py-3 rounded-xl bg-white" 
                    placeholder="Enter teacher's name" required>
                </div>
              </div>
            </div>

            <!-- Reason Selection -->
            <div class="bg-purple-50 rounded-xl p-6">
              <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-clipboard-list mr-2 text-purple-600"></i>
                Reason for Special Exam
              </h3>
              
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <label class="reason-card p-4 cursor-pointer">
                  <input type="radio" name="reason" value="Medical Certificate" class="sr-only" onchange="updateReasonSelection(this)">
                  <div class="text-center">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                      <i class="fas fa-heartbeat text-red-600 text-xl"></i>
                    </div>
                    <div class="font-semibold text-gray-800">Medical Certificate</div>
                    <div class="text-sm text-gray-600 mt-1">Health-related absence</div>
                  </div>
                </label>
                
                <label class="reason-card p-4 cursor-pointer">
                  <input type="radio" name="reason" value="Death Certificate" class="sr-only" onchange="updateReasonSelection(this)">
                  <div class="text-center">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                      <i class="fas fa-cross text-gray-600 text-xl"></i>
                    </div>
                    <div class="font-semibold text-gray-800">Death Certificate</div>
                    <div class="text-sm text-gray-600 mt-1">Family bereavement</div>
                  </div>
                </label>
                
                <label class="reason-card p-4 cursor-pointer">
                  <input type="radio" name="reason" value="Conflict Schedule" class="sr-only" onchange="updateReasonSelection(this)">
                  <div class="text-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                      <i class="fas fa-calendar-times text-orange-600 text-xl"></i>
                    </div>
                    <div class="font-semibold text-gray-800">Schedule Conflict</div>
                    <div class="text-sm text-gray-600 mt-1">Time conflict issue</div>
                  </div>
                </label>
              </div>

              <!-- Conflict Details (Hidden by default) -->
              <div id="conflictDetails" style="display: none;" class="animate-fadeInUp">
                <label class="block text-sm font-medium text-gray-700 mb-2">Conflict Schedule Details</label>
                <input type="text" name="conflict_info" class="form-input w-full px-4 py-3 rounded-xl bg-white" 
                  placeholder="e.g. Same time with NSTP, part-time work, etc.">
                <div class="mt-2 p-3 bg-orange-100 rounded-lg">
                  <p class="text-sm text-orange-700">
                    <i class="fas fa-info-circle mr-1"></i>
                    Please provide detailed information about the schedule conflict
                  </p>
                </div>
              </div>
            </div>

            <!-- File Upload -->
            <div class="bg-green-50 rounded-xl p-6">
              <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-upload mr-2 text-green-600"></i>
                Supporting Documents
              </h3>
              
              <div class="file-upload-area p-8 text-center" id="fileUploadArea">
                <input type="file" name="proof" id="fileInput" accept=".pdf,.jpg,.jpeg,.png" required class="hidden">
                <div id="uploadContent">
                  <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-cloud-upload-alt text-blue-600 text-2xl"></i>
                  </div>
                  <h4 class="text-lg font-semibold text-gray-800 mb-2">Upload Supporting Document</h4>
                  <p class="text-gray-600 mb-4">Medical Certificate, Death Certificate, or other proof</p>
                  <button type="button" onclick="document.getElementById('fileInput').click()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium transition-colors">
                    <i class="fas fa-folder-open mr-2"></i>
                    Choose File
                  </button>
                  <p class="text-sm text-gray-500 mt-3">Supported formats: PDF, JPG, JPEG, PNG (Max 10MB)</p>
                </div>
                
                <div id="filePreview" style="display: none;" class="text-left">
                  <div class="flex items-center justify-between bg-white rounded-lg p-4 border">
                    <div class="flex items-center space-x-3">
                      <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file text-green-600"></i>
                      </div>
                      <div>
                        <div class="font-medium text-gray-800" id="fileName"></div>
                        <div class="text-sm text-gray-600" id="fileSize"></div>
                      </div>
                    </div>
                    <button type="button" onclick="removeFile()" class="text-red-600 hover:text-red-700">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center pt-6">
              <button type="submit" class="bg-gradient-to-r from-green-600 to-emerald-700 text-white px-12 py-4 rounded-xl font-semibold hover:from-green-700 hover:to-emerald-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                <i class="fas fa-paper-plane mr-2"></i>
                Submit Special Exam Request
              </button>
              <p class="text-sm text-gray-600 mt-3">
                <i class="fas fa-clock mr-1"></i>
                Your request will be reviewed within 2-3 business days
              </p>
            </div>
          </form>
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

    // Update reason selection
    function updateReasonSelection(radio) {
      // Remove selected class from all cards
      document.querySelectorAll('.reason-card').forEach(card => {
        card.classList.remove('selected');
      });
      
      // Add selected class to current card
      radio.closest('.reason-card').classList.add('selected');
      
      // Show/hide conflict details
      const conflictDetails = document.getElementById('conflictDetails');
      if (radio.value === 'Conflict Schedule') {
        conflictDetails.style.display = 'block';
        conflictDetails.querySelector('input').required = true;
      } else {
        conflictDetails.style.display = 'none';
        conflictDetails.querySelector('input').required = false;
      }
    }

    // File upload handling
    document.getElementById('fileInput').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const fileName = file.name;
        const fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
        
        document.getElementById('fileName').textContent = fileName;
        document.getElementById('fileSize').textContent = fileSize;
        document.getElementById('uploadContent').style.display = 'none';
        document.getElementById('filePreview').style.display = 'block';
      }
    });

    // Remove file
    function removeFile() {
      document.getElementById('fileInput').value = '';
      document.getElementById('uploadContent').style.display = 'block';
      document.getElementById('filePreview').style.display = 'none';
    }

    // Drag and drop functionality
    const fileUploadArea = document.getElementById('fileUploadArea');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
      fileUploadArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
      e.preventDefault();
      e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
      fileUploadArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
      fileUploadArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
      fileUploadArea.classList.add('dragover');
    }

    function unhighlight(e) {
      fileUploadArea.classList.remove('dragover');
    }

    fileUploadArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
      const dt = e.dataTransfer;
      const files = dt.files;
      
      if (files.length > 0) {
        document.getElementById('fileInput').files = files;
        document.getElementById('fileInput').dispatchEvent(new Event('change'));
      }
    }

    // Toggle conflict input (for backward compatibility)
    function toggleConflictInput() {
      const reasonSelect = document.getElementById('reasonSelect');
      const conflictDetails = document.getElementById('conflictDetails');
      
      if (reasonSelect && reasonSelect.value === 'Conflict Schedule') {
        conflictDetails.style.display = 'block';
        conflictDetails.querySelector('input').required = true;
      } else {
        conflictDetails.style.display = 'none';
        conflictDetails.querySelector('input').required = false;
      }
    }
  </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'987c9b4503690dcd',t:'MTc1OTMyODQ4Ny4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
