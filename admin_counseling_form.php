<?php
include("db.php");
session_start();

if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

// --- Get real data for "Sessions This Month" ---
$currentMonth = date('m');
$currentYear = date('Y');

$query = "SELECT COUNT(*) as total_sessions 
          FROM counseling_history 
          WHERE MONTH(interview_date) = ? AND YEAR(interview_date) = ?";
$stmtCount = $con->prepare($query);
$stmtCount->bind_param("ii", $currentMonth, $currentYear);
$stmtCount->execute();
$resultCount = $stmtCount->get_result();
$rowCount = $resultCount->fetch_assoc();
$totalSessions = $rowCount['total_sessions'];

// --- Handle form submission ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_no = $_POST['student_no'];
    $email = $_POST['email'];
    $counselor = $_POST['counselor'];
    $nature = $_POST['nature'];
    $status = $_POST['status'];
    $interview_date = $_POST['interview_date'];
    $time_started = $_POST['time_started'];
    $time_ended = $_POST['time_ended'];
    $remarks = $_POST['remarks'];

    $stmt = $con->prepare("INSERT INTO counseling_history 
        (student_no, email, counselor, nature, status, interview_date, time_started, time_ended, remarks)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $student_no, $email, $counselor, $nature, $status, $interview_date, $time_started, $time_ended, $remarks);

    if ($stmt->execute()) {
        echo "<script>alert('Counseling report successfully sent to student!'); window.location.href='admin_counseling_form.php';</script>";
    } else {
        echo "<script>alert('Error saving counseling report. Please try again.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Counseling Reports</title>
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
        }
        
        .sidebar h2 {
            padding: 0 1.5rem;
            margin-bottom: 2rem;
            font-size: 1.25rem;
            font-weight: 600;
            color: #495057;
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
            flex: 1;
            margin-left: 250px;
            padding: 2rem;
        }
        
        .header {
            background: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .counseling-section {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 0.75rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #1976d2;
            box-shadow: 0 0 0 0.2rem rgba(25, 118, 210, 0.25);
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #1976d2, #1565c0);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
        }
        
        .btn-submit:hover {
            background: linear-gradient(135deg, #1565c0, #0d47a1);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(25, 118, 210, 0.4);
        }
        
        .counseling-icon {
            font-size: 2rem;
            color: #1976d2;
            margin-right: 0.5rem;
        }
        
        .form-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 0.5rem;
            border-left: 4px solid #1976d2;
            margin-bottom: 1.5rem;
        }
        
        .section-title {
            color: #1976d2;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
        
        .char-counter {
            font-size: 0.875rem;
            color: #6c757d;
            text-align: right;
            margin-top: 0.25rem;
        }
        
        .time-group {
            display: flex;
            gap: 1rem;
            align-items: end;
        }
        
        .duration-display {
            background: #e3f2fd;
            color: #1976d2;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2><i class="bi bi-speedometer2"></i> Admin Panel</h2>
            <ul>
                <li><a href="admin_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
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
            <!-- Header -->
            <div class="header">
                <div>
                    <h1><i class="bi bi-heart me-2"></i>Counseling Reports</h1>
                    <p class="text-muted mb-0">Create and manage student counseling session reports</p>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <div class="badge bg-success">
                        <i class="bi bi-check-circle me-1"></i>
                        <span><?php echo $totalSessions; ?> Sessions This Month</span>
                    </div>
                </div>
            </div>

            <!-- Counseling Report Form -->
            <div class="counseling-section">
                <div class="text-center mb-4">
                    <i class="bi bi-heart-pulse counseling-icon"></i>
                    <h3 class="d-inline-block">Submit Counseling Report</h3>
                    <p class="text-muted">Document counseling sessions and track student progress</p>
                </div>

                <form id="counselingForm" method="POST">
                    <!-- Student Information Section -->
                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="bi bi-person-circle"></i>
                            Student Information
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">Student Number</label>
                                <input type="text" class="form-control" name="student_no" id="studentNo" 
                                       placeholder="e.g., S001, S002..." required>
                                <div class="form-text">Enter the student's unique identification number</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">Student Email</label>
                                <input type="email" class="form-control" name="email" id="studentEmail" 
                                       placeholder="student@school.edu" required>
                                <div class="form-text">Student will receive session summary via email</div>
                            </div>
                        </div>
                    </div>

                    <!-- Session Details Section -->
                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="bi bi-calendar-event"></i>
                            Session Details
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">Counselor Name</label>
                                <input type="text" class="form-control" name="counselor" id="counselorName" 
                                       placeholder="e.g., Ms. Dela Cruz" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">Interview Date</label>
                                <input type="date" class="form-control" name="interview_date" id="interviewDate" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Time Started</label>
                                <input type="time" class="form-control" name="time_started" id="timeStarted" 
                                       onchange="calculateDuration()">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Time Ended</label>
                                <input type="time" class="form-control" name="time_ended" id="timeEnded" 
                                       onchange="calculateDuration()">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Session Duration</label>
                                <div class="duration-display" id="sessionDuration">
                                    <i class="bi bi-clock me-1"></i>Not calculated
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Counseling Information Section -->
                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="bi bi-clipboard-heart"></i>
                            Counseling Information
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">Nature of Counseling</label>
                                <select name="nature" class="form-select" id="counselingNature" required>
                                    <option value="">-- Select Nature --</option>
                                    <option value="Academic"> Academic</option>
                                    <option value="Personal"> Personal</option>
                                    <option value="Career"> Career</option>
                                    <option value="Crisis"> Crisis</option>
                                    <option value="Group"> Group</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">Status</label>
                                <select name="status" class="form-select" id="sessionStatus" required>
                                    <option value="">-- Select Status --</option>
                                    <option value="Complete">Complete</option>
                                    <option value="For Follow Up">For Follow Up</option>
                                    <option value="For Scheduling">For Scheduling</option>
                                    <option value="External Referral">External Referral</option>
                                    <option value="N/A">N/A (Not Applicable)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Session Summary Section -->
                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="bi bi-journal-text"></i>
                            Session Summary
                        </h5>
                        <div class="mb-3">
                            <label class="form-label">Remarks / Summary</label>
                            <textarea name="remarks" class="form-control" id="sessionRemarks" rows="5" 
                                      placeholder="Write detailed notes about the session, student progress, recommendations, and follow-up actions..."
                                      oninput="updateCharCount()"></textarea>
                            <div class="char-counter">
                                <span id="charCount">0</span>/1000 characters
                            </div>
                        </div>
                    </div>

                    <!-- Submit Section -->
                    <div class="text-center mt-4">
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Privacy Notice:</strong> This counseling report will be securely stored and only accessible to authorized personnel. The student will receive a summary notification.
                        </div>
                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-send me-2"></i>Send Counseling Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set today's date as default
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('interviewDate').value = today;
        });

        // Character counter for remarks
        function updateCharCount() {
            const textarea = document.getElementById('sessionRemarks');
            const charCount = document.getElementById('charCount');
            const count = textarea.value.length;
            
            charCount.textContent = count;
            
            if (count > 1000) {
                charCount.classList.add('text-danger');
                textarea.value = textarea.value.substring(0, 1000);
                charCount.textContent = '1000';
            } else {
                charCount.classList.remove('text-danger');
            }
        }

        // Calculate session duration
        function calculateDuration() {
            const startTime = document.getElementById('timeStarted').value;
            const endTime = document.getElementById('timeEnded').value;
            const durationDisplay = document.getElementById('sessionDuration');
            
            if (startTime && endTime) {
                const start = new Date(`2000-01-01T${startTime}`);
                const end = new Date(`2000-01-01T${endTime}`);
                
                if (end > start) {
                    const diffMs = end - start;
                    const diffMins = Math.floor(diffMs / 60000);
                    const hours = Math.floor(diffMins / 60);
                    const minutes = diffMins % 60;
                    
                    let durationText = '';
                    if (hours > 0) {
                        durationText += `${hours}h `;
                    }
                    durationText += `${minutes}m`;
                    
                    durationDisplay.innerHTML = `<i class="bi bi-clock me-1"></i>${durationText}`;
                } else {
                    durationDisplay.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i>Invalid time range';
                }
            } else {
                durationDisplay.innerHTML = '<i class="bi bi-clock me-1"></i>Not calculated';
            }
        }

        // Form submission with confirmation
        document.getElementById('counselingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                studentNo: document.getElementById('studentNo').value,
                studentEmail: document.getElementById('studentEmail').value,
                counselor: document.getElementById('counselorName').value,
                date: document.getElementById('interviewDate').value,
                timeStarted: document.getElementById('timeStarted').value,
                timeEnded: document.getElementById('timeEnded').value,
                nature: document.getElementById('counselingNature').value,
                status: document.getElementById('sessionStatus').value,
                remarks: document.getElementById('sessionRemarks').value
            };
            
            // Validate required fields
            if (!formData.studentNo || !formData.studentEmail || !formData.counselor || 
                !formData.date || !formData.nature || !formData.status) {
                showToast('Please fill in all required fields marked with *', 'danger');
                return;
            }
            
            // Show confirmation dialog
            const studentInfo = `${formData.studentNo} (${formData.studentEmail})`;
            const sessionDate = new Date(formData.date).toLocaleDateString();
            
            showConfirmationDialog(
                'Confirm Counseling Report',
                `Are you sure you want to submit this counseling report?<br><br>
                <strong>Please make sure all information is correct:</strong><br>
                • Student: ${studentInfo}<br>
                • Date: ${sessionDate}<br>
                • Counselor: ${formData.counselor}<br>
                • Nature: ${formData.nature}<br>
                • Status: ${formData.status}<br><br>
                <em>The student will receive a notification about this session.</em>`,
                () => {
                    // User confirmed - proceed with actual PHP form submission
                    const submitBtn = document.querySelector('.btn-submit');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Submitting...';
                    submitBtn.disabled = true;
                    
                    // Submit the actual form to PHP
                    document.getElementById('counselingForm').submit();
                }
            );
        });

        function showConfirmationDialog(title, message, onConfirm) {
            const confirmModal = document.createElement('div');
            confirmModal.className = 'modal fade';
            confirmModal.innerHTML = `
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-question-circle me-2"></i>${title}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-3">
                                <i class="bi bi-question-circle text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <div class="text-center">
                                ${message}
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </button>
                            <button type="button" class="btn btn-primary" id="confirmBtn">
                                <i class="bi bi-check-circle me-1"></i>Yes, Submit Report
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(confirmModal);
            const bsConfirmModal = new bootstrap.Modal(confirmModal);
            bsConfirmModal.show();
            
            // Handle confirmation
            document.getElementById('confirmBtn').addEventListener('click', () => {
                bsConfirmModal.hide();
                onConfirm();
            });
            
            // Clean up when hidden
            confirmModal.addEventListener('hidden.bs.modal', () => {
                confirmModal.remove();
            });
        }

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type} position-fixed`;
            toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            toast.innerHTML = `
                ${message}
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 4000);
        }
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9906b40773c60dc9',t:'MTc2MDc3NjUzNi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
