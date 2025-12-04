<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

include("db.php");

// Fetch and group attendance logs
$attendance_sql = "SELECT * FROM attendance_log ORDER BY section, time_in DESC";
$attendance_result = $con->query($attendance_sql);

$grouped_attendance = [];

// Initialize summary variables
$totalStudents = 0;
$presentToday = 0;
$lowAttendance = 0;
$totalRate = 0;

if ($attendance_result && $attendance_result->num_rows > 0) {
    // Reset pointer
    $attendance_result->data_seek(0);
    $uniqueStudents = [];

    while ($row = $attendance_result->fetch_assoc()) {
        // Grouping by grade & strand
        $parts = explode('-', strtoupper($row['section']));
        $grade = trim($parts[0] ?? 'UNKNOWN');
        $strand = trim($parts[1] ?? 'UNKNOWN');
        $grouped_attendance[$grade][$strand][] = $row;

        // Summary calculations
        $studentNo = $row['student_no'];
        $present = (int)$row['present_days'];
        $totalDays = (int)$row['total_days'];
        $rate = $totalDays > 0 ? round(($present / $totalDays) * 100, 1) : 0;

        $uniqueStudents[$studentNo] = true;
        if (strtolower($row['status']) === 'present') $presentToday++;
        if ($rate < 75) $lowAttendance++;
        $totalRate += $rate;
    }

    $totalStudents = count($uniqueStudents);
    $overallRate = $totalStudents > 0 ? round($totalRate / $totalStudents, 1) : 0;
} else {
    $totalStudents = $presentToday = $lowAttendance = $overallRate = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senior High Attendance – Student Records</title>
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
            margin-left: 250px;
            flex: 1;
            padding: 2rem;
        }

        .page-header {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-left: 4px solid #1976d2;
        }

        .page-header h1 {
            margin: 0;
            color: #495057;
            font-weight: 600;
            font-size: 1.75rem;
        }

        .page-header p {
            margin: 0.5rem 0 0 0;
            color: #6c757d;
            font-size: 0.95rem;
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-left: 4px solid;
            transition: transform 0.2s ease;
        }

        .summary-card:hover {
            transform: translateY(-2px);
        }

        .summary-card.total { border-left-color: #1976d2; }
        .summary-card.present { border-left-color: #28a745; }
        .summary-card.low-attendance { border-left-color: #dc3545; }
        .summary-card.rate { border-left-color: #17a2b8; }

        .summary-card h3 {
            font-size: 2rem;
            font-weight: 600;
            margin: 0;
            color: #495057;
        }

        .summary-card p {
            margin: 0.5rem 0 0 0;
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .summary-card i {
            font-size: 1.5rem;
            opacity: 0.7;
            float: right;
            margin-top: -0.5rem;
        }

        .filters-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .table-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .table-header {
            padding: 1.5rem;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-header h3 {
            margin: 0;
            color: #495057;
            font-weight: 600;
        }

        .table-responsive {
            max-height: 600px;
            overflow-y: auto;
        }

        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: #495057;
            position: sticky;
            top: 0;
            z-index: 10;
            cursor: pointer;
            user-select: none;
        }

        .table th:hover {
            background-color: #e9ecef;
        }

        .table th i {
            margin-left: 0.5rem;
            opacity: 0.5;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .status-present {
            background-color: #d4edda;
            color: #155724;
        }

        .status-late {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-absent {
            background-color: #f8d7da;
            color: #721c24;
        }

        .attendance-rate {
            font-weight: 600;
        }

        .attendance-rate.high { color: #28a745; }
        .attendance-rate.medium { color: #ffc107; }
        .attendance-rate.low { color: #dc3545; }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }

        .low-attendance-row {
            background-color: #fff5f5;
        }

        .search-input {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 0.5rem 1rem;
        }

        .search-input:focus {
            border-color: #1976d2;
            box-shadow: 0 0 0 0.2rem rgba(25, 118, 210, 0.25);
        }

        .filter-select {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 0.5rem 1rem;
        }

        .export-buttons {
            display: flex;
            gap: 0.5rem;
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

            .summary-cards {
                grid-template-columns: 1fr;
            }

            .table-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .export-buttons {
                justify-content: stretch;
            }

            .export-buttons .btn {
                flex: 1;
            }
        }

        .mobile-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1050;
            background: #1976d2;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.5rem;
        }

        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Mobile Toggle Button -->
        <button class="mobile-toggle btn" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <h2><i class="bi bi-mortarboard-fill me-2"></i>Admin Panel</h2>
            <ul>
                <li><a href="admin_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li><a href="admin_records.php" class="active"><i class="bi bi-people"></i> Student Records</a></li>
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
                <h1><i class="bi bi-people me-2"></i>Senior High Attendance – Student Records</h1>
                <p>View and manage student attendance records</p>
            </div>

            <!-- Summary Cards -->
            <div class="summary-cards">
                <div class="summary-card total">
                    <i class="bi bi-people"></i>
                    <h3 id="totalStudents"><?php echo $totalStudents; ?></h3>
                    <p>Total Students</p>
                </div>
                <div class="summary-card rate">
                    <i class="bi bi-graph-up"></i>
                    <h3 id="overallRate"><?php echo $overallRate; ?>%</h3>
                    <p>Overall Attendance Rate</p>
                </div>
                <div class="summary-card present">
                    <i class="bi bi-check-circle"></i>
                    <h3 id="presentToday"><?php echo $presentToday; ?></h3>
                    <p>Present Today</p>
                </div>
                <div class="summary-card low-attendance">
                    <i class="bi bi-exclamation-triangle"></i>
                    <h3 id="lowAttendance"><?php echo $lowAttendance; ?></h3>
                    <p>Low Attendance</p>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="filters-section">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Search Students</label>
                        <input type="text" class="form-control search-input" id="searchInput" 
                               placeholder="Search by name, student number, or date...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Grade & Section</label>
                        <select class="form-select filter-select" id="gradeFilter">
                            <option value="">All Grades</option>
                            <option value="Grade 11 - STEM A">Grade 11 - STEM A</option>
                            <option value="Grade 11 - STEM B">Grade 11 - STEM B</option>
                            <option value="Grade 12 - STEM A">Grade 12 - STEM A</option>
                            <option value="Grade 12 - STEM B">Grade 12 - STEM B</option>
                            <option value="Grade 11 - ABM A">Grade 11 - ABM A</option>
                            <option value="Grade 12 - ABM A">Grade 12 - ABM A</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select filter-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="present">Present</option>
                            <option value="late">Late</option>
                            <option value="absent">Absent</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Attendance Rate</label>
                        <select class="form-select filter-select" id="rateFilter">
                            <option value="">All Rates</option>
                            <option value="high">High (≥90%)</option>
                            <option value="medium">Medium (75-89%)</option>
                            <option value="low">Low (<75%)</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                            <i class="bi bi-arrow-clockwise"></i> Clear
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="table-section">
                <div class="table-header">
                    <h3><i class="bi bi-table me-2"></i>Student Attendance Records</h3>
                    <div class="export-buttons">
                        <button class="btn btn-success btn-sm" onclick="exportToCSV()">
                            <i class="bi bi-file-earmark-excel"></i> Export CSV
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="exportToPDF()">
                            <i class="bi bi-file-earmark-pdf"></i> Export PDF
                        </button>
                        <button class="btn btn-primary btn-sm" onclick="addNewRecord()">
                            <i class="bi bi-plus-circle"></i> Add Record
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="recordsTable">
                        <thead>
                            <tr>
                                <th onclick="sortTable(0)">Student No. <i class="bi bi-arrow-down-up"></i></th>
                                <th onclick="sortTable(1)">First Name <i class="bi bi-arrow-down-up"></i></th>
                                <th onclick="sortTable(2)">Last Name <i class="bi bi-arrow-down-up"></i></th>
                                <th onclick="sortTable(3)">Grade & Section <i class="bi bi-arrow-down-up"></i></th>
                                <th onclick="sortTable(4)">Course/Strand <i class="bi bi-arrow-down-up"></i></th>
                                <th onclick="sortTable(5)">Total Days <i class="bi bi-arrow-down-up"></i></th>
                                <th onclick="sortTable(6)">Present <i class="bi bi-arrow-down-up"></i></th>
                                <th onclick="sortTable(7)">Absent <i class="bi bi-arrow-down-up"></i></th>
                                <th onclick="sortTable(8)">Rate % <i class="bi bi-arrow-down-up"></i></th>
                                <th onclick="sortTable(9)">Date <i class="bi bi-arrow-down-up"></i></th>
                                <th onclick="sortTable(10)">Status <i class="bi bi-arrow-down-up"></i></th>
                                <th onclick="sortTable(11)">Time In <i class="bi bi-arrow-down-up"></i></th>
                                <th onclick="sortTable(12)">Time Out <i class="bi bi-arrow-down-up"></i></th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="recordsTableBody">
<?php
if ($attendance_result && $attendance_result->num_rows > 0) {
    while ($row = $attendance_result->fetch_assoc()) {
        // Extract student info
        $studentNo = htmlspecialchars($row['student_no']);
        $firstName = htmlspecialchars($row['first_name']);
        $lastName = htmlspecialchars($row['last_name']);
        $section = htmlspecialchars($row['section']);
        $strand = htmlspecialchars($row['strand']);
        $totalDays = (int)$row['total_days'];
        $present = (int)$row['present_days'];
        $absent = $totalDays - $present;
        $rate = $totalDays > 0 ? round(($present / $totalDays) * 100, 1) : 0;
        $date = date("M d, Y", strtotime($row['date']));

        // Determine status
        $status = strtolower($row['status']); // present / late / absent
        $statusIcon = '';
        $statusClass = '';
        if ($status === 'present') {
            $statusIcon = 'check-circle';
            $statusClass = 'status-present';
        } elseif ($status === 'late') {
            $statusIcon = 'clock';
            $statusClass = 'status-late';
        } else {
            $statusIcon = 'x-circle';
            $statusClass = 'status-absent';
        }

        // Attendance rate class
        if ($rate >= 90) $rateClass = 'high';
        elseif ($rate >= 75) $rateClass = 'medium';
        else $rateClass = 'low';

        // Time in/out
        $timeIn = !empty($row['time_in']) ? date("g:i A", strtotime($row['time_in'])) : '–';
        $timeOut = !empty($row['time_out']) ? date("g:i A", strtotime($row['time_out'])) : '–';

        // Low attendance row class
        $lowRowClass = $rate < 75 ? 'low-attendance-row' : '';
        echo "<tr class='{$lowRowClass}'>
                <td>{$studentNo}</td>
                <td>{$firstName}</td>
                <td>{$lastName}</td>
                <td>{$section}</td>
                <td>{$strand}</td>
                <td>{$totalDays}</td>
                <td>{$present}</td>
                <td>{$absent}</td>
                <td><span class='attendance-rate {$rateClass}'>{$rate}%</span></td>
                <td>{$date}</td>
                <td><span class='status-badge {$statusClass}'><i class='bi bi-{$statusIcon}'></i> " . ucfirst($status) . "</span></td>
                <td>{$timeIn}</td>
                <td>{$timeOut}</td>
                <td>
                    <div class='action-buttons'>
            
                        <button class='btn btn-outline-info btn-sm' onclick='viewDetails(this)' title='View Details'><i class='bi bi-eye'></i></button>
                        <button class='btn btn-outline-danger btn-sm' onclick='deleteRecord(this)' title='Delete'><i class='bi bi-trash'></i></button>
                    </div>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='14' class='text-center'>No records found.</td></tr>";
}
?>
</tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle sidebar for mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        // Search and filter functionality
        function initializeFilters() {
            const searchInput = document.getElementById('searchInput');
            const gradeFilter = document.getElementById('gradeFilter');
            const statusFilter = document.getElementById('statusFilter');
            const rateFilter = document.getElementById('rateFilter');
            
            [searchInput, gradeFilter, statusFilter, rateFilter].forEach(element => {
                element.addEventListener('input', filterTable);
                element.addEventListener('change', filterTable);
            });
        }

        function filterTable() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const gradeFilter = document.getElementById('gradeFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const rateFilter = document.getElementById('rateFilter').value;
            
            const rows = document.querySelectorAll('#recordsTableBody tr');
            
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
                
                // Search match
                const matchesSearch = rowText.includes(searchTerm);
                
                // Grade filter
                const gradeCell = cells[3].textContent;
                const matchesGrade = !gradeFilter || gradeCell === gradeFilter;
                
                // Status filter
                const statusElement = cells[10].querySelector('.status-badge');
                const matchesStatus = !statusFilter || statusElement.classList.contains(`status-${statusFilter}`);
                
                // Rate filter
                const rateElement = cells[8].querySelector('.attendance-rate');
                let matchesRate = true;
                if (rateFilter) {
                    matchesRate = rateElement.classList.contains(rateFilter);
                }
                
                // Show/hide row
                row.style.display = (matchesSearch && matchesGrade && matchesStatus && matchesRate) ? '' : 'none';
            });
            
            updateSummaryCards();
        }

        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('gradeFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('rateFilter').value = '';
            filterTable();
        }

        // Sort table functionality
        let sortDirection = {};
        
        function sortTable(columnIndex) {
            const table = document.getElementById('recordsTable');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            // Toggle sort direction
            sortDirection[columnIndex] = sortDirection[columnIndex] === 'asc' ? 'desc' : 'asc';
            
            rows.sort((a, b) => {
                const aValue = a.cells[columnIndex].textContent.trim();
                const bValue = b.cells[columnIndex].textContent.trim();
                
                // Handle numeric values
                if (!isNaN(aValue) && !isNaN(bValue)) {
                    return sortDirection[columnIndex] === 'asc' ? 
                        parseFloat(aValue) - parseFloat(bValue) : 
                        parseFloat(bValue) - parseFloat(aValue);
                }
                
                // Handle percentage values
                if (aValue.includes('%') && bValue.includes('%')) {
                    const aNum = parseFloat(aValue.replace('%', ''));
                    const bNum = parseFloat(bValue.replace('%', ''));
                    return sortDirection[columnIndex] === 'asc' ? aNum - bNum : bNum - aNum;
                }
                
                // Handle text values
                return sortDirection[columnIndex] === 'asc' ? 
                    aValue.localeCompare(bValue) : 
                    bValue.localeCompare(aValue);
            });
            
            // Re-append sorted rows
            rows.forEach(row => tbody.appendChild(row));
            
            // Update sort indicators
            updateSortIndicators(columnIndex);
        }

        function updateSortIndicators(activeColumn) {
            const headers = document.querySelectorAll('#recordsTable th');
            headers.forEach((header, index) => {
                const icon = header.querySelector('i');
                if (icon) {
                    if (index === activeColumn) {
                        icon.className = sortDirection[activeColumn] === 'asc' ? 
                            'bi bi-arrow-up' : 'bi bi-arrow-down';
                    } else {
                        icon.className = 'bi bi-arrow-down-up';
                    }
                }
            });
        }

        // Action functions

        function deleteRecord(button) {
            const row = button.closest('tr');
            const studentName = `${row.cells[1].textContent} ${row.cells[2].textContent}`;
            
            if (confirm(`Are you sure you want to delete the attendance record for ${studentName}?`)) {
                row.remove();
                showNotification(`Record deleted for ${studentName}`, 'success');
                updateSummaryCards();
            }
        }

        function addNewRecord() {
            showNotification('Opening new record form...', 'info');
            // Here you would typically open an add record modal or navigate to add page
        }

        // Export functions
        function exportToCSV() {
            const table = document.getElementById('recordsTable');
            const rows = table.querySelectorAll('tr:not([style*="display: none"])');
            
            let csv = '';
            rows.forEach((row, index) => {
                const cells = row.querySelectorAll(index === 0 ? 'th' : 'td');
                const rowData = [];
                
                cells.forEach((cell, cellIndex) => {
                    if (cellIndex < cells.length - 1) { // Skip actions column
                        let text = cell.textContent.trim();
                        // Clean up status badges and attendance rates
                        text = text.replace(/\s+/g, ' ');
                        rowData.push(`"${text}"`);
                    }
                });
                
                csv += rowData.join(',') + '\n';
            });
            
            // Download CSV
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'student_attendance_records.csv';
            a.click();
            window.URL.revokeObjectURL(url);
            
            showNotification('CSV file exported successfully!', 'success');
        }

        function exportToPDF() {
            showNotification('PDF export feature coming soon!', 'info');
        }

        // Update summary cards based on visible data
        function updateSummaryCards() {
            const visibleRows = document.querySelectorAll('#recordsTableBody tr:not([style*="display: none"])');
            
            let totalStudents = 0;
            let presentToday = 0;
            let lowAttendanceCount = 0;
            let totalRate = 0;
            
            const uniqueStudents = new Set();
            
            visibleRows.forEach(row => {
                const studentNo = row.cells[0].textContent;
                const status = row.cells[10].textContent.toLowerCase();
                const rateText = row.cells[8].textContent;
                const rate = parseFloat(rateText.replace('%', ''));
                
                uniqueStudents.add(studentNo);
                
                if (status.includes('present')) presentToday++;
                if (rate < 75) lowAttendanceCount++;
                totalRate += rate;
            });
            
            totalStudents = uniqueStudents.size;
            const avgRate = totalStudents > 0 ? (totalRate / visibleRows.length).toFixed(1) : 0;
            
            document.getElementById('totalStudents').textContent = totalStudents;
            document.getElementById('presentToday').textContent = presentToday;
            document.getElementById('lowAttendance').textContent = lowAttendanceCount;
            document.getElementById('overallRate').textContent = avgRate + '%';
        }

        // Show notification
        function showNotification(message, type = 'info') {
            // Create toast notification
            const toastContainer = document.querySelector('.toast-container') || createToastContainer();
            
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} border-0`;
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            
            toastContainer.appendChild(toast);
            
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            // Remove toast after it's hidden
            toast.addEventListener('hidden.bs.toast', () => {
                toast.remove();
            });
        }

        function createToastContainer() {
            const container = document.createElement('div');
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '1055';
            document.body.appendChild(container);
            return container;
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const mobileBtn = document.querySelector('.mobile-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !mobileBtn.contains(e.target) && 
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            initializeFilters();
            updateSummaryCards();
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98e5b4f3913b0dcb',t:'MTc2MDQzMDU0NC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
