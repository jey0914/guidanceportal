<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

include("db.php");

$sql = "SELECT p.fullname AS parent_name, p.email, p.contact, p.relationship, p.created_at,
               f.student_no, CONCAT(f.fname, ' ', f.lname) AS student_name
        FROM parents p
        LEFT JOIN form f ON p.student_id = f.student_no
        ORDER BY p.created_at DESC";

$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Accounts Management - Admin Panel</title>
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
        
        .content {
            flex: 1;
            margin-left: 250px;
            padding: 2rem;
        }
        
        .page-header {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #28a745;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-content h1 {
            font-size: 1.75rem;
            font-weight: 600;
            color: #495057;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .page-subtitle {
            color: #6c757d;
            margin: 0.5rem 0 0 0;
            font-size: 1rem;
        }
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            border-left: 4px solid;
        }
        
        .stat-card.total { border-left-color: #28a745; }
        .stat-card.recent { border-left-color: #0d6efd; }
        .stat-card.mothers { border-left-color: #e91e63; }
        .stat-card.fathers { border-left-color: #ff9800; }
        
        .stat-card i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .stat-card.total i { color: #28a745; }
        .stat-card.recent i { color: #0d6efd; }
        .stat-card.mothers i { color: #e91e63; }
        .stat-card.fathers i { color: #ff9800; }
        
        .stat-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0.5rem 0;
            color: #495057;
        }
        
        .stat-card p {
            color: #6c757d;
            margin: 0;
            font-size: 0.9rem;
        }
        
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }
        
        .search-box {
            position: relative;
            flex: 1;
            max-width: 400px;
        }
        
        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            font-size: 0.9rem;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        
        .filter-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .filter-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #dee2e6;
            background: white;
            color: #6c757d;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }
        
        .filter-btn:hover, .filter-btn.active {
            background: #28a745;
            color: white;
            border-color: #28a745;
        }
        
        .parent-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            border-left: 4px solid #28a745;
            margin-bottom: 1.5rem;
        }
        
        .parent-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .parent-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }
        
        .parent-info h5 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .parent-meta {
            color: #6c757d;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .relationship-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .relationship-badge.mother {
            background: #fce4ec;
            color: #c2185b;
        }
        
        .relationship-badge.father {
            background: #fff3e0;
            color: #f57c00;
        }
        
        .relationship-badge.guardian {
            background: #e8f5e8;
            color: #2e7d32;
        }
        
        .parent-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #495057;
            font-size: 0.9rem;
        }
        
        .detail-item i {
            color: #28a745;
            width: 16px;
        }
        
        .student-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }
        
        .student-info h6 {
            color: #495057;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .modal-header {
            background: #28a745;
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 1.5rem;
        }
        
        .modal-title {
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .modal-body {
            padding: 2rem;
        }
        
        .modal-detail-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f8f9fa;
        }
        
        .modal-detail-item:last-child {
            border-bottom: none;
        }
        
        .modal-detail-item i {
            color: #28a745;
            width: 20px;
            text-align: center;
        }
        
        .modal-detail-item strong {
            color: #495057;
            min-width: 120px;
        }
        
        .no-parents {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .no-parents i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }
        
        .no-parents h3 {
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .no-parents p {
            color: #6c757d;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .content {
                margin-left: 0;
                padding: 1rem;
            }
            
            .page-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            .filter-section {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }
            
            .stats-cards {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .parent-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2><i class="bi bi-speedometer2"></i> Admin Panel</h2>
            <ul>
                <li><a href="admin_dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a></li>
                <li><a href="admin_records.php"><i class="bi bi-people"></i> Student Records</a></li>
                <li><a href="admin_appointments.php"><i class="bi bi-calendar-check"></i> Appointments</a></li>
                <li><a href="admin_reports.php"><i class="bi bi-graph-up"></i> Reports</a></li>
                <li><a href="admin_settings.php"><i class="bi bi-gear"></i> Settings</a></li>
                <li><a href="admin_announcements.php"><i class="bi bi-megaphone"></i> Announcements</a></li>
                <li><a href="admin_parents.php" class="active"><i class="bi bi-person-lines-fill"></i> Parent Accounts</a></li>
            </ul>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Page Header -->
            <div class="page-header">
                <div class="header-content">
                    <h1 class="page-title">
                        <i class="bi bi-person-lines-fill"></i>
                        Parent Accounts Management
                    </h1>
                    <p class="page-subtitle">Manage registered parent accounts and their student connections</p>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-cards">
                <div class="stat-card total">
                    <i class="bi bi-people"></i>
                    <h3 id="totalParents">0</h3>
                    <p>Total Parents</p>
                </div>
                <div class="stat-card recent">
                    <i class="bi bi-clock"></i>
                    <h3 id="recentParents">0</h3>
                    <p>This Month</p>
                </div>
                <div class="stat-card mothers">
                    <i class="bi bi-person-heart"></i>
                    <h3 id="motherCount">0</h3>
                    <p>Mothers</p>
                </div>
                <div class="stat-card fathers">
                    <i class="bi bi-person-check"></i>
                    <h3 id="fatherCount">0</h3>
                    <p>Fathers</p>
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="filter-section">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="Search parents by name, email, or student..." class="search-input">
                </div>
                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="all">All</button>
                    <button class="filter-btn" data-filter="mother">Mothers</button>
                    <button class="filter-btn" data-filter="father">Fathers</button>
                    <button class="filter-btn" data-filter="guardian">Guardians</button>
                </div>
            </div>

            <!-- Parents List -->
            <div class="parents-container">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="parent-card" 
                             data-name="<?= strtolower(htmlspecialchars($row['parent_name'])) ?>"
                             data-email="<?= strtolower(htmlspecialchars($row['email'])) ?>"
                             data-student="<?= strtolower(htmlspecialchars($row['student_name'])) ?>"
                             data-relationship="<?= strtolower(htmlspecialchars($row['relationship'])) ?>"
                             data-date="<?= $row['created_at'] ?>"
                             data-bs-toggle="modal" 
                             data-bs-target="#parentModal<?= $row['student_no'] ?>">
                            
                            <div class="parent-header">
                                <div class="parent-info">
                                    <h5>
                                        <i class="bi bi-person-circle"></i>
                                        <?= htmlspecialchars($row['parent_name']) ?>
                                    </h5>
                                    <div class="parent-meta">
                                        <i class="bi bi-calendar3"></i>
                                        Registered <?= date("M d, Y", strtotime($row['created_at'])) ?>
                                    </div>
                                </div>
                                <span class="relationship-badge <?= strtolower($row['relationship']) ?>">
                                    <?= htmlspecialchars($row['relationship']) ?>
                                </span>
                            </div>

                            <div class="parent-details">
                                <div class="detail-item">
                                    <i class="bi bi-envelope"></i>
                                    <span><?= htmlspecialchars($row['email']) ?></span>
                                </div>
                                <div class="detail-item">
                                    <i class="bi bi-telephone"></i>
                                    <span><?= htmlspecialchars($row['contact']) ?></span>
                                </div>
                            </div>

                            <div class="student-info">
                                <h6>
                                    <i class="bi bi-mortarboard"></i>
                                    Connected Student
                                </h6>
                                <div class="detail-item">
                                    <i class="bi bi-person"></i>
                                    <span><?= htmlspecialchars($row['student_name']) ?></span>
                                </div>
                                <div class="detail-item">
                                    <i class="bi bi-hash"></i>
                                    <span>Student #<?= htmlspecialchars($row['student_no']) ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="parentModal<?= $row['student_no'] ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <i class="bi bi-person-circle"></i>
                                            <?= htmlspecialchars($row['parent_name']) ?>'s Profile
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="modal-detail-item">
                                            <i class="bi bi-person"></i>
                                            <strong>Full Name:</strong>
                                            <span><?= htmlspecialchars($row['parent_name']) ?></span>
                                        </div>
                                        <div class="modal-detail-item">
                                            <i class="bi bi-envelope"></i>
                                            <strong>Email Address:</strong>
                                            <span><?= htmlspecialchars($row['email']) ?></span>
                                        </div>
                                        <div class="modal-detail-item">
                                            <i class="bi bi-telephone"></i>
                                            <strong>Contact Number:</strong>
                                            <span><?= htmlspecialchars($row['contact']) ?></span>
                                        </div>
                                        <div class="modal-detail-item">
                                            <i class="bi bi-heart"></i>
                                            <strong>Relationship:</strong>
                                            <span class="relationship-badge <?= strtolower($row['relationship']) ?>">
                                                <?= htmlspecialchars($row['relationship']) ?>
                                            </span>
                                        </div>
                                        <div class="modal-detail-item">
                                            <i class="bi bi-mortarboard"></i>
                                            <strong>Student Name:</strong>
                                            <span><?= htmlspecialchars($row['student_name']) ?></span>
                                        </div>
                                        <div class="modal-detail-item">
                                            <i class="bi bi-hash"></i>
                                            <strong>Student Number:</strong>
                                            <span><?= htmlspecialchars($row['student_no']) ?></span>
                                        </div>
                                        <div class="modal-detail-item">
                                            <i class="bi bi-calendar-check"></i>
                                            <strong>Registration Date:</strong>
                                            <span><?= date("F d, Y h:i A", strtotime($row['created_at'])) ?></span>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-success">
                                            <i class="bi bi-envelope"></i>
                                            Send Message
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-parents">
                        <i class="bi bi-people"></i>
                        <h3>No Parent Registrations</h3>
                        <p>No parents have registered yet. They will appear here once they create accounts.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update statistics
        function updateStats() {
            const cards = document.querySelectorAll('.parent-card');
            const totalCount = cards.length;
            
            // Calculate recent registrations (last 30 days)
            const oneMonthAgo = new Date();
            oneMonthAgo.setDate(oneMonthAgo.getDate() - 30);
            
            let recentCount = 0;
            let motherCount = 0;
            let fatherCount = 0;
            
            cards.forEach(card => {
                const dateStr = card.dataset.date;
                const relationship = card.dataset.relationship;
                
                if (dateStr) {
                    const cardDate = new Date(dateStr);
                    if (cardDate >= oneMonthAgo) {
                        recentCount++;
                    }
                }
                
                if (relationship === 'mother') motherCount++;
                if (relationship === 'father') fatherCount++;
            });
            
            document.getElementById('totalParents').textContent = totalCount;
            document.getElementById('recentParents').textContent = recentCount;
            document.getElementById('motherCount').textContent = motherCount;
            document.getElementById('fatherCount').textContent = fatherCount;
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const cards = document.querySelectorAll('.parent-card');
            
            cards.forEach(card => {
                const name = card.dataset.name || '';
                const email = card.dataset.email || '';
                const student = card.dataset.student || '';
                
                if (name.includes(searchTerm) || email.includes(searchTerm) || student.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active button
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const filter = this.dataset.filter;
                const cards = document.querySelectorAll('.parent-card');
                
                cards.forEach(card => {
                    const relationship = card.dataset.relationship;
                    
                    if (filter === 'all' || relationship === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateStats();
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98a4b9f9f4150dc9',t:'MTc1OTc0OTE3Ni4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
