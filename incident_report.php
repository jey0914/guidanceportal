<?php
include 'db.php';
session_start();

if (!isset($_SESSION['admin_email'])) {
  header("Location: admin_login.php");
  exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // sample only
    $stmt = $con->prepare("INSERT INTO incident_reports (student_email, report_date, summary, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $_POST['student_email'], $_POST['report_date'], $_POST['summary'], $_POST['status']);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Report - Admin Dashboard</title>
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
            background: #f8f9fa;
            padding: 2rem;
            border-bottom: 1px solid #dee2e6;
            text-align: center;
        }

        .form-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: #495057;
            margin: 0;
        }

        .form-subtitle {
            color: #6c757d;
            margin: 0.5rem 0 0 0;
            font-style: italic;
        }

        .form-content {
            padding: 2rem;
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
            font-size: 1.1rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: block;
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

        .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .checkbox-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .checkbox-item:hover {
            background: #e9ecef;
        }

        .checkbox-item input[type="checkbox"],
        .checkbox-item input[type="radio"] {
            margin-top: 0.25rem;
            transform: scale(1.2);
            accent-color: #1976d2;
        }

        .checkbox-item label {
            flex: 1;
            color: #495057;
            font-weight: 500;
            cursor: pointer;
        }

        .sub-options {
            display: flex;
            gap: 1rem;
            margin-left: 2rem;
            margin-top: 0.5rem;
            flex-wrap: wrap;
        }

        .sub-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #f1f3f4;
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

        .confidential-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .confidential-note p {
            margin: 0;
            color: #856404;
            font-size: 0.9rem;
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
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
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
                <h1><i class="bi bi-exclamation-triangle"></i> Incident Report Form</h1>
                <p>Create and manage incident reports for safety assessment purposes</p>
            </div>

            <!-- Form Container -->
            <div class="form-container">
                <div class="form-header">
                    <h2 class="form-title">Incident Report</h2>
                    <p class="form-subtitle">(Please print the template and submit a handwritten report)</p>
                </div>

                <div class="form-content">
                    <div id="incidentForm">
                        <!-- Section: Person Reporting -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="bi bi-person-fill"></i> Reporter Information
                            </h3>
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label for="student_email" class="form-label">Name of Person Reporting:</label>
                                    <input type="email" name="student_email" id="student_email" class="form-input-line" placeholder="Enter full name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="dateIncident" class="form-label">Date of Incident Report:</label>
                                    <input type="date" id="dateIncident" class="form-input-line">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="courseJob" class="form-label">Course/Section or Job Title of Person Reporting:</label>
                                <input type="text" id="courseJob" class="form-input-line" placeholder="Enter course/section or job title">
                            </div>

                            <div>
                                <label for="dateTimeIncident" class="form-label">Date, Place and Time of Incident:</label>
                                <input type="text" id="dateTimeIncident" class="form-input-line" placeholder="Enter date, place and time details">
                            </div>
                        </div>

                        <!-- Section: People Present -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="bi bi-people-fill"></i> Witnesses Present
                            </h3>
                            <p class="form-label mb-3">Were there other people present during the incident?</p>
                            <div class="d-flex gap-4 mb-4">
                                <div class="checkbox-item">
                                    <input type="radio" id="presentYes" name="peoplePresent" value="yes">
                                    <label for="presentYes">Yes</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="radio" id="presentNo" name="peoplePresent" value="no">
                                    <label for="presentNo">No</label>
                                </div>
                            </div>
                            <p class="text-muted mb-3">
                                If yes, kindly enumerate the full names and course/section or job title. Attach to 2<sup>nd</sup> page if space provided cannot accommodate.
                            </p>
                            <textarea id="peoplePresentDetails" class="text-area-line" rows="4" placeholder="List names and details of people present..."></textarea>
                        </div>

                        <!-- Section: Type of Incident -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="bi bi-clipboard-check"></i> Incident Classification
                            </h3>
                            <p class="form-label mb-4">What type of incident? (check all that apply)</p>
                            <div class="checkbox-group">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="personHarmed" name="incidentType" value="personHarmed">
                                    <label for="personHarmed">1. A person was harmed or injured during the incident</label>
                                </div>
                                <div class="sub-options">
                                    <div class="sub-option">
                                        <input type="checkbox" id="harmed" name="harmStatus" value="harmed">
                                        <label for="harmed">harmed</label>
                                    </div>
                                    <div class="sub-option">
                                        <input type="checkbox" id="injured" name="harmStatus" value="injured">
                                        <label for="injured">injured</label>
                                    </div>
                                </div>

                                <div class="checkbox-item">
                                    <input type="checkbox" id="propertyDamage" name="incidentType" value="propertyDamage">
                                    <label for="propertyDamage">2. Property or inventory were damaged</label>
                                </div>
                                <div class="sub-options">
                                    <div class="sub-option">
                                        <input type="checkbox" id="property" name="damageType" value="property">
                                        <label for="property">property</label>
                                    </div>
                                    <div class="sub-option">
                                        <input type="checkbox" id="inventory" name="damageType" value="inventory">
                                        <label for="inventory">inventory</label>
                                    </div>
                                </div>
                                <p class="text-muted ms-4 mt-2">
                                    <i class="bi bi-camera me-1"></i> Identity and attach pictures below or to next page.
                                </p>

                                <div class="checkbox-item">
                                    <input type="checkbox" id="theftRobbery" name="incidentType" value="theftRobbery">
                                    <label for="theftRobbery">3. Were there theft/robbery</label>
                                </div>
                                <div class="sub-options">
                                    <div class="sub-option">
                                        <input type="checkbox" id="theft" name="crimeType" value="theft">
                                        <label for="theft">theft</label>
                                    </div>
                                    <div class="sub-option">
                                        <input type="checkbox" id="robbery" name="crimeType" value="robbery">
                                        <label for="robbery">robbery</label>
                                    </div>
                                </div>

                                <div class="checkbox-item">
                                    <input type="checkbox" id="othersSpecify" name="incidentType" value="othersSpecify">
                                    <label for="othersSpecify">4. Others, please specify:</label>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Brief Description -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="bi bi-file-text"></i> Incident Details
                            </h3>
                            <label for="briefDescription" class="form-label">Brief Description of Incident:</label>
                            <textarea id="briefDescription" class="text-area-line" rows="8" placeholder="Provide a detailed description of what happened..."></textarea>
                        </div>

                        <!-- Section: Signature -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="bi bi-pen"></i> Authorization
                            </h3>
                            <label for="signature" class="form-label">Name/Signature and Date Signed:</label>
                            <input type="text" id="signature" class="form-input-line">
                            
                            <div class="confidential-note">
                                <p>
                                    <i class="bi bi-shield-lock me-2"></i>
                                    <strong>Submitted to:</strong> (please provide full name and job title of person)<br>
                                    <em>This will be treated as a confidential document and will be used for safety assessment purposes in the future.</em>
                                </p>
                            </div>
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

        function downloadPDF() {
            const original = document.getElementById('incidentForm');
            const clone = original.cloneNode(true);
            
            // Remove action buttons from PDF
            const actionButtons = clone.querySelector('.action-buttons');
            if (actionButtons) {
                actionButtons.remove();
            }
            
            clone.style.position = 'static';
            clone.style.margin = '0';
            clone.style.boxShadow = 'none';
            clone.style.background = 'white';
            document.body.appendChild(clone);

            html2pdf().set({
                margin: 0.25,
                filename: 'incident_report.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 1.5 },
                jsPDF: { unit: 'in', format: [8.5, 11], orientation: 'portrait' }
            }).from(clone).save().then(() => {
                document.body.removeChild(clone);
                showNotification('PDF downloaded successfully!', 'success');
            });
        }

        function printForm() {
            const original = document.getElementById('incidentForm');
            const clone = original.cloneNode(true);
            
            // Remove action buttons from print
            const actionButtons = clone.querySelector('.action-buttons');
            if (actionButtons) {
                actionButtons.remove();
            }
            
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Incident Report</title>
                        <style>
                            body { font-family: Arial, sans-serif; margin: 20px; }
                            .form-section { margin-bottom: 30px; page-break-inside: avoid; }
                            .section-title { font-weight: bold; margin-bottom: 15px; }
                            .form-input-line { border-bottom: 1px solid #000; padding: 5px 0; margin-bottom: 10px; }
                            .text-area-line { border: 1px solid #000; padding: 10px; }
                            .checkbox-item { margin: 10px 0; }
                        </style>
                    </head>
                    <body>
                        ${clone.outerHTML}
                    </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
            showNotification('Form sent to printer!', 'info');
        }

        function clearForm() {
            if (confirm('Are you sure you want to clear all form data?')) {
                document.getElementById('incidentForm').querySelectorAll('input, textarea').forEach(element => {
                    if (element.type === 'checkbox' || element.type === 'radio') {
                        element.checked = false;
                    } else {
                        element.value = '';
                    }
                });
                showNotification('Form cleared successfully!', 'info');
            }
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
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98ac4686b7160dc9',t:'MTc1OTgyODMzMi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
