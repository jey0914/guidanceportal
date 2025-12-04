<?php
session_start();
include("db.php");

// Optional: check if admin is logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch incident reports
$query = "SELECT id, email, incident_type, description, status, date_reported FROM student_incident_reports ORDER BY date_reported DESC";
$result = mysqli_query($con, $query);

$incidentReports = [];
while ($row = mysqli_fetch_assoc($result)) {
    $incidentReports[] = $row;
}

// Get total number of incident reports
$totalReportsQuery = "SELECT COUNT(*) AS total_reports FROM student_incident_reports";
$totalResult = mysqli_query($con, $totalReportsQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalReports = $totalRow['total_reports'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Incident Reports</title>
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
        
        .incident-section {
            background: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .incident-description {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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
                    <h1><i class="bi bi-file-text me-2"></i>Student Incident Reports</h1>
                    <p class="text-muted mb-0">View and manage all student incident reports with privacy guidelines</p>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <div class="badge bg-info">
                        <i class="bi bi-info-circle me-1"></i>
                        <span><?php echo $totalReports; ?> Total Reports</span>
                    </div>
                </div>
            </div>
                <!-- Instructions Section -->
            <div class="incident-section">
                <h5><i class="bi bi-info-circle me-2"></i>Student-Facing Incident Report Table Guidelines</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-info">
                            <h6><i class="bi bi-calendar me-2"></i>Date Reported</h6>
                            <p class="mb-2">Shows when the incident was officially recorded by admin.</p>
                            <small class="text-muted">Example: 17/10/2025</small>
                        </div>
                        <div class="alert alert-warning">
                            <h6><i class="bi bi-tag me-2"></i>Incident Type</h6>
                            <p class="mb-2">Short classification of the incident.</p>
                            <small class="text-muted">Example: Slip / Minor Injury, Property Damage, etc.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-success">
                            <h6><i class="bi bi-clipboard-check me-2"></i>Status</h6>
                            <p class="mb-2">Current state of the incident report or any follow-up required.</p>
                            <small class="text-muted">Example: Follow-up: Visit Guidance Office, Completed, Pending</small>
                        </div>
                        <div class="alert alert-secondary">
                            <h6><i class="bi bi-shield-lock me-2"></i>Additional Notes</h6>
                            <p class="mb-2">Only student's personal involvement shown; other students' details protected for privacy.</p>
                            <small class="text-muted">Longer details accessible via "View Details" link if available</small>
                        </div>
                    </div>
                </div>
                <div class="alert alert-primary">
                    <h6><i class="bi bi-file-text me-2"></i>Description Guidelines</h6>
                    <p class="mb-2">Provides brief, factual summary focusing only on the student's involvement.</p>
                    <p class="mb-0"><strong>Do not expect other students' or witnesses' information here.</strong></p>
                    <small class="text-muted">Example: "You slipped in the hallway and injured your knee."</small>
                </div>
            </div>

            <!-- Incident Reports Table -->
            <div class="incident-section">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5><i class="bi bi-table me-2"></i>All Student Incident Reports</h5>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" style="width: auto;" onchange="filterByStudent(this.value)">
    <option value="all">All Students</option>
    <?php
        include("db.php");
        $result = $con->query("SELECT student_no, fname, lname FROM form ORDER BY lname ASC");

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $studentNo = htmlspecialchars($row['student_no']);
                $fullName = htmlspecialchars($row['fname'] . ' ' . $row['lname']);
                echo "<option value='$studentNo'>$fullName</option>";
            }
        } else {
            echo "<option disabled>No students found</option>";
        }
    ?>
</select>

                        <select class="form-select form-select-sm" style="width: auto;" onchange="filterReports(this.value)">
                            <option value="all">All Reports</option>
                            <option value="pending">Pending Action</option>
                            <option value="completed">Completed</option>
                        </select>
                        <button class="btn btn-outline-success btn-sm" onclick="createNewReport()">
                            <i class="bi bi-plus"></i> New Report
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="refreshTable()">
                            <i class="bi bi-arrow-clockwise"></i> Refresh
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover" id="incidentTable">
                        <thead class="table-light">
                            <tr>
                                <th>Student</th>
                                <th>Date Reported</th>
                                <th>Incident Type</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="incidentTableBody">
                            <!-- Table content will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script> 
    const incidentReports = <?php echo json_encode($incidentReports); ?>;


        // Initialize table on page load
        document.addEventListener('DOMContentLoaded', function() {
            filterReports('all');
        });

        function filterByStudent(studentFilter) {
            showToast(`Filtering by student: ${studentFilter}`, 'info');
        }

        function filterReports(filter) {
            const tbody = document.getElementById('incidentTableBody');
            let filteredReports = incidentReports;

            if (filter === 'pending') {
                filteredReports = incidentReports.filter(report => 
                    report.status === 'Follow-up Required' || report.status === 'Monitoring'
                );
            } else if (filter === 'completed') {
                filteredReports = incidentReports.filter(report => 
                    report.status === 'Completed'
                );
            }

            tbody.innerHTML = filteredReports.map(report => `
                <tr>
                    <td>
                        <strong>${report.student}</strong><br>
                        <small class="text-muted">${report.grade} - ${report.studentId}</small>
                    </td>
                    <td>
                        <strong>${report.date}</strong><br>
                        <small class="text-muted">${report.time}</small>
                    </td>
                    <td>
                        <span class="badge ${getTypeBadgeClass(report.type)}">${report.type}</span>
                    </td>
                    <td>
                        <div class="incident-description">
                            ${report.description}
                        </div>
                    </td>
                    <td>
                        <span class="badge ${report.statusClass}">${report.status}</span><br>
                        <small class="text-muted">${report.statusDetail}</small>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-outline-primary" onclick="viewDetails('${report.id}')">
                                <i class="bi bi-eye"></i> View
                            </button>
                            <button class="btn btn-sm btn-outline-warning" onclick="editReport('${report.id}')">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');

            showToast(`Showing ${filteredReports.length} reports`, 'info');
        }

        function getTypeBadgeClass(type) {
            switch(type) {
                case 'Minor Injury': return 'bg-warning';
                case 'Property Damage': return 'bg-danger';
                case 'Behavioral': return 'bg-secondary';
                case 'Medical': return 'bg-info';
                default: return 'bg-primary';
            }
        }

    function createNewReport() {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'newReportModal';
    modal.innerHTML = `
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Create New Incident Report
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="newReportForm">
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Admin Notice:</strong> This report will be automatically sent to the student and logged in the system.
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="studentSelect" class="form-label">
                                    <i class="bi bi-person me-1"></i>Select Student *
                                </label>
                                <select class="form-select" id="studentSelect" required>
                                    <option value="">Loading students...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="reportedBy" class="form-label">
                                    <i class="bi bi-person-badge me-1"></i>Reported By
                                </label>
                                <input type="text" class="form-control" id="reportedBy" value="Admin User" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="incidentDate" class="form-label">
                                    <i class="bi bi-calendar me-1"></i>Date of Incident *
                                </label>
                                <input type="date" class="form-control" id="incidentDate" required>
                            </div>
                            <div class="col-md-6">
                                <label for="incidentTime" class="form-label">
                                    <i class="bi bi-clock me-1"></i>Time of Incident *
                                </label>
                                <input type="time" class="form-control" id="incidentTime" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="incidentType" class="form-label">
                                    <i class="bi bi-tag me-1"></i>Incident Type *
                                </label>
                                <select class="form-select" id="incidentType" required>
                                    <option value="">Select type...</option>
                                    <option value="Minor Injury">Minor Injury</option>
                                    <option value="Property Damage">Property Damage</option>
                                    <option value="Behavioral">Behavioral Issue</option>
                                    <option value="Medical">Medical Emergency</option>
                                    <option value="Safety">Safety Concern</option>
                                    <option value="Academic">Academic Misconduct</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="incidentLocation" class="form-label">
                                    <i class="bi bi-geo-alt me-1"></i>Location *
                                </label>
                                <input type="text" class="form-control" id="incidentLocation" placeholder="e.g., Classroom 201, Cafeteria..." required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="incidentDescription" class="form-label">
                                <i class="bi bi-file-text me-1"></i>Detailed Description *
                            </label>
                            <textarea class="form-control" id="incidentDescription" rows="4" placeholder="Provide a detailed description..." required></textarea>
                            <div class="form-text"><span id="charCount">0</span>/500 characters</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="witnessInfo" class="form-label">
                                    <i class="bi bi-people me-1"></i>Witnesses/Others Involved
                                </label>
                                <textarea class="form-control" id="witnessInfo" rows="2" placeholder="List any witnesses..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="actionTaken" class="form-label">
                                    <i class="bi bi-clipboard-check me-1"></i>Immediate Action Taken
                                </label>
                                <textarea class="form-control" id="actionTaken" rows="2" placeholder="Describe any actions taken..."></textarea>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reportStatus" class="form-label">
                                <i class="bi bi-flag me-1"></i>Initial Status
                            </label>
                            <select class="form-select" id="reportStatus">
                                <option value="Follow-up Required">Follow-up Required</option>
                                <option value="Monitoring">Monitoring</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>

                        <div class="alert alert-success">
                            <i class="bi bi-send me-2"></i>
                            <strong>Notification:</strong> The student will receive an automatic notification about this report via their portal and email.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-1"></i>Send Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    `;
            document.body.appendChild(modal);
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            
            // Set today's date as default
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('incidentDate').value = today;
            
            // Character counter for description
            const descriptionField = document.getElementById('incidentDescription');
            const charCountSpan = document.getElementById('charCount');
            
            descriptionField.addEventListener('input', function() {
                const count = this.value.length;
                charCountSpan.textContent = count;
                
                if (count > 500) {
                    charCountSpan.classList.add('text-danger');
                    this.value = this.value.substring(0, 500);
                    charCountSpan.textContent = '500';
                } else {
                    charCountSpan.classList.remove('text-danger');
                }
            });

            
    // --------- ADD: Fetch students dynamically ---------
    // Kunin ang select element
const studentSelect = modal.querySelector('#studentSelect');

// Fetch real students
fetch('fetch_students.php')
    .then(response => response.json())
    .then(data => {
        // Clear placeholder
        studentSelect.innerHTML = '<option value="">Select a student...</option>';

        data.forEach(student => {
            const option = document.createElement('option');
            option.value = student.student_no; // use the correct key
            option.textContent = student.full_name; // use the correct key
            studentSelect.appendChild(option);
        });
    })
    .catch(err => {
        studentSelect.innerHTML = '<option value="">Failed to load students</option>';
        console.error('Error loading students:', err);
    });

            
// Form submission
document.getElementById('newReportForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = {
        studentId: document.getElementById('studentSelect').value,
        date: document.getElementById('incidentDate').value,
        time: document.getElementById('incidentTime').value,
        type: document.getElementById('incidentType').value,
        location: document.getElementById('incidentLocation').value,
        description: document.getElementById('incidentDescription').value,
        witnesses: document.getElementById('witnessInfo').value,
        action: document.getElementById('actionTaken').value,
        status: document.getElementById('reportStatus').value
    };

    // Validate required fields
    if (!formData.studentId || !formData.date || !formData.time || !formData.type || !formData.location || !formData.description) {
        showToast('Please fill in all required fields marked with *', 'danger');
        return;
    }

    const studentName = document.getElementById('studentSelect').selectedOptions[0].text.split(' (')[0];
    showConfirmationDialog(
        'Confirm New Report',
        `Are you sure you want to send this incident report?<br><br>
        <strong>Please make sure all information is correct:</strong><br>
        • Student: ${studentName}<br>
        • Date: ${new Date(formData.date).toLocaleDateString()}<br>
        • Type: ${formData.type}<br>
        • Location: ${formData.location}<br>
        • Status: ${formData.status}<br><br>
        <em>The student will be automatically notified about this report.</em>`,
        () => {
            const submitBtn = document.querySelector('#newReportForm button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Sending...';
            submitBtn.disabled = true;

            // ✅ Send data to server
            fetch('submit_incident_report.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    showToast(`Incident report created successfully for ${studentName}! Student has been notified.`, 'success');

                    bsModal.hide();

                    // Optionally, add to the table immediately
                    const newReport = {
                        id: data.id, // returned insert_id
                        student: studentName,
                        studentId: formData.studentId,
                        date: new Date(formData.date).toLocaleDateString('en-GB'),
                        time: formData.time,
                        type: formData.type,
                        description: formData.description,
                        status: formData.status,
                        statusDetail: formData.status === 'Follow-up Required' ? 'Visit Guidance Office' : 
                                     formData.status === 'Monitoring' ? 'Under Observation' : 'No Action Required',
                        statusClass: formData.status === 'Follow-up Required' ? 'bg-info' : 
                                    formData.status === 'Monitoring' ? 'bg-warning' : 'bg-success'
                    };
                    incidentReports.unshift(newReport);
                    filterReports('all'); // Refresh table
                } else {
                    showToast(data.message, 'danger');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('Failed to send report. Please try again.', 'danger');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }
    );
});
            
            // Clean up modal when hidden
            modal.addEventListener('hidden.bs.modal', () => {
                modal.remove();
            });
        }

        function editReport(reportId) {
            const report = incidentReports.find(r => r.id === reportId);
            if (!report) return;

            // Create edit report modal
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.id = 'editReportModal';
            modal.innerHTML = `
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title">
                                <i class="bi bi-pencil-square me-2"></i>Edit Incident Report - ${report.id}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form id="editReportForm">
                            <div class="modal-body">
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>Edit Notice:</strong> Changes to this report will be logged and the student will be notified of updates.
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="editStudentSelect" class="form-label">
                                            <i class="bi bi-person me-1"></i>Student *
                                        </label>
                                        <select class="form-select" id="editStudentSelect" required>
                                            <option value="emma" ${report.student === 'Emma Johnson' ? 'selected' : ''}>Emma Johnson (S001 - Grade 5A)</option>
                                            <option value="liam" ${report.student === 'Liam Smith' ? 'selected' : ''}>Liam Smith (S002 - Grade 4B)</option>
                                            <option value="olivia" ${report.student === 'Olivia Brown' ? 'selected' : ''}>Olivia Brown (S003 - Grade 6A)</option>
                                            <option value="noah" ${report.student === 'Noah Davis' ? 'selected' : ''}>Noah Davis (S004 - Grade 5B)</option>
                                            <option value="ava" ${report.student === 'Ava Wilson' ? 'selected' : ''}>Ava Wilson (S005 - Grade 3A)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="editReportedBy" class="form-label">
                                            <i class="bi bi-person-badge me-1"></i>Last Modified By
                                        </label>
                                        <input type="text" class="form-control" id="editReportedBy" value="Admin User" readonly>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="editIncidentDate" class="form-label">
                                            <i class="bi bi-calendar me-1"></i>Date of Incident *
                                        </label>
                                        <input type="date" class="form-control" id="editIncidentDate" value="${new Date(report.date.split('/').reverse().join('-')).toISOString().split('T')[0]}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="editIncidentTime" class="form-label">
                                            <i class="bi bi-clock me-1"></i>Time of Incident *
                                        </label>
                                        <input type="time" class="form-control" id="editIncidentTime" value="${report.time.replace(' AM', '').replace(' PM', '')}" required>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="editIncidentType" class="form-label">
                                            <i class="bi bi-tag me-1"></i>Incident Type *
                                        </label>
                                        <select class="form-select" id="editIncidentType" required>
                                            <option value="Minor Injury" ${report.type === 'Minor Injury' ? 'selected' : ''}>Minor Injury</option>
                                            <option value="Property Damage" ${report.type === 'Property Damage' ? 'selected' : ''}>Property Damage</option>
                                            <option value="Behavioral" ${report.type === 'Behavioral' ? 'selected' : ''}>Behavioral Issue</option>
                                            <option value="Medical" ${report.type === 'Medical' ? 'selected' : ''}>Medical Emergency</option>
                                            <option value="Safety" ${report.type === 'Safety' ? 'selected' : ''}>Safety Concern</option>
                                            <option value="Academic" ${report.type === 'Academic' ? 'selected' : ''}>Academic Misconduct</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="editIncidentLocation" class="form-label">
                                            <i class="bi bi-geo-alt me-1"></i>Location *
                                        </label>
                                        <input type="text" class="form-control" id="editIncidentLocation" value="Hallway near cafeteria" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="editIncidentDescription" class="form-label">
                                        <i class="bi bi-file-text me-1"></i>Detailed Description *
                                    </label>
                                    <textarea class="form-control" id="editIncidentDescription" rows="4" required>${report.description}</textarea>
                                    <div class="form-text">
                                        <span id="editCharCount">${report.description.length}</span>/500 characters
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="editWitnessInfo" class="form-label">
                                            <i class="bi bi-people me-1"></i>Witnesses/Others Involved
                                        </label>
                                        <textarea class="form-control" id="editWitnessInfo" rows="2" 
                                                  placeholder="List any witnesses or other students involved...">Teacher Ms. Anderson witnessed the incident</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="editActionTaken" class="form-label">
                                            <i class="bi bi-clipboard-check me-1"></i>Action Taken
                                        </label>
                                        <textarea class="form-control" id="editActionTaken" rows="2" 
                                                  placeholder="Describe actions taken...">Applied ice pack and contacted parents</textarea>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="editReportStatus" class="form-label">
                                        <i class="bi bi-flag me-1"></i>Current Status
                                    </label>
                                    <select class="form-select" id="editReportStatus">
                                        <option value="Follow-up Required" ${report.status === 'Follow-up Required' ? 'selected' : ''}>Follow-up Required</option>
                                        <option value="Monitoring" ${report.status === 'Monitoring' ? 'selected' : ''}>Monitoring</option>
                                        <option value="Completed" ${report.status === 'Completed' ? 'selected' : ''}>Completed</option>
                                    </select>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Update Notice:</strong> The student will receive a notification about any changes made to this report.
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-1"></i>Cancel Changes
                                </button>
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-check-circle me-1"></i>Update Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            
            // Character counter for description
            const descriptionField = document.getElementById('editIncidentDescription');
            const charCountSpan = document.getElementById('editCharCount');
            
            descriptionField.addEventListener('input', function() {
                const count = this.value.length;
                charCountSpan.textContent = count;
                
                if (count > 500) {
                    charCountSpan.classList.add('text-danger');
                    this.value = this.value.substring(0, 500);
                    charCountSpan.textContent = '500';
                } else {
                    charCountSpan.classList.remove('text-danger');
                }
            });
            
            // Form submission with confirmation
            document.getElementById('editReportForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = {
                    student: document.getElementById('editStudentSelect').selectedOptions[0].text.split(' (')[0],
                    date: document.getElementById('editIncidentDate').value,
                    time: document.getElementById('editIncidentTime').value,
                    type: document.getElementById('editIncidentType').value,
                    location: document.getElementById('editIncidentLocation').value,
                    description: document.getElementById('editIncidentDescription').value,
                    witnesses: document.getElementById('editWitnessInfo').value,
                    action: document.getElementById('editActionTaken').value,
                    status: document.getElementById('editReportStatus').value
                };
                
                // Validate required fields
                if (!formData.student || !formData.date || !formData.time || !formData.type || !formData.location || !formData.description) {
                    showToast('Please fill in all required fields marked with *', 'danger');
                    return;
                }
                
                // Show confirmation dialog
                showConfirmationDialog(
                    'Confirm Report Update',
                    `Are you sure you want to update this incident report?<br><br>
                    <strong>Please make sure all information is correct:</strong><br>
                    • Student: ${formData.student}<br>
                    • Date: ${new Date(formData.date).toLocaleDateString()}<br>
                    • Type: ${formData.type}<br>
                    • Status: ${formData.status}<br><br>
                    <em>The student will be notified of these changes.</em>`,
                    () => {
                        // User confirmed - proceed with update
                        const submitBtn = document.querySelector('#editReportForm button[type="submit"]');
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Updating...';
                        submitBtn.disabled = true;
                        
                        // Simulate updating report
                        setTimeout(() => {
                            // Update the report in the array
                            const reportIndex = incidentReports.findIndex(r => r.id === reportId);
                            if (reportIndex !== -1) {
                                incidentReports[reportIndex] = {
                                    ...incidentReports[reportIndex],
                                    student: formData.student,
                                    date: new Date(formData.date).toLocaleDateString('en-GB'),
                                    time: formData.time,
                                    type: formData.type,
                                    description: formData.description,
                                    status: formData.status,
                                    statusDetail: formData.status === 'Follow-up Required' ? 'Visit Guidance Office' : 
                                                 formData.status === 'Monitoring' ? 'Under Observation' : 'No Action Required',
                                    statusClass: formData.status === 'Follow-up Required' ? 'bg-info' : 
                                                formData.status === 'Monitoring' ? 'bg-warning' : 'bg-success'
                                };
                            }
                            
                            showToast(`Report ${reportId} updated successfully! ${formData.student} has been notified of the changes.`, 'success');
                            
                            bsModal.hide();
                            filterReports('all'); // Refresh the table
                            
                            // Reset button
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }, 2000);
                    }
                );
            });
            
            // Clean up modal when hidden
            modal.addEventListener('hidden.bs.modal', () => {
                modal.remove();
            });
        }

        function showConfirmationDialog(title, message, onConfirm) {
            const confirmModal = document.createElement('div');
            confirmModal.className = 'modal fade';
            confirmModal.innerHTML = `
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title">
                                <i class="bi bi-exclamation-triangle me-2"></i>${title}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-3">
                                <i class="bi bi-question-circle text-warning" style="font-size: 3rem;"></i>
                            </div>
                            <div class="text-center">
                                ${message}
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </button>
                            <button type="button" class="btn btn-warning" id="confirmBtn">
                                <i class="bi bi-check-circle me-1"></i>Yes, Update Report
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

        function viewDetails(reportId) {
            const report = incidentReports.find(r => r.id === reportId);
            if (!report) return;

            // Create detailed view modal
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.innerHTML = `
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bi bi-file-text me-2"></i>Incident Report Details - ${report.id}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6><i class="bi bi-person me-2"></i>Student Information</h6>
                                    <p><strong>${report.student}</strong><br>
                                    ${report.grade} - ${report.studentId}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="bi bi-calendar me-2"></i>Date & Time</h6>
                                    <p>${report.date} at ${report.time}</p>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h6><i class="bi bi-tag me-2"></i>Incident Type</h6>
                                    <p><span class="badge ${getTypeBadgeClass(report.type)}">${report.type}</span></p>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <h6><i class="bi bi-file-text me-2"></i>Description</h6>
                                <div class="alert alert-light">
                                    ${report.description}
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <h6><i class="bi bi-clipboard-check me-2"></i>Current Status</h6>
                                <p>
                                    <span class="badge ${report.statusClass}">${report.status}</span><br>
                                    <small class="text-muted">${report.statusDetail}</small>
                                </p>
                            </div>
                            
                            ${report.status === 'Follow-up Required' || report.status === 'Monitoring' ? `
                            <div class="alert alert-warning">
                                <h6><i class="bi bi-exclamation-triangle me-2"></i>Action Required</h6>
                                <p class="mb-0">Student needs to ${report.statusDetail.toLowerCase()}.</p>
                            </div>
                            ` : ''}
                            
                            <div class="alert alert-info">
                                <h6><i class="bi bi-info-circle me-2"></i>Student View Guidelines</h6>
                                <p class="mb-0">When students view this report, they will only see information related to their involvement. Other students' details are automatically protected for privacy.</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-warning" onclick="editReport('${report.id}')">
                                <i class="bi bi-pencil me-1"></i>Edit Report
                            </button>
                            <button type="button" class="btn btn-outline-primary" onclick="printReport('${report.id}')">
                                <i class="bi bi-printer me-1"></i>Print Report
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            
            modal.addEventListener('hidden.bs.modal', () => {
                modal.remove();
            });
        }

        function printReport(reportId) {
            const report = incidentReports.find(r => r.id === reportId);
            if (!report) return;

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Incident Report - ${report.id}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
                        .section { margin-bottom: 15px; }
                        .badge { background: #007bff; color: white; padding: 3px 8px; border-radius: 3px; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h2>Student Incident Report</h2>
                        <p>Report ID: ${report.id}</p>
                    </div>
                    <div class="section">
                        <strong>Date & Time:</strong> ${report.date} at ${report.time}
                    </div>
                    <div class="section">
                        <strong>Incident Type:</strong> ${report.type}
                    </div>
                    <div class="section">
                        <strong>Description:</strong><br>
                        ${report.description}
                    </div>
                    <div class="section">
                        <strong>Status:</strong> ${report.status} - ${report.statusDetail}
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }

        function refreshTable() {
            showToast('Table refreshed successfully!', 'success');
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
            }, 3000);
        }

    function loadIncidentReports() {
    const tableBody = document.querySelector("#incidentTable tbody");
    tableBody.innerHTML = "";

    if (incidentReports.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="6" class="text-center">No incident reports found.</td></tr>`;
        return;
    }

    incidentReports.forEach((r) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${r.id}</td>
            <td>${r.email}</td>
            <td>${r.incident_type}</td>
            <td>${r.description}</td>
            <td>
                <span class="badge ${r.status === 'Pending' ? 'bg-warning' : 'bg-success'}">${r.status}</span>
            </td>
            <td>${new Date(r.date_reported).toLocaleString()}</td>
        `;
        tableBody.appendChild(row);
    });
}

// Call the function when the page is ready
document.addEventListener("DOMContentLoaded", loadIncidentReports);

    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9906095ab2e80dc9',t:'MTc2MDc2OTU0NS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
