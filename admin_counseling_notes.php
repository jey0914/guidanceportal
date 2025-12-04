<?php
include 'db.php';
session_start();

if (!isset($_SESSION['admin_email'])) {
  header("Location: admin_login.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_email = $_POST['student_email'] ?? '';
    $interview_date = $_POST['interviewDate'] ?? '';
    $time_started = $_POST['timeStarted'] ?? '';
    $time_ended = $_POST['timeEnded'] ?? '';
    $school_year = $_POST['schoolYear'] ?? '';
    $semester = isset($_POST['tertiarySemester']) ? implode(", ", (array)$_POST['tertiarySemester']) : '';
    $name = $_POST['name'] ?? '';
    $quarter = isset($_POST['seniorHighQuarter']) ? implode(", ", (array)$_POST['seniorHighQuarter']) : '';
    $grade_section = $_POST['gradeLevelSection'] ?? '';
    $program = $_POST['program'] ?? '';
    $nature = $_POST['natureCounseling'] ?? '';

    $situations = [];
    if (!empty($_POST['counselingSituation'])) {
        $input = $_POST['counselingSituation'];
        if (is_array($input)) {
            $situations = array_merge($situations, $input);
        } else {
            $situations[] = $input;
        }
    }
    if (!empty($_POST['referredByText'])) {
        $situations[] = "Referred By: " . $_POST['referredByText'];
    }
    $situation = implode(", ", $situations);

    $presenting_problem = $_POST['presentingProblem'] ?? '';
    $assessment = $_POST['assessment'] ?? '';
    $interventions = $_POST['interventions'] ?? '';
    $plan_of_action = $_POST['planOfAction'] ?? '';

    $recommendations = [];
    if (!empty($_POST['recommendation'])) {
        $input = $_POST['recommendation'];
        if (is_array($input)) {
            $recommendations = array_merge($recommendations, $input);
        } else {
            $recommendations[] = $input;
        }
    }
    $follow_date = $_POST['followThroughDate'] ?? '';
    $referral = $_POST['referralName'] ?? '';

    if (!empty($follow_date)) {
        $recommendations[] = "Follow-Up Date: " . $follow_date;
    }
    if (!empty($referral)) {
        $recommendations[] = "Referral: " . $referral;
    }
    $recommendation = implode(", ", $recommendations);

    $counselor_signature = $_POST['counselorSignature'] ?? '';
    $status = "Completed";

    // Insert into counseling_notes
    $stmt = $con->prepare("INSERT INTO counseling_notes 
        (student_email, interview_date, time_started, time_ended, school_year, semester, name, quarter, grade_section, program, nature, situation, presenting_problem, assessment, interventions, plan_of_action, recommendation, follow_through_date, referral_agency, counselor_signature)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssssssssssssssssssss", 
        $student_email, $interview_date, $time_started, $time_ended, 
        $school_year, $semester, $name, $quarter, $grade_section, 
        $program, $nature, $situation, $presenting_problem, $assessment, 
        $interventions, $plan_of_action, $recommendation, 
        $follow_date, $referral, $counselor_signature);

    if (!$stmt->execute()) {
        $_SESSION['error'] = "Failed to save counseling notes.";
        header("Location: student_counseling_notes.php?success=0");
        exit();
    }

    // Insert into counseling_history
    $stmt2 = $con->prepare("INSERT INTO counseling_history (
    student_email, interview_date, time_started, time_ended, 
    school_year, semester, quarter, grade_section, 
    program, nature, status, counselor, remarks
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$remarks = ''; // or set a real value if applicable

$stmt2->bind_param("sssssssssssss",
    $student_email,
    $interview_date,
    $time_started,
    $time_ended,
    $school_year,
    $semester,
    $quarter,
    $grade_section,
    $program,
    $nature,
    $status,
    $counselor_signature,
    $remarks
);
    $stmt2->execute();

    $_SESSION['success'] = "Notes submitted and history updated.";
    header("Location: admin_counseling_notes.php?success=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counseling Notes - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <style>
       body {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }
        
        .dashboard {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background-color: #fff;
            border-right: 1px solid #dee2e6;
            padding: 1.5rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }
        
        .sidebar h2 {
            padding: 0 1.5rem;
            margin-bottom: 2rem;
            font-size: 1.25rem;
            font-weight: 600;
            color: #495057;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar li {
            margin-bottom: 0.25rem;
        }
        
        .sidebar a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: #6c757d;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .sidebar a:hover {
            background-color: #f8f9fa;
            color: #495057;
        }
        
        .sidebar a.active {
            background-color: #e3f2fd;
            color: #1976d2;
            border-right: 3px solid #1976d2;
        }
        
        .sidebar a i {
            font-size: 1.1rem;
            width: 18px;
            text-align: center;
        }
        
        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 2rem;
        }
        
        .page-header {
            background: linear-gradient(135deg, #1976d2, #7b1fa2);
            color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
        }
        
        .page-header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .page-header p {
            margin: 0;
            opacity: 0.9;
        }
        
        .form-input-line {
            width: 100%;
            border: none;
            border-bottom: 2px solid #dee2e6;
            padding: 0.5rem 0;
            background: transparent;
            transition: border-color 0.2s;
            font-family: 'Inter', sans-serif;
        }
        
        .form-input-line:focus {
            outline: none;
            border-bottom-color: #1976d2;
            box-shadow: none;
        }
        
        .text-area-line {
            width: 100%;
            border: 2px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 0.75rem;
            resize: vertical;
            transition: border-color 0.2s;
            font-family: 'Inter', sans-serif;
        }
        
        .text-area-line:focus {
            outline: none;
            border-color: #1976d2;
            box-shadow: 0 0 0 0.2rem rgba(25, 118, 210, 0.25);
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.25rem;
            display: block;
        }
        
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .radio-group {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        
        .radio-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .quarter-checkboxes {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.5rem;
        }
        
        .form-section {
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .form-container {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            padding: 2rem;
        }
        
        @media (max-width: 768px) {
            .quarter-checkboxes {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            
            .sidebar {
                transform: translateX(-100%);
            }
        }
    </style>
</head>
<body>
     <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2><i class="bi bi-gear-fill"></i> Admin Panel</h2>
            <ul>
      <li><a href="admin_dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a></li>
      <li><a href="admin_records.php"><i class="bi bi-people"></i> Student Records</a></li>
      <li><a href="admin_appointments.php"><i class="bi bi-calendar-check"></i> Appointments</a></li>
      <li><a href="admin_reports.php"><i class="bi bi-graph-up"></i> Reports</a></li>
      <li><a href="admin_settings.php"><i class="bi bi-gear"></i> Settings</a></li>
      <li><a href="admin_announcements.php"><i class="bi bi-megaphone"></i> Announcements</a></li>
      <li><a href="admin_parents.php"><i class="bi bi-person-lines-fill"></i> Parent Accounts</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Page Header -->
            <div class="page-header">
                <h1>
                    <i class="bi bi-chat-heart"></i>
                    Counseling Notes
                </h1>
                <p>Comprehensive guidance and counseling session documentation</p>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex flex-wrap gap-3 mb-4">
                <button onclick="saveForm()" class="btn btn-success d-flex align-items-center gap-2">
                    <i class="bi bi-save"></i>
                    Save Notes
                </button>
                <button onclick="downloadPDF()" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="bi bi-download"></i>
                    Download PDF
                </button>
                <button onclick="printForm()" class="btn btn-secondary d-flex align-items-center gap-2">
                    <i class="bi bi-printer"></i>
                    Print Form
                </button>
                <button onclick="clearForm()" class="btn btn-danger d-flex align-items-center gap-2">
                    <i class="bi bi-arrow-clockwise"></i>
                    Clear Form
                </button>
            </div>

            <!-- Main container -->
            <div class="form-container">
                <header class="text-center mb-4">
                    <h1 class="h3 fw-bold text-dark">STI Guidance and Counseling Office</h1>
                    <p class="h5 fw-semibold text-secondary">Guidance/Counseling Notes</p>
                </header>

                <form action="admin_counseling_notes.php" method="POST">
                    <input type="hidden" name="student_email" value="">

                    <!-- Interview Details Section -->
                    <div class="form-section">
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="interviewDate" class="form-label">Interview Date:</label>
                                <input type="date" id="interviewDate" name="interviewDate" class="form-input-line">
                            </div>
                            <div class="col-md-6">
                                <label for="timeStarted" class="form-label">Time Started:</label>
                                <input type="time" id="timeStarted" name="timeStarted" class="form-input-line">
                            </div>
                        </div>
                    </div>

                    <!-- School Year and Student Info Section -->
                    <div class="form-section">
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="schoolYear" class="form-label">School Year:</label>
                                <input type="text" id="schoolYear" name="schoolYear" class="form-input-line" placeholder="e.g., 2023-2024">
                            </div>
                            <div class="col-md-6">
                                <p class="form-label mb-2">Tertiary (Semester):</p>
                                <div class="checkbox-group">
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="sem1" name="tertiarySemester" value="1" class="form-check-input">
                                        <label for="sem1" class="form-check-label">1<sup>st</sup></label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="sem2" name="tertiarySemester" value="2" class="form-check-input">
                                        <label for="sem2" class="form-check-label">2<sup>nd</sup></label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="semSummer" name="tertiarySemester" value="Summer" class="form-check-input">
                                        <label for="semSummer" class="form-check-label">Summer</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" id="name" name="name" class="form-input-line" placeholder="Full Name">
                            </div>
                            <div class="col-md-6">
                                <p class="form-label mb-2">Senior High (Quarter):</p>
                                <div class="checkbox-group quarter-checkboxes">
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="q1" name="seniorHighQuarter" value="1" class="form-check-input">
                                        <label for="q1" class="form-check-label">1<sup>st</sup></label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="q2" name="seniorHighQuarter" value="2" class="form-check-input">
                                        <label for="q2" class="form-check-label">2<sup>nd</sup></label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="q3" name="seniorHighQuarter" value="3" class="form-check-input">
                                        <label for="q3" class="form-check-label">3<sup>rd</sup></label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="q4" name="seniorHighQuarter" value="4" class="form-check-input">
                                        <label for="q4" class="form-check-label">4<sup>th</sup></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="gradeLevelSection" class="form-label">Grade/Year Level & Section:</label>
                                <input type="text" id="gradeLevelSection" name="gradeLevelSection" class="form-input-line" placeholder="e.g., Grade 11 - A">
                            </div>
                            <div class="col-md-6">
                                <label for="program" class="form-label">Program:</label>
                                <input type="text" id="program" name="program" class="form-input-line" placeholder="e.g., BSIT, ABM">
                            </div>
                        </div>
                    </div>

                    <!-- Nature of Counseling Section -->
                    <div class="form-section">
                        <p class="form-label mb-3">Nature of Counseling:</p>
                        <div class="radio-group">
                            <div class="radio-item">
                                <input type="radio" id="counselingAcademic" name="natureCounseling" value="Academic" class="form-check-input">
                                <label for="counselingAcademic" class="form-check-label">Academic</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="counselingBehavioral" name="natureCounseling" value="Behavioral" class="form-check-input">
                                <label for="counselingBehavioral" class="form-check-label">Behavioral</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="counselingPersonal" name="natureCounseling" value="Personal" class="form-check-input">
                                <label for="counselingPersonal" class="form-check-label">Personal</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="counselingSocial" name="natureCounseling" value="Social" class="form-check-input">
                                <label for="counselingSocial" class="form-check-label">Social</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="counselingCareer" name="natureCounseling" value="Career" class="form-check-input">
                                <label for="counselingCareer" class="form-check-label">Career</label>
                            </div>
                        </div>
                    </div>

                    <!-- Counseling Situation/s Section -->
                    <div class="form-section">
                        <p class="form-label mb-3">Counseling Situation/s:</p>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="checkbox-item mb-3">
                                    <input type="checkbox" id="sitFollowUp" name="counselingSituation" value="Follow-up" class="form-check-input">
                                    <label for="sitFollowUp" class="form-check-label">Follow-up</label>
                                </div>
                                <div class="checkbox-item mb-3">
                                    <input type="checkbox" id="sitCounselorInitiated" name="counselingSituation" value="Counselor-Initiated" class="form-check-input">
                                    <label for="sitCounselorInitiated" class="form-check-label">Counselor-Initiated</label>
                                </div>
                                <div class="checkbox-item mb-3">
                                    <input type="checkbox" id="sitWalkIn" name="counselingSituation" value="Walk-in" class="form-check-input">
                                    <label for="sitWalkIn" class="form-check-label">Walk-in</label>
                                </div>
                                <div class="checkbox-item mb-3">
                                    <input type="checkbox" id="sitIndividual" name="counselingSituation" value="Individual" class="form-check-input">
                                    <label for="sitIndividual" class="form-check-label">Individual</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="checkbox-item mb-2">
                                        <input type="checkbox" id="sitReferredBy" name="counselingSituation" value="Referred By" class="form-check-input">
                                        <label for="sitReferredBy" class="form-check-label">Referred By:</label>
                                    </div>
                                    <input type="text" class="form-input-line" id="referredByText" placeholder="Enter referrer name">
                                </div>
                                <div class="checkbox-item mb-3">
                                    <input type="checkbox" id="sitGroup" name="counselingSituation" value="Group" class="form-check-input">
                                    <label for="sitGroup" class="form-check-label">Group</label>
                                </div>
                                <div class="checkbox-item mb-3">
                                    <input type="checkbox" id="sitClass" name="counselingSituation" value="Class" class="form-check-input">
                                    <label for="sitClass" class="form-check-label">Class</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Presenting Problem Section -->
                    <div class="form-section">
                        <label for="presentingProblem" class="form-label">Presenting Problem:</label>
                        <textarea id="presentingProblem" name="presentingProblem" class="text-area-line" rows="5" placeholder="Describe the main issues or concerns presented by the student..."></textarea>
                    </div>

                    <!-- Assessment Section -->
                    <div class="form-section">
                        <label for="assessment" class="form-label">Assessment:</label>
                        <textarea id="assessment" name="assessment" class="text-area-line" rows="5" placeholder="Professional assessment and observations..."></textarea>
                    </div>

                    <!-- Intervention/s Section -->
                    <div class="form-section">
                        <label for="interventions" class="form-label">Intervention/s:</label>
                        <textarea id="interventions" name="interventions" class="text-area-line" rows="5" placeholder="Describe the interventions and strategies used..."></textarea>
                    </div>

                    <!-- Plan of Action Section -->
                    <div class="form-section">
                        <label for="planOfAction" class="form-label">Plan of Action:</label>
                        <textarea id="planOfAction" name="planOfAction" class="text-area-line" rows="5" placeholder="Outline the planned next steps and actions..."></textarea>
                    </div>

                    <!-- Recommendation/s Section -->
                    <div class="form-section">
                        <p class="form-label mb-3">Recommendation/s:</p>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="recFollowThrough" name="recommendation" value="Follow-Through Session" class="form-check-input">
                                    <label for="recFollowThrough" class="form-check-label">Follow-Through Session</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="followThroughDate" class="form-label">Date of follow-through session:</label>
                                <input type="date" id="followThroughDate" name="followThroughDate" class="form-input-line">
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="recReferral" name="recommendation" value="Referral" class="form-check-input">
                                    <label for="recReferral" class="form-check-label">Referral</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="referralName" class="form-label">Professional or Agency Name:</label>
                                <input type="text" id="referralName" name="referralName" class="form-input-line" placeholder="Enter professional or agency name">
                            </div>
                        </div>
                    </div>

                    <!-- Signature Section -->
                    <div class="form-section border-top pt-4 mt-4">
                        <label for="counselorSignature" class="form-label">Name and signature of Guidance Counselor/Associate:</label>
                        <input type="text" id="counselorSignature" name="counselorSignature" class="form-input-line" placeholder="Enter counselor name and signature">
                    </div>


                <footer class="text-center mt-5">
                    <p class="small text-muted">Copyright 2017 STI EDUCATION SERVICES GROUP, INC. All rights reserved.</p>
                    <p class="small text-muted">STRICTLY CONFIDENTIAL. Should only be accessed by the Guidance Counselor/Associate.</p>
                    <p class="small fw-semibold text-secondary mt-3">STUDENT DEVELOPMENT AND WELFARE</p>
                    <p class="small text-muted">FT-SDW-097-00 | GUIDANCE/COUNSELING NOTES FORM | PAGE 1 OF 1</p>
                </footer>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        function saveForm() {
            // Validate required fields
            const requiredFields = ['interviewDate', 'name'];
            let isValid = true;
            
            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (!field.value.trim()) {
                    field.style.borderBottomColor = '#ef4444';
                    isValid = false;
                } else {
                    field.style.borderBottomColor = '#e5e7eb';
                }
            });
            
            if (isValid) {
                alert('Counseling notes saved successfully!');
            } else {
                alert('Please fill in all required fields.');
            }
        }

        function downloadPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            // Header
            doc.setFontSize(16);
            doc.setFont(undefined, 'bold');
            doc.text('STI Guidance and Counseling Office', 105, 20, { align: 'center' });
            doc.setFontSize(14);
            doc.text('Guidance/Counseling Notes', 105, 30, { align: 'center' });
            
            // Form data
            doc.setFontSize(10);
            doc.setFont(undefined, 'normal');
            let yPos = 50;
            
            // Get form values
            const formData = {
                'Interview Date': document.getElementById('interviewDate').value || 'Not specified',
                'Time Started': document.getElementById('timeStarted').value || 'Not specified',
                'School Year': document.getElementById('schoolYear').value || 'Not specified',
                'Student Name': document.getElementById('name').value || 'Not specified',
                'Grade/Year Level & Section': document.getElementById('gradeLevelSection').value || 'Not specified',
                'Program': document.getElementById('program').value || 'Not specified'
            };
            
            // Basic info
            Object.entries(formData).forEach(([key, value]) => {
                doc.setFont(undefined, 'bold');
                doc.text(key + ':', 20, yPos);
                doc.setFont(undefined, 'normal');
                doc.text(value, 70, yPos);
                yPos += 8;
            });
            
            yPos += 5;
            
            // Nature of Counseling
            const natureCounseling = document.querySelector('input[name="natureCounseling"]:checked');
            doc.setFont(undefined, 'bold');
            doc.text('Nature of Counseling:', 20, yPos);
            doc.setFont(undefined, 'normal');
            doc.text(natureCounseling ? natureCounseling.value : 'Not specified', 70, yPos);
            yPos += 10;
            
            // Counseling Situations
            const situations = Array.from(document.querySelectorAll('input[name="counselingSituation"]:checked'))
                .map(cb => cb.value).join(', ');
            doc.setFont(undefined, 'bold');
            doc.text('Counseling Situation/s:', 20, yPos);
            doc.setFont(undefined, 'normal');
            const situationText = situations || 'Not specified';
            const splitSituation = doc.splitTextToSize(situationText, 120);
            doc.text(splitSituation, 70, yPos);
            yPos += splitSituation.length * 5 + 5;
            
            // Text areas
            const textAreas = [
                { label: 'Presenting Problem', id: 'presentingProblem' },
                { label: 'Assessment', id: 'assessment' },
                { label: 'Intervention/s', id: 'interventions' },
                { label: 'Plan of Action', id: 'planOfAction' }
            ];
            
            textAreas.forEach(area => {
                if (yPos > 250) {
                    doc.addPage();
                    yPos = 20;
                }
                
                doc.setFont(undefined, 'bold');
                doc.text(area.label + ':', 20, yPos);
                yPos += 8;
                
                doc.setFont(undefined, 'normal');
                const content = document.getElementById(area.id).value || 'Not specified';
                const splitText = doc.splitTextToSize(content, 170);
                doc.text(splitText, 20, yPos);
                yPos += splitText.length * 5 + 10;
            });
            
            // Recommendations
            if (yPos > 240) {
                doc.addPage();
                yPos = 20;
            }
            
            doc.setFont(undefined, 'bold');
            doc.text('Recommendations:', 20, yPos);
            yPos += 8;
            
            const recommendations = Array.from(document.querySelectorAll('input[name="recommendation"]:checked'))
                .map(cb => cb.value);
            
            if (recommendations.length > 0) {
                doc.setFont(undefined, 'normal');
                recommendations.forEach(rec => {
                    doc.text('• ' + rec, 25, yPos);
                    yPos += 6;
                });
                
                // Follow-through date
                const followDate = document.getElementById('followThroughDate').value;
                if (followDate) {
                    doc.text('Follow-through Date: ' + followDate, 25, yPos);
                    yPos += 6;
                }
                
                // Referral name
                const referralName = document.getElementById('referralName').value;
                if (referralName) {
                    doc.text('Professional/Agency: ' + referralName, 25, yPos);
                    yPos += 6;
                }
            } else {
                doc.setFont(undefined, 'normal');
                doc.text('Not specified', 25, yPos);
                yPos += 6;
            }
            
            yPos += 10;
            
            // Counselor signature
            doc.setFont(undefined, 'bold');
            doc.text('Counselor/Associate:', 20, yPos);
            doc.setFont(undefined, 'normal');
            doc.text(document.getElementById('counselorSignature').value || 'Not specified', 70, yPos);
            
            // Footer
            yPos = 280;
            doc.setFontSize(8);
            doc.text('Copyright 2017 STI EDUCATION SERVICES GROUP, INC. All rights reserved.', 105, yPos, { align: 'center' });
            doc.text('STRICTLY CONFIDENTIAL. Should only be accessed by the Guidance Counselor/Associate.', 105, yPos + 5, { align: 'center' });
            doc.text('FT-SDW-097-00 | GUIDANCE/COUNSELING NOTES FORM | PAGE 1 OF 1', 105, yPos + 15, { align: 'center' });
            
            // Generate filename
            const studentName = document.getElementById('name').value || 'Student';
            const date = document.getElementById('interviewDate').value || new Date().toISOString().split('T')[0];
            const filename = `Counseling_Notes_${studentName.replace(/\s+/g, '_')}_${date}.pdf`;
            
            doc.save(filename);
        }

        function printForm() {
            window.print();
        }

        function clearForm() {
            if (confirm('Are you sure you want to clear all form data?')) {
                document.querySelector('form').reset();
            }
        }

        // Auto-resize textareas
        document.querySelectorAll('textarea').forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98b3541795b10dcb',t:'MTc1OTkwMjI4OC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
