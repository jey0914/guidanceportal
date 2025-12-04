<?php
session_start();
include("db.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];

// ✅ Student info
$student_query = "SELECT * FROM form WHERE email = ?";
$student_stmt = $con->prepare($student_query);
$student_stmt->bind_param("s", $email);
$student_stmt->execute();
$student_result = $student_stmt->get_result();
$student = $student_result->fetch_assoc();

// ✅ Exit interview info
$exit_query = "SELECT * FROM exit_interviews WHERE email = ?";
$exit_stmt = $con->prepare($exit_query);
$exit_stmt->bind_param("s", $email);
$exit_stmt->execute();
$exit_result = $exit_stmt->get_result();
$exit_completed = $exit_result->num_rows > 0;

// ✅ Get scheduled date if interview exists
$scheduled_date = '';
if ($exit_completed) {
    $exit_data = $exit_result->fetch_assoc();
    $scheduled_date = $exit_data['scheduled_date'] ?? '';
}

// ✅ Certificates
$cert_query = "SELECT * FROM certificate_requests WHERE email = ?";
$cert_stmt = $con->prepare($cert_query);
$cert_stmt->bind_param("s", $email);
$cert_stmt->execute();
$cert_result = $cert_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exit Interview Form - Guidance Portal</title>
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
            width: 260px;
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

        /* Form styling */
        .form-section {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .form-input {
            background: rgba(255, 255, 255, 0.8);
            border: 2px solid rgba(59, 130, 246, 0.2);
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .submit-btn {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            color: white;
            border: none;
            border-radius: 16px;
            padding: 16px 32px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 32px rgba(59, 130, 246, 0.3);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(59, 130, 246, 0.4);
        }

        /* Modal styling */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: white;
            border-radius: 24px;
            padding: 2rem;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            margin: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transform: scale(0.9) translateY(20px);
            transition: all 0.3s ease;
        }

        .modal-overlay.active .modal-content {
            transform: scale(1) translateY(0);
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Instructions Modal -->
    <div id="instructionsModal" class="modal-overlay active">
        <div class="modal-content">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                    Exit Interview Instructions
                </h2>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="space-y-4 text-gray-700 leading-relaxed">
                <p class="font-semibold text-blue-600">The Exit Interview Form is an important process that helps the Guidance Office understand your reasons for leaving, transferring, or completing your program. To ensure your request is processed smoothly, please follow these instructions carefully:</p>
                
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-blue-800 mb-2">📝 Student Information</h3>
                    <p>First, make sure your student information is complete and accurate. This includes your student number, full name, grade or year level, section, and email address. Providing accurate information will help the Guidance Office identify your record correctly.</p>
                </div>
                
                <div class="bg-green-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-green-800 mb-2">🎯 Reason for Exit</h3>
                    <p>Next, indicate the reason for your exit interview. Select the option that best describes your situation, such as graduation, school transfer, dropping out, completion of program requirements, or clearance. If your reason does not match any of the provided choices, choose "Others" and clearly explain your reason in the space provided.</p>
                </div>
                
                <div class="bg-purple-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-purple-800 mb-2">📅 Schedule Interview</h3>
                    <p>After specifying the reason, please select your preferred date and time for the interview. This helps the Guidance Office schedule your exit interview according to your availability. Make sure the date you choose gives you enough time to prepare and coordinate with your counselor.</p>
                </div>
                
                <div class="bg-orange-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-orange-800 mb-2">💬 Additional Comments</h3>
                    <p>You may also include additional comments or concerns related to your exit interview. This is optional but can be helpful if you have specific issues you want to discuss during your interview.</p>
                </div>
                
                <div class="bg-red-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-red-800 mb-2">✅ Final Review</h3>
                    <p>Finally, review all the information you have entered to ensure it is complete and accurate. Once you are satisfied, click the Submit button to send your request. After submission, your exit interview request will be recorded and the status will be marked as "For Scheduling." You will be notified once your interview has been scheduled.</p>
                </div>
                
                <p class="font-semibold text-gray-800 bg-yellow-50 p-4 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                    Remember, submitting this form is the first step in completing your exit interview process, so please provide honest and thorough information.
                </p>
            </div>
            
            <div class="flex justify-center mt-6">
                <button onclick="closeModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                    <i class="fas fa-check mr-2"></i>I Understand, Continue
                </button>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo-icon">
                <i class="fas fa-graduation-cap text-white"></i>
            </div>
            <div>
                <h2>Guidance Portal</h2>
                <div class="subtitle">Student Dashboard</div>
            </div>
        </div>
        
        <div class="sidebar-nav">
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-home"></i>Dashboard</a></li>
                <li><a href="student_records.php"><i class="fas fa-clipboard-check"></i>Attendance</a></li>
                <li><a href="appointments.php"><i class="fas fa-calendar-check"></i>Appointments</a></li>
                <li><a href="student_reports.php"><i class="fas fa-file-alt"></i>Reports</a></li>
                <li><a href="settings.php"><i class="fas fa-cog"></i>Settings</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="ml-[260px] p-6">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-800 mb-2">Exit Interview Form</h1>
                    <p class="text-gray-600">Complete your exit interview request to begin the process</p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="showModal()" class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-info-circle mr-2"></i>Instructions
                    </button>
                    <div class="bg-green-100 text-green-700 px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-user-check mr-2"></i>Student Portal
                    </div>
                </div>
            </div>
        </div>

        <!-- Exit Interview Form -->
      <form id="exitInterviewForm" class="space-y-6" method="POST" action="submit_exit_interview.php">
    <!-- Student Information Section -->
    <div class="form-section">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-user-circle text-blue-500 mr-3"></i>
            Student Information
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Student Number *</label>
                <input type="text" name="student_no" class="form-input w-full" value="<?= $student['student_no'] ?>" readonly required>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                <input type="text" name="full_name" class="form-input w-full" 
       value="<?= $student['lname'] . ', ' . $student['fname'] . 
                 (!empty($student['mname']) && strtoupper($student['mname']) !== 'N/A' 
                   ? ' ' . $student['mname'] 
                   : '') ?>" 
       readonly required>

            </div>
            
            <div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">Grade/Year Level *</label>
    <input type="text" name="year_level" class="form-input w-full" value="<?= $student['year_level'] ?>" readonly required>
</div>
            
            <div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">Section/Course *</label>
    <input type="text" name="strand_course" class="form-input w-full" value="<?= $student['strand_course'] ?>" readonly required>
</div>

            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                <input type="email" name="email" class="form-input w-full" value="<?= $student['email'] ?>" readonly required>
            </div>
        </div>
    </div>

            <!-- Exit Reason Section -->
            <div class="form-section">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-clipboard-list text-green-500 mr-3"></i>
                    Reason for Exit Interview
                </h2>
                
                <div class="space-y-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Select your reason *</label>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-300 cursor-pointer transition-colors">
                            <input type="radio" name="exit_reason" value="Graduation" class="mr-3 text-blue-500">
                            <div>
                                <div class="font-semibold text-gray-800">🎓 Graduation</div>
                                <div class="text-sm text-gray-600">Completing program requirements</div>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-300 cursor-pointer transition-colors">
                            <input type="radio" name="exit_reason" value="School Transfer" class="mr-3 text-blue-500">
                            <div>
                                <div class="font-semibold text-gray-800">🏫 School Transfer</div>
                                <div class="text-sm text-gray-600">Moving to another institution</div>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-300 cursor-pointer transition-colors">
                            <input type="radio" name="exit_reason" value="Dropping Out" class="mr-3 text-blue-500">
                            <div>
                                <div class="font-semibold text-gray-800">📚 Dropping Out</div>
                                <div class="text-sm text-gray-600">Discontinuing studies</div>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-300 cursor-pointer transition-colors">
                            <input type="radio" name="exit_reason" value="Clearance" class="mr-3 text-blue-500">
                            <div>
                                <div class="font-semibold text-gray-800">✅ Clearance</div>
                                <div class="text-sm text-gray-600">Administrative clearance</div>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-300 cursor-pointer transition-colors md:col-span-2">
                            <input type="radio" name="exit_reason" value="Others" class="mr-3 text-blue-500" onchange="toggleOtherReason()">
                            <div>
                                <div class="font-semibold text-gray-800">📝 Others</div>
                                <div class="text-sm text-gray-600">Please specify your reason</div>
                            </div>
                        </label>
                    </div>
                    
                    <div id="otherReasonDiv" class="hidden mt-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Please specify your reason *</label>
                        <textarea name="other_reason" class="form-input w-full h-24" placeholder="Please explain your reason for the exit interview..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Interview Schedule Section -->
            <div class="form-section">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-calendar-alt text-purple-500 mr-3"></i>
                    Preferred Interview Schedule
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Preferred Date *</label>
                        <input type="date" name="preferred_date" class="form-input w-full" required min="">
                        <div class="text-sm text-gray-500 mt-1">Choose a date at least 3 days from today</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Preferred Time *</label>
                        <select name="preferred_time" class="form-input w-full" required>
                            <option value="">Select Time Slot</option>
                            <option value="08:00 AM">08:00 AM - 09:00 AM</option>
                            <option value="09:00 AM">09:00 AM - 10:00 AM</option>
                            <option value="10:00 AM">10:00 AM - 11:00 AM</option>
                            <option value="11:00 AM">11:00 AM - 12:00 PM</option>
                            <option value="01:00 PM">01:00 PM - 02:00 PM</option>
                            <option value="02:00 PM">02:00 PM - 03:00 PM</option>
                            <option value="03:00 PM">03:00 PM - 04:00 PM</option>
                            <option value="04:00 PM">04:00 PM - 05:00 PM</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Additional Comments Section -->
            <div class="form-section">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-comment-dots text-orange-500 mr-3"></i>
                    Additional Comments
                </h2>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Comments or Concerns (Optional)</label>
                    <textarea name="comments" class="form-input w-full h-32" placeholder="Share any specific concerns, questions, or topics you'd like to discuss during your exit interview..."></textarea>
                    <div class="text-sm text-gray-500 mt-1">This information helps us prepare for your interview</div>
                </div>
            </div>

            <!-- Submit Section -->
            <div class="form-section text-center">
                <div class="bg-blue-50 p-6 rounded-lg mb-6">
                    <div class="flex items-center justify-center mb-4">
                        <i class="fas fa-info-circle text-blue-500 text-2xl mr-3"></i>
                        <h3 class="text-lg font-semibold text-blue-800">Before You Submit</h3>
                    </div>
                    <p class="text-blue-700 mb-4">Please review all information carefully. After submission, your request will be marked as "For Scheduling" and you'll be notified once your interview is confirmed.</p>
                    <div class="flex items-center justify-center text-sm text-blue-600">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Your information is secure and confidential
                    </div>
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Submit Exit Interview Request
                </button>
            </div>
        </form>
    </div>

   <script>
    // Set minimum date to 3 days from today
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.querySelector('input[name="preferred_date"]');
        const today = new Date();
        today.setDate(today.getDate() + 3);
        dateInput.min = today.toISOString().split('T')[0];

        // ✅ Auto-combine names into full_name field
        const fnameInput = document.querySelector('input[name="fname"]');
        const mnameInput = document.querySelector('input[name="mname"]');
        const lnameInput = document.querySelector('input[name="lname"]');
        const fullNameInput = document.querySelector('input[name="full_name"]');

        if(fnameInput && mnameInput && lnameInput && fullNameInput){
            [fnameInput, mnameInput, lnameInput].forEach(input => {
                input.addEventListener('input', () => {
                    const fname = fnameInput.value.trim();
                    const mname = mnameInput.value.trim();
                    const lname = lnameInput.value.trim();
                    fullNameInput.value = `${lname}, ${fname}${mname ? ' ' + mname.charAt(0) + '.' : ''}`;
                });
            });
        }
    });

    // Modal functions
    function showModal() {
        document.getElementById('instructionsModal').classList.add('active');
    }

    function closeModal() {
        document.getElementById('instructionsModal').classList.remove('active');
    }

    // Toggle other reason textarea
    function toggleOtherReason() {
        const otherDiv = document.getElementById('otherReasonDiv');
        const otherRadio = document.querySelector('input[value="Others"]');
        
        if (otherRadio.checked) {
            otherDiv.classList.remove('hidden');
            document.querySelector('textarea[name="other_reason"]').required = true;
        } else {
            otherDiv.classList.add('hidden');
            document.querySelector('textarea[name="other_reason"]').required = false;
        }
    }

    // Handle radio button changes
    document.querySelectorAll('input[name="exit_reason"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value !== 'Others') {
                document.getElementById('otherReasonDiv').classList.add('hidden');
                document.querySelector('textarea[name="other_reason"]').required = false;
            }
        });
    });

    // Form submission
    document.getElementById('exitInterviewForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        // Validate required fields
        const requiredFields = ['student_no', 'full_name', 'year_level', 'strand_course', 'email', 'exit_reason', 'preferred_date', 'preferred_time'];
        const missingFields = requiredFields.filter(field => !data[field]);
        
        if (missingFields.length > 0) {
            showNotification('Please fill in all required fields marked with *', 'error');
            return;
        }
        
        // Check if "Others" is selected and other_reason is provided
        if (data.exit_reason === 'Others' && !data.other_reason) {
            showNotification('Please specify your reason when selecting "Others"', 'error');
            return;
        }
        
        // Show confirmation
        showConfirmation(data);
    });

    function showConfirmation(data) {
        const confirmModal = document.createElement('div');
        confirmModal.className = 'modal-overlay active';
        confirmModal.innerHTML = `
            <div class="modal-content">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    Confirm Exit Interview Request
                </h2>
                
                <div class="space-y-4 mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-2">Student Information</h3>
                        <p><strong>Name:</strong> ${data.full_name}</p>
                        <p><strong>Student Number:</strong> ${data.student_no}</p>
                        <p><strong>Year/Section:</strong> ${data.year_level} - ${data.strand_course}</p>
                    </div>
                    
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-blue-800 mb-2">Exit Reason</h3>
                        <p>${data.exit_reason === 'Others' ? data.other_reason : data.exit_reason}</p>
                    </div>
                    
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-purple-800 mb-2">Preferred Schedule</h3>
                        <p><strong>Date:</strong> ${new Date(data.preferred_date).toLocaleDateString()}</p>
                        <p><strong>Time:</strong> ${data.preferred_time}</p>
                    </div>
                </div>
                
                <div class="flex justify-center space-x-4">
                    <button onclick="this.closest('.modal-overlay').remove()" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button onclick="submitFormServer()" class="px-6 py-3 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition-colors">
                        <i class="fas fa-check mr-2"></i>Confirm & Submit
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(confirmModal);
    }

    // Submit to server via fetch
    function submitFormServer() {
        const form = document.getElementById('exitInterviewForm');
        const formData = new FormData(form);

        fetch('submit_exit_interview.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            showNotification(result.message, result.status);
            
            if(result.status === 'success'){
                form.reset();
                document.getElementById('otherReasonDiv').classList.add('hidden');
                document.querySelector('.modal-overlay').remove();

                // Reset minimum date
                const dateInput = document.querySelector('input[name="preferred_date"]');
                const today = new Date();
                today.setDate(today.getDate() + 3);
                dateInput.min = today.toISOString().split('T')[0];
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('There was an error submitting the form. Please try again.', 'error');
        });
    }

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-md ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-3"></i>
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            e.target.classList.remove('active');
        }
    });
</script>
