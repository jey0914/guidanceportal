<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Drafts</title>
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
        
        .header {
            background-color: #fff;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #dee2e6;
            margin: -2rem -2rem 2rem -2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .email-toolbar {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }
        
        .draft-list {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .draft-item {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid #f8f9fa;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }
        
        .draft-item:last-child {
            border-bottom: none;
        }
        
        .draft-item:hover {
            background-color: #f8f9fa;
        }
        
        .draft-item.selected {
            background-color: #e3f2fd;
        }
        
        .draft-checkbox {
            margin-right: 1rem;
        }
        
        .draft-content {
            flex: 1;
            margin-right: 1rem;
        }
        
        .draft-subject {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }
        
        .draft-recipients {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }
        
        .draft-preview {
            color: #6c757d;
            font-size: 0.9rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .draft-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.5rem;
            min-width: 150px;
        }
        
        .draft-date {
            color: #6c757d;
            font-size: 0.85rem;
        }
        
        .draft-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
        }
        
        .auto-save-indicator {
            color: #28a745;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .draft-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        
        .priority-high {
            color: #dc3545;
        }
        
        .priority-normal {
            color: #6c757d;
        }
        
        .search-box {
            flex: 1;
            max-width: 400px;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2><i class="bi bi-speedometer2"></i> Admin Panel</h2>
            <ul>
                <li><a href="../admin_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li><a href="../admin_records.php"><i class="bi bi-people"></i> Student Records</a></li>
                <li><a href="../admin_appointments.php"><i class="bi bi-calendar-check"></i> Appointments</a></li>
                <li><a href="../admin_reports.php"><i class="bi bi-graph-up"></i> Reports</a></li>
                <li><a href="../admin_settings.php"><i class="bi bi-gear"></i> Settings</a></li>
                <li><a href="../admin_announcements.php"><i class="bi bi-megaphone"></i> Announcements</a></li>
                <li><a href="../admin_parents.php"><i class="bi bi-person-lines-fill"></i> Parent Accounts</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <div>
                    <h1><i class="bi bi-file-earmark-text me-2"></i>Drafts</h1>
                    <p class="text-muted mb-0">Manage your draft messages</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="inbox.php" class="btn btn-outline-secondary">
                        <i class="bi bi-inbox me-1"></i>Inbox
                    </a>
                    <button class="btn btn-primary" onclick="composeEmail()">
                        <i class="bi bi-plus-circle me-1"></i>New Draft
                    </button>
                </div>
            </div>

            <!-- Email Toolbar -->
            <div class="email-toolbar">
                <div class="d-flex align-items-center gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                        <label class="form-check-label" for="selectAll">Select All</label>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-outline-primary btn-sm" onclick="editSelected()" title="Edit Selected">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <button class="btn btn-outline-success btn-sm" onclick="sendSelected()" title="Send Selected">
                            <i class="bi bi-send"></i> Send
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="deleteDrafts()" title="Delete">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </div>
                    <button class="btn btn-outline-secondary btn-sm" onclick="refreshDrafts()" title="Refresh">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
                <div class="search-box">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search drafts..." id="searchInput" onkeyup="searchDrafts()">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Draft List -->
            <div class="draft-list" id="draftList">
                <!-- Sample Draft Items -->
                <div class="draft-item" onclick="selectDraft(this)">
                    <input type="checkbox" class="form-check-input draft-checkbox" onclick="event.stopPropagation()">
                    <div class="draft-content">
                        <div class="draft-subject">Weekly Newsletter - December 2024</div>
                        <div class="draft-recipients">To: All Parents (156 recipients)</div>
                        <div class="draft-preview">
                            Dear Parents and Guardians, We hope this message finds you well. As we approach the end of the semester, we wanted to share some important updates and upcoming events...
                        </div>
                    </div>
                    <div class="draft-meta">
                        <div class="draft-date">2 hours ago</div>
                        <div class="auto-save-indicator">
                            <i class="bi bi-check-circle"></i>
                            Auto-saved
                        </div>
                        <i class="bi bi-exclamation-circle priority-high" title="High priority"></i>
                        <div class="draft-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="editDraft(event, 1)">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-outline-success" onclick="sendDraft(event, 1)">
                                <i class="bi bi-send"></i> Send
                            </button>
                        </div>
                    </div>
                </div>

                <div class="draft-item" onclick="selectDraft(this)">
                    <input type="checkbox" class="form-check-input draft-checkbox" onclick="event.stopPropagation()">
                    <div class="draft-content">
                        <div class="draft-subject">Parent-Teacher Conference Reminder</div>
                        <div class="draft-recipients">To: Selected Parents (23 recipients)</div>
                        <div class="draft-preview">
                            This is a friendly reminder about the upcoming parent-teacher conferences scheduled for next week. Please confirm your attendance and let us know if you need to reschedule...
                        </div>
                    </div>
                    <div class="draft-meta">
                        <div class="draft-date">Yesterday</div>
                        <div class="auto-save-indicator">
                            <i class="bi bi-check-circle"></i>
                            Auto-saved
                        </div>
                        <i class="bi bi-circle priority-normal"></i>
                        <div class="draft-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="editDraft(event, 2)">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-outline-success" onclick="sendDraft(event, 2)">
                                <i class="bi bi-send"></i> Send
                            </button>
                        </div>
                    </div>
                </div>

                <div class="draft-item" onclick="selectDraft(this)">
                    <input type="checkbox" class="form-check-input draft-checkbox" onclick="event.stopPropagation()">
                    <div class="draft-content">
                        <div class="draft-subject">Monthly Academic Report Summary</div>
                        <div class="draft-recipients">To: School Administration (5 recipients)</div>
                        <div class="draft-preview">
                            Please find attached the monthly academic report summary for November 2024. This report includes student performance metrics, attendance statistics, and behavioral observations...
                        </div>
                    </div>
                    <div class="draft-meta">
                        <div class="draft-date">2 days ago</div>
                        <div class="auto-save-indicator">
                            <i class="bi bi-clock"></i>
                            Saving...
                        </div>
                        <i class="bi bi-paperclip" title="Has attachment"></i>
                        <i class="bi bi-circle priority-normal"></i>
                        <div class="draft-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="editDraft(event, 3)">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-outline-success" onclick="sendDraft(event, 3)">
                                <i class="bi bi-send"></i> Send
                            </button>
                        </div>
                    </div>
                </div>

                <div class="draft-item" onclick="selectDraft(this)">
                    <input type="checkbox" class="form-check-input draft-checkbox" onclick="event.stopPropagation()">
                    <div class="draft-content">
                        <div class="draft-subject">Student Counseling Session Follow-up</div>
                        <div class="draft-recipients">To: Maria Garcia (Parent)</div>
                        <div class="draft-preview">
                            Thank you for attending the counseling session yesterday. As discussed, I wanted to follow up with some additional resources and recommendations for supporting your child...
                        </div>
                    </div>
                    <div class="draft-meta">
                        <div class="draft-date">3 days ago</div>
                        <div class="auto-save-indicator">
                            <i class="bi bi-check-circle"></i>
                            Auto-saved
                        </div>
                        <i class="bi bi-circle priority-normal"></i>
                        <div class="draft-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="editDraft(event, 4)">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-outline-success" onclick="sendDraft(event, 4)">
                                <i class="bi bi-send"></i> Send
                            </button>
                        </div>
                    </div>
                </div>

                <div class="draft-item" onclick="selectDraft(this)">
                    <input type="checkbox" class="form-check-input draft-checkbox" onclick="event.stopPropagation()">
                    <div class="draft-content">
                        <div class="draft-subject">Holiday Schedule Announcement</div>
                        <div class="draft-recipients">To: All Staff and Parents (200+ recipients)</div>
                        <div class="draft-preview">
                            We are pleased to share the holiday schedule for the upcoming winter break. Please note the important dates and plan accordingly for the temporary closure of school facilities...
                        </div>
                    </div>
                    <div class="draft-meta">
                        <div class="draft-date">1 week ago</div>
                        <div class="auto-save-indicator">
                            <i class="bi bi-check-circle"></i>
                            Auto-saved
                        </div>
                        <i class="bi bi-exclamation-triangle priority-high" title="High priority"></i>
                        <div class="draft-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="editDraft(event, 5)">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-outline-success" onclick="sendDraft(event, 5)">
                                <i class="bi bi-send"></i> Send
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State (hidden when there are drafts) -->
            <div class="empty-state d-none" id="emptyState">
                <i class="bi bi-file-earmark-text"></i>
                <h4>No drafts yet</h4>
                <p>Start composing a message to create your first draft.</p>
                <button class="btn btn-primary" onclick="composeEmail()">
                    <i class="bi bi-plus-circle me-1"></i>Compose Message
                </button>
            </div>

            <!-- Quick Stats -->
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-primary">5</h5>
                            <p class="card-text small text-muted">Total Drafts</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-success">2</h5>
                            <p class="card-text small text-muted">Ready to Send</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-warning">1</h5>
                            <p class="card-text small text-muted">Currently Editing</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-info">0</h5>
                            <p class="card-text small text-muted">Scheduled</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.draft-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        }

        function selectDraft(draftItem) {
            // Remove previous selections
            document.querySelectorAll('.draft-item').forEach(item => {
                item.classList.remove('selected');
            });
            
            // Select current draft
            draftItem.classList.add('selected');
        }

        function editDraft(event, draftId) {
            event.stopPropagation();
            showToast(`Opening draft ${draftId} for editing...`, 'info');
            // Here you would typically redirect to compose page with draft data
        }

        function sendDraft(event, draftId) {
            event.stopPropagation();
            if (confirm('Are you sure you want to send this draft?')) {
                showToast(`Draft ${draftId} sent successfully!`, 'success');
                // Remove the draft from the list after sending
                setTimeout(() => {
                    event.target.closest('.draft-item').remove();
                    updateDraftCount();
                }, 1000);
            }
        }

        function editSelected() {
            const selectedDrafts = getSelectedDrafts();
            if (selectedDrafts.length === 0) {
                showToast('Please select drafts to edit', 'warning');
                return;
            }
            
            if (selectedDrafts.length > 1) {
                showToast('Please select only one draft to edit', 'warning');
                return;
            }
            
            showToast('Opening draft for editing...', 'info');
        }

        function sendSelected() {
            const selectedDrafts = getSelectedDrafts();
            if (selectedDrafts.length === 0) {
                showToast('Please select drafts to send', 'warning');
                return;
            }
            
            if (confirm(`Are you sure you want to send ${selectedDrafts.length} draft(s)?`)) {
                selectedDrafts.forEach(draft => {
                    draft.style.opacity = '0.5';
                    setTimeout(() => {
                        draft.remove();
                    }, 300);
                });
                
                showToast(`${selectedDrafts.length} draft(s) sent successfully!`);
                updateDraftCount();
            }
        }

        function deleteDrafts() {
            const selectedDrafts = getSelectedDrafts();
            if (selectedDrafts.length === 0) {
                showToast('Please select drafts to delete', 'warning');
                return;
            }
            
            if (confirm(`Are you sure you want to delete ${selectedDrafts.length} draft(s)?`)) {
                selectedDrafts.forEach(draft => {
                    draft.style.opacity = '0.5';
                    setTimeout(() => {
                        draft.remove();
                    }, 300);
                });
                
                showToast(`${selectedDrafts.length} draft(s) deleted`);
                updateDraftCount();
            }
        }

        function refreshDrafts() {
            const refreshBtn = document.querySelector('[onclick="refreshDrafts()"] i');
            refreshBtn.classList.add('fa-spin');
            
            setTimeout(() => {
                refreshBtn.classList.remove('fa-spin');
                showToast('Drafts refreshed');
            }, 1000);
        }

        function searchDrafts() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const drafts = document.querySelectorAll('.draft-item');
            
            drafts.forEach(draft => {
                const subject = draft.querySelector('.draft-subject').textContent.toLowerCase();
                const recipients = draft.querySelector('.draft-recipients').textContent.toLowerCase();
                const preview = draft.querySelector('.draft-preview').textContent.toLowerCase();
                
                if (subject.includes(searchTerm) || recipients.includes(searchTerm) || preview.includes(searchTerm)) {
                    draft.style.display = 'flex';
                } else {
                    draft.style.display = 'none';
                }
            });
        }

        function composeEmail() {
            showToast('Opening compose window...', 'info');
            // This would typically open a compose modal or redirect to compose page
        }

        function getSelectedDrafts() {
            const selectedCheckboxes = document.querySelectorAll('.draft-checkbox:checked');
            return Array.from(selectedCheckboxes).map(checkbox => 
                checkbox.closest('.draft-item')
            );
        }

        function updateDraftCount() {
            const draftItems = document.querySelectorAll('.draft-item');
            const count = draftItems.length;
            
            // Show empty state if no drafts
            if (count === 0) {
                document.getElementById('draftList').classList.add('d-none');
                document.getElementById('emptyState').classList.remove('d-none');
            }
            
            // Update stats
            const totalCard = document.querySelector('.card-title.text-primary');
            if (totalCard) {
                totalCard.textContent = count;
            }
        }

        function showToast(message, type = 'success') {
            // Create a simple toast notification
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

        // Simulate auto-save functionality
        function simulateAutoSave() {
            const savingIndicators = document.querySelectorAll('.auto-save-indicator');
            savingIndicators.forEach(indicator => {
                if (indicator.textContent.includes('Saving')) {
                    setTimeout(() => {
                        indicator.innerHTML = '<i class="bi bi-check-circle"></i> Auto-saved';
                        indicator.style.color = '#28a745';
                    }, Math.random() * 3000 + 1000);
                }
            });
        }

        // Initialize auto-save simulation
        simulateAutoSave();
        
        // Simulate periodic auto-save
        setInterval(() => {
            const indicators = document.querySelectorAll('.auto-save-indicator');
            if (indicators.length > 0 && Math.random() > 0.7) {
                const randomIndicator = indicators[Math.floor(Math.random() * indicators.length)];
                randomIndicator.innerHTML = '<i class="bi bi-clock"></i> Saving...';
                randomIndicator.style.color = '#6c757d';
                
                setTimeout(() => {
                    randomIndicator.innerHTML = '<i class="bi bi-check-circle"></i> Auto-saved';
                    randomIndicator.style.color = '#28a745';
                }, 2000);
            }
        }, 10000);
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98fda15d12560dc9',t:'MTc2MDY4MTQwMC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
