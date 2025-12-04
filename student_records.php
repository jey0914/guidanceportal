<?php
session_start();
include("db.php");

// Redirect if no student is logged in
if (!isset($_SESSION['student_no'])) {
    header("Location: login.php"); // redirect to login page
    exit;
}

$student_no = $_SESSION['student_no'];

// Fetch student info
$sql = "SELECT * FROM form WHERE student_no = '$student_no'";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    echo "Student not found.";
    exit;
}

// Fetch all attendance logs for this student
$attendance_sql = "SELECT * FROM attendance_logs WHERE student_no='$student_no' ORDER BY date DESC";
$attendance_result = $con->query($attendance_sql);

// Store attendance rows
$attendance_rows = [];
if ($attendance_result->num_rows > 0) {
    while ($row = $attendance_result->fetch_assoc()) {
        $attendance_rows[] = $row;
    }
}

// Calculate summary statistics
$totalDays = count($attendance_rows);
$presentDays = 0;
$absentDays = 0;

foreach ($attendance_rows as $row) {
    if (strtolower($row['status']) === 'present') {
        $presentDays++;
    } elseif (strtolower($row['status']) === 'absent') {
        $absentDays++;
    }
}

$attendanceRate = ($totalDays > 0) ? round(($presentDays / $totalDays) * 100) : 0;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senior High RFID Attendance – Student View</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F3F4F6;
            color: #111827;
        }

        /* Header */
        .header {
            background-color: #1E3A8A;
            color: white;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
        }

        .header p {
            margin: 0.5rem 0 0 0;
            font-size: 1rem;
            opacity: 0.9;
        }

        /* Main Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Student Profile Section */
        .student-profile {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .profile-picture {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3B82F6, #1E40AF);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
            border: 4px solid white;
            flex-shrink: 0;
        }

        .profile-info {
            flex: 1;
        }

        .student-name {
            font-size: 2rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .student-details {
            color: #6B7280;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .student-details p {
            margin: 0.25rem 0;
        }

        .current-status {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
        }

        .status-present {
            background: rgba(34, 197, 94, 0.1);
            color: #15803D;
            border: 2px solid rgba(34, 197, 94, 0.3);
        }

        .status-late {
            background: rgba(245, 158, 11, 0.1);
            color: #D97706;
            border: 2px solid rgba(245, 158, 11, 0.3);
        }

        .status-absent {
            background: rgba(239, 68, 68, 0.1);
            color: #DC2626;
            border: 2px solid rgba(239, 68, 68, 0.3);
        }

        /* Controls Section */
        .controls {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .search-filter {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .search-input {
            padding: 0.75rem;
            border: 2px solid #E5E7EB;
            border-radius: 8px;
            font-size: 1rem;
            width: 250px;
        }

        .search-input:focus {
            outline: none;
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .filter-select {
            padding: 0.75rem;
            border: 2px solid #E5E7EB;
            border-radius: 8px;
            font-size: 1rem;
            background: white;
            cursor: pointer;
        }

        .export-buttons {
            display: flex;
            gap: 0.75rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: #3B82F6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563EB;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #6B7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4B5563;
            transform: translateY(-1px);
        }

        /* Attendance Table */
        .table-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table-header {
            background: #1E3A8A;
            color: white;
            padding: 1.5rem 2rem;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .table-wrapper {
            max-height: 500px;
            overflow-y: auto;
        }

        .attendance-table {
            width: 100%;
            border-collapse: collapse;
        }

        .attendance-table th {
            background: #1E3A8A;
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 700;
            position: sticky;
            top: 0;
            z-index: 10;
            border-bottom: 2px solid #1E40AF;
        }

        .attendance-table td {
            padding: 1rem;
            border-bottom: 1px solid #F1F5F9;
            color: #374151;
        }

        .attendance-table tr:nth-child(even) {
            background: #F8FAFC;
        }

        .attendance-table tr:hover {
            background: #EFF6FF;
        }

        .table-status {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .table-status.present {
            background: rgba(34, 197, 94, 0.1);
            color: #15803D;
        }

        .table-status.late {
            background: rgba(245, 158, 11, 0.1);
            color: #D97706;
        }

        .table-status.absent {
            background: rgba(239, 68, 68, 0.1);
            color: #DC2626;
        }

        /* Summary Cards */
        .summary-section {
            margin-top: 2rem;
        }

        .summary-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 1rem;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #E2E8F0;
        }

        .summary-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
        }

        .summary-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .summary-label {
            color: #6B7280;
            font-weight: 600;
            font-size: 1rem;
        }

        .summary-card.total .summary-icon { background: rgba(59, 130, 246, 0.1); color: #3B82F6; }
        .summary-card.total .summary-number { color: #3B82F6; }

        .summary-card.present .summary-icon { background: rgba(34, 197, 94, 0.1); color: #22C55E; }
        .summary-card.present .summary-number { color: #22C55E; }

        .summary-card.absent .summary-icon { background: rgba(239, 68, 68, 0.1); color: #EF4444; }
        .summary-card.absent .summary-number { color: #EF4444; }

        .summary-card.attendance-rate .summary-icon { background: rgba(168, 85, 247, 0.1); color: #A855F7; }
        .summary-card.attendance-rate .summary-number { color: #A855F7; }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .student-profile {
                flex-direction: column;
                text-align: center;
            }

            .controls {
                flex-direction: column;
                align-items: stretch;
            }

            .search-filter {
                flex-direction: column;
            }

            .search-input {
                width: 100%;
            }

            .attendance-table {
                font-size: 0.875rem;
            }

            .attendance-table th,
            .attendance-table td {
                padding: 0.75rem 0.5rem;
            }

            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Loading Animation */
        .loading {
            text-align: center;
            padding: 2rem;
            color: #6B7280;
        }

        .spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 4px solid #E5E7EB;
            border-radius: 50%;
            border-top-color: #3B82F6;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* No Records Message */
        .no-records {
            text-align: center;
            padding: 3rem;
            color: #6B7280;
        }

        .no-records i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Senior High RFID Attendance – Student View</h1>
        <p>View your personal attendance records and statistics</p>
    </div>

  <!-- Main Container -->
<div class="container">
    <!-- Student Profile Section -->
    <div class="student-profile">
        <div class="profile-picture">
            <i class="fas fa-user"></i>
        </div>
        <div class="profile-info">
            <h2 class="student-name">
                <?php echo $student['fname'] . ' ' . $student['lname']; ?>
            </h2>
            <div class="student-details">
                <p><strong>Student No.:</strong> <?php echo $student['student_no']; ?></p>
                <p><strong>Year:</strong> <?php echo $student['year_level']; ?></p>
                <p><strong>Course/Strand:</strong> <?php echo $student['strand_course']; ?></p>
                <p><strong>School Year:</strong> 2024-2025</p>
            </div>

            <?php
                // Determine today's attendance status
                $today = date('Y-m-d');
                $statusToday = 'Absent';
                foreach($attendance_rows as $log) {
                    if($log['date'] == $today){
                        $statusToday = $log['status'];
                        break;
                    }
                }
                $statusClass = strtolower($statusToday) === 'present' ? 'status-present' : (strtolower($statusToday) === 'late' ? 'status-late' : 'status-absent');
            ?>
            <div class="current-status <?php echo $statusClass; ?>">
                <i class="<?php echo ($statusClass=='status-present') ? 'fas fa-check-circle' : (($statusClass=='status-late') ? 'fas fa-clock' : 'fas fa-times-circle'); ?>"></i>
                <span><?php echo ucfirst($statusToday) . " Today"; ?></span>
            </div>
        </div>
    </div>
</div>

        <!-- Controls Section -->
        <div class="controls">
            <div class="search-filter">
                <input type="text" class="search-input" id="searchInput" placeholder="Search by date or remarks...">
                <select class="filter-select" id="filterSelect">
                    <option value="all">All Records</option>
                    <option value="present">Present Only</option>
                    <option value="late">Late Only</option>
                    <option value="absent">Absent Only</option>
                </select>
            </div>
            <div class="export-buttons">
                <button class="btn btn-primary" onclick="exportToCSV()">
                    <i class="fas fa-download"></i>
                    Export CSV
                </button>
                <button class="btn btn-secondary" onclick="exportToPDF()">
                    <i class="fas fa-file-pdf"></i>
                    Export PDF
                </button>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="table-container">
            <div class="table-header">
                <i class="fas fa-table"></i> My Attendance Records
            </div>
            
            <div class="table-wrapper">
                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Total Days</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                   <tbody id="attendanceTableBody">
<?php
if (!empty($attendance_rows)) {
    foreach ($attendance_rows as $row) {
        $statusClass = strtolower($row['status']); // present, late, absent
        $icon = ($statusClass == 'present') ? 'fas fa-check-circle' : (($statusClass == 'late') ? 'fas fa-clock' : 'fas fa-times-circle');
        echo "<tr>
            <td>{$row['name']}</td>
            <td>{$row['section']}</td>
            <td>{$row['total_days']}</td>
            <td>{$row['present_days']}</td>
            <td>" . ($row['total_days'] - $row['present_days']) . "</td>
            <td>{$row['time_in']}</td>
            <td>{$row['time_out']}</td>
            <td><span class='table-status {$statusClass}'><i class='{$icon}'></i> {$row['status']}</span></td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='8' class='no-records'>No attendance records found.</td></tr>";
}
?>
</tbody>

                </table>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="summary-section">
            <h3 class="summary-title">Attendance Summary</h3>
            <div class="summary-grid">
                <div class="summary-card total">
                    <div class="summary-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="summary-number" id="totalDaysCard"><?php echo $totalDays; ?></div>
                    <div class="summary-label">Total School Days</div>
                </div>
                <div class="summary-card present">
                    <div class="summary-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="summary-number" id="presentDaysCard"><?php echo $presentDays; ?></div>
                    <div class="summary-label">Days Present</div>
                </div>
                <div class="summary-card absent">
                    <div class="summary-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="summary-number" id="absentDaysCard"><?php echo $absentDays; ?></div>
                    <div class="summary-label">Days Absent</div>
                </div>
                <div class="summary-card attendance-rate">
                    <div class="summary-icon">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div class="summary-number" id="attendanceRate"><?php echo $attendanceRate; ?>%</div>
                    <div class="summary-label">Attendance Rate</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Search and filter functionality
        function initializeFilters() {
            const searchInput = document.getElementById('searchInput');
            const filterSelect = document.getElementById('filterSelect');
            
            searchInput.addEventListener('input', filterTable);
            filterSelect.addEventListener('change', filterTable);
        }

        function filterTable() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const filterValue = document.getElementById('filterSelect').value;
            const rows = document.querySelectorAll('#attendanceTableBody tr');
            
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const remarksCell = cells[7];
                const statusElement = remarksCell.querySelector('.table-status');
                
                // Get text content for search
                const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
                
                // Check search match
                const matchesSearch = rowText.includes(searchTerm);
                
                // Check filter match
                let matchesFilter = true;
                if (filterValue !== 'all') {
                    matchesFilter = statusElement.classList.contains(filterValue);
                }
                
                // Show/hide row
                row.style.display = (matchesSearch && matchesFilter) ? '' : 'none';
            });
        }

        // Export to CSV
        function exportToCSV() {
            const table = document.getElementById('attendanceTableBody');
            const rows = table.querySelectorAll('tr:not([style*="display: none"])');
            
            let csv = 'First Name,Last Name,Total Days,Present,Absent,Time In,Time Out,Remarks\n';
            
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const rowData = [];
                
                cells.forEach((cell, index) => {
                    if (index === 7) { // Remarks column
                        const statusText = cell.querySelector('.table-status').textContent.trim();
                        rowData.push(`"${statusText}"`);
                    } else {
                        rowData.push(`"${cell.textContent.trim()}"`);
                    }
                });
                
                csv += rowData.join(',') + '\n';
            });
            
            // Download CSV
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'attendance_records.csv';
            a.click();
            window.URL.revokeObjectURL(url);
            
            showNotification('CSV file downloaded successfully!', 'success');
        }

        // Export to PDF (simplified version)
        function exportToPDF() {
            showNotification('PDF export feature coming soon!', 'info');
        }

        // Show notification
        function showNotification(message, type = 'success') {
            // Create notification element
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                color: white;
                font-weight: 600;
                z-index: 1000;
                transform: translateX(400px);
                transition: transform 0.3s ease;
            `;
            
            // Set background color based on type
            switch(type) {
                case 'success':
                    notification.style.background = '#22C55E';
                    break;
                case 'info':
                    notification.style.background = '#3B82F6';
                    break;
                case 'warning':
                    notification.style.background = '#F59E0B';
                    break;
                case 'error':
                    notification.style.background = '#EF4444';
                    break;
            }
            
            notification.textContent = message;
            document.body.appendChild(notification);
            
            // Show notification
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);
            
            // Hide notification
            setTimeout(() => {
                notification.style.transform = 'translateX(400px)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Update summary statistics
        function updateSummaryStats() {
            const rows = document.querySelectorAll('#attendanceTableBody tr');
            let totalDays = 0;
            let presentDays = 0;
            let absentDays = 0;
            
            // Get the latest record (first row) for current stats
            if (rows.length > 0) {
                const latestRow = rows[0];
                const cells = latestRow.querySelectorAll('td');
                
                totalDays = parseInt(cells[2].textContent);
                presentDays = parseInt(cells[3].textContent);
                absentDays = parseInt(cells[4].textContent);
            }
            
            const attendanceRate = totalDays > 0 ? Math.round((presentDays / totalDays) * 100) : 0;
            
            // Update summary cards
            document.getElementById('totalDaysCard').textContent = totalDays;
            document.getElementById('presentDaysCard').textContent = presentDays;
            document.getElementById('absentDaysCard').textContent = absentDays;
            document.getElementById('attendanceRate').textContent = attendanceRate + '%';
        }
            
            // Determine status based on time
            const hour = now.getHours();
            let status, statusClass, icon;
            
            if (hour < 8) {
                status = 'Present';
                statusClass = 'present';
                icon = 'fas fa-check-circle';
            } else {
                status = 'Late';
                statusClass = 'late';
                icon = 'fas fa-clock';
            }
            
            // Create new row
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>Ayesha</td>
                <td>Dela Cruz</td>
                <td>16</td>
                <td>${status === 'Present' ? '13' : '12'}</td>
                <td>3</td>
                <td>${timeIn}</td>
                <td>–</td>
                <td><span class="table-status ${statusClass}"><i class="${icon}"></i> ${status}</span></td>
            `;
            
            // Insert at the top
            tableBody.insertBefore(newRow, tableBody.firstChild);
            
            // Update summary
            updateSummaryStats();
            
            showNotification(`New attendance record: ${status} at ${timeIn}`, 'success');
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            initializeFilters();
            updateSummaryStats();
            
            // Simulate RFID updates every 30 seconds for demo
            setInterval(simulateRFIDUpdate, 30000);
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98e51eb614540dcb',t:'MTc2MDQyNDM5MC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
