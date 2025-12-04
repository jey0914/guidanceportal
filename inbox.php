<?php
include("db.php");
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// ✅ Fetch messages for the logged-in user
$query = "SELECT id, sender_name, sender_email, subject, message, attachment, date_sent 
          FROM messages 
          WHERE receiver_email = ? 
          ORDER BY date_sent DESC";

$stmt = $con->prepare($query);

if (!$stmt) {
    die("SQL prepare failed: " . $con->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GP Dashboard - Inbox</title>
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
        
        /* Inbox Specific Styles */
        .inbox-header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 24px 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .message-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .message-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: #3b82f6;
        }
        
        .message-card.unread {
            border-left: 4px solid #3b82f6;
            background: linear-gradient(135deg, #f8faff 0%, #ffffff 100%);
        }
        
        .message-card.read {
            opacity: 0.8;
        }
        
        .message-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 12px;
        }
        
        .sender-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .sender-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .message-actions {
            display: flex;
            gap: 8px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .message-card:hover .message-actions {
            opacity: 1;
        }
        
        .action-btn {
            padding: 8px;
            border-radius: 8px;
            background: #f1f5f9;
            color: #64748b;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .action-btn:hover {
            background: #e2e8f0;
            color: #334155;
        }
        
        .priority-high {
            border-left-color: #ef4444 !important;
        }
        
        .priority-medium {
            border-left-color: #f59e0b !important;
        }
        
        .priority-low {
            border-left-color: #10b981 !important;
        }
        
        .filter-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 24px;
        }
        
        .filter-tab {
            padding: 12px 20px;
            border-radius: 12px;
            background: white;
            border: 2px solid #e2e8f0;
            color: #64748b;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .filter-tab:hover {
            border-color: #3b82f6;
            color: #3b82f6;
        }
        
        .filter-tab.active {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            color: white;
            border-color: #3b82f6;
        }
        
        .compose-btn {
            position: fixed;
            bottom: 32px;
            right: 32px;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            color: white;
            border: none;
            cursor: pointer;
            box-shadow: 0 8px 32px rgba(59, 130, 246, 0.4);
            transition: all 0.3s ease;
            z-index: 100;
        }
        
        .compose-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 40px rgba(59, 130, 246, 0.6);
        }
        
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
        
        .search-bar {
            position: relative;
            margin-bottom: 24px;
        }
        
        .search-input {
            width: 100%;
            padding: 16px 20px 16px 48px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
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
        <div class="inbox-header">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">
                        <i class="fas fa-inbox text-blue-600 mr-3"></i>
                        Inbox
                    </h1>
                    <p class="text-gray-600">Manage your messages and communications</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 px-4 py-2 rounded-full">
                        <span class="text-blue-800 font-semibold" id="unreadCount">5 Unread</span>
                    </div>
                    <button onclick="markAllAsRead()" class="bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-check-double mr-2"></i>Mark All Read
                    </button>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-8">
            <!-- Search Bar -->
            <div class="search-bar">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Search messages..." id="searchInput">
            </div>

            <!-- Filter Tabs -->
            <div class="filter-tabs">
                <div class="filter-tab active" data-filter="all">
                    <i class="fas fa-inbox mr-2"></i>All Messages
                </div>
                <div class="filter-tab" data-filter="unread">
                    <i class="fas fa-envelope mr-2"></i>Unread
                </div>
            </div>

            <!-- Messages List -->
            <div id="messagesList">
<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Determine if the message is read or unread
        $statusClass = isset($row['is_read']) && $row['is_read'] ? 'read' : 'unread';

        // Optional: set priority based on some field (here just default low)
        $priorityClass = 'priority-low';

        // Format date nicely
        $dateSent = date("M d, Y H:i", strtotime($row['date_sent']));

        // Sender initials
        $initials = '';
        $names = explode(' ', $row['sender_name']);
        foreach ($names as $n) $initials .= strtoupper($n[0]);
        $initials = substr($initials, 0, 2);
?>
    <div class="message-card <?= $statusClass ?> <?= $priorityClass ?>" data-id="<?= $row['id'] ?>">
        <div class="message-header">
            <div class="sender-info">
                <div class="sender-avatar"><?= $initials ?></div>
                <div>
                    <div class="font-semibold text-gray-800"><?= htmlspecialchars($row['sender_name']) ?></div>
                    <div class="text-sm text-gray-500"><?= htmlspecialchars($row['sender_email']) ?></div>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500"><?= $dateSent ?></span>
                <div class="message-actions">
                    <button class="action-btn" onclick="markAsRead(<?= $row['id'] ?>)">
                        <i class="fas fa-check"></i>
                    </button>
                    <button class="action-btn" onclick="toggleImportant(<?= $row['id'] ?>)">
                        <i class="fas fa-star"></i>
                    </button>
                    <button class="action-btn" onclick="deleteMessage(<?= $row['id'] ?>)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="message-content">
            <h3 class="font-semibold text-gray-800 mb-2"><?= htmlspecialchars($row['subject']) ?></h3>
            <p class="text-gray-600 mb-3"><?= nl2br(htmlspecialchars($row['message'])) ?></p>
            <?php if(!empty($row['attachment'])): ?>
                <div class="flex gap-2 mb-2">
                    <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded-full">Attachment</span>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php
    }
} else {
    echo '<div id="emptyState" class="text-center py-16">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No messages found</h3>
            <p class="text-gray-500">Try adjusting your search or filter criteria</p>
          </div>';
}
?>
</div>



    <script>
        // Filter functionality
        const filterTabs = document.querySelectorAll('.filter-tab');
        const messageCards = document.querySelectorAll('.message-card');
        const searchInput = document.getElementById('searchInput');
        const emptyState = document.getElementById('emptyState');
        const messagesList = document.getElementById('messagesList');

        filterTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                filterTabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                filterMessages();
            });
        });

        searchInput.addEventListener('input', filterMessages);

        function filterMessages() {
            const activeFilter = document.querySelector('.filter-tab.active').dataset.filter;
            const searchTerm = searchInput.value.toLowerCase();
            let visibleCount = 0;

            messageCards.forEach(card => {
                const category = card.dataset.category;
                const isUnread = card.classList.contains('unread');
                const isImportant = card.classList.contains('important');
                const cardText = card.textContent.toLowerCase();

                let shouldShow = false;

                switch(activeFilter) {
                    case 'all':
                        shouldShow = true;
                        break;
                    case 'unread':
                        shouldShow = isUnread;
                        break;
                    case 'important':
                        shouldShow = isImportant;
                        break;
                    default:
                        shouldShow = category === activeFilter;
                }

                if (shouldShow && (searchTerm === '' || cardText.includes(searchTerm))) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (visibleCount === 0) {
                emptyState.classList.remove('hidden');
                messagesList.style.display = 'none';
            } else {
                emptyState.classList.add('hidden');
                messagesList.style.display = 'block';
            }
        }

        // Message actions
        function markAsRead(messageId) {
            const card = document.querySelector(`[data-id="${messageId}"]`);
            card.classList.remove('unread');
            card.classList.add('read');
            updateUnreadCount();
            showNotification('Message marked as read', 'success');
        }

        function markAsUnread(messageId) {
            const card = document.querySelector(`[data-id="${messageId}"]`);
            card.classList.remove('read');
            card.classList.add('unread');
            updateUnreadCount();
            showNotification('Message marked as unread', 'info');
        }

        function toggleImportant(messageId) {
            const card = document.querySelector(`[data-id="${messageId}"]`);
            card.classList.toggle('important');
            const isImportant = card.classList.contains('important');
            showNotification(isImportant ? 'Message marked as important' : 'Message unmarked as important', 'info');
        }

        function deleteMessage(messageId) {
            if (confirm('Are you sure you want to delete this message?')) {
                const card = document.querySelector(`[data-id="${messageId}"]`);
                card.style.animation = 'fadeOut 0.3s ease';
                setTimeout(() => {
                    card.remove();
                    updateUnreadCount();
                    filterMessages();
                }, 300);
                showNotification('Message deleted', 'success');
            }
        }

        function markAllAsRead() {
            const unreadCards = document.querySelectorAll('.message-card.unread');
            unreadCards.forEach(card => {
                card.classList.remove('unread');
                card.classList.add('read');
            });
            updateUnreadCount();
            showNotification('All messages marked as read', 'success');
        }

        function updateUnreadCount() {
            const unreadCount = document.querySelectorAll('.message-card.unread').length;
            document.getElementById('unreadCount').textContent = `${unreadCount} Unread`;
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
                closeComposeModal();
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateUnreadCount();
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98f5783310800dc9',t:'MTc2MDU5NTgyOC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>