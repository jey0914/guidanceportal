<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

include("db.php");

// Handle new announcement insertion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['announce_title'], $_POST['announce_message'])) {
    $title = trim($_POST['announce_title']);
    $message = trim($_POST['announce_message']);

    if (!empty($title) && !empty($message)) {
        $stmt = $con->prepare("INSERT INTO exam_announcements (title, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $message);
        $stmt->execute();
    }
}

// Delete announcement
if (isset($_GET['delete_announcement'])) {
    $delete_id = intval($_GET['delete_announcement']);
    $con->query("DELETE FROM exam_announcements WHERE id = $delete_id");
}

// Fetch all announcements
$announcements = $con->query("SELECT * FROM exam_announcements ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements Management - Admin Panel</title>
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
            border-left: 4px solid #fd7e14;
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
        
        .add-button {
            background: #fd7e14;
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .add-button:hover {
            background: #e8690b;
            transform: translateY(-1px);
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
        
        .stat-card.total { border-left-color: #fd7e14; }
        .stat-card.recent { border-left-color: #0d6efd; }
        .stat-card.active { border-left-color: #198754; }
        .stat-card.draft { border-left-color: #6c757d; }
        
        .stat-card i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .stat-card.total i { color: #fd7e14; }
        .stat-card.recent i { color: #0d6efd; }
        .stat-card.active i { color: #198754; }
        .stat-card.draft i { color: #6c757d; }
        
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
            border-color: #fd7e14;
            box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
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
            background: #fd7e14;
            color: white;
            border-color: #fd7e14;
        }
        
        .announcements-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .announcement-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border-left: 4px solid #fd7e14;
        }
        
        .announcement-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .announcement-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }
        
        .announcement-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .announcement-date {
            color: #6c757d;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-badge.active {
            background: #d1edff;
            color: #0969da;
        }
        
        .announcement-content {
            margin: 1.5rem 0;
            line-height: 1.6;
            color: #495057;
        }
        
        .announcement-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid #f8f9fa;
        }
        
        .announcement-stats {
            display: flex;
            gap: 1rem;
        }
        
        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .announcement-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .action-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #dee2e6;
            background: white;
            color: #6c757d;
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .action-btn:hover {
            transform: translateY(-1px);
        }
        
        .action-btn.edit:hover {
            background: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }
        
        .action-btn.duplicate:hover {
            background: #198754;
            color: white;
            border-color: #198754;
        }
        
        .action-btn.delete:hover {
            background: #dc3545;
            color: white;
            border-color: #dc3545;
        }
        
        .no-announcements {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .no-announcements i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }
        
        .no-announcements h3 {
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .no-announcements p {
            color: #6c757d;
            margin-bottom: 2rem;
        }
        
        .btn-primary {
            background: #fd7e14;
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover {
            background: #e8690b;
            transform: translateY(-1px);
        }
        
        .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .modal-header {
            background: #fd7e14;
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
        
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-control:focus {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 0.75rem;
        }
        
        .form-control:focus {
            border-color: #fd7e14;
            box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
        }
        
        .modal-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid #f8f9fa;
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
            
            .announcement-header {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .announcement-footer {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            .announcement-actions {
                width: 100%;
                justify-content: space-between;
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
                <li><a href="admin_announcements.php" class="active"><i class="bi bi-megaphone"></i> Announcements</a></li>
                <li><a href="admin_parents.php"><i class="bi bi-person-lines-fill"></i> Parent Accounts</a></li>
            </ul>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Page Header -->
            <div class="page-header">
                <div class="header-content">
                    <h1 class="page-title">
                        <i class="bi bi-megaphone"></i>
                        Announcements Management
                    </h1>
                    <p class="page-subtitle">Create and manage school announcements for students and parents</p>
                </div>
                <button class="add-button" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                    <i class="bi bi-plus-circle"></i>
                    Add New Announcement
                </button>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-cards">
                <div class="stat-card total">
                    <i class="bi bi-megaphone"></i>
                    <h3 id="totalCount">0</h3>
                    <p>Total Announcements</p>
                </div>
                <div class="stat-card recent">
                    <i class="bi bi-clock"></i>
                    <h3 id="recentCount">0</h3>
                    <p>This Week</p>
                </div>
                <div class="stat-card active">
                    <i class="bi bi-eye"></i>
                    <h3 id="activeCount">0</h3>
                    <p>Currently Active</p>
                </div>
                <div class="stat-card draft">
                    <i class="bi bi-file-earmark-text"></i>
                    <h3 id="draftCount">0</h3>
                    <p>Draft Posts</p>
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="filter-section">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="Search announcements..." class="search-input">
                </div>
                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="all">All</button>
                    <button class="filter-btn" data-filter="recent">Recent</button>
                    <button class="filter-btn" data-filter="important">Important</button>
                </div>
            </div>

            <!-- Announcements Container -->
            <div class="announcements-container">
                <?php if ($announcements && $announcements->num_rows > 0): ?>
                    <?php while ($row = $announcements->fetch_assoc()): ?>
                        <div class="announcement-card" data-title="<?= strtolower(htmlspecialchars($row['title'])) ?>" data-date="<?= $row['created_at'] ?>">
                            <div class="announcement-header">
                                <div class="announcement-meta">
                                    <div class="announcement-title"><?= htmlspecialchars($row['title']) ?></div>
                                    <div class="announcement-date">
                                        <i class="bi bi-calendar3"></i>
                                        Posted on <?= date("F d, Y h:i A", strtotime($row['created_at'])) ?>
                                    </div>
                                </div>
                                <div class="announcement-status">
                                    <span class="status-badge active">Active</span>
                                </div>
                            </div>
                            
                            <div class="announcement-content">
                                <p><?= nl2br(htmlspecialchars($row['message'])) ?></p>
                            </div>
                            
                            <div class="announcement-footer">
                                <div class="announcement-stats">
                                    <span class="stat-item">
                                        <i class="bi bi-eye"></i>
                                        <span><?= $row['views'] ?> views</span>
                                    </span>
                                    <span class="stat-item">
                                        <i class="bi bi-heart"></i>
                                        <span><?= $row['likes'] ?> likes</span>
                                    </span>
                                </div>
                                <div class="announcement-actions">
                                    <a href="edit_announcements.php?id=<?= $row['id'] ?>" class="action-btn edit">
                                        ✏️ Edit
                                    </a>
                                    <a href="#" 
                                    class="action-btn delete" 
                                    data-id="<?= $row['id'] ?>" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteConfirmModal">
                                   🗑️ Delete
                                    </a>

                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No announcements found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal: Add Announcement -->
<div class="modal fade" id="addAnnouncementModal" tabindex="-1" aria-labelledby="addAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAnnouncementModalLabel">
                    <i class="bi bi-plus-circle"></i>
                    Add New Announcement
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Announcement Title</label>
                    <input type="text" name="announce_title" class="form-control" placeholder="Enter announcement title..." required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Message Content</label>
                    <textarea name="announce_message" rows="6" class="form-control" placeholder="Write your announcement message here..." required></textarea>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="importantCheck">
                        <label class="form-check-label" for="importantCheck">
                            Mark as important announcement
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send"></i>
                    Post Announcement
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Delete Confirmation -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">
          <i class="bi bi-exclamation-triangle"></i> Confirm Deletion
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this announcement? This action cannot be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Yes, Delete</a>
      </div>
    </div>
  </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Delete confirmation handling
        document.addEventListener("DOMContentLoaded", function () {
        const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
        document.querySelectorAll(".action-btn.delete").forEach(btn => {
            btn.addEventListener("click", function () {
                let deleteId = this.getAttribute("data-id");
                confirmDeleteBtn.setAttribute("href", "?delete_announcement=" + deleteId);
            });
        });
    });

        // Update statistics
        function updateStats() {
            const cards = document.querySelectorAll('.announcement-card');
            const totalCount = cards.length;
            
            // Calculate recent announcements (last 7 days)
            const oneWeekAgo = new Date();
            oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);
            
            let recentCount = 0;
            cards.forEach(card => {
                const dateStr = card.dataset.date;
                if (dateStr) {
                    const cardDate = new Date(dateStr);
                    if (cardDate >= oneWeekAgo) {
                        recentCount++;
                    }
                }
            });
            
            // Simulate other stats
            const activeCount = totalCount; // All are active for now
            const draftCount = 0; // No drafts in current implementation
            
            document.getElementById('totalCount').textContent = totalCount;
            document.getElementById('recentCount').textContent = recentCount;
            document.getElementById('activeCount').textContent = activeCount;
            document.getElementById('draftCount').textContent = draftCount;
            
            // Simulate view and like counts
            cards.forEach((card, index) => {
                const id = card.querySelector('[id^="views-"]')?.id.split('-')[1];
                if (id) {
                    document.getElementById(`views-${id}`).textContent = Math.floor(Math.random() * 100) + 10;
                    document.getElementById(`likes-${id}`).textContent = Math.floor(Math.random() * 20) + 1;
                }
            });
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const cards = document.querySelectorAll('.announcement-card');
            
            cards.forEach(card => {
                const title = card.dataset.title || '';
                const content = card.querySelector('.announcement-content p').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || content.includes(searchTerm)) {
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
                const cards = document.querySelectorAll('.announcement-card');
                
                cards.forEach(card => {
                    let show = true;
                    
                    if (filter === 'recent') {
                        const oneWeekAgo = new Date();
                        oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);
                        const cardDate = new Date(card.dataset.date);
                        show = cardDate >= oneWeekAgo;
                    } else if (filter === 'important') {
                        // For now, show all since we don't have importance flag in DB
                        show = true;
                    }
                    
                    card.style.display = show ? 'block' : 'none';
                });
            });
        });

        // Duplicate announcement function
        function duplicateAnnouncement(id) {
            const card = document.querySelector(`[id*="${id}"]`).closest('.announcement-card');
            const title = card.querySelector('.announcement-title').textContent;
            const content = card.querySelector('.announcement-content p').textContent;
            
            // Fill modal with existing data
            document.querySelector('input[name="announce_title"]').value = title + ' (Copy)';
            document.querySelector('textarea[name="announce_message"]').value = content;
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('addAnnouncementModal'));
            modal.show();
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateStats();
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98a3fa0fd3510dc9',t:'MTc1OTc0MTMxNC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
