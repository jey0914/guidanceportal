<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

include("db.php");

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_no = trim($_POST['student_no']);
    $fname = ucwords(strtolower(trim($_POST['fname'])));
    $mname = ucwords(strtolower(trim($_POST['mname'])));
    if (empty($mname)) $mname = "N/A";
    $lname_display = ucwords(trim($_POST['lname']));
    $raw_lname = strtolower(str_replace(' ', '', trim($_POST['lname'])));
    $bday = $_POST['bday'];
    $year_level = $_POST['year_level'];
    $strand_course = $_POST['strand_course'];
    $personal_email = trim($_POST['personal_email']);

    // Check duplicate student_no
    $check = $con->prepare("SELECT student_no FROM form WHERE student_no = ?");
    $check->bind_param("s", $student_no);
    $check->execute();
    $exists = $check->get_result();

    if ($exists->num_rows > 0) {
        echo "<script>alert('Student number already registered.'); window.location='add_student.php';</script>";
    } else {
       // Remove leading zeros, then keep last 6 digits
$short_no = ltrim($student_no, '0'); 
$short_no = substr($short_no, -6); 

// Build email with short number
$email = $raw_lname . "." . $short_no . "@guidanceportal.rosario.sti.edu.ph";

// Generate default password
$raw_password = $raw_lname . date("Ymd", strtotime($bday));
$hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);


        $stmt = $con->prepare("INSERT INTO form 
            (student_no, fname, mname, lname, bday, email, pass, year_level, strand_course, personal_email) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssssssssss",
            $student_no, $fname, $mname, $lname_display, $bday,
            $email, $hashed_password, $year_level, $strand_course, $personal_email
        );

        if ($stmt->execute()) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'guidanceoffice879@gmail.com';
                $mail->Password = 'oenlipnkxqmteifm';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('your_email@gmail.com', 'Guidance Office');
                $mail->addAddress($personal_email, "$fname $lname_display");

                $mail->isHTML(true);
                $mail->Subject = "Your Guidance Portal Account";
                $mail->Body = "
                    <p>Hello <b>$fname $lname_display</b>,</p>
                    <p>Your student account has been created in the Guidance Portal.</p>
                    <p><b>Portal Email:</b> $email<br>
                    <b>Default Password:</b> $raw_password</p>
                    <p>Please log in and change your password immediately.</p>
                    <br><p>Regards,<br>Guidance Office</p>
                ";

                $mail->send();

                $modal_msg = "Student added successfully!<br>Email: $email<br>Password: $raw_password";
            } catch (Exception $e) {
                $modal_msg = "Student added but email could not be sent.<br>Error: {$mail->ErrorInfo}";
            }
        } else {
            $modal_msg = "Error: Failed to insert student.";
        }

        // Show Bootstrap modal
        echo "
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('modalMessage').innerHTML = '$modal_msg';
            var myModal = new bootstrap.Modal(document.getElementById('successModal'));
            myModal.show();
          });
        </script>";
    }
}

// Fetch students
$sql = "SELECT * FROM form ORDER BY lname ASC";
$all_students = $con->query($sql);

$senior_high = [];
$college = [];
while ($row = $all_students->fetch_assoc()) {
    $year = strtolower($row['year_level']);
    if (strpos($year, 'grade') !== false) $senior_high[] = $row;
    else $college[] = $row;
}

