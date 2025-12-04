<?php
include 'db.php';
session_start();

if (!isset($_SESSION['admin_email'])) {
  header("Location: admin_login.php");
  exit();
}
?>
   
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exit Interview Form - Admin Dashboard</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        }

        .page-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .form-header {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 2rem;
            border-bottom: 3px solid #1976d2;
            text-align: center;
        }

        .form-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: #495057;
            margin: 0;
        }

        .form-subtitle {
            color: #1976d2;
            margin: 0.5rem 0 0 0;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .form-content {
            padding: 2rem;
        }

        .instructions {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .instructions p {
            margin: 0;
            color: #856404;
            font-weight: 500;
        }

        .form-section {
            margin-bottom: 2.5rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid #f1f3f4;
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #1976d2;
        }

        .form-table-header {
            display: grid;
            grid-template-columns: 2fr 80px 2fr 2fr;
            gap: 1rem;
            background: #1976d2;
            color: white;
            padding: 1rem;
            border-radius: 8px 8px 0 0;
            font-weight: 600;
            text-align: center;
        }

        .form-table-row {
            display: grid;
            grid-template-columns: 2fr 80px 2fr 2fr;
            gap: 1rem;
            padding: 1rem;
            border: 1px solid #dee2e6;
            border-top: none;
            align-items: center;
            transition: all 0.2s ease;
        }

        .form-table-row:hover {
            background: #f8f9fa;
        }

        .form-table-row:last-child {
            border-radius: 0 0 8px 8px;
        }

        .form-table-row > div:first-child {
            font-weight: 500;
            color: #495057;
        }

        .y-n-input {
            width: 60px !important;
            text-align: center;
            font-weight: 600;
            text-transform: uppercase;
        }

        .form-input-line {
            width: 100%;
            border: none;
            border-bottom: 2px solid #e2e8f0;
            padding: 0.75rem 0;
            font-size: 1rem;
            background: transparent;
            transition: all 0.2s ease;
        }

        .form-input-line:focus {
            outline: none;
            border-bottom-color: #1976d2;
            background: #f8f9fa;
        }

        .table-text-area {
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 0.5rem;
            font-size: 0.9rem;
            resize: vertical;
            transition: all 0.2s ease;
            min-height: 40px;
        }

        .table-text-area:focus {
            outline: none;
            border-color: #1976d2;
            box-shadow: 0 0 0 0.2rem rgba(25, 118, 210, 0.25);
        }

        .text-area-line {
            width: 100%;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            font-size: 1rem;
            resize: vertical;
            transition: all 0.2s ease;
        }

        .text-area-line:focus {
            outline: none;
            border-color: #1976d2;
            box-shadow: 0 0 0 0.2rem rgba(25, 118, 210, 0.25);
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: block;
        }

        .signature-section {
            background: #f0f8ff;
            border: 2px solid #b3d9ff;
            border-radius: 8px;
            padding: 1.5rem;
        }

        .counselor-notes {
            background: #f5f5f5;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 1.5rem;
        }

        .assessed-section {
            background: #e8f5e8;
            border: 2px solid #c3e6c3;
            border-radius: 8px;
            padding: 1.5rem;
        }

        .form-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            align-items: end;
        }

        .form-field {
            display: flex;
            flex-direction: column;
        }

        .full-width {
            width: 100%;
        }

        .small-input {
            max-width: 120px;
        }

        .grid-checkboxes {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin: 1rem 0;
        }

        .grid-checkbox-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .signature-container {
            display: flex;
            justify-content: space-between;
            margin: 3rem 0;
            gap: 2rem;
        }

        .signature-block {
            flex: 1;
            text-align: center;
        }

        .signature-line {
            border-bottom: 2px solid #000;
            height: 40px;
            margin-bottom: 0.5rem;
        }

        .signature-label {
            font-weight: 600;
            color: #495057;
        }

        .page-section {
            margin-bottom: 2rem;
        }

        .page-navigation {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin: 2rem 0;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .page-footer {
            background: #dc3545;
            color: white;
            padding: 1.5rem;
            text-align: center;
            margin-top: 2rem;
            border-radius: 8px;
        }

        .page-footer p {
            margin: 0.25rem 0;
        }

        .confidential-footer {
            background: #dc3545;
            color: white;
            padding: 1.5rem;
            text-align: center;
            margin-top: 2rem;
            border-radius: 8px;
        }

        .confidential-footer p {
            margin: 0.25rem 0;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin: 2rem 0;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            color: white;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-outline-secondary {
            border: 2px solid #6c757d;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            background: white;
            color: #6c757d;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-outline-secondary:hover {
            background: #6c757d;
            color: white;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            
            .form-table-header,
            .form-table-row {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
            
            .form-table-header > div,
            .form-table-row > div {
                text-align: left;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .form-row {
                flex-direction: column;
                gap: 0.5rem;
            }

            .grid-checkboxes {
                grid-template-columns: 1fr;
            }

            .signature-container {
                flex-direction: column;
                gap: 1rem;
            }
        }

        @media print {
            .sidebar, .page-header, .action-buttons, .btn, .page-navigation {
                display: none !important;
            }
            
            .main-content {
                margin-left: 0;
                padding: 0;
            }
            
            .form-container {
                box-shadow: none;
                border: 2px solid #000;
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
                <h1><i class="bi bi-door-open"></i> Exit Interview Form</h1>
                <p>Comprehensive two-page student feedback and service evaluation system</p>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button type="button" class="btn btn-primary" onclick="downloadPDF()">
                    <i class="bi bi-download"></i> Download PDF
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="printForm()">
                    <i class="bi bi-printer"></i> Print Form
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="clearForm()">
                    <i class="bi bi-arrow-clockwise"></i> Clear Form
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="fillSampleData()">
                    <i class="bi bi-file-plus"></i> Fill Sample Data
                </button>
            </div>

            <!-- Form Container -->
            <div class="form-container" id="exitInterviewForm">
                <div class="form-header">
                    <h1 class="form-title">STI Guidance and Counseling Office</h1>
                    <p class="form-subtitle">Exit Interview Form</p>
                </div>

                <div class="form-content">
                    <!-- Page 1 Content -->
                    <div class="page-section" id="page1">
                        <form action="#" method="post">
                            <div class="form-row">
                                <div class="form-field" style="flex-basis: 30%;">
                                    <label for="interviewDate" class="form-label">Interview Date:</label>
                                    <input type="date" id="interviewDate" name="interviewDate" class="form-input-line">
                                </div>
                                <div class="form-field" style="flex-basis: 30%;">
                                    <label for="timeStarted" class="form-label">Time Started:</label>
                                    <input type="text" id="timeStarted" name="timeStarted" class="form-input-line small-input" placeholder="e.g., 9:00 AM">
                                </div>
                                <div class="form-field" style="flex-basis: 30%;">
                                    <label for="timeEnded" class="form-label">Time Ended:</label>
                                    <input type="text" id="timeEnded" name="timeEnded" class="form-input-line small-input" placeholder="e.g., 10:30 AM">
                                </div>
                            </div>

                            <hr style="border: 0.5px solid #000; margin: 15px 0;">

                            <div class="form-row">
                                <div class="form-field" style="flex-basis: 20%;">
                                    <label class="form-label">SY:</label>
                                    <input type="text" id="sy" name="sy" class="form-input-line" placeholder="2023-2024">
                                </div>
                                <div class="form-field" style="flex-basis: 35%;">
                                    <label class="form-label">Year/Section:</label>
                                    <input type="text" id="yearSection" name="yearSection" class="form-input-line" placeholder="4th Year - A">
                                </div>
                                <div class="form-field" style="flex-basis: 35%;">
                                    <label class="form-label">Student No.:</label>
                                    <input type="text" id="studentNo" name="studentNo" class="form-input-line" placeholder="2020-123456">
                                </div>
                            </div>

                            <div class="form-row" style="margin-bottom: 10px;">
                                <div class="form-field" style="flex-grow: 0; margin-right: 15px; width: auto;">
                                    <label class="form-label">STI</label>
                                    <input type="checkbox" id="sti_checkbox" name="sti_checkbox">
                                </div>
                                <div class="form-field" style="flex-grow: 0; margin-right: 15px; width: auto;">
                                    <label class="form-label">Tertiary (Semester)</label>
                                    <input type="checkbox" id="tertiary_q1" name="tertiary_q" value="1">
                                    <label for="tertiary_q1">1<sup>st</sup></label>
                                    <input type="checkbox" id="tertiary_q2" name="tertiary_q" value="2">
                                    <label for="tertiary_q2">2<sup>nd</sup></label>
                                </div>
                                <div class="form-field" style="flex-grow: 0; margin-right: 15px; width: auto;">
                                    <label class="form-label">Summer</label>
                                    <input type="checkbox" id="summer_checkbox" name="summer_checkbox">
                                </div>
                                <div class="form-field" style="flex-basis: 40%;">
                                    <label for="courseProgram" class="form-label">Course/Program:</label>
                                    <input type="text" id="courseProgram" name="courseProgram" class="form-input-line" placeholder="BS Computer Science">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-field" style="flex-grow: 0; margin-right: 15px; width: auto;">
                                    <label class="form-label">Senior High (Quarter)</label>
                                    <input type="checkbox" id="sh_q1" name="sh_q" value="1">
                                    <label for="sh_q1">1<sup>st</sup></label>
                                    <input type="checkbox" id="sh_q2" name="sh_q" value="2">
                                    <label for="sh_q2">2<sup>nd</sup></label>
                                    <input type="checkbox" id="sh_q3" name="sh_q" value="3">
                                    <label for="sh_q3">3<sup>rd</sup></label>
                                    <input type="checkbox" id="sh_q4" name="sh_q" value="4">
                                    <label for="sh_q4">4<sup>th</sup></label>
                                </div>
                                <div class="form-field" style="flex-grow: 0; width: auto;">
                                    <label class="form-label">Summer</label>
                                    <input type="checkbox" id="sh_summer_checkbox" name="sh_summer_checkbox">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-field" style="flex-basis: 40%;">
                                    <label for="name" class="form-label">Name:</label>
                                    <input type="text" id="name" name="name" class="form-input-line" placeholder="Juan Dela Cruz">
                                </div>
                                <div class="form-field" style="flex-basis: 20%;">
                                    <label for="gender" class="form-label">Gender:</label>
                                    <input type="text" id="gender" name="gender" class="form-input-line" placeholder="Male/Female">
                                </div>
                                <div class="form-field" style="flex-basis: 20%;">
                                    <label for="status" class="form-label">Status:</label>
                                    <input type="text" id="status" name="status" class="form-input-line" placeholder="Single/Married">
                                </div>
                                <div class="form-field" style="flex-basis: 10%;">
                                    <label for="age" class="form-label">Age:</label>
                                    <input type="text" id="age" name="age" class="form-input-line" placeholder="22">
                                </div>
                            </div>

                            <div class="form-row" style="margin-top: 10px;">
                                <label style="font-weight: bold; width: 100%; color: #1976d2;">Contact Information</label>
                            </div>

                            <div class="form-row">
                                <div class="form-field" style="flex-basis: 48%;">
                                    <label for="cellphoneNumber" class="form-label">Cellular Phone Number/s:</label>
                                    <input type="text" id="cellphoneNumber" name="cellphoneNumber" class="form-input-line" placeholder="09123456789">
                                </div>
                                <div class="form-field" style="flex-basis: 48%;">
                                    <label for="email1" class="form-label">Email Address 1:</label>
                                    <input type="email" id="email1" name="email1" class="form-input-line" placeholder="student@email.com">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-field" style="flex-basis: 48%;">
                                    <label for="email2" class="form-label">Email Address 2:</label>
                                    <input type="email" id="email2" name="email2" class="form-input-line" placeholder="alternate@email.com">
                                </div>
                                <div class="form-field" style="flex-basis: 48%;">
                                    <label for="homeNumber" class="form-label">Home Number:</label>
                                    <input type="text" id="homeNumber" name="homeNumber" class="form-input-line" placeholder="(02) 1234567">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-field full-width">
                                    <label for="presentAddress" class="form-label">Present Address:</label>
                                    <input type="text" id="presentAddress" name="presentAddress" class="form-input-line" placeholder="123 Main St., Barangay ABC, City, Province">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-field full-width">
                                    <label for="permanentAddress" class="form-label">Permanent Address:</label>
                                    <input type="text" id="permanentAddress" name="permanentAddress" class="form-input-line" placeholder="456 Home St., Barangay XYZ, City, Province">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-field full-width">
                                    <label for="provincialAddress" class="form-label">Provincial Address:</label>
                                    <input type="text" id="provincialAddress" name="provincialAddress" class="form-input-line" placeholder="789 Province St., Municipality, Province">
                                </div>
                            </div>

                            <div class="form-row" style="margin-top: 15px;">
                                <label style="font-weight: bold; margin-bottom: 5px; color: #1976d2;">Reason for Leaving</label>
                            </div>

                            <div class="form-row">
                                <div class="form-field" style="flex-grow: 0; margin-right: 15px; width: auto;">
                                    <input type="checkbox" id="graduating" name="reasonForLeaving" value="Graduating">
                                    <label for="graduating" class="form-label">Graduating</label>
                                </div>
                                <div class="form-field" style="flex-grow: 0; margin-right: 15px; width: auto;">
                                    <input type="checkbox" id="withdrawal" name="reasonForLeaving" value="Withdrawal">
                                    <label for="withdrawal" class="form-label">Withdrawal</label>
                                </div>
                                <div class="form-field" style="flex-grow: 0; margin-right: 15px; width: auto;">
                                    <input type="checkbox" id="transferNewSchool" name="reasonForLeaving" value="Transfer to a New School">
                                    <label for="transferNewSchool" class="form-label">Transfer to a New School</label>
                                </div>
                                <div class="form-field" style="flex-grow: 0; width: auto;">
                                    <input type="checkbox" id="leaveOfAbsence" name="reasonForLeaving" value="Leave of Absence">
                                    <label for="leaveOfAbsence" class="form-label">Leave of Absence</label>
                                </div>
                            </div>

                            <div class="form-row" style="margin-top: 10px;">
                                <label style="font-weight: bold; margin-bottom: 5px; color: #1976d2;">Reason for Withdrawal/Transfer/Leave of Absence</label>
                            </div>

                            <div class="grid-checkboxes">
                                <div class="grid-checkbox-item">
                                    <input type="checkbox" id="financialConcerns" name="withdrawalReason" value="Financial concerns">
                                    <label for="financialConcerns" class="form-label">Financial concerns</label>
                                </div>
                                <div class="grid-checkbox-item">
                                    <input type="checkbox" id="familyConcerns" name="withdrawalReason" value="Family concerns">
                                    <label for="familyConcerns" class="form-label">Family concerns</label>
                                </div>
                                <div class="grid-checkbox-item">
                                    <input type="checkbox" id="personalProblems" name="withdrawalReason" value="Personal problem/s">
                                    <label for="personalProblems" class="form-label">Personal problem/s</label>
                                </div>
                                <div class="grid-checkbox-item">
                                    <input type="checkbox" id="healthConditions" name="withdrawalReason" value="Health conditions">
                                    <label for="healthConditions" class="form-label">Health conditions</label>
                                </div>
                                <div class="grid-checkbox-item">
                                    <input type="checkbox" id="toFocusOnWork" name="withdrawalReason" value="To focus on work">
                                    <label for="toFocusOnWork" class="form-label">To focus on work</label>
                                </div>
                                <div class="grid-checkbox-item">
                                    <input type="checkbox" id="teacherFactor" name="withdrawalReason" value="Teacher factor">
                                    <label for="teacherFactor" class="form-label">Teacher factor</label>
                                </div>
                                <div class="grid-checkbox-item">
                                    <input type="checkbox" id="transferOfResidence" name="withdrawalReason" value="Transfer of residence">
                                    <label for="transferOfResidence" class="form-label">Transfer of residence</label>
                                </div>
                                <div class="grid-checkbox-item">
                                    <input type="checkbox" id="dissatisfactionProgram" name="withdrawalReason" value="Dissatisfaction with program">
                                    <label for="dissatisfactionProgram" class="form-label">Dissatisfaction with program</label>
                                </div>
                                <div class="grid-checkbox-item">
                                    <input type="checkbox" id="shiftingOfProgram" name="withdrawalReason" value="Shifting of program">
                                    <label for="shiftingOfProgram" class="form-label">Shifting of program</label>
                                </div>
                                <div class="grid-checkbox-item" style="grid-column: span 3; display: flex; align-items: center;">
                                    <input type="checkbox" id="othersPlsSpecify" name="withdrawalReason" value="Others (pls. specify):">
                                    <label for="othersPlsSpecify" class="form-label" style="margin-right: 5px;">Others (pls. specify):</label>
                                    <input type="text" id="othersSpecifyText" name="othersSpecifyText" class="form-input-line" style="flex-grow: 1;" placeholder="Please specify...">
                                </div>
                            </div>

                            <div class="form-row" style="margin-top: 20px;">
                                <label style="font-weight: bold; width: 100%; color: #1976d2;">Plans after leaving</label>
                                <textarea class="text-area-line" id="plansAfterLeaving" name="plansAfterLeaving" rows="4" placeholder="Describe your plans after leaving STI..."></textarea>
                            </div>

                            <div class="form-row" style="margin-top: 20px;">
                                <label style="font-weight: bold; width: 100%; color: #1976d2;">What were the values you learned from STI?</label>
                                <textarea class="text-area-line" id="valuesLearned" name="valuesLearned" rows="4" placeholder="Share the values you learned during your time at STI..."></textarea>
                            </div>

                            <div class="form-row" style="margin-top: 20px; margin-bottom: 50px;">
                                <label style="font-weight: bold; width: 100%; color: #1976d2;">What were the skills you learned from STI?</label>
                                <textarea class="text-area-line" id="skillsLearned" name="skillsLearned" rows="4" placeholder="List the skills you acquired at STI..."></textarea>
                            </div>

                            <div class="signature-container">
                                <div class="signature-block">
                                    <div class="signature-line"></div>
                                    <span class="signature-label">Interviewed by</span>
                                </div>
                                <div class="signature-block">
                                    <div class="signature-line"></div>
                                    <span class="signature-label">Student's Signature over Printed Name</span>
                                </div>
                            </div>

                            <div class="page-footer">
                                <p>Copyright 2017 STI EDUCATION SERVICES GROUP, INC. All rights reserved.</p>
                                <p><strong>STRICTLY CONFIDENTIAL. Should only be accessed by the Guidance Counselor/Associate.</strong></p>
                                <p class="mt-3"><strong>STUDENT DEVELOPMENT AND WELFARE</strong></p>
                                <p><strong>FT-SDW-098-00 | EXIT INTERVIEW FORM | PAGE 1 OF 2</strong></p>
                            </div>
                        </form>
                    </div>

                    <!-- Page Navigation -->
                    <div class="page-navigation">
                        <button type="button" class="btn btn-primary" onclick="showPage(2)">
                            <i class="bi bi-arrow-right"></i> Continue to Page 2
                        </button>
                    </div>

                    <!-- Page 2 Content (Services & Activities Evaluation) -->
                    <div class="page-section" id="page2" style="display: none;">
                        <div class="instructions">
                            <p>
                                Write 'Y' for "Yes" if you availed of the service and activity. Write 'N' for "No" if not. 
                                Please feel free to write your comments about your stay at STI as well as your recommendations 
                                for the improvement of our services and activities.
                            </p>
                        </div>

                        <!-- Services Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="bi bi-gear-fill"></i> Services Evaluation
                            </div>
                            
                            <div class="form-table-header">
                                <div>Services</div>
                                <div>Y/N</div>
                                <div>Comments</div>
                                <div>Recommendations</div>
                            </div>

                            <div class="form-table-row">
                                <div>Library Services</div>
                                <input type="text" class="form-input-line y-n-input" maxlength="1" placeholder="Y/N">
                                <textarea class="table-text-area" rows="1" placeholder="Enter comments here..."></textarea>
                                <textarea class="table-text-area" rows="1" placeholder="Enter recommendations here..."></textarea>
                            </div>
                            
                            <div class="form-table-row">
                                <div>Computer Laboratory Services</div>
                                <input type="text" class="form-input-line y-n-input" maxlength="1" placeholder="Y/N">
                                <textarea class="table-text-area" rows="1" placeholder="Enter comments here..."></textarea>
                                <textarea class="table-text-area" rows="1" placeholder="Enter recommendations here..."></textarea>
                            </div>
                            
                            <div class="form-table-row">
                                <div>Records Services (Registrar)</div>
                                <input type="text" class="form-input-line y-n-input" maxlength="1" placeholder="Y/N">
                                <textarea class="table-text-area" rows="1" placeholder="Enter comments here..."></textarea>
                                <textarea class="table-text-area" rows="1" placeholder="Enter recommendations here..."></textarea>
                            </div>
                            
                            <div class="form-table-row">
                                <div>Counseling Services</div>
                                <input type="text" class="form-input-line y-n-input" maxlength="1" placeholder="Y/N">
                                <textarea class="table-text-area" rows="1" placeholder="Enter comments here..."></textarea>
                                <textarea class="table-text-area" rows="1" placeholder="Enter recommendations here..."></textarea>
                            </div>
                            
                            <div class="form-table-row">
                                <div>Guidance Services</div>
                                <input type="text" class="form-input-line y-n-input" maxlength="1" placeholder="Y/N">
                                <textarea class="table-text-area" rows="1" placeholder="Enter comments here..."></textarea>
                                <textarea class="table-text-area" rows="1" placeholder="Enter recommendations here..."></textarea>
                            </div>
                            
                            <div class="form-table-row">
                                <div>Admissions</div>
                                <input type="text" class="form-input-line y-n-input" maxlength="1" placeholder="Y/N">
                                <textarea class="table-text-area" rows="1" placeholder="Enter comments here..."></textarea>
                                <textarea class="table-text-area" rows="1" placeholder="Enter recommendations here..."></textarea>
                            </div>
                            
                            <div class="form-table-row">
                                <div>Facilities</div>
                                <input type="text" class="form-input-line y-n-input" maxlength="1" placeholder="Y/N">
                                <textarea class="table-text-area" rows="1" placeholder="Enter comments here..."></textarea>
                                <textarea class="table-text-area" rows="1" placeholder="Enter recommendations here..."></textarea>
                            </div>
                            
                            <div class="form-table-row">
                                <div>Faculty Members/Staff</div>
                                <input type="text" class="form-input-line y-n-input" maxlength="1" placeholder="Y/N">
                                <textarea class="table-text-area" rows="1" placeholder="Enter comments here..."></textarea>
                                <textarea class="table-text-area" rows="1" placeholder="Enter recommendations here..."></textarea>
                            </div>
                            
                            <div class="form-table-row">
                                <div>Clinic Services</div>
                                <input type="text" class="form-input-line y-n-input" maxlength="1" placeholder="Y/N">
                                <textarea class="table-text-area" rows="1" placeholder="Enter comments here..."></textarea>
                                <textarea class="table-text-area" rows="1" placeholder="Enter recommendations here..."></textarea>
                            </div>
                            
                            <div class="form-table-row">
                                <div>Canteen Services</div>
                                <input type="text" class="form-input-line y-n-input" maxlength="1" placeholder="Y/N">
                                <textarea class="table-text-area" rows="1" placeholder="Enter comments here..."></textarea>
                                <textarea class="table-text-area" rows="1" placeholder="Enter recommendations here..."></textarea>
                            </div>
                            
                            <div class="form-table-row">
                                <div>Security Services</div>
                                <input type="text" class="form-input-line y-n-input" maxlength="1" placeholder="Y/N">
                                <textarea class="table-text-area" rows="1" placeholder="Enter comments here..."></textarea>
                                <textarea class="table-text-area" rows="1" placeholder="Enter recommendations here..."></textarea>
                            </div>
                            
                            <div class="form-table-row">
                                <div>Other Services:</div>
                                <input type="text" class="form-input-line y-n-input" maxlength="1" placeholder="Y/N">
                                <textarea class="table-text-area" rows="1" placeholder="Enter comments here..."></textarea>
                                <textarea class="table-text-area" rows="1" placeholder="Enter recommendations here..."></textarea>
                            </div>
                            
                            <div class="form-table-row">
                                <input type="text" class="form-input-line" placeholder="Specify other service...">
                                <input type="text" class="form-input-line y-n-input" maxlength="1" placeholder="Y/N">
                                <textarea class="table-text-area" rows="1" placeholder="Enter comments here..."></textarea>
                                <textarea class="table-text-area" rows="1" placeholder="Enter recommendations here..."></textarea>
                            </div>
                        </div>

                        <!-- Activities Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="bi bi-calendar-event"></i> Activities Evaluation
                            </div>
                            
                            <div class="form-table-header">
                                <div>Activities</div>
                                <div>Y/N</div>
                                <div>Comments</div>
                                <div>Recommendations</div>
                            </div>

                            <div class="form-table-row">
                                <div>Student Organizations</div>
                                <input type="text" class="form-input-line y-n-input" maxlength="1" placeholder="Y/N">
                                <textarea class="table-text-area" rows="1" placeholder="Enter comments here..."></textarea>
                                <textarea class="table-text-area" rows="1" placeholder="Enter recommendations here..."></textarea>
                            </div>
                            
                            <div class="form-table-row">
                                <div>Sports Fest</div>
                                <input type="text" class="form-input-line y-n-input" maxlength="1" placeholder="Y/N">
                                <textarea class="table-text-area" rows="1" placeholder="Enter comments here..."></textarea>
                                <textarea class="table-text-area" rows="1" placeholder="Enter recommendations here..."></textarea>
                            </div>
                            
                            <div class="form-table-row">
                                <div>Educational Tours</div>
                                <input type="text" class="form-input-line y-n-input" maxlength="1" placeholder="Y/N">
                                <textarea class="table-text-area" rows="1" placeholder="Enter recommendations here..."></textarea>
                                <textarea class="table-text-area" rows="1" placeholder="Enter recommendations here..."></textarea>
                            </div>
                            
                            <div class="form-table-row">
                                <div>Other Activities:</div>
                                <input type="text" class="form-input-line y-n-input" maxlength="1" placeholder="Y/N">
                                <textarea class="table-text-area" rows="1" placeholder="Enter comments here..."></textarea>
                                <textarea class="table-text-area" rows="1" placeholder="Enter recommendations here..."></textarea>
                            </div>
                            
                            <div class="form-table-row">
                                <input type="text" class="form-input-line" placeholder="Specify other activity...">
                                <input type="text" class="form-input-line y-n-input" maxlength="1" placeholder="Y/N">
                                <textarea class="table-text-area" rows="1" placeholder="Enter comments here..."></textarea>
                                <textarea class="table-text-area" rows="1" placeholder="Enter recommendations here..."></textarea>
                            </div>
                        </div>

                        <!-- Student's Signature Section -->
                        <div class="form-section signature-section">
                            <div class="section-title">
                                <i class="bi bi-pen"></i> Student Authorization
                            </div>
                            <label for="studentSignature" class="form-label">Student's signature over printed name</label>
                            <input type="text" id="studentSignature" class="form-input-line" placeholder="Student's name and signature">
                        </div>

                        <!-- Counselor/Associate's Notes Section -->
                        <div class="form-section counselor-notes">
                            <div class="section-title">
                                <i class="bi bi-journal-text"></i> Counselor's Assessment
                            </div>
                            <label for="counselorNotes" class="form-label">Counselor/Associate's Notes</label>
                            <textarea id="counselorNotes" class="text-area-line" rows="8" placeholder="Enter detailed notes and observations..."></textarea>
                        </div>

                        <!-- Assessed By Section -->
                        <div class="form-section assessed-section">
                            <div class="section-title">
                                <i class="bi bi-person-check"></i> Assessment Authorization
                            </div>
                            <p class="form-label mb-3">Assessed by:</p>
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="assessedByCounselor" class="form-label">Guidance Counselor/Associate's name and signature</label>
                                    <input type="text" id="assessedByCounselor" class="form-input-line" placeholder="Counselor's name and signature">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="assessedDate" class="form-label">Date:</label>
                                    <input type="date" id="assessedDate" class="form-input-line">
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="confidential-footer">
                            <p><strong>Copyright 2017 STI EDUCATION SERVICES GROUP, INC. All rights reserved.</strong></p>
                            <p><strong>STRICTLY CONFIDENTIAL. Should only be accessed by the Guidance Counselor/Associate.</strong></p>
                            <p class="mt-3"><strong>STUDENT DEVELOPMENT AND WELFARE</strong></p>
                            <p><strong>FT-SDW-098-00 | EXIT INTERVIEW FORM | PAGE 2 OF 2</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Toggle -->
    <button class="btn btn-primary d-md-none position-fixed" style="top: 1rem; left: 1rem; z-index: 1100;" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.querySelector('[onclick="toggleSidebar()"]');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !toggleBtn.contains(e.target) && 
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });

        function showPage(pageNumber) {
            // Hide all pages
            document.querySelectorAll('.page-section').forEach(page => {
                page.style.display = 'none';
            });
            
            // Show selected page
            const targetPage = document.getElementById(`page${pageNumber}`);
            if (targetPage) {
                targetPage.style.display = 'block';
            }
            
            // Update navigation
            const navigation = document.querySelector('.page-navigation');
            if (pageNumber === 1) {
                navigation.innerHTML = `
                    <button type="button" class="btn btn-primary" onclick="showPage(2)">
                        <i class="bi bi-arrow-right"></i> Continue to Page 2
                    </button>
                `;
            } else {
                navigation.innerHTML = `
                    <button type="button" class="btn btn-outline-secondary" onclick="showPage(1)">
                        <i class="bi bi-arrow-left"></i> Back to Page 1
                    </button>
                    <button type="button" class="btn btn-primary" onclick="downloadPDF()">
                        <i class="bi bi-download"></i> Download Complete Form
                    </button>
                `;
            }
            
            showNotification(`Switched to Page ${pageNumber}`, 'info');
        }

        function downloadPDF() {
            // Show both pages for PDF generation
            document.querySelectorAll('.page-section').forEach(page => {
                page.style.display = 'block';
            });
            
            const original = document.getElementById('exitInterviewForm');
            const clone = original.cloneNode(true);
            
            // Hide navigation in PDF
            const nav = clone.querySelector('.page-navigation');
            if (nav) nav.style.display = 'none';
            
            clone.style.position = 'static';
            clone.style.margin = '0';
            clone.style.boxShadow = 'none';
            clone.style.background = 'white';
            document.body.appendChild(clone);

            html2pdf().set({
                margin: 0.25,
                filename: 'exit_interview_form_complete.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 1.2 },
                jsPDF: { unit: 'in', format: [8.5, 14], orientation: 'portrait' }
            }).from(clone).save().then(() => {
                document.body.removeChild(clone);
                // Restore original page view
                showPage(1);
                showNotification('Complete PDF downloaded successfully!', 'success');
            });
        }

        function printForm() {
            // Show both pages for printing
            document.querySelectorAll('.page-section').forEach(page => {
                page.style.display = 'block';
            });
            window.print();
            // Restore original page view
            setTimeout(() => showPage(1), 100);
            showNotification('Form sent to printer!', 'info');
        }

        function clearForm() {
            if (confirm('Are you sure you want to clear all form data?')) {
                document.getElementById('exitInterviewForm').querySelectorAll('input, textarea').forEach(element => {
                    if (element.type !== 'checkbox') {
                        element.value = '';
                    } else {
                        element.checked = false;
                    }
                });
                showNotification('Form cleared successfully!', 'info');
            }
        }

        function fillSampleData() {
            // Page 1 sample data
            document.getElementById('interviewDate').value = new Date().toISOString().split('T')[0];
            document.getElementById('timeStarted').value = '9:00 AM';
            document.getElementById('timeEnded').value = '10:30 AM';
            document.getElementById('sy').value = '2023-2024';
            document.getElementById('yearSection').value = '4th Year - A';
            document.getElementById('studentNo').value = '2020-123456';
            document.getElementById('courseProgram').value = 'BS Computer Science';
            document.getElementById('name').value = 'Juan Dela Cruz';
            document.getElementById('gender').value = 'Male';
            document.getElementById('status').value = 'Single';
            document.getElementById('age').value = '22';
            document.getElementById('cellphoneNumber').value = '09123456789';
            document.getElementById('email1').value = 'juan.delacruz@email.com';
            document.getElementById('email2').value = 'juan.alt@email.com';
            document.getElementById('homeNumber').value = '(02) 1234567';
            document.getElementById('presentAddress').value = '123 Main St., Barangay ABC, Quezon City, Metro Manila';
            document.getElementById('permanentAddress').value = '456 Home St., Barangay XYZ, Quezon City, Metro Manila';
            document.getElementById('provincialAddress').value = '789 Province St., Municipality, Batangas';
            document.getElementById('graduating').checked = true;
            document.getElementById('plansAfterLeaving').value = 'I plan to pursue a career in software development and continue learning new technologies.';
            document.getElementById('valuesLearned').value = 'Integrity, excellence, service, and teamwork. STI taught me the importance of continuous learning and professional growth.';
            document.getElementById('skillsLearned').value = 'Programming languages (Java, Python, C#), database management, project management, communication skills, and problem-solving abilities.';
            
            // Page 2 sample data
            const ynInputs = document.querySelectorAll('.y-n-input');
            const responses = ['Y', 'Y', 'N', 'Y', 'Y', 'N', 'Y', 'Y', 'Y', 'N', 'Y', 'N', 'Y', 'Y', 'N'];
            
            ynInputs.forEach((input, index) => {
                if (index < responses.length) {
                    input.value = responses[index];
                }
            });
            
            const comments = document.querySelectorAll('.table-text-area');
            comments.forEach((textarea, index) => {
                if (index % 2 === 0) {
                    textarea.value = 'Good service overall, helpful staff.';
                } else {
                    textarea.value = 'Could improve response time and accessibility.';
                }
            });
            
            document.getElementById('studentSignature').value = 'Juan Dela Cruz - Student';
            document.getElementById('counselorNotes').value = 'Student showed positive attitude throughout the interview. Expressed satisfaction with most services. Provided constructive feedback for improvement areas.';
            document.getElementById('assessedByCounselor').value = 'Maria Santos - Guidance Counselor';
            document.getElementById('assessedDate').value = new Date().toISOString().split('T')[0];
            
            showNotification('Sample data filled successfully!', 'success');
        }

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} position-fixed`;
            notification.style.cssText = 'top: 2rem; right: 2rem; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                <i class="bi bi-${type === 'error' ? 'exclamation-triangle' : type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }

        // Auto-uppercase Y/N inputs
        document.querySelectorAll('.y-n-input').forEach(input => {
            input.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
                if (this.value !== 'Y' && this.value !== 'N' && this.value !== '') {
                    this.value = '';
                }
            });
        });

        // Initialize with page 1
        showPage(1);
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98b31d3a77c10dcb',t:'MTc1OTkwMDA0MS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
