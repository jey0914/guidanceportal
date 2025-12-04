<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Inbox</title>
  <script src="/_sdk/element_sdk.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet">
  <style>
        body {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            width: 100%;
            height: 100%;
        }
        
        html, body {
            height: 100%;
        }
        
        .dashboard {
            display: flex;
            min-height: 100%;
            width: 100%;
        }
        
        .sidebar {
            width: 250px;
            background-color: #fff;
            border-right: 1px solid #dee2e6;
            padding: 1.5rem 0;
            position: fixed;
            height: 100%;
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
            cursor: pointer;
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
            width: 100%;
        }
        
        .inbox-header {
            background-color: #fff;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .inbox-header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            color: #212529;
            margin: 0;
        }
        
        .inbox-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            border-radius: 8px;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .filter-tabs {
            display: flex;
            gap: 0.5rem;
        }
        
        .filter-btn {
            padding: 0.5rem 1rem;
            border: none;
            background-color: transparent;
            color: #6c757d;
            font-weight: 500;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        
        .filter-btn:hover {
            background-color: #f8f9fa;
        }
        
        .filter-btn.active {
            background-color: #1976d2;
            color: #fff;
        }
        
        .search-box {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background-color: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            width: 300px;
        }
        
        .search-box input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 0.9rem;
        }
        
        .inbox-list {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .message-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .message-item:last-child {
            border-bottom: none;
        }
        
        .message-item:hover {
            background-color: #f8f9fa;
        }
        
        .message-item.unread {
            background-color: #f0f7ff;
        }
        
        .message-checkbox {
            margin-right: 1rem;
        }
        
        .message-checkbox input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .message-sender {
            flex: 0 0 200px;
            font-weight: 600;
            color: #212529;
        }
        
        .message-item.unread .message-sender {
            font-weight: 700;
        }
        
        .message-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .message-subject {
            font-weight: 500;
            color: #495057;
        }
        
        .message-item.unread .message-subject {
            font-weight: 600;
            color: #212529;
        }
        
        .message-preview {
            font-size: 0.875rem;
            color: #6c757d;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .message-time {
            flex: 0 0 100px;
            text-align: right;
            font-size: 0.875rem;
            color: #6c757d;
        }
        
        .message-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-right: 0.5rem;
        }
        
        .badge-important {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .badge-urgent {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .message-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
        }
        
        .message-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content-custom {
            background-color: #fff;
            border-radius: 8px;
            width: 90%;
            max-width: 800px;
            max-height: 90%;
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
        .modal-header-custom {
            padding: 1.5rem;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header-custom h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6c757d;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .close-btn:hover {
            color: #495057;
        }
        
        .modal-body-custom {
            padding: 1.5rem;
        }
        
        .message-meta {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .message-meta-item {
            display: flex;
            gap: 0.5rem;
        }
        
        .message-meta-label {
            font-weight: 600;
            color: #495057;
        }
        
        .message-body {
            line-height: 1.6;
            color: #495057;
        }
        
        .reply-section {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }
        
        .reply-section textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            resize: vertical;
            min-height: 120px;
        }
        
        .reply-section textarea:focus {
            outline: none;
            border-color: #1976d2;
        }
        
        .reply-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .btn-primary-custom {
            padding: 0.5rem 1.5rem;
            background-color: #1976d2;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-primary-custom:hover {
            background-color: #1565c0;
        }
        
        .btn-secondary-custom {
            padding: 0.5rem 1.5rem;
            background-color: #6c757d;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-secondary-custom:hover {
            background-color: #5a6268;
        }
    </style>
  
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
  <script src="https://cdn.tailwindcss.com" type="text/javascript"></script>
 </head>
 <body>
  <div class="dashboard"><!-- Sidebar -->
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

   </div><!-- Main Content -->
   <div class="main-content">
    <div class="inbox-header">
     <h1 id="page-title">Admin Inbox</h1>
    </div>
    <div class="inbox-controls">
     <div class="filter-tabs"><button class="filter-btn active" data-filter="all">All</button> <button class="filter-btn" data-filter="unread">Unread</button> <button class="filter-btn" data-filter="important">Important</button> <button class="filter-btn" data-filter="urgent">Urgent</button>
     </div>
     <div class="search-box"><i class="bi bi-search"></i> <input type="text" id="search-input" placeholder="Search messages...">
     </div>
    </div>

    <div class="inbox-list" id="inbox-list">
     <div class="message-item unread" data-id="1" data-type="urgent">
      <div class="message-checkbox"><input type="checkbox">
      </div>
      <div class="message-sender">
       Sarah Johnson
      </div>
      <div class="message-content">
       <div class="message-subject"><span class="message-badge badge-urgent">Urgent</span> Student Medical Emergency
       </div>
       <div class="message-preview">
        Need immediate attention regarding student health incident...
       </div>
      </div>
      <div class="message-time">
       10:30 AM
      </div>
     </div>
     <div class="message-item unread" data-id="2" data-type="important">
      <div class="message-checkbox"><input type="checkbox">
      </div>
      <div class="message-sender">
       Michael Chen
      </div>
      <div class="message-content">
       <div class="message-subject"><span class="message-badge badge-important">Important</span> Parent-Teacher Conference Request
       </div>
       <div class="message-preview">
        Would like to schedule a meeting to discuss academic progress...
       </div>
      </div>
      <div class="message-time">
       9:15 AM
      </div>
     </div>
     <div class="message-item" data-id="3" data-type="general">
      <div class="message-checkbox"><input type="checkbox">
      </div>
      <div class="message-sender">
       Emily Rodriguez
      </div>
      <div class="message-content">
       <div class="message-subject">
        Field Trip Permission Forms
       </div>
       <div class="message-preview">
        Attached are the signed permission forms for next week's field trip...
       </div>
      </div>
      <div class="message-time">
       Yesterday
      </div>
     </div>
     <div class="message-item" data-id="4" data-type="general">
      <div class="message-checkbox"><input type="checkbox">
      </div>
      <div class="message-sender">
       David Thompson
      </div>
      <div class="message-content">
       <div class="message-subject">
        Lunch Menu Update
       </div>
       <div class="message-preview">
        Please review the updated lunch menu for next month...
       </div>
      </div>
      <div class="message-time">
       2 days ago
      </div>
     </div>
     <div class="message-item" data-id="5" data-type="important">
      <div class="message-checkbox"><input type="checkbox">
      </div>
      <div class="message-sender">
       Jessica Williams
      </div>
      <div class="message-content">
       <div class="message-subject"><span class="message-badge badge-important">Important</span> Budget Approval Needed
       </div>
       <div class="message-preview">
        Requesting approval for additional classroom supplies budget...
       </div>
      </div>
      <div class="message-time">
       3 days ago
      </div>
     </div>
    </div>
   </div>
   
  </div><!-- Message Detail Modal -->
  <div class="message-modal" id="message-modal">
   <div class="modal-content-custom">
    <div class="modal-header-custom">
     <h3 id="modal-subject">Message Subject</h3><button class="close-btn" id="close-modal">×</button>
    </div>
    <div class="modal-body-custom">
     <div class="message-meta">
      <div class="message-meta-item"><span class="message-meta-label">From:</span> <span id="modal-sender">Sender Name</span>
      </div>
      <div class="message-meta-item"><span class="message-meta-label">Date:</span> <span id="modal-date">Date</span>
      </div>
     </div>
     <div class="message-body" id="modal-body">
      Message content will appear here...
     </div>
     <div class="reply-section">
      <h4>Reply</h4><textarea id="reply-text" placeholder="Type your reply here..."></textarea>
      <div class="reply-actions"><button class="btn-primary-custom" id="send-reply">Send Reply</button> <button class="btn-secondary-custom" id="cancel-reply">Cancel</button>
      </div>
     </div>
    </div>
   </div>
  </div>
  <script>
        const defaultConfig = {
            page_title: "Admin Inbox",
            empty_message: "No messages to display"
        };

        let config = { ...defaultConfig };

        const messages = {
            1: {
                sender: "Sarah Johnson",
                subject: "Student Medical Emergency",
                date: "10:30 AM",
                body: "Dear Admin,\n\nI need to inform you about a medical incident that occurred in the classroom today. One of our students experienced a severe allergic reaction during lunch. The school nurse has been notified and the student is receiving appropriate care.\n\nThe parents have been contacted and are on their way to the school. Please advise on the next steps we should take.\n\nBest regards,\nSarah Johnson"
            },
            2: {
                sender: "Michael Chen",
                subject: "Parent-Teacher Conference Request",
                date: "9:15 AM",
                body: "Hello,\n\nI would like to request a parent-teacher conference to discuss my child's academic progress and recent behavioral concerns. I am available next week on Tuesday or Thursday afternoon.\n\nPlease let me know what time works best for you.\n\nThank you,\nMichael Chen"
            },
            3: {
                sender: "Emily Rodriguez",
                subject: "Field Trip Permission Forms",
                date: "Yesterday",
                body: "Hi Admin,\n\nAttached are all the signed permission forms for next week's field trip to the science museum. All 28 students in my class have submitted their forms.\n\nPlease confirm receipt.\n\nBest,\nEmily Rodriguez"
            },
            4: {
                sender: "David Thompson",
                subject: "Lunch Menu Update",
                date: "2 days ago",
                body: "Dear Team,\n\nPlease review the updated lunch menu for next month. We've added more vegetarian options based on parent feedback and dietary requirements.\n\nLet me know if you have any concerns.\n\nRegards,\nDavid Thompson"
            },
            5: {
                sender: "Jessica Williams",
                subject: "Budget Approval Needed",
                date: "3 days ago",
                body: "Hello Admin,\n\nI am requesting approval for an additional $500 in classroom supplies budget. The current supplies are running low and we need art materials for the upcoming project.\n\nPlease review and approve at your earliest convenience.\n\nThank you,\nJessica Williams"
            }
        };

        async function onConfigChange(newConfig) {
            const pageTitle = document.getElementById('page-title');
            if (pageTitle) {
                pageTitle.textContent = newConfig.page_title || defaultConfig.page_title;
            }
        }

        function mapToCapabilities(config) {
            return {
                recolorables: [],
                borderables: [],
                fontEditable: undefined,
                fontSizeable: undefined
            };
        }

        function mapToEditPanelValues(config) {
            return new Map([
                ["page_title", config.page_title || defaultConfig.page_title],
                ["empty_message", config.empty_message || defaultConfig.empty_message]
            ]);
        }

        if (window.elementSdk) {
            window.elementSdk.init({
                defaultConfig,
                onConfigChange,
                mapToCapabilities,
                mapToEditPanelValues
            });
            config = window.elementSdk.config;
        }

        onConfigChange(config);

        // Filter buttons
        const filterBtns = document.querySelectorAll('.filter-btn');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                const filter = btn.dataset.filter;
                const messageItems = document.querySelectorAll('.message-item');
                
                messageItems.forEach(item => {
                    if (filter === 'all') {
                        item.style.display = 'flex';
                    } else if (filter === 'unread') {
                        item.style.display = item.classList.contains('unread') ? 'flex' : 'none';
                    } else if (filter === 'important') {
                        const badge = item.querySelector('.badge-important');
                        item.style.display = badge ? 'flex' : 'none';
                    } else if (filter === 'urgent') {
                        const badge = item.querySelector('.badge-urgent');
                        item.style.display = badge ? 'flex' : 'none';
                    }
                });
            });
        });

        // Search functionality
        const searchInput = document.getElementById('search-input');
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const messageItems = document.querySelectorAll('.message-item');
            
            messageItems.forEach(item => {
                const sender = item.querySelector('.message-sender').textContent.toLowerCase();
                const subject = item.querySelector('.message-subject').textContent.toLowerCase();
                const preview = item.querySelector('.message-preview').textContent.toLowerCase();
                
                const matches = sender.includes(searchTerm) || subject.includes(searchTerm) || preview.includes(searchTerm);
                item.style.display = matches ? 'flex' : 'none';
            });
        });

        // Message click to open modal
        const messageItems = document.querySelectorAll('.message-item');
        const modal = document.getElementById('message-modal');
        const closeModalBtn = document.getElementById('close-modal');
        
        messageItems.forEach(item => {
            item.addEventListener('click', (e) => {
                if (e.target.type === 'checkbox') return;
                
                const messageId = item.dataset.id;
                const message = messages[messageId];
                
                document.getElementById('modal-subject').textContent = message.subject;
                document.getElementById('modal-sender').textContent = message.sender;
                document.getElementById('modal-date').textContent = message.date;
                document.getElementById('modal-body').textContent = message.body;
                
                item.classList.remove('unread');
                
                modal.classList.add('show');
            });
        });

        closeModalBtn.addEventListener('click', () => {
            modal.classList.remove('show');
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('show');
            }
        });

        // Reply functionality
        const sendReplyBtn = document.getElementById('send-reply');
        const cancelReplyBtn = document.getElementById('cancel-reply');
        const replyText = document.getElementById('reply-text');

        sendReplyBtn.addEventListener('click', () => {
            const replyContent = replyText.value.trim();
            if (replyContent) {
                const tempMessage = document.createElement('div');
                tempMessage.style.cssText = 'position: fixed; top: 20px; right: 20px; background-color: #4caf50; color: white; padding: 1rem 1.5rem; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); z-index: 2000;';
                tempMessage.textContent = 'Reply sent successfully!';
                document.body.appendChild(tempMessage);
                
                setTimeout(() => {
                    tempMessage.remove();
                }, 3000);
                
                replyText.value = '';
                modal.classList.remove('show');
            }
        });

        cancelReplyBtn.addEventListener('click', () => {
            replyText.value = '';
        });
    </script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9a3ff87de2170dcb',t:'MTc2NDA2MTM4My4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>