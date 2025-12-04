<?php
session_start();
include 'db.php';

$id = $_GET['id'];
$sql = "SELECT * FROM appointments WHERE id=?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo "Appointment not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Appointment - GuidanceHub</title>
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
    
    .form-input {
      transition: all 0.3s ease;
      border: 2px solid #e2e8f0;
    }
    
    .form-input:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
      outline: none;
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
            <h1 class="text-3xl font-bold text-gray-800">✏️ Edit Appointment</h1>
            <p class="text-gray-600 mt-1">Update your appointment details</p>
          </div>
          
          <div class="flex items-center space-x-4">
            <a href="view_appointments.php" class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-xl font-medium transition-colors">
              <i class="fas fa-arrow-left mr-2"></i>
              Back to Appointments
            </a>
            
            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
              <i class="fas fa-user text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </header>

    <div class="p-8">
      <div class="max-w-4xl mx-auto">
        <div class="form-card p-8 animate-fadeInUp">
          <div class="flex items-center space-x-3 mb-8">
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
              <i class="fas fa-edit text-orange-600 text-xl"></i>
            </div>
            <div>
              <h2 class="text-2xl font-bold text-gray-800">Update Appointment Details</h2>
              <p class="text-gray-600">Make changes to your scheduled appointment</p>
            </div>
          </div>

          <form action="update_appointment.php" method="POST" class="space-y-8">
            <input type="hidden" name="id" value="<?= $data['id'] ?>">
            
            <!-- Personal Information -->
            <div class="bg-gray-50 rounded-xl p-6">
              <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-user mr-2 text-blue-600"></i>
                Personal Information
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                  <input type="text" name="name" class="form-input w-full px-4 py-3 rounded-xl bg-white" 
                    value="<?= htmlspecialchars($data['name']) ?>" readonly>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                  <input type="email" name="email" class="form-input w-full px-4 py-3 rounded-xl bg-white" 
                    value="<?= htmlspecialchars($data['email']) ?>" readonly>
                </div>
              </div>
            </div>

            <!-- Schedule Information -->
            <div class="bg-blue-50 rounded-xl p-6">
              <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-calendar mr-2 text-blue-600"></i>
                Schedule Details
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Appointment Date</label>
                  <input type="date" name="date" class="form-input w-full px-4 py-3 rounded-xl bg-white" 
                    value="<?= $data['date'] ?>" required>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Appointment Time</label>
                  <input type="time" name="time" class="form-input w-full px-4 py-3 rounded-xl bg-white" 
                    value="<?= $data['time'] ?>" required min="08:00" max="17:00">
                </div>
              </div>
              <div class="mt-3 p-3 bg-blue-100 rounded-lg">
                <p class="text-sm text-blue-700">
                  <i class="fas fa-info-circle mr-1"></i>
                  Office hours: 8:00 AM - 5:00 PM, Monday to Friday
                </p>
              </div>
            </div>

            <!-- Academic Information -->
            <div class="bg-purple-50 rounded-xl p-6">
              <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-graduation-cap mr-2 text-purple-600"></i>
                Academic Information
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Year Level</label>
                  <input type = "text" name = "grade" class="form-input w-full px-4 py-3 rounded-xl bg-white" value="<?= htmlspecialchars($data['grade']) ?>" readonly>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Strand / Course</label>
                  <input type = "text" name ="section" class="form-input w-full px-4 py-3 rounded-xl bg-white" value="<?= htmlspecialchars($data['section']) ?>" readonly>
                    
                </div>
              </div>
            </div>

            <!-- Consultation Details -->
            <div class="bg-green-50 rounded-xl p-6">
              <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-comments mr-2 text-green-600"></i>
                Consultation Details
              </h3>
              
              <div class="space-y-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Consultation Interest</label>
                  <select name="interest" id="mainInterest" class="form-input w-full px-4 py-3 rounded-xl bg-white" onchange="toggleSubCategory()" required>
                    <option value="<?= htmlspecialchars($data['interest']) ?>" selected><?= htmlspecialchars($data['interest']) ?></option>
                    <option value="Personal">Personal</option>
                    <option value="Academic"> Academic</option>
                    <option value="Behavioral"> Behavioral</option>
                    <option value="Career">Career</option>
                    <option value="Others"> Others</option>
                  </select>
                </div>

                <div id="subInterestDiv" style="display:none;">
                  <label class="block text-sm font-medium text-gray-700 mb-2">Specific Concern</label>
                  <select name="specific_concern" id="interest" class="form-input w-full px-4 py-3 rounded-xl bg-white">
                    <option value="">-- Select Concern --</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Consultation</label>
                  <textarea name="reason" class="form-input w-full px-4 py-3 rounded-xl bg-white resize-none" 
                    rows="4" placeholder="Please describe what you'd like to discuss..." required><?= htmlspecialchars($data['reason']) ?></textarea>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6">
              <a href="view_appointments.php" 
                 class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-8 py-3 rounded-xl font-semibold transition-colors">
                <i class="fas fa-times mr-2"></i>
                Cancel
              </a>
              
              <button type="submit" 
                      class="bg-gradient-to-r from-green-600 to-emerald-700 text-white px-12 py-3 rounded-xl font-semibold hover:from-green-700 hover:to-emerald-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                <i class="fas fa-save mr-2"></i>
                Update Appointment
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Toggle subcategory dropdown
    function toggleSubCategory() {
      const main = document.getElementById("mainInterest").value;
      const subDiv = document.getElementById("subInterestDiv");
      const sub = document.getElementById("interest");

      sub.innerHTML = '<option value="">-- Select Concern --</option>';
      let options = [];

      if (main === "Personal") {
        options = ["Body Image", "Financial and Money", "Gender Issues", "Goal Setting", "Pregnancy", "Religious and Spirit", "Self-Confidence", "Self-Esteem", "Study Habit", "Time Management", "Lack of Interest", "Loss of Motivation", "Eating Concern", "Sleep Pattern Concern", "Legal Concerns"];
      } else if (main === "Academic") {
        options = ["Attendance", "Learning Disability", "Grades Thesis", "Adjustment", "Compliance to School", "Perfectionism", "Repeating year level", "Underachievement"];
      } else if (main === "Behavioral") {
        options = ["Disciplinary", "Handbook", "School Process"];
      } else if (main === "Career") {
        options = ["Career Shift", "Decision Making", "School Transfer", "Work", "Career Planning"];
      }

      options.forEach(function(opt) {
        const o = document.createElement("option");
        o.value = opt;
        o.text = opt;
        sub.appendChild(o);
      });

      if (options.length > 0) {
        subDiv.style.display = "block";
        subDiv.classList.add('animate-fadeInUp');
      } else {
        subDiv.style.display = "none";
      }
    }

    // Set minimum date to today
    document.addEventListener('DOMContentLoaded', function() {
      const dateInput = document.querySelector('input[name="date"]');
      if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
      }
    });
  </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'987cc78294990dcd',t:'MTc1OTMzMDI5OS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
