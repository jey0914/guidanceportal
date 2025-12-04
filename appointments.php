<?php    
session_start();    
if (!isset($_SESSION['email'])) {    
    header("Location: login.php");    
    exit();    
}

include("db.php");

$successMsg = "";
$errorMsg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $student_no = $_POST['student_no'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $grade = $_POST['grade'];
    $section = $_POST['section'];
    $interest = $_POST['interest'];
    $reason = $_POST['reason'];
    $teacher = $_POST['teacher'];

    // Time validation (backend)
    if ($time < "08:00" || $time > "17:00") {
        $errorMsg = "Error: Appointments are only allowed from 8:00 AM to 5:00 PM.";
    } elseif ($time >= "12:00" && $time < "13:00") {
        $errorMsg = "Error: Appointments are not allowed during lunch break (12:00 PM – 1:00 PM).";
    } else {
        $stmt = $con->prepare("INSERT INTO appointments (name, email, student_no, date, time, grade, section, interest, reason, teacher) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $name, $email, $student_no, $date, $time, $grade, $section, $interest, $reason, $teacher);

        if ($stmt->execute()) {
            $successMsg = "Appointment successfully submitted!";
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
  <title>Appointments</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
     body {
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }
    
    /* Enhanced Sidebar styling */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 300px;
      height: 100vh;
      background: linear-gradient(180deg, #0f172a 0%, #1e293b 50%, #334155 100%);
      color: white;
      z-index: 1000;
      box-shadow: 8px 0 32px rgba(0, 0, 0, 0.3);
      border-right: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .sidebar-header {
      padding: 32px 24px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(147, 51, 234, 0.1) 100%);
      position: relative;
      overflow: hidden;
    }
    
    .sidebar-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.05), transparent);
      animation: shimmer 3s ease-in-out infinite;
    }
    
    @keyframes shimmer {
      0% { transform: translateX(-100%); }
      100% { transform: translateX(100%); }
    }
    
    .sidebar-header h2 {
      margin: 0;
      font-size: 1.75rem;
      font-weight: 800;
      display: flex;
      align-items: center;
      gap: 16px;
      position: relative;
      z-index: 1;
    }
    
    .sidebar-header .logo-icon {
      width: 48px;
      height: 48px;
      background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 8px 32px rgba(59, 130, 246, 0.3);
      animation: pulse 2s ease-in-out infinite;
    }
    
    .sidebar-header .subtitle {
      font-size: 0.875rem;
      color: rgba(255, 255, 255, 0.7);
      margin-top: 8px;
      font-weight: 500;
      position: relative;
      z-index: 1;
    }
    
    .sidebar-nav {
      padding: 24px 0;
      height: calc(100vh - 140px);
      overflow-y: auto;
    }
    
    .sidebar ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    
    .sidebar li {
      margin-bottom: 8px;
      padding: 0 16px;
    }
    
    .sidebar a {
      display: flex;
      align-items: center;
      padding: 16px 20px;
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      border-radius: 16px;
      position: relative;
      font-weight: 500;
      overflow: hidden;
    }
    
    .sidebar a::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .sidebar a:hover::before {
      opacity: 1;
    }
    
    .sidebar a:hover {
      color: white;
      transform: translateX(8px) scale(1.02);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    }
    
    .sidebar a.active {
      background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
      color: white;
      box-shadow: 0 8px 32px rgba(59, 130, 246, 0.4);
      transform: translateX(4px);
    }
    
    .sidebar a.active::after {
      content: '';
      position: absolute;
      right: -16px;
      top: 50%;
      transform: translateY(-50%);
      width: 4px;
      height: 24px;
      background: linear-gradient(180deg, #3b82f6 0%, #8b5cf6 100%);
      border-radius: 2px;
    }
    
    .sidebar a i {
      width: 24px;
      margin-right: 16px;
      font-size: 1.2rem;
      text-align: center;
    }
    
    .appointment-card {
      background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
      border: 1px solid #e2e8f0;
      transition: all 0.3s ease;
    }
    
    .appointment-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
    }
    
    .category-card {
      background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
      border-radius: 16px;
      border: 2px solid transparent;
      transition: all 0.3s ease;
      cursor: pointer;
    }
    
    .category-card:hover {
      border-color: #3b82f6;
      transform: translateY(-2px);
      box-shadow: 0 12px 40px rgba(59, 130, 246, 0.15);
    }
    
    .category-card.selected {
      border-color: #3b82f6;
      background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
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
    
    .success-message {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
      transform: translateX(400px);
      transition: transform 0.3s ease;
    }
    
    .success-message.show {
      transform: translateX(0);
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
    
    .quote-card {
      background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
      border-radius: 16px;
      border: 1px solid #bae6fd;
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
            <h1 class="text-3xl font-bold text-gray-800">📅 Book Appointment</h1>
            <p class="text-gray-600 mt-1">Schedule your counseling session with our guidance team</p>
          </div>
          
          <div class="flex items-center space-x-4">
            <a href="view_appointments.php" class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-xl font-medium transition-colors">
              <i class="fas fa-list mr-2"></i>
              View Appointments
            </a>
            
            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
              <i class="fas fa-user text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </header>

    <div class="p-8">

      <!-- Appointment Type Selection -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="appointment-card p-8 animate-fadeInUp">
          <div class="text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-calendar-plus text-blue-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Regular Appointment</h3>
            <p class="text-gray-600 mb-6">Schedule a consultation with our guidance counselors</p>
            <button onclick="showForm('appointment')" class="bg-gradient-to-r from-blue-600 to-purple-700 text-white px-8 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
              <i class="fas fa-plus mr-2"></i>
              Book Now
            </button>
          </div>
        </div>
        
        <div class="appointment-card p-8 animate-fadeInUp" style="animation-delay: 0.1s;">
          <div class="text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-file-alt text-green-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Special Exam Form</h3>
            <p class="text-gray-600 mb-6">Request for special examination arrangements</p>
            <a href="special_exam.php" class="bg-gradient-to-r from-green-600 to-teal-700 text-white px-8 py-3 rounded-xl font-semibold hover:from-green-700 hover:to-teal-800 transition-all duration-300 transform hover:scale-105 shadow-lg inline-block">
              <i class="fas fa-file-medical mr-2"></i>
              Apply Now
            </a>
          </div>
        </div>
      </div>

      <!-- Appointment Form -->
      <div id="appointmentForm" style="display:none;" class="animate-fadeInUp">
        <div class="appointment-card p-8">
          <div class="flex items-center space-x-3 mb-8">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
              <i class="fas fa-form text-blue-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Appointment Details</h2>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Section -->
            <div class="lg:col-span-2">
              <form action="add_appointment.php" method="POST" class="space-y-6">
                <!-- Personal Information -->
                <div class="bg-gray-50 rounded-xl p-6">
                  <h3 class="text-lg font-semibold text-gray-800 mb-4">Personal Information</h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                      <input type="text" name="name" class="form-control"value="<?= htmlspecialchars(($_SESSION['fname'] ?? '') . ' ' . ucfirst($_SESSION['lname'] ?? '')) ?>" readonly>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                       <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_SESSION['email']) ?>" readonly>                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Student Number</label>
                     <input type="text" name="student_no" class="form-control" value="<?= htmlspecialchars($_SESSION['student_no']) ?>" readonly></div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Year Level</label>
                      <input type="text" name="grade" class="form-control" value="<?= htmlspecialchars($_SESSION['year_level']) ?>" readonly>
                    </div>
                    <div class="md:col-span-2">
                      <label class="block text-sm font-medium text-gray-700 mb-2">Course/Strand</label>
                      <input type="text" name="section" class="form-control" value="<?= htmlspecialchars($_SESSION['strand_course']) ?>" readonly>
                    </div>
                  </div>
                </div>

                <!-- Appointment Schedule -->
                <div class="bg-blue-50 rounded-xl p-6">
                  <h3 class="text-lg font-semibold text-gray-800 mb-4">Schedule</h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Date</label>
                      <input type="date" name="date" class="form-input w-full px-4 py-3 rounded-xl bg-white" required>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Time</label>
                      <input type="time" name="time" class="form-input w-full px-4 py-3 rounded-xl bg-white" required min="08:00" max="17:00">
                    </div>
                  </div>
                  <div class="mt-3 p-3 bg-blue-100 rounded-lg">
                    <p class="text-sm text-blue-700">
                      <i class="fas fa-info-circle mr-1"></i>
                      Office hours: 8:00 AM - 5:00 PM, Monday to Friday
                    </p>
                  </div>
                </div>

                <!-- Consultation Details -->
                <div class="bg-purple-50 rounded-xl p-6">
                  <h3 class="text-lg font-semibold text-gray-800 mb-4">Consultation Details</h3>
                  
                  <div class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-3">Consultation Category</label>
                      <select id="mainInterest" name="interest" class="form-input w-full px-4 py-3 rounded-xl bg-white" onchange="toggleSubCategory()" required>
                        <option value="">-- Select Category --</option>
                        <option value="Personal">🧠 Personal</option>
                        <option value="Academic">📚 Academic</option>
                        <option value="Behavioral">⚖️ Behavioral</option>
                        <option value="Career">💼 Career</option>
                        <option value="Others">❓ Others</option>
                      </select>
                    </div>

                    <div id="subInterestDiv" style="display:none;">
                      <label class="block text-sm font-medium text-gray-700 mb-3">Specific Concern</label>
                      <select name="specific_concern" id="interest" class="form-input w-full px-4 py-3 rounded-xl bg-white" required>
                        <option value="">--Adjustments--</option>
                      </select>
                    </div>

                    
              <div>
  <label class="block text-sm font-medium text-gray-700 mb-3">
    Teacher (Optional)
  </label>
  <input 
    type="text" 
    name="teacher" 
    class="form-input w-full px-4 py-3 rounded-xl bg-white" 
    placeholder="Enter teacher's name (optional)"
  >
  <p class="text-xs text-gray-500 mt-2 italic">
    Note: Leave this blank if your concern is not subject-related or if no specific teacher is required.
  </p>
</div>


                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-3">Reason for Consultation</label>
                      <textarea name="reason" class="form-input w-full px-4 py-3 rounded-xl bg-white resize-none" placeholder="Please describe what you'd like to discuss or any specific concerns you have..." rows="4" required></textarea>
                    </div>
                  </div>
                </div>


                <!-- Submit Button -->
                <div class="text-center pt-6">
                  <button type="submit" class="bg-gradient-to-r from-green-600 to-emerald-700 text-white px-12 py-4 rounded-xl font-semibold hover:from-green-700 hover:to-emerald-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Submit Appointment Request
                  </button>
                  <p class="text-sm text-gray-600 mt-3">
                    <i class="fas fa-clock mr-1"></i>
                    You'll receive a confirmation email within 24 hours
                  </p>
                </div>
              </form>
            </div>

            <!-- Quote Section -->
            <div class="lg:col-span-1">
              <div class="quote-card p-6 h-fit sticky top-24">
                <div class="text-center">
                  <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-quote-left text-white"></i>
                  </div>
                  <blockquote class="text-lg font-medium text-gray-800 mb-4" id="quoteText">
                    "Small steps every day lead to big results."
                  </blockquote>
                  <footer class="text-sm text-gray-600" id="quoteAuthor">- Unknown</footer>
                </div>
                
                <div class="mt-8 space-y-4">
                  <div class="bg-white/50 rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                      <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-shield-alt text-green-600"></i>
                      </div>
                      <div>
                        <div class="font-semibold text-gray-800">Confidential</div>
                        <div class="text-sm text-gray-600">Your privacy is protected</div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="bg-white/50 rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                      <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-md text-blue-600"></i>
                      </div>
                      <div>
                        <div class="font-semibold text-gray-800">Professional</div>
                        <div class="text-sm text-gray-600">Licensed counselors</div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="bg-white/50 rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                      <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-heart text-purple-600"></i>
                      </div>
                      <div>
                        <div class="font-semibold text-gray-800">Supportive</div>
                        <div class="text-sm text-gray-600">Non-judgmental environment</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Success Message -->
  <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class="success-message show bg-green-500 text-white px-6 py-4 rounded-xl shadow-2xl" id="successMessage">
      <div class="flex items-center space-x-3">
        <i class="fas fa-check-circle text-xl"></i>
        <div>
          <div class="font-semibold">Appointment Submitted!</div>
          <div class="text-sm opacity-90">We'll contact you within 24 hours.</div>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <script>
    // Success message handling
    const successBox = document.getElementById('successMessage');
    if (successBox) {
      setTimeout(() => {
        successBox.classList.remove('show');
      }, 5000);
    }

    // Show appointment form
    function showForm(type) {
      if (type === 'appointment') {
        document.getElementById('appointmentForm').style.display = 'block';
        document.getElementById('appointmentForm').scrollIntoView({ 
          behavior: 'smooth',
          block: 'start'
        });
      }
    }

    // Toggle subcategory dropdown
    function toggleSubCategory() {
      const main = document.getElementById("mainInterest").value;
      const subDiv = document.getElementById("subInterestDiv");
      const sub = document.getElementById("interest");

      sub.innerHTML = '<option value="">-- Adjustments --</option>';
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

    // Rotating quotes
    const quotes = [
      { text: "Small steps every day lead to big results.", author: "Unknown" },
      { text: "Your mental health is just as important as your physical health.", author: "Mental Health Advocate" },
      { text: "It's okay to not be okay. What's not okay is staying that way.", author: "Anonymous" },
      { text: "Healing takes time, and asking for help is a courageous step.", author: "Guidance Counselor" },
      { text: "You are stronger than you think and more capable than you imagine.", author: "Motivational Speaker" }
    ];

    let currentQuote = 0;
    function rotateQuote() {
      const quoteText = document.getElementById('quoteText');
      const quoteAuthor = document.getElementById('quoteAuthor');
      
      currentQuote = (currentQuote + 1) % quotes.length;
      
      quoteText.style.opacity = '0';
      quoteAuthor.style.opacity = '0';
      
      setTimeout(() => {
        quoteText.textContent = `"${quotes[currentQuote].text}"`;
        quoteAuthor.textContent = `- ${quotes[currentQuote].author}`;
        quoteText.style.opacity = '1';
        quoteAuthor.style.opacity = '1';
      }, 300);
    }

    // Rotate quotes every 10 seconds
    setInterval(rotateQuote, 10000);

    // Set minimum date to today
    document.addEventListener('DOMContentLoaded', function() {
      const dateInput = document.querySelector('input[name="date"]');
      if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
      }
    });
  </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'987c2e6eb4f10dcd',t:'MTc1OTMyNDAyOS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
