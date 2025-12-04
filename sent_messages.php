<?php
session_start();
include("db.php");

if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}

// Logged-in user
$email = $_SESSION['email'];

// Fetch messages where the user is the sender
$stmt = $con->prepare("SELECT * FROM messages WHERE sender_email = ? ORDER BY date_sent DESC");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GP Dashboard - Sent Messages</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
         /* Enhanced Sidebar styling */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 300px;
      height: 100vh;
      background: linear-gradient(180deg, #0f172a 0%, #1e293b 50%, #334155 100%);
      color: white;
      z-index: 1000;
      box-shadow: 8px 0 32px rgba(0, 0, 0, 0.3);
      border-right: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .sidebar-header {
      padding: 32px 24px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(147, 51, 234, 0.1) 100%);
      position: relative;
      overflow: hidden;
    }
    
    .sidebar-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.05), transparent);
      animation: shimmer 3s ease-in-out infinite;
    }
    
    @keyframes shimmer {
      0% { transform: translateX(-100%); }
      100% { transform: translateX(100%); }
    }
    
    .sidebar-header h2 {
      margin: 0;
      font-size: 1.75rem;
      font-weight: 800;
      display: flex;
      align-items: center;
      gap: 16px;
      position: relative;
      z-index: 1;
    }
    
    .sidebar-header .logo-icon {
      width: 48px;
      height: 48px;
      background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 8px 32px rgba(59, 130, 246, 0.3);
      animation: pulse 2s ease-in-out infinite;
    }
    
    .sidebar-header .subtitle {
      font-size: 0.875rem;
      color: rgba(255, 255, 255, 0.7);
      margin-top: 8px;
      font-weight: 500;
      position: relative;
      z-index: 1;
    }
    
    .sidebar-nav {
      padding: 24px 0;
      height: calc(100vh - 140px);
      overflow-y: auto;
    }
    
    .sidebar ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    
    .sidebar li {
      margin-bottom: 8px;
      padding: 0 16px;
    }
    
    .sidebar a {
      display: flex;
      align-items: center;
      padding: 16px 20px;
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      border-radius: 16px;
      position: relative;
      font-weight: 500;
      overflow: hidden;
    }
    
    .sidebar a::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .sidebar a:hover::before {
      opacity: 1;
    }
    
    .sidebar a:hover {
      color: white;
      transform: translateX(8px) scale(1.02);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    }
    
    .sidebar a.active {
      background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
      color: white;
      box-shadow: 0 8px 32px rgba(59, 130, 246, 0.4);
      transform: translateX(4px);
    }
    
    .sidebar a.active::after {
      content: '';
      position: absolute;
      right: -16px;
      top: 50%;
      transform: translateY(-50%);
      width: 4px;
      height: 24px;
      background: linear-gradient(180deg, #3b82f6 0%, #8b5cf6 100%);
      border-radius: 2px;
    }
    
    .sidebar a i {
      width: 24px;
      margin-right: 16px;
      font-size: 1.2rem;
      text-align: center;
    }
    
    .sidebar a .nav-text {
      flex: 1;
      font-size: 0.95rem;
    }
    
    .sidebar a .nav-badge {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: white;
      font-size: 0.75rem;
      padding: 2px 8px;
      border-radius: 12px;
      font-weight: 600;
      min-width: 20px;
      text-align: center;
    }
        
        /* Main Content Area */
        .main-content {
            margin-left: 300px;
            min-height: 100vh;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        /* Message Item Styles */
        .message-item {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        
        .message-item:hover {
            background: linear-gradient(135deg, #f8faff 0%, #ffffff 100%);
            border-left-color: #8b5cf6;
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .message-item.active {
            background: linear-gradient(135deg, #f0f4ff 0%, #ffffff 100%);
            border-left-color: #3b82f6;
        }
        
        /* Message Preview Styles */
        #messagePreview {
            background: white;
            border-radius: 16px;
            margin: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }
        
        .preview-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 24px;
            border-radius: 16px 16px 0 0;
        }
        
        .preview-content {
            padding: 24px;
            line-height: 1.6;
        }
        
        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .modal.active {
            opacity: 1;
            visibility: visible;
        }
        
        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 32px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }
        
        .modal.active .modal-content {
            transform: scale(1);
        }
        
        /* Empty State */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 400px;
            color: #9ca3af;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-menu-btn {
                display: block;
            }
        }
        
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px;
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Enhanced Sidebar -->
  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <h2>
        <div class="logo-icon">
          <i class="fas fa-graduation-cap"></i>
        </div>
        Guidance Portal
      </h2>
      <div class="subtitle">Student Portal • Dashboard</div>
    </div>
    <div class="sidebar-nav">
      <ul>
        <li><a href="dashboard.php"><i class="fas fa-home"></i>
          <span class="nav-text">Dashboard</span>
        </a></li>
        <?php if (stripos($_SESSION['strand_course'], 'SHS') !== false || stripos($_SESSION['year_level'], 'Grade') !== false): ?>
        <li><a href="student_records.php">
          <i class="fas fa-clipboard-check"></i>
          <span class="nav-text">Attendance</span>
        </a></li>
        <?php endif; ?>
        <li><a href="appointments.php">
          <i class="fas fa-calendar-check"></i>
          <span class="nav-text">Appointments</span>
          <span class="nav-badge">2</span>
        </a></li>
        <li><a href="student_reports.php">
          <i class="fas fa-file-alt"></i>
          <span class="nav-text">Reports</span>
        </a></li>
        <li><a href="settings.php">
          <i class="fas fa-cog"></i>
          <span class="nav-text">Settings</span>
        </a></li>
        <li><a href="help.php">
          <i class="fas fa-question-circle"></i>
          <span class="nav-text">Help & Support</span>
        </a></li>
      </ul>
    </div>
  </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-6 shadow-lg">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold">
                    <i class="fas fa-paper-plane mr-3"></i>Sent Messages
                </h2>
                <div class="flex gap-4">
                    <a href="inbox.php" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg transition-all duration-300 flex items-center">
                        <i class="fas fa-inbox mr-2"></i>Inbox
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex flex-1 h-screen">
            <!-- Left: Sent Message List -->
            <div class="w-full md:w-1/3 border-r border-gray-200 bg-white overflow-y-auto">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="message-item p-6 border-b border-gray-100 cursor-pointer hover:shadow-md transition-all duration-300"
                             onclick="showMessage(`<?php echo htmlspecialchars($row['subject']); ?>`,
                                               `<?php echo nl2br(htmlspecialchars($row['message'])); ?>`,
                                               `<?php echo htmlspecialchars($row['receiver_name']); ?>`,
                                               `<?php echo date('M d, Y \a\t g:i A', strtotime($row['date_sent'])); ?>`,
                                               this)">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                                        <?php echo strtoupper(substr($row['receiver_name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800">
                                            To: <?php echo htmlspecialchars($row['receiver_name']); ?>
                                        </h4>
                                        <span class="text-xs text-gray-500">
                                            <i class="fas fa-clock mr-1"></i>
                                            <?php echo date("M d, Y", strtotime($row['date_sent'])); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center text-gray-400">
                                    <i class="fas fa-chevron-right text-sm"></i>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 truncate font-medium mb-1">
                                <?php echo htmlspecialchars($row['subject']); ?>
                            </p>
                            <p class="text-xs text-gray-500 truncate">
                                <?php echo htmlspecialchars(substr($row['message'], 0, 100)) . (strlen($row['message']) > 100 ? '...' : ''); ?>
                            </p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-paper-plane text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold mb-2">No sent messages found</h3>
                        <p class="text-center">Messages you send will appear here</p>
                       
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right: Message Preview -->
            <div class="hidden md:block flex-1 bg-gray-50" id="messagePreview">
                <div class="h-full flex items-center justify-center">
                    <div class="text-center text-gray-500">
                        <i class="fas fa-envelope-open text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold mb-2">Select a sent message</h3>
                        <p>Choose a message from the list to view its content</p>
                    </div>
                </div>
            </div>
        </main>
    </div>


    <script>
        function showMessage(subject, message, recipient, dateSent, element) {
            // Remove active class from all message items
            document.querySelectorAll('.message-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Add active class to clicked item
            element.classList.add('active');
            
            // Update preview content
            const preview = document.getElementById("messagePreview");
            preview.innerHTML = `
                <div class="preview-header">
                    <h3 class="text-xl font-bold mb-2">${subject}</h3>
                    <div class="flex items-center text-purple-100">
                        <i class="fas fa-user mr-2"></i>
                        <span class="mr-4">To: ${recipient}</span>
                        <i class="fas fa-clock mr-2"></i>
                        <span>${dateSent}</span>
                    </div>
                </div>
                <div class="preview-content">
                    <div class="prose max-w-none">
                        ${message}
                    </div>
                </div>
            `;
        }

        function openModal() {
            document.getElementById('composeModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('composeModal').classList.remove('active');
            document.getElementById('composeForm').reset();
        }

        function sendMessage(event) {
            event.preventDefault();
            
            const recipient = document.getElementById('recipient').value;
            const subject = document.getElementById('subject').value;
            const priority = document.getElementById('priority').value;
            const content = document.getElementById('messageContent').value;

            // Here you would typically send the data to your PHP backend
            console.log('Sending message:', { recipient, subject, priority, content });
            
            showNotification('Message sent successfully!', 'success');
            closeModal();
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
        }

        // Notification system
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white max-w-sm transform translate-x-full transition-transform duration-300`;
            
            switch(type) {
                case 'success':
                    notification.classList.add('bg-green-500');
                    break;
                case 'error':
                    notification.classList.add('bg-red-500');
                    break;
                case 'warning':
                    notification.classList.add('bg-yellow-500');
                    break;
                default:
                    notification.classList.add('bg-blue-500');
            }
            
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }

        // Close modal when clicking outside
        document.getElementById('composeModal').addEventListener('click', (e) => {
            if (e.target === e.currentTarget) {
                closeModal();
            }
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            const sidebar = document.getElementById('sidebar');
            const menuBtn = document.querySelector('.mobile-menu-btn');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !menuBtn.contains(e.target) && 
                sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
            }
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98f5ad0d37d40dc9',t:'MTc2MDU5Nzk5Mi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
