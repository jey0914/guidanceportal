<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inbox - Parent Portal</title>
  <script src="/_sdk/element_sdk.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
  <style>
        body {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        /* Enhanced Sidebar */
        .sidebar {
            width: 280px;
            background: #2c3e50;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar-header h3 {
            color: white;
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .sidebar-nav ul {
            list-style: none;
            padding: 1.5rem 0;
            margin: 0;
        }

        .sidebar-nav li {
            margin: 0.5rem 1rem;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar-nav a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .sidebar-nav a.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .sidebar-nav i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
        }

        .content-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            margin-top: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .page-header {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .page-header h2 {
            color: #2c3e50;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 2rem;
        }

        .page-header .breadcrumb {
            background: none;
            padding: 0;
            margin: 1rem 0 0 0;
        }

        .breadcrumb-item a {
            color: #667eea;
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: #6c757d;
        }

        /* Inbox Controls */
        .inbox-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .inbox-filters {
            display: flex;
            gap: 0.5rem;
        }

        .filter-btn {
            padding: 0.5rem 1rem;
            border: 2px solid #e9ecef;
            background: white;
            color: #6c757d;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-btn.active {
            background: #667eea;
            border-color: #667eea;
            color: white;
        }

        .filter-btn:hover {
            border-color: #667eea;
            color: #667eea;
        }

        .filter-btn.active:hover {
            color: white;
        }

        /* Message List */
        .message-list {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1rem;
        }

        .message-item {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid #e9ecef;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .message-item:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .message-item.unread {
            border-left: 4px solid #667eea;
            background: #f8f9ff;
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }

        .message-sender {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sender-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .sender-info h6 {
            margin: 0;
            font-weight: 600;
            color: #2c3e50;
        }

        .sender-info small {
            color: #6c757d;
        }

        .message-meta {
            text-align: right;
        }

        .message-time {
            color: #6c757d;
            font-size: 0.85rem;
        }

        .message-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .action-btn {
            background: none;
            border: none;
            color: #6c757d;
            font-size: 0.9rem;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }

        .action-btn.starred {
            color: #ffc107;
        }

        .message-subject {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .message-preview {
            color: #6c757d;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .priority-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .priority-high {
            background: #dc3545;
            color: white;
        }

        .priority-normal {
            background: #28a745;
            color: white;
        }

        .priority-low {
            background: #6c757d;
            color: white;
        }

        /* Enhanced Buttons */
        .btn {
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(40, 167, 69, 0.4);
        }

        /* Modal Styling */
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            border-bottom: 1px solid #e9ecef;
            padding: 2rem 2rem 1rem;
        }

        .modal-body {
            padding: 1rem 2rem 2rem;
        }

        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.875rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        /* Mobile Toggle Button */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: #2c3e50;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.5rem;
            font-size: 1.2rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }

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

            .content-card {
                padding: 2rem;
                margin-top: 1rem;
            }

            .page-header {
                padding: 1.5rem;
            }

            .page-header h2 {
                font-size: 1.5rem;
            }

            .inbox-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .inbox-filters {
                justify-content: center;
            }

            .message-header {
                flex-direction: column;
                gap: 0.5rem;
            }

            .message-meta {
                text-align: left;
            }
        }

        /* Animations */
        .message-item, .btn {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Success Alert */
        .alert {
            border: none;
            border-radius: 15px;
            padding: 1.25rem 1.5rem;
            font-weight: 500;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .alert-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .unread-count {
            background: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            min-width: 20px;
            text-align: center;
        }
    </style>
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
  <script src="https://cdn.tailwindcss.com" type="text/javascript"></script>
 </head>
 <body><!-- Mobile Menu Toggle --> <button class="mobile-toggle" onclick="toggleSidebar()"> <i class="fas fa-bars"></i> </button> <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
   <div class="sidebar-header">
    <h3 id="portal_title"><i class="fas fa-graduation-cap"></i> Parent Portal</h3>
   </div>
   <nav class="sidebar-nav">
    <ul>
     <li><a href="parent_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
     <li><a href="child_info.php"><i class="fas fa-child"></i> Child Info</a></li>
     <li><a href="parent_appointments.php"><i class="fas fa-calendar-check"></i> Appointments</a></li>
     <li><a href="parent_reports.php"><i class="fas fa-file-alt"></i> Reports</a></li>
     <li><a href="parent_settings.php"><i class="fas fa-cog"></i> Settings</a></li>
    </ul>
   </nav>
  </div><!-- Main Content -->
  <div class="main-content">
   <div class="page-header">
    <h2><i class="fas fa-inbox"></i> Inbox</h2>
    <nav aria-label="breadcrumb">
     <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="parent_dashboard.php">Dashboard</a></li>
      <li class="breadcrumb-item active">Inbox</li>
     </ol>
    </nav>
   </div>
   <div class="content-card"><!-- Inbox Controls -->
    <div class="inbox-controls">
     <div class="inbox-filters"><button class="filter-btn active" data-filter="all">All Messages</button> <button class="filter-btn" data-filter="unread">Unread (3)</button> <button class="filter-btn" data-filter="important">Important</button> <button class="filter-btn" data-filter="sent">Sent</button>
     </div>
     <div class="inbox-actions"><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#composeModal"> <i class="fas fa-plus"></i> Compose </button> <button class="btn btn-success" onclick="markAllRead()"> <i class="fas fa-check-double"></i> Mark All Read </button>
     </div>
    </div><!-- Message List -->
    <div class="message-list" id="messageList"><!-- Message 1 - Unread -->
     <div class="message-item unread" data-type="unread" onclick="openMessage(1)">
      <div class="priority-badge priority-normal">
       Normal
      </div>
      <div class="message-header">
       <div class="message-sender">
        <div class="sender-avatar">
         MS
        </div>
        <div class="sender-info">
         <h6>Ms. Sarah Johnson</h6><small>Math Teacher</small>
        </div>
       </div>
       <div class="message-meta">
        <div class="message-time">
         2 hours ago
        </div>
        <div class="message-actions"><button class="action-btn" onclick="toggleStar(event, 1)" title="Star"> <i class="far fa-star"></i> </button> <button class="action-btn" onclick="markAsRead(event, 1)" title="Mark as read"> <i class="fas fa-envelope"></i> </button>
        </div>
       </div>
      </div>
      <div class="message-subject">
       Math Homework - Extra Help Available
      </div>
      <div class="message-preview">
       Hi! I noticed Emma might benefit from some extra help with fractions. I'm available after school on Tuesdays and Thursdays...
      </div>
     </div><!-- Message 2 - Unread Important -->
     <div class="message-item unread" data-type="unread important" onclick="openMessage(2)">
      <div class="priority-badge priority-high">
       High
      </div>
      <div class="message-header">
       <div class="message-sender">
        <div class="sender-avatar">
         DR
        </div>
        <div class="sender-info">
         <h6>Dr. Robert Martinez</h6><small>Principal</small>
        </div>
       </div>
       <div class="message-meta">
        <div class="message-time">
         5 hours ago
        </div>
        <div class="message-actions"><button class="action-btn starred" onclick="toggleStar(event, 2)" title="Unstar"> <i class="fas fa-star"></i> </button> <button class="action-btn" onclick="markAsRead(event, 2)" title="Mark as read"> <i class="fas fa-envelope"></i> </button>
        </div>
       </div>
      </div>
      <div class="message-subject">
       Parent-Teacher Conference Scheduling
      </div>
      <div class="message-preview">
       Dear Parents, we're scheduling parent-teacher conferences for next month. Please log in to book your preferred time slot...
      </div>
     </div><!-- Message 3 - Unread -->
     <div class="message-item unread" data-type="unread" onclick="openMessage(3)">
      <div class="priority-badge priority-normal">
       Normal
      </div>
      <div class="message-header">
       <div class="message-sender">
        <div class="sender-avatar">
         NW
        </div>
        <div class="sender-info">
         <h6>Nurse Williams</h6><small>School Nurse</small>
        </div>
       </div>
       <div class="message-meta">
        <div class="message-time">
         1 day ago
        </div>
        <div class="message-actions"><button class="action-btn" onclick="toggleStar(event, 3)" title="Star"> <i class="far fa-star"></i> </button> <button class="action-btn" onclick="markAsRead(event, 3)" title="Mark as read"> <i class="fas fa-envelope"></i> </button>
        </div>
       </div>
      </div>
      <div class="message-subject">
       Health Form Update Required
      </div>
      <div class="message-preview">
       Please update Emma's health information form. The current form expires next month and we need updated emergency contacts...
      </div>
     </div><!-- Message 4 - Read -->
     <div class="message-item" data-type="read" onclick="openMessage(4)">
      <div class="priority-badge priority-normal">
       Normal
      </div>
      <div class="message-header">
       <div class="message-sender">
        <div class="sender-avatar">
         MR
        </div>
        <div class="sender-info">
         <h6>Mr. David Rodriguez</h6><small>English Teacher</small>
        </div>
       </div>
       <div class="message-meta">
        <div class="message-time">
         2 days ago
        </div>
        <div class="message-actions"><button class="action-btn" onclick="toggleStar(event, 4)" title="Star"> <i class="far fa-star"></i> </button> <button class="action-btn" onclick="markAsUnread(event, 4)" title="Mark as unread"> <i class="far fa-envelope"></i> </button>
        </div>
       </div>
      </div>
      <div class="message-subject">
       Great Progress in Reading!
      </div>
      <div class="message-preview">
       I wanted to let you know that Emma has shown excellent improvement in her reading comprehension this quarter...
      </div>
     </div><!-- Message 5 - Read -->
     <div class="message-item" data-type="read" onclick="openMessage(5)">
      <div class="priority-badge priority-low">
       Low
      </div>
      <div class="message-header">
       <div class="message-sender">
        <div class="sender-avatar">
         CT
        </div>
        <div class="sender-info">
         <h6>Coach Thompson</h6><small>PE Teacher</small>
        </div>
       </div>
       <div class="message-meta">
        <div class="message-time">
         3 days ago
        </div>
        <div class="message-actions"><button class="action-btn starred" onclick="toggleStar(event, 5)" title="Unstar"> <i class="fas fa-star"></i> </button> <button class="action-btn" onclick="markAsUnread(event, 5)" title="Mark as unread"> <i class="far fa-envelope"></i> </button>
        </div>
       </div>
      </div>
      <div class="message-subject">
       Sports Day Participation
      </div>
      <div class="message-preview">
       Emma did fantastic at sports day! She participated enthusiastically in all activities and showed great teamwork...
      </div>
     </div>
    </div>
   </div>
  </div><!-- Compose Message Modal -->
  <div class="modal fade" id="composeModal" tabindex="-1">
   <div class="modal-dialog modal-lg">
    <div class="modal-content">
     <div class="modal-header">
      <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Compose Message</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
     </div>
     <div class="modal-body">
      <form id="composeForm">
       <div class="mb-3"><label for="recipient" class="form-label">To:</label> <select class="form-select" id="recipient" required> <option value="">Select recipient...</option> <option value="ms_johnson">Ms. Sarah Johnson (Math Teacher)</option> <option value="dr_martinez">Dr. Robert Martinez (Principal)</option> <option value="nurse_williams">Nurse Williams (School Nurse)</option> <option value="mr_rodriguez">Mr. David Rodriguez (English Teacher)</option> <option value="coach_thompson">Coach Thompson (PE Teacher)</option> </select>
       </div>
       <div class="mb-3"><label for="subject" class="form-label">Subject:</label> <input type="text" class="form-control" id="subject" placeholder="Enter message subject" required>
       </div>
       <div class="mb-3"><label for="priority" class="form-label">Priority:</label> <select class="form-select" id="priority"> <option value="normal">Normal</option> <option value="high">High</option> <option value="low">Low</option> </select>
       </div>
       <div class="mb-3"><label for="message" class="form-label">Message:</label> <textarea class="form-control" id="message" rows="6" placeholder="Type your message here..." required></textarea>
       </div>
      </form>
     </div>
     <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button> <button type="button" class="btn btn-primary" onclick="sendMessage()"> <i class="fas fa-paper-plane"></i> Send Message </button>
     </div>
    </div>
   </div>
  </div><!-- Read Message Modal -->
  <div class="modal fade" id="readModal" tabindex="-1">
   <div class="modal-dialog modal-lg">
    <div class="modal-content">
     <div class="modal-header">
      <h5 class="modal-title" id="readModalTitle">Message</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
     </div>
     <div class="modal-body" id="readModalBody"><!-- Message content will be loaded here -->
     </div>
     <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> <button type="button" class="btn btn-primary" onclick="replyToMessage()"> <i class="fas fa-reply"></i> Reply </button>
     </div>
    </div>
   </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
        // Configuration
        const defaultConfig = {
            portal_title: "Parent Portal"
        };

        // Sample message data
        const messages = {
            1: {
                sender: "Ms. Sarah Johnson",
                role: "Math Teacher",
                subject: "Math Homework - Extra Help Available",
                content: "Hi! I noticed Emma might benefit from some extra help with fractions. I'm available after school on Tuesdays and Thursdays from 3:30-4:30 PM. Please let me know if you'd like to schedule some extra sessions. Emma is a bright student and with a little extra practice, I'm confident she'll master these concepts quickly!",
                time: "2 hours ago",
                priority: "normal"
            },
            2: {
                sender: "Dr. Robert Martinez",
                role: "Principal",
                subject: "Parent-Teacher Conference Scheduling",
                content: "Dear Parents, we're scheduling parent-teacher conferences for next month. Please log in to the parent portal to book your preferred time slot. Conferences will be held from March 15-19, with both in-person and virtual options available. Each conference is scheduled for 20 minutes. If you need additional time, please let us know when booking.",
                time: "5 hours ago",
                priority: "high"
            },
            3: {
                sender: "Nurse Williams",
                role: "School Nurse",
                subject: "Health Form Update Required",
                content: "Please update Emma's health information form. The current form expires next month and we need updated emergency contacts and any changes to medications or allergies. You can access the health form through the parent portal under 'Health Records'. Please complete this by the end of the month.",
                time: "1 day ago",
                priority: "normal"
            },
            4: {
                sender: "Mr. David Rodriguez",
                role: "English Teacher",
                subject: "Great Progress in Reading!",
                content: "I wanted to let you know that Emma has shown excellent improvement in her reading comprehension this quarter. Her book reports have been thoughtful and well-written. She's currently reading at grade level and shows great enthusiasm for literature. Keep encouraging her reading at home!",
                time: "2 days ago",
                priority: "normal"
            },
            5: {
                sender: "Coach Thompson",
                role: "PE Teacher",
                subject: "Sports Day Participation",
                content: "Emma did fantastic at sports day! She participated enthusiastically in all activities and showed great teamwork during the relay races. Her positive attitude and encouragement of her classmates was wonderful to see. Thank you for encouraging her participation in physical activities.",
                time: "3 days ago",
                priority: "low"
            }
        };

        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !toggle.contains(event.target) && 
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });

        // Filter messages
        document.addEventListener('DOMContentLoaded', function() {
            const filterBtns = document.querySelectorAll('.filter-btn');
            const messageItems = document.querySelectorAll('.message-item');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Update active filter
                    filterBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const filter = this.dataset.filter;
                    
                    messageItems.forEach(item => {
                        const types = item.dataset.type.split(' ');
                        
                        if (filter === 'all' || types.includes(filter)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });
        });

        // Open message
        function openMessage(messageId) {
            const message = messages[messageId];
            if (!message) return;

            const modal = new bootstrap.Modal(document.getElementById('readModal'));
            document.getElementById('readModalTitle').textContent = message.subject;
            
            document.getElementById('readModalBody').innerHTML = `
                <div class="mb-3">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="sender-avatar">${message.sender.split(' ').map(n => n[0]).join('')}</div>
                        <div>
                            <h6 class="mb-0">${message.sender}</h6>
                            <small class="text-muted">${message.role}</small>
                        </div>
                        <div class="ms-auto text-muted">${message.time}</div>
                    </div>
                    <div class="border-bottom pb-2 mb-3">
                        <strong>Subject:</strong> ${message.subject}
                    </div>
                    <div class="message-content">
                        ${message.content}
                    </div>
                </div>
            `;
            
            modal.show();

            // Mark as read
            const messageElement = document.querySelector(`[onclick="openMessage(${messageId})"]`);
            if (messageElement && messageElement.classList.contains('unread')) {
                messageElement.classList.remove('unread');
                messageElement.dataset.type = messageElement.dataset.type.replace('unread', 'read').trim();
                updateUnreadCount();
            }
        }

        // Toggle star
        function toggleStar(event, messageId) {
            event.stopPropagation();
            const btn = event.currentTarget;
            const icon = btn.querySelector('i');
            
            if (btn.classList.contains('starred')) {
                btn.classList.remove('starred');
                icon.className = 'far fa-star';
            } else {
                btn.classList.add('starred');
                icon.className = 'fas fa-star';
            }
        }

        // Mark as read
        function markAsRead(event, messageId) {
            event.stopPropagation();
            const messageElement = document.querySelector(`[onclick="openMessage(${messageId})"]`);
            if (messageElement) {
                messageElement.classList.remove('unread');
                messageElement.dataset.type = messageElement.dataset.type.replace('unread', 'read').trim();
                
                const btn = event.currentTarget;
                btn.innerHTML = '<i class="far fa-envelope"></i>';
                btn.setAttribute('onclick', `markAsUnread(event, ${messageId})`);
                btn.setAttribute('title', 'Mark as unread');
                
                updateUnreadCount();
            }
        }

        // Mark as unread
        function markAsUnread(event, messageId) {
            event.stopPropagation();
            const messageElement = document.querySelector(`[onclick="openMessage(${messageId})"]`);
            if (messageElement) {
                messageElement.classList.add('unread');
                messageElement.dataset.type = messageElement.dataset.type.replace('read', 'unread').trim();
                
                const btn = event.currentTarget;
                btn.innerHTML = '<i class="fas fa-envelope"></i>';
                btn.setAttribute('onclick', `markAsRead(event, ${messageId})`);
                btn.setAttribute('title', 'Mark as read');
                
                updateUnreadCount();
            }
        }

        // Mark all as read
        function markAllRead() {
            const unreadMessages = document.querySelectorAll('.message-item.unread');
            unreadMessages.forEach(message => {
                message.classList.remove('unread');
                message.dataset.type = message.dataset.type.replace('unread', 'read').trim();
                
                const readBtn = message.querySelector('[title="Mark as read"]');
                if (readBtn) {
                    readBtn.innerHTML = '<i class="far fa-envelope"></i>';
                    readBtn.setAttribute('title', 'Mark as unread');
                    const messageId = readBtn.getAttribute('onclick').match(/\d+/)[0];
                    readBtn.setAttribute('onclick', `markAsUnread(event, ${messageId})`);
                }
            });
            
            updateUnreadCount();
            
            // Show success message
            showAlert('All messages marked as read!', 'success');
        }

        // Update unread count
        function updateUnreadCount() {
            const unreadCount = document.querySelectorAll('.message-item.unread').length;
            const badge = document.querySelector('.unread-count');
            const unreadFilter = document.querySelector('[data-filter="unread"]');
            
            if (unreadCount > 0) {
                badge.textContent = unreadCount;
                badge.style.display = 'inline-block';
                unreadFilter.textContent = `Unread (${unreadCount})`;
            } else {
                badge.style.display = 'none';
                unreadFilter.textContent = 'Unread (0)';
            }
        }

        // Send message
        function sendMessage() {
            const form = document.getElementById('composeForm');
            const recipient = document.getElementById('recipient').value;
            const subject = document.getElementById('subject').value;
            const message = document.getElementById('message').value;
            
            if (!recipient || !subject || !message) {
                showAlert('Please fill in all required fields.', 'danger');
                return;
            }
            
            const btn = event.target;
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
            btn.disabled = true;
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                
                // Close modal and reset form
                bootstrap.Modal.getInstance(document.getElementById('composeModal')).hide();
                form.reset();
                
                showAlert('Message sent successfully!', 'success');
            }, 2000);
        }

        // Reply to message
        function replyToMessage() {
            bootstrap.Modal.getInstance(document.getElementById('readModal')).hide();
            
            setTimeout(() => {
                const composeModal = new bootstrap.Modal(document.getElementById('composeModal'));
                composeModal.show();
            }, 300);
        }

        // Show alert
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}`;
            
            const container = document.querySelector('.content-card');
            container.insertBefore(alertDiv, container.firstChild);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }

        // Element SDK Configuration
        async function onConfigChange(config) {
            document.getElementById('portal_title').innerHTML = `<i class="fas fa-graduation-cap"></i> ${config.portal_title || defaultConfig.portal_title}`;
        }

        // Initialize Element SDK
        if (window.elementSdk) {
            window.elementSdk.init({
                defaultConfig,
                onConfigChange,
                mapToCapabilities: (config) => ({
                    recolorables: [],
                    borderables: [],
                    fontEditable: undefined,
                    fontSizeable: undefined
                }),
                mapToEditPanelValues: (config) => new Map([
                    ["portal_title", config.portal_title || defaultConfig.portal_title]
                ])
            });
        }
    </script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'997336fc24e20dc9',t:'MTc2MTkxNDM2Mi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>