<?php
include("db.php");

$error = "";
$success = "";

// Siguraduhin na POST method at may 'final_submit'
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['final_submit'])) {

    // Safely get all POST values
    $fullname = isset($_POST['parent_name']) ? trim($_POST['parent_name']) : "";
    $email = isset($_POST['parent_email']) ? trim($_POST['parent_email']) : "";
    $contact = isset($_POST['parent_contact']) ? trim($_POST['parent_contact']) : "";
    $relationship = isset($_POST['relationship']) ? trim($_POST['relationship']) : "";
    $password_raw = isset($_POST['password']) ? trim($_POST['password']) : "";
    $confirm_pass = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : "";
    $student_id = isset($_POST['student_no']) ? trim($_POST['student_no']) : "";
    // Validate Student Number
if (!preg_match('/^\d{10}$/', $student_id)) {
    $error = "Student Number must be exactly 11 digits.";
}
    $student_fname = isset($_POST['student_fname']) ? strtolower(trim($_POST['student_fname'])) : "";
    $student_mname = isset($_POST['student_mname']) ? strtolower(trim($_POST['student_mname'])) : "";
    $student_lname = isset($_POST['student_lname']) ? strtolower(trim($_POST['student_lname'])) : "";
    $student_bday = isset($_POST['student_bday']) ? $_POST['student_bday'] : "";

    if (empty($student_mname)) {
        $student_mname = "n/a";
    }

    // 1️⃣ Validate proper email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // 2️⃣ Check if domain exists (MX record)
        $email_domain = substr(strrchr($email, "@"), 1);
        if (!checkdnsrr($email_domain, "MX")) {
            $error = "Email domain does not exist.";
        }

        // 3️⃣ Restrict to allowed domains
        $allowed_domains = ['gmail.com','yahoo.com','outlook.com','icloud.com','hotmail.com'];
        if (!in_array($email_domain, $allowed_domains)) {
            $error = "Please use a real email provider.";
        }
    }

    // Only proceed if no email errors
    if (empty($error)) {

        // Password checks
        if ($password_raw !== $confirm_pass) {
            $error = "Passwords do not match.";
        } elseif (strlen($password_raw) < 8) {
            $error = "Password must be at least 8 characters long.";
        } elseif (!preg_match('/[A-Z]/', $password_raw) || 
                !preg_match('/[a-z]/', $password_raw) || 
                !preg_match('/[0-9]/', $password_raw) || 
                !preg_match('/[^A-Za-z0-9]/', $password_raw)) {
            $error = "Password must include uppercase, lowercase, number, and symbol.";
        } else {
            $password = password_hash($password_raw, PASSWORD_DEFAULT);

            // Validate student
            $stmt = $con->prepare("SELECT * FROM form WHERE student_no = ? AND bday = ?");
            $stmt->bind_param("ss", $student_id, $student_bday);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $student = $result->fetch_assoc();

                $actual_fname = strtolower($student['fname']);
                $actual_mname = strtolower($student['mname']);
                $actual_lname = strtolower($student['lname']);

                if ($student_fname === $actual_fname && $student_mname === $actual_mname && $student_lname === $actual_lname) {
                    // Check for duplicate email
                    $check_email = $con->prepare("SELECT * FROM parents WHERE email = ?");
                    $check_email->bind_param("s", $email);
                    $check_email->execute();
                    $check_result = $check_email->get_result();

                    if ($check_result->num_rows > 0) {
                        $error = "This email is already registered.";
                    } else {
                        $student_fullname = $student_fname . " " . $student_mname . " " . $student_lname;
                        $insert = $con->prepare("INSERT INTO parents (fullname, email, contact, password, relationship, student_id, student_name, student_birthday) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                        $insert->bind_param("ssssssss", $fullname, $email, $contact, $password, $relationship, $student_id, $student_fullname, $student_bday);

                        if ($insert->execute()) {
                            $success = "🎉 You're registered successfully!";
                        } else {
                            $error = "Error: Failed to create account.";
                        }
                    }
                } else {
                    $error = "Student name does not match our records.";
                }
            } else {
                $error = "No student found with that ID and birthday.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Sign-Up - Guidance Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        html, body {
            height: 100%;
        }

        /* Header Styles */
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        header h1 {
            color: #4f46e5;
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
        }

        header h1::before {
            content: "🎓";
            margin-right: 0.5rem;
            font-size: 1.5rem;
        }

        nav {
            display: flex;
            gap: 2rem;
        }

        nav a {
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        nav a:hover {
            background: #f1f5f9;
            color: #4f46e5;
            transform: translateY(-1px);
        }

        /* Main Section */
        .main-section {
            padding: 2rem;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: calc(100vh - 100px);
        }

        .form-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 500px;
            position: relative;
            overflow: hidden;
            animation: slideIn 0.6s ease-out;
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4f46e5, #7c3aed, #ec4899);
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-header .icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }

        .form-header h2 {
            color: #1e293b;
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0 0 0.5rem;
        }

        .form-header p {
            color: #64748b;
            margin: 0;
            font-size: 0.95rem;
        }

        /* Form Sections */
        .form-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 12px;
            border-left: 4px solid #4f46e5;
        }

        .section-title {
            color: #1e293b;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e5e7eb;
            color: #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin: 0 0.5rem;
            position: relative;
        }

        .step.active {
            background: #4f46e5;
            color: white;
        }

        .step.completed {
            background: #10b981;
            color: white;
        }

        .step::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 100%;
            width: 40px;
            height: 2px;
            background: #e5e7eb;
            transform: translateY(-50%);
        }

        .step:last-child::after {
            display: none;
        }

        .step.completed::after {
            background: #10b981;
        }

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            color: #374151;
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .required::after {
            content: " *";
            color: #ef4444;
        }

        input[type="text"], input[type="email"], input[type="date"], input[type="password"], select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
            box-sizing: border-box;
        }

        input[type="text"]:focus, input[type="email"]:focus, input[type="date"]:focus, input[type="password"]:focus, select:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        select {
            cursor: pointer;
        }

        .password-container {
            position: relative;
        }

        .password-container input {
            padding-right: 3rem;
        }

        .toggle-eye {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #9ca3af;
            transition: color 0.3s ease;
        }

        .toggle-eye:hover {
            color: #4f46e5;
        }

        /* Terms and Conditions */
        .terms {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin: 1.5rem 0;
            padding: 1rem;
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 10px;
        }

        .terms input[type="checkbox"] {
            width: auto;
            margin: 0;
            transform: scale(1.2);
        }

        .terms span {
            color: #374151;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .terms a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 500;
        }

        .terms a:hover {
            text-decoration: underline;
        }

        /* Buttons */
        .btn-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            gap: 1rem;
        }

        .btn-prev, .btn-next, .create-account-btn {
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
        }

        .btn-prev {
            background: #6b7280;
            color: white;
        }

        .btn-prev:hover {
            background: #4b5563;
            transform: translateY(-1px);
        }

        .btn-next, .create-account-btn {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
        }

        .btn-next:hover, .create-account-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
        }

        .btn-next:active, .create-account-btn:active {
            transform: translateY(0);
        }

        .btn-next:disabled, .create-account-btn:disabled {
            background: #d1d5db;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Modal Enhancements */
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .modal-header {
            border-radius: 15px 15px 0 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            border-top: 1px solid #e5e7eb;
            padding: 1.5rem 2rem;
        }

        .confirmation-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .confirmation-item {
            background: #f8fafc;
            padding: 0.75rem;
            border-radius: 8px;
            border-left: 3px solid #4f46e5;
        }

        .confirmation-item strong {
            color: #1e293b;
            font-size: 0.85rem;
            display: block;
            margin-bottom: 0.25rem;
        }

        .confirmation-item span {
            color: #64748b;
            font-size: 0.9rem;
        }

        .password-help {
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-section {
                padding: 1rem;
            }

            .form-container {
                padding: 2rem;
                margin: 1rem 0;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .confirmation-grid {
                grid-template-columns: 1fr;
            }

            nav {
                gap: 1rem;
            }

            nav a {
                padding: 0.5rem;
                font-size: 0.9rem;
            }

            header {
                padding: 1rem;
            }

            header h1 {
                font-size: 1.5rem;
            }
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            animation: fadeInUp 0.5s ease-out;
            animation-fill-mode: both;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .form-group:nth-child(4) { animation-delay: 0.4s; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <h1>Guidance Portal</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="parent_login.php">Parent Login</a>
        </nav>
    </header>

    <!-- Main Section -->
    <div class="main-section">
        <div class="form-container">
            <div class="form-header">
                <div class="icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h2>Parent Registration</h2>
                <p>Create your account to access your child's guidance records</p>
            </div>

            <!-- Step Indicator -->
            <div class="step-indicator">
                <div class="step active" id="step1">1</div>
                <div class="step" id="step2">2</div>
                <div class="step" id="step3">3</div>
            </div>

            <form id="signupForm" method="POST" action="parent_signup.php">
                <!-- Step 1: Parent Information -->
                <div class="form-step active" id="parentStep">
                    <div class="section-title">
                        <i class="fas fa-user"></i>
                        Parent Information
                    </div>
                    
                    <div class="form-group">
                        <label class="required">Full Name</label>
                        <input type="text" name="parent_name" required>
                    </div>

                    <div class="form-group">
                        <label class="required">Email Address</label>
                        <input type="email" name="parent_email" required>
                    </div>

                    <div class="form-group">
                        <label class="required">Contact Number</label>
                        <input type="text" name="parent_contact" pattern="^09\d{9}$" maxlength="11" placeholder="09XXXXXXXXX" required>
                    </div>

                    <div class="form-group">
                        <label class="required">Relationship to Student</label>
                        <select name="relationship" required>
                            <option value="">-- Select Relationship --</option>
                            <option value="Mother">Mother</option>
                            <option value="Father">Father</option>
                            <option value="Guardian">Guardian</option>
                        </select>
                    </div>

                      <div class="btn-navigation">
                        <div></div>
                        <button type="button" class="btn-next" onclick="nextStep(1)">
                            <i class="fas fa-arrow-right mr-1"></i> Next
                        </button>
                    </div>
                </div>


                <!-- Step 2: Student Information -->
                <div class="form-step" id="studentStep">
                    <div class="section-title">
                        <i class="fas fa-graduation-cap"></i>
                        Student Information
                    </div>
                    
                    <div class="form-group">
                        <label class="required">Student Number</label>
                        <input type="text" name="student_no" pattern="^\d{10}$" maxlength="10" placeholder="1234567890" required>
                    </div>

                    <div class="form-group">
                        <label class="required">Student First Name</label>
                        <input type="text" name="student_fname" required>
                    </div>

                    <div class="form-group">
                        <label>Student Middle Name</label>
                        <input type="text" name="student_mname" placeholder="Leave blank if none">
                    </div>

                    <div class="form-group">
                        <label class="required">Student Last Name</label>
                        <input type="text" name="student_lname" required>
                    </div>

                    <div class="form-group">
                        <label class="required">Student Birthday</label>
                        <input type="date" name="student_bday" required min="1900-01-01" max="2015-12-31">
                    </div>

                    <div class="btn-navigation">
                        <button type="button" class="btn-prev" onclick="prevStep(2)">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        <button type="button" class="btn-next" onclick="showConfirmation()">
                            Review Information <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Password Setup -->
                <div class="form-step" id="passwordStep">
                    <div class="section-title">
                        <i class="fas fa-lock"></i>
                        Set Your Password
                    </div>
                    
                    <div class="form-group">
                        <label class="required">Password</label>
                        <div class="password-container">
                            <input type="password" name="password" id="password" required>
                            <i class="fas fa-eye-slash toggle-eye" onclick="togglePassword('password', this)"></i>
                        </div>
                        <div id="passwordHelp" class="password-help text-danger"></div>
                    </div>

                    <div class="form-group">
                        <label class="required">Confirm Password</label>
                        <div class="password-container">
                            <input type="password" name="confirm_password" id="confirm_password" required>
                            <i class="fas fa-eye-slash toggle-eye" onclick="togglePassword('confirm_password', this)"></i>
                        </div>
                    </div>

                  <div class="terms">
    <input type="checkbox" name="agree_terms" id="agree_terms" required>
    <span>I agree to the 
        <a href="terms_and_conditions.php" target="_blank">terms and conditions</a> 
        and 
        <a href="privacy_policy.php" target="_blank">privacy policy</a>
    </span>
</div>


                    <div class="btn-navigation">
                        <button type="button" class="btn-prev" onclick="prevStep(3)">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        <button type="button" class="create-account-btn" onclick="submitForm()">
                            <i class="fas fa-user-plus"></i> Create Account
                        </button>
                    </div>
                </div>

                <input type="hidden" name="final_submit" value="1">
            </form>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle me-2"></i>
                        Confirm Your Information
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-user me-2"></i>
                                Parent Information
                            </h6>
                            <div class="confirmation-grid">
                                <div class="confirmation-item">
                                    <strong>Full Name</strong>
                                    <span id="c_parent_name"></span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Email</strong>
                                    <span id="c_parent_email"></span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Contact</strong>
                                    <span id="c_parent_contact"></span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Relationship</strong>
                                    <span id="c_relationship"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success mb-3">
                                <i class="fas fa-graduation-cap me-2"></i>
                                Student Information
                            </h6>
                            <div class="confirmation-grid">
                                <div class="confirmation-item">
                                    <strong>Student Number</strong>
                                    <span id="c_student_no"></span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Birthday</strong>
                                    <span id="c_student_bday"></span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>First Name</strong>
                                    <span id="c_student_fname"></span>
                                </div>
                                <div class="confirmation-item">
                                    <strong>Middle Name</strong>
                                    <span id="c_student_mname"></span>
                                </div>
                                <div class="confirmation-item" style="grid-column: 1 / -1;">
                                    <strong>Last Name</strong>
                                    <span id="c_student_lname"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-edit me-1"></i> Edit
                    </button>
                    <button class="btn btn-success" onclick="openPasswordModal()">
                        <i class="fas fa-arrow-right me-1"></i> Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success & Error Modals -->
    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Registration Successful</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php echo $success ?: "🎉 You're registered successfully!"; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="window.location.href='parent_login.php'">OK</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="errorModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php echo $error ?: "Something went wrong."; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($error)): ?>
    <script>document.addEventListener("DOMContentLoaded",()=>{new bootstrap.Modal("#errorModal").show();});</script>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
    <script>document.addEventListener("DOMContentLoaded",()=>{new bootstrap.Modal("#successModal").show();});</script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentStep = 1;

        function nextStep(step) {
            if (validateCurrentStep(step)) {
                currentStep = step + 1;
                showStep(currentStep);
                updateStepIndicator();
            }
        }

        function prevStep(step) {
            currentStep = step - 1;
            showStep(currentStep);
            updateStepIndicator();
        }

        function showStep(step) {
            document.querySelectorAll('.form-step').forEach(s => s.classList.remove('active'));
            
            if (step === 1) document.getElementById('parentStep').classList.add('active');
            else if (step === 2) document.getElementById('studentStep').classList.add('active');
            else if (step === 3) document.getElementById('passwordStep').classList.add('active');
        }

        function updateStepIndicator() {
            for (let i = 1; i <= 3; i++) {
                const stepEl = document.getElementById(`step${i}`);
                
                if (i < currentStep) {
                    stepEl.classList.add('completed');
                    stepEl.classList.remove('active');
                } else if (i === currentStep) {
                    stepEl.classList.add('active');
                    stepEl.classList.remove('completed');
                } else {
                    stepEl.classList.remove('active', 'completed');
                }
            }
        }

        function validateCurrentStep(step) {
            const form = document.getElementById('signupForm');
            let isValid = true;
            let errorMsg = '';

            if (step === 1) {
                // Validate parent information
                if (!form.parent_name.value.trim()) {
                    errorMsg = 'Please enter your full name.';
                    isValid = false;
                } else if (!form.parent_email.value.trim()) {
                    errorMsg = 'Please enter your email address.';
                    isValid = false;
                    
                } else if (!form.parent_contact.value.trim()) {
                    errorMsg = 'Please enter your contact number.';
                    isValid = false;
                } else if (!form.relationship.value) {
                    errorMsg = 'Please select your relationship to the student.';
                    isValid = false;
                } else if (!/^09\d{9}$/.test(form.parent_contact.value)) {
                    errorMsg = 'Contact number must start with 09 and be 11 digits.';
                    isValid = false;
                }
            } else if (step === 2) {
                // Validate student information
                if (!form.student_no.value.trim()) {
                    errorMsg = 'Please enter the student number.';
                    isValid = false;
                } else if (!form.student_fname.value.trim()) {
                    errorMsg = 'Please enter the student first name.';
                    isValid = false;
                } else if (!form.student_lname.value.trim()) {
                    errorMsg = 'Please enter the student last name.';
                    isValid = false;
                } else if (!form.student_bday.value) {
                    errorMsg = 'Please enter the student birthday.';
                    isValid = false;
                } else if (!/^\d{10}$/.test(form.student_no.value)) {
                    errorMsg = 'Student number must be exactly 10 digits.';
                    isValid = false;
                }
            }

            if (!isValid) {
    // Set the error message in the modal
            const errorModalBody = document.querySelector("#errorModal .modal-body");
            errorModalBody.innerText = errorMsg;

    // Show the error modal
           const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
           errorModal.show();
}

            return isValid;
        }

        function showConfirmation() {
            if (validateCurrentStep(2)) {
                const form = document.getElementById('signupForm');
                
                // Populate confirmation modal
                document.getElementById('c_parent_name').innerText = form.parent_name.value;
                document.getElementById('c_parent_email').innerText = form.parent_email.value;
                document.getElementById('c_parent_contact').innerText = form.parent_contact.value;
                document.getElementById('c_relationship').innerText = form.relationship.value;
                document.getElementById('c_student_no').innerText = form.student_no.value;
                document.getElementById('c_student_fname').innerText = form.student_fname.value;
                document.getElementById('c_student_mname').innerText = form.student_mname.value || "N/A";
                document.getElementById('c_student_lname').innerText = form.student_lname.value;
                document.getElementById('c_student_bday').innerText = form.student_bday.value;

                new bootstrap.Modal(document.getElementById('confirmModal')).show();
            }
        }

        function openPasswordModal() {
            bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide();
            currentStep = 3;
            showStep(currentStep);
            updateStepIndicator();
        }

        function submitForm() {
    const form = document.getElementById('signupForm');
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const agreeTerms = document.getElementById('agree_terms').checked;

    // Function to show error modal
    function showErrorModal(message) {
        const errorModalBody = document.querySelector("#errorModal .modal-body");
        errorModalBody.innerText = message;
        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
    }

    // Validate password
    if (!password) {
        showErrorModal('Please enter a password.');
        return;
    }

    if (!confirmPassword) {
        showErrorModal('Please confirm your password.');
        return;
    }

    if (password !== confirmPassword) {
        showErrorModal('Passwords do not match.');
        return;
    }

    // Validate password strength
    const error = validatePasswordStrength(password);
    if (error) {
        showErrorModal(error.replace('❌ ', ''));
        return;
    }

    // Check terms agreement
    if (!agreeTerms) {
        showErrorModal('Please agree to the terms and conditions.');
        return;
    }

    // Submit form
    form.submit();
}


        function togglePassword(fieldId, icon) {
            const field = document.getElementById(fieldId);
            if (field.type === "password") {
                field.type = "text";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            } else {
                field.type = "password";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            }
        }

        function validatePasswordStrength(password) {
            const minLength = /.{8,}/;
            const upper = /[A-Z]/;
            const lower = /[a-z]/;
            const number = /[0-9]/;
            const symbol = /[^A-Za-z0-9]/;

            if (!minLength.test(password)) return "❌ At least 8 characters.";
            if (!upper.test(password)) return "❌ Must include uppercase letter.";
            if (!lower.test(password)) return "❌ Must include lowercase letter.";
            if (!number.test(password)) return "❌ Must include number.";
            if (!symbol.test(password)) return "❌ Must include symbol.";
            return "";
        }

        // Real-time password validation
        document.addEventListener("DOMContentLoaded", () => {
            const passwordInput = document.getElementById("password");
            const confirmInput = document.getElementById("confirm_password");
            const passwordHelp = document.getElementById("passwordHelp");

            function checkPasswordValidity() {
                const pass = passwordInput.value;
                const confirmPass = confirmInput.value;

                const error = validatePasswordStrength(pass);
                if (error) {
                    passwordHelp.innerText = error;
                    passwordHelp.className = "password-help text-danger";
                    return;
                }

                if (pass && confirmPass && pass !== confirmPass) {
                    passwordHelp.innerText = "❌ Passwords do not match.";
                    passwordHelp.className = "password-help text-danger";
                    return;
                }

                if (pass && confirmPass && pass === confirmPass) {
                    passwordHelp.innerText = "✅ Strong password!";
                    passwordHelp.className = "password-help text-success";
                } else {
                    passwordHelp.innerText = "";
                }
            }

            passwordInput.addEventListener("input", checkPasswordValidity);
            confirmInput.addEventListener("input", checkPasswordValidity);
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98c366fac12a0dcb',t:'MTc2MDA3MDgzNC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
