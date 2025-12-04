<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

include("db.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Examination Form - Admin Dashboard</title>
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

        .form-copy {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            overflow: hidden;
            page-break-after: always;
        }

        .form-copy h2 {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            color: #495057;
            text-align: center;
            padding: 1.5rem;
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            border-bottom: 3px solid #1976d2;
        }

        .form-copy p {
            text-align: center;
            background: #1976d2;
            color: white;
            margin: 0;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .form-table td, .form-table th {
            border: 2px solid #dee2e6;
            padding: 0.75rem;
            vertical-align: top;
            font-size: 0.9rem;
        }

        .form-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
            text-align: center;
        }

        .form-table td {
            background: white;
        }

        .editable {
            border: none;
            background: transparent;
            width: 100%;
            padding: 0.25rem;
            font-size: 0.9rem;
            border-bottom: 1px solid #ccc;
            transition: all 0.2s ease;
        }

        .editable:focus {
            outline: none;
            border-bottom-color: #1976d2;
            background: #f8f9fa;
        }

        .checkbox-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .checkbox-group label {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.85rem;
            cursor: pointer;
        }

        .checkbox-group input[type="checkbox"] {
            transform: scale(1.1);
            accent-color: #1976d2;
        }

        .instructions {
            background: #fff3cd !important;
            border: 2px solid #ffeaa7 !important;
        }

        .instructions strong {
            color: #856404;
        }

        .cashier-section {
            background: #e8f5e8 !important;
            border: 2px solid #c3e6c3 !important;
        }

        .endorsement-section {
            background: #f0f8ff !important;
            border: 2px solid #b3d9ff !important;
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

        .copy-indicator {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #1976d2;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
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

            .form-table {
                font-size: 0.8rem;
            }

            .form-table td, .form-table th {
                padding: 0.5rem;
            }
        }

        @media print {
            .sidebar, .page-header, .action-buttons, .btn {
                display: none !important;
            }
            
            .main-content {
                margin-left: 0;
                padding: 0;
            }
            
            .form-copy {
                box-shadow: none;
                border: 2px solid #000;
                margin-bottom: 2rem;
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
                <h1><i class="bi bi-file-earmark-text"></i> Special Examination Form</h1>
                <p>Generate and manage special examination applications</p>
            </div>

            
                <!-- Student's Copy -->
                <div class="form-copy position-relative">
                    <div class="copy-indicator">Copy 1 of 2</div>
                    <h2>APPLICATION FOR SPECIAL EXAMINATION FORM</h2>
                    <p><strong>(STUDENT'S COPY)</strong></p>
                    <table class="form-table">
                        <tr>
                            <td colspan="2"><strong>STI:</strong> <input class="editable" type="text" placeholder="Campus Name"></td>
                            <td colspan="2"><strong>TERM/SY:</strong> <input class="editable" type="text" placeholder="Term/SY"></td>
                            <td colspan="2">
                                <strong>PERIODICAL EXAM:</strong><br>
                                <div class="checkbox-group mt-2">
                                    <label><input type="checkbox"> Prelim</label>
                                    <label><input type="checkbox"> Midterm</label>
                                    <label><input type="checkbox"> Pre-Final</label>
                                    <label><input type="checkbox"> Final</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6"><strong>Student Number (SN):</strong> <input class="editable" type="text" placeholder="Student Number"></td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>LAST NAME:</strong> <input class="editable" type="text" placeholder="Last Name"></td>
                            <td colspan="2"><strong>FIRST NAME, SUFFIX:</strong> <input class="editable" type="text" placeholder="First Name, Suffix"></td>
                            <td colspan="2"><strong>MIDDLE NAME:</strong> <input class="editable" type="text" placeholder="Middle Name"></td>
                        </tr>
                        <tr>
                            <td colspan="3"><strong>PROGRAM:</strong> <input class="editable" type="text" placeholder="Program"></td>
                            <td colspan="3"><strong>YEAR LEVEL:</strong> <input class="editable" type="text" placeholder="Year Level"></td>
                        </tr>
                        <tr>
                            <th>SUBJECTS</th>
                            <th>SECTION</th>
                            <th>TEACHER'S NAME & SIGNATURE</th>
                            <th>PROCTOR'S NAME & SIGNATURE</th>
                            <th colspan="2">Printed Name & Signature</th>
                        </tr>
                        <tr>
                            <td><input class="editable" type="text" placeholder="Subject"></td>
                            <td><input class="editable" type="text" placeholder="Section"></td>
                            <td><input class="editable" type="text" placeholder="Teacher's Name & Signature"></td>
                            <td><input class="editable" type="text" placeholder="Proctor's Name & Signature"></td>
                            <td colspan="2">
                                <strong>Student:</strong> <input class="editable" type="text" placeholder="Student Name & Signature"><br><br>
                                <strong>Guardian:</strong> <input class="editable" type="text" placeholder="Guardian Name & Signature">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="cashier-section">
                                <strong>FOR CASHIER'S USE ONLY</strong><br><br>
                                <strong>Amount Due:</strong> <input class="editable" type="text" placeholder="Amount"><br><br>
                                <strong>Verified by / Date:</strong> <input class="editable" type="text" placeholder="Verifier & Date"><br><br>
                                <strong>Invoice Number:</strong> <input class="editable" type="text" placeholder="Invoice Number"><br><br>
                                <strong>Invoice Date:</strong> <input class="editable" type="text" placeholder="Invoice Date">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="endorsement-section">
                                <strong>ENDORSED BY:</strong><br>
                                <div class="checkbox-group mt-2">
                                    <label><input type="checkbox"> Yes</label>
                                    <label><input type="checkbox"> No</label>
                                </div>
                                <br><strong>PROGRAM HEAD:</strong> <input class="editable" type="text" placeholder="Program Head Signature">
                            </td>
                            <td colspan="3" class="endorsement-section">
                                <strong>RECEIVED BY (AFTER PAYMENT):</strong><br><br>
                                <strong>PROGRAM HEAD:</strong> <input class="editable" type="text" placeholder="Program Head Signature">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="instructions">
                                <strong>INSTRUCTION TO THE STUDENT:</strong><br>
                                1. Accomplish this form and sign with your guardian.<br>
                                2. Get signatures from your teachers and Program Head.<br>
                                3. Pay at the cashier and attach the invoice.<br>
                                4. Confirm schedule at Program Head's Office.<br>
                                5. Bring form and invoice on exam day for proctor's signature.
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Program Head's Copy -->
                <div class="form-copy position-relative">
                    <div class="copy-indicator">Copy 2 of 2</div>
                    <h2>APPLICATION FOR SPECIAL EXAMINATION FORM</h2>
                    <p><strong>(PROGRAM HEAD'S COPY)</strong></p>
                    <table class="form-table">
                        <tr>
                            <td colspan="2"><strong>STI:</strong> <input class="editable" type="text" placeholder="Campus Name"></td>
                            <td colspan="2"><strong>TERM/SY:</strong> <input class="editable" type="text" placeholder="Term/SY"></td>
                            <td colspan="2">
                                <strong>PERIODICAL EXAM:</strong><br>
                                <div class="checkbox-group mt-2">
                                    <label><input type="checkbox"> Prelim</label>
                                    <label><input type="checkbox"> Midterm</label>
                                    <label><input type="checkbox"> Pre-Final</label>
                                    <label><input type="checkbox"> Final</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6"><strong>Student Number (SN):</strong> <input class="editable" type="text" placeholder="Student Number"></td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>LAST NAME:</strong> <input class="editable" type="text" placeholder="Last Name"></td>
                            <td colspan="2"><strong>FIRST NAME, SUFFIX:</strong> <input class="editable" type="text" placeholder="First Name, Suffix"></td>
                            <td colspan="2"><strong>MIDDLE NAME:</strong> <input class="editable" type="text" placeholder="Middle Name"></td>
                        </tr>
                        <tr>
                            <td colspan="3"><strong>PROGRAM:</strong> <input class="editable" type="text" placeholder="Program"></td>
                            <td colspan="3"><strong>YEAR LEVEL:</strong> <input class="editable" type="text" placeholder="Year Level"></td>
                        </tr>
                        <tr>
                            <th>SUBJECTS</th>
                            <th>SECTION</th>
                            <th>TEACHER'S NAME & SIGNATURE</th>
                            <th>PROCTOR'S NAME & SIGNATURE</th>
                            <th colspan="2">Printed Name & Signature</th>
                        </tr>
                        <tr>
                            <td><input class="editable" type="text" placeholder="Subject"></td>
                            <td><input class="editable" type="text" placeholder="Section"></td>
                            <td><input class="editable" type="text" placeholder="Teacher's Name & Signature"></td>
                            <td><input class="editable" type="text" placeholder="Proctor's Name & Signature"></td>
                            <td colspan="2">
                                <strong>Student:</strong> <input class="editable" type="text" placeholder="Student Name & Signature"><br><br>
                                <strong>Guardian:</strong> <input class="editable" type="text" placeholder="Guardian Name & Signature">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="cashier-section">
                                <strong>FOR CASHIER'S USE ONLY</strong><br><br>
                                <strong>Amount Due:</strong> <input class="editable" type="text" placeholder="Amount"><br><br>
                                <strong>Verified by / Date:</strong> <input class="editable" type="text" placeholder="Verifier & Date"><br><br>
                                <strong>Invoice Number:</strong> <input class="editable" type="text" placeholder="Invoice Number"><br><br>
                                <strong>Invoice Date:</strong> <input class="editable" type="text" placeholder="Invoice Date">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="endorsement-section">
                                <strong>ENDORSED BY:</strong><br>
                                <div class="checkbox-group mt-2">
                                    <label><input type="checkbox"> Yes</label>
                                    <label><input type="checkbox"> No</label>
                                </div>
                                <br><strong>PROGRAM HEAD:</strong> <input class="editable" type="text" placeholder="Program Head Signature">
                            </td>
                            <td colspan="3" class="endorsement-section">
                                <strong>RECEIVED BY (AFTER PAYMENT):</strong><br><br>
                                <strong>PROGRAM HEAD:</strong> <input class="editable" type="text" placeholder="Program Head Signature">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="instructions">
                                <strong>INSTRUCTION TO THE STUDENT:</strong><br>
                                1. Accomplish this form and sign with your guardian.<br>
                                2. Get signatures from your teachers and Program Head.<br>
                                3. Pay at the cashier and attach the invoice.<br>
                                4. Confirm schedule at Program Head's Office.<br>
                                5. Bring form and invoice on exam day for proctor's signature.
                            </td>
                        </tr>
                    </table>
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
            <div id="examForm">
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
            const original = document.getElementById('examForm');
            const clone = original.cloneNode(true);
            
            // Remove copy indicators from PDF
            const indicators = clone.querySelectorAll('.copy-indicator');
            indicators.forEach(indicator => indicator.remove());
            
            clone.style.position = 'static';
            clone.style.margin = '0';
            clone.style.boxShadow = 'none';
            clone.style.background = 'white';
            document.body.appendChild(clone);

            html2pdf().set({
                margin: 0.25,
                filename: 'special_examination_form.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 1.2 },
                jsPDF: { unit: 'in', format: [8.5, 11], orientation: 'portrait' }
            }).from(clone).save().then(() => {
                document.body.removeChild(clone);
                showNotification('PDF downloaded successfully!', 'success');
            });
        }

        function printForm() {
            window.print();
            showNotification('Form sent to printer!', 'info');
        }

        function clearForm() {
            if (confirm('Are you sure you want to clear all form data?')) {
                document.getElementById('examForm').querySelectorAll('input').forEach(element => {
                    if (element.type === 'checkbox') {
                        element.checked = false;
                    } else {
                        element.value = '';
                    }
                });
                showNotification('Form cleared successfully!', 'info');
            }
        }

        function fillSampleData() {
            // Fill sample data for demonstration
            const inputs = document.querySelectorAll('.editable');
            const sampleData = [
                'STI College Caloocan', '1st Term SY 2024-2025', // Campus and Term
                '2021-123456', // Student Number
                'Dela Cruz', 'Juan Carlos', 'Santos', // Names
                'Bachelor of Science in Information Technology', '3rd Year', // Program and Year
                'Programming 3', 'BSIT-3A' // Subject and Section
            ];
            
            inputs.forEach((input, index) => {
                if (index < sampleData.length) {
                    input.value = sampleData[index];
                }
            });
            
            // Check some checkboxes
            document.querySelectorAll('input[type="checkbox"]')[1].checked = true; // Midterm
            
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

        // Sync data between both copies
        function syncFormData() {
            const studentCopy = document.querySelectorAll('.form-copy')[0];
            const programHeadCopy = document.querySelectorAll('.form-copy')[1];
            
            const studentInputs = studentCopy.querySelectorAll('.editable, input[type="checkbox"]');
            const programHeadInputs = programHeadCopy.querySelectorAll('.editable, input[type="checkbox"]');
            
            studentInputs.forEach((input, index) => {
                input.addEventListener('input', function() {
                    if (programHeadInputs[index]) {
                        if (input.type === 'checkbox') {
                            programHeadInputs[index].checked = input.checked;
                        } else {
                            programHeadInputs[index].value = input.value;
                        }
                    }
                });
            });
        }

        // Initialize form synchronization
        document.addEventListener('DOMContentLoaded', syncFormData);
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98acedee800f0dc9',t:'MTc1OTgzNTE5MC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