// Fetch logins
$login_sql = "SELECT * FROM form WHERE last_login IS NOT NULL ORDER BY last_login DESC";
$login_result = $con->query($login_sql);
$logins_by_date = [];
while ($row = $login_result->fetch_assoc()) {
    $date = date("F j, Y", strtotime($row['last_login']));
    $logins_by_date[$date][] = $row;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - Admin Dashboard</title>
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
        
        .stats-card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        .stats-card .card-body {
            padding: 1.5rem;
        }
        
        .stats-card h5 {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        .stats-card h3 {
            font-weight: 600;
            margin: 0;
        }
        
        .import-section {
            background: linear-gradient(135deg, #28a745, #20c997);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .import-section h5 {
            color: white;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .file-upload-area {
            border: 2px dashed rgba(255, 255, 255, 0.5);
            border-radius: 0.5rem;
            padding: 2rem;
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .file-upload-area:hover {
            border-color: rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.2);
        }
        
        .file-upload-area.dragover {
            border-color: white;
            background: rgba(255, 255, 255, 0.3);
        }
        
        .file-upload-area i {
            font-size: 3rem;
            color: white;
            margin-bottom: 1rem;
        }
        
        .file-upload-area p {
            color: white;
            margin: 0;
            font-weight: 500;
        }
        
        .csv-template {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 1rem;
        }
        
        .csv-template code {
            background: rgba(0, 0, 0, 0.2);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }
        
        .accordion-button {
            font-weight: 500;
        }
        
        .accordion-button:not(.collapsed) {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        
        .list-group-item {
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
        }
        
        .list-group-item:hover {
            border-left-color: #1976d2;
            background-color: #f8f9fa;
        }
        
        .progress-container {
            display: none;
            margin-top: 1rem;
        }
        
        .import-results {
            display: none;
            margin-top: 1rem;
        }
        
        @media (max-width: 768px) {
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
                    <i class="bi bi-person-plus"></i>
                    Student Management
                </h1>
                <p>Add and manage student records efficiently</p>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-white bg-primary shadow stats-card">
                        <div class="card-body">
                            <h5><i class="bi bi-people me-2"></i>Total Students</h5>
                            <h3><?= count($senior_high) + count($college) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success shadow stats-card">
                        <div class="card-body">
                            <h5><i class="bi bi-mortarboard me-2"></i>Senior High Students</h5>
                            <h3><?= count($senior_high) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-info shadow stats-card">
                        <div class="card-body">
                            <h5><i class="bi bi-award me-2"></i>College Students</h5>
                            <h3><?= count($college) ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CSV Import Section -->
            <div class="import-section">
                <h5><i class="bi bi-cloud-upload me-2"></i>Bulk Import Students via CSV</h5>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="file-upload-area" id="fileUploadArea">
                            <i class="bi bi-cloud-upload"></i>
                            <p class="mb-2">Drag and drop your CSV file here, or click to browse</p>
                            <p class="small mb-0">Supported format: .csv (Max size: 10MB)</p>
                            <input type="file" id="csvFile" accept=".csv" style="display: none;">
                        </div>
                        
                        <div class="progress-container">
                            <div class="progress mb-2">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                            </div>
                            <p class="text-white mb-0" id="progressText">Processing...</p>
                        </div>
                        
                        <div class="import-results">
                            <div class="alert alert-light" id="importResults"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="csv-template">
                            <h6 class="text-white mb-3"><i class="bi bi-info-circle me-2"></i>CSV Format Required:</h6>
                            <div class="text-white small">
                                <p class="mb-2"><strong>Column Headers (in order):</strong></p>
                                <code>student_no</code><br>
                                <code>fname</code><br>
                                <code>mname</code><br>
                                <code>lname</code><br>
                                <code>bday</code> (YYYY-MM-DD)<br>
                                <code>year_level</code><br>
                                <code>strand_course</code><br>
                                <code>personal_email</code>
                            </div>
                            <button class="btn btn-light btn-sm mt-3" onclick="downloadTemplate()">
                                <i class="bi bi-download me-1"></i>Download Template
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Folder View -->
            <div class="row">
                <div class="col-12">
                    <!-- Senior High -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-mortarboard me-2"></i>Senior High Students</h5>
                        </div>
                        <div class="card-body">
                            <?php if (count($senior_high) > 0): ?>
                                <div class="accordion" id="accordionSHS">
                                    <?php 
                                        $grouped_shs = [];
                                        foreach ($senior_high as $sh) {
                                            $grouped_shs[$sh['year_level']][$sh['strand_course']][] = $sh;
                                        }
                                        foreach ($grouped_shs as $year => $strands): 
                                    ?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingSHS<?= md5($year) ?>">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSHS<?= md5($year) ?>">
                                                    <i class="bi bi-folder me-2"></i><?= htmlspecialchars($year) ?>
                                                </button>
                                            </h2>
                                            <div id="collapseSHS<?= md5($year) ?>" class="accordion-collapse collapse" data-bs-parent="#accordionSHS">
                                                <div class="accordion-body">
                                                    <div class="accordion" id="accordionStrand<?= md5($year) ?>">
                                                        <?php foreach ($strands as $strand => $students): ?>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingStrand<?= md5($year.$strand) ?>">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseStrand<?= md5($year.$strand) ?>">
                                                                        <i class="bi bi-folder2 me-2"></i><?= htmlspecialchars($strand) ?> <span class="badge bg-primary ms-2"><?= count($students) ?></span>
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseStrand<?= md5($year.$strand) ?>" class="accordion-collapse collapse" data-bs-parent="#accordionStrand<?= md5($year) ?>">
                                                                    <div class="accordion-body">
                                                                        <div class="list-group">
                                                                            <?php foreach ($students as $st): ?>
                                                                                <div class="list-group-item">
                                                                                    <div class="d-flex justify-content-between align-items-start">
                                                                                        <div>
                                                                                            <h6 class="mb-1">
                                                                                                <i class="bi bi-person me-2"></i>
                                                                                                <strong>
                                                                                                    <?php 
                                                                                                        $mid = ($st['mname'] !== "N/A" && !empty($st['mname'])) ? $st['mname']." " : "";
                                                                                                        echo $st['fname'].' '.$mid.$st['lname']; 
                                                                                                    ?>
                                                                                                </strong>
                                                                                            </h6>
                                                                                            <p class="mb-1 text-muted">
                                                                                                <i class="bi bi-hash me-1"></i><?= $st['student_no'] ?>
                                                                                            </p>
                                                                                            <small class="text-muted">
                                                                                                <i class="bi bi-envelope me-1"></i><?= $st['email'] ?> | 
                                                                                                <i class="bi bi-calendar me-1"></i><?= date('M j, Y', strtotime($st['bday'])) ?>
                                                                                            </small>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            <?php endforeach; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="bi bi-inbox display-1 text-muted"></i>
                                    <p class="text-muted mt-3">No senior high students found.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- College -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-award me-2"></i>College Students</h5>
                        </div>
                        <div class="card-body">
                            <?php if (count($college) > 0): ?>
                                <div class="accordion" id="accordionCollege">
                                    <?php 
                                        $grouped_college = [];
                                        foreach ($college as $col) {
                                            $grouped_college[$col['year_level']][$col['strand_course']][] = $col;
                                        }
                                        foreach ($grouped_college as $year => $courses): 
                                    ?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingCOL<?= md5($year) ?>">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCOL<?= md5($year) ?>">
                                                    <i class="bi bi-folder me-2"></i><?= htmlspecialchars($year) ?>
                                                </button>
                                            </h2>
                                            <div id="collapseCOL<?= md5($year) ?>" class="accordion-collapse collapse" data-bs-parent="#accordionCollege">
                                                <div class="accordion-body">
                                                    <div class="accordion" id="accordionCourse<?= md5($year) ?>">
                                                        <?php foreach ($courses as $course => $students): ?>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingCourse<?= md5($year.$course) ?>">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCourse<?= md5($year.$course) ?>">
                                                                        <i class="bi bi-folder2 me-2"></i><?= htmlspecialchars($course) ?> <span class="badge bg-primary ms-2"><?= count($students) ?></span>
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseCourse<?= md5($year.$course) ?>" class="accordion-collapse collapse" data-bs-parent="#accordionCourse<?= md5($year) ?>">
                                                                    <div class="accordion-body">
                                                                        <div class="list-group">
                                                                            <?php foreach ($students as $st): ?>
                                                                                <div class="list-group-item">
                                                                                    <div class="d-flex justify-content-between align-items-start">
                                                                                        <div>
                                                                                            <h6 class="mb-1">
                                                                                                <i class="bi bi-person me-2"></i>
                                                                                                <strong>
                                                                                                    <?php 
                                                                                                        $mid = ($st['mname'] !== "N/A" && !empty($st['mname'])) ? $st['mname']." " : "";
                                                                                                        echo $st['fname'].' '.$mid.$st['lname']; 
                                                                                                    ?>
                                                                                                </strong>
                                                                                            </h6>
                                                                                            <p class="mb-1 text-muted">
                                                                                                <i class="bi bi-hash me-1"></i><?= $st['student_no'] ?>
                                                                                            </p>
                                                                                            <small class="text-muted">
                                                                                                <i class="bi bi-envelope me-1"></i><?= $st['email'] ?> | 
                                                                                                <i class="bi bi-calendar me-1"></i><?= date('M j, Y', strtotime($st['bday'])) ?>
                                                                                            </small>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            <?php endforeach; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="bi bi-inbox display-1 text-muted"></i>
                                    <p class="text-muted mt-3">No college students found.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="bi bi-check-circle me-2"></i>Import Complete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modalMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Import Error</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="errorMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // File upload functionality
        const fileUploadArea = document.getElementById('fileUploadArea');
        const csvFile = document.getElementById('csvFile');
        const progressContainer = document.querySelector('.progress-container');
        const progressBar = document.querySelector('.progress-bar');
        const progressText = document.getElementById('progressText');
        const importResults = document.querySelector('.import-results');

        // Click to upload
        fileUploadArea.addEventListener('click', () => {
            csvFile.click();
        });

        // Drag and drop functionality
        fileUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileUploadArea.classList.add('dragover');
        });

        fileUploadArea.addEventListener('dragleave', () => {
            fileUploadArea.classList.remove('dragover');
        });

        fileUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            fileUploadArea.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleFile(files[0]);
            }
        });

        // File input change
        csvFile.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFile(e.target.files[0]);
            }
        });

        function handleFile(file) {
            // Validate file type
            if (!file.name.toLowerCase().endsWith('.csv')) {
                showError('Please select a valid CSV file.');
                return;
            }

            // Validate file size (10MB max)
            if (file.size > 10 * 1024 * 1024) {
                showError('File size must be less than 10MB.');
                return;
            }

            // Show progress
            progressContainer.style.display = 'block';
            importResults.style.display = 'none';
            
            // Process file
            processCSV(file);
        }

        function processCSV(file) {
            const formData = new FormData();
            formData.append('csv_file', file);
            formData.append('action', 'import_csv');

            fetch('process_csv_import.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                progressContainer.style.display = 'none';
                
                if (data.success) {
               showSuccess(data.message);
            // Optional: refresh only after user clicks OK
           const successModal = document.getElementById('successModal');
          successModal.addEventListener('hidden.bs.modal', () => {
          window.location.reload();
          });

                } else {
                    showError(data.message);
                }
            })
            .catch(error => {
                progressContainer.style.display = 'none';
                showError('An error occurred while processing the file.');
                console.error('Error:', error);
            });

            // Simulate progress for better UX
            let progress = 0;
            const interval = setInterval(() => {
                progress += Math.random() * 15;
                if (progress > 90) progress = 90;
                progressBar.style.width = progress + '%';
                progressText.textContent = `Processing... ${Math.round(progress)}%`;
            }, 200);

            // Clear interval after 10 seconds max
            setTimeout(() => {
                clearInterval(interval);
                progressBar.style.width = '100%';
                progressText.textContent = 'Finalizing...';
            }, 10000);
        }

        function showSuccess(message) {
            document.getElementById('modalMessage').innerHTML = message;
            const modal = new bootstrap.Modal(document.getElementById('successModal'));
            modal.show();
        }

        function showError(message) {
            document.getElementById('errorMessage').innerHTML = message;
            const modal = new bootstrap.Modal(document.getElementById('errorModal'));
            modal.show();
        }

        function downloadTemplate() {
            // Create CSV template
            const csvContent = "student_no,fname,mname,lname,bday,year_level,strand_course,personal_email\n" +
                              "2024001234,John,Michael,Doe,2005-01-15,Grade 11,STEM,john.doe@email.com\n" +
                              "2024001235,Jane,,Smith,2004-12-20,1st year,BSIT,jane.smith@email.com";
            
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'student_import_template.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }

        // Show any PHP messages
        <?php if (isset($modal_msg)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('modalMessage').innerHTML = '<?= $modal_msg ?>';
            var myModal = new bootstrap.Modal(document.getElementById('successModal'));
            myModal.show();
        });
        <?php endif; ?>
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98b3760d53230dcb',t:'MTc1OTkwMzY4MC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
