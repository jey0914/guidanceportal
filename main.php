<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Guidance Portal</title>

  <!-- CSS and Fonts -->
  <link rel="stylesheet" href="assets/css/sidebar.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    /* 🔹 General Layout */
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f9fafb;
      margin: 0;
      display: flex;
    }

    .content-area {
      flex-grow: 1;
      padding: 30px;
      overflow-y: auto;
      transition: all 0.3s ease;
      background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
      min-height: 100vh;
    }

    /* ---------------------------------- */
    /* 🔹 NOTIFICATIONS DESIGN */
    /* ---------------------------------- */
    .notif-container {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
      padding: 25px;
    }

    .notif-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .notif-header h2 {
      font-size: 24px;
      font-weight: 600;
      color: #222;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .notif-actions button {
      background: #dc2626;
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 500;
      transition: background 0.3s;
    }

    .notif-actions button:hover {
      background: #b91c1c;
    }

    .notif-list {
      border-top: 1px solid #e5e7eb;
    }

    .notif-item {
      display: flex;
      align-items: flex-start;
      gap: 15px;
      padding: 15px 0;
      border-bottom: 1px solid #f1f1f1;
      transition: background 0.2s ease;
    }

    .notif-item:hover {
      background: #f9fafc;
    }

    .notif-item input[type="checkbox"] {
      width: 18px;
      height: 18px;
      accent-color: #3b82f6;
    }

    .notif-content {
      flex: 1;
    }

    .notif-content h3 {
      margin: 0;
      font-size: 16px;
      font-weight: 600;
      color: #111827;
    }

    .notif-content p {
      margin: 5px 0;
      color: #4b5563;
      font-size: 14px;
    }

    .notif-time {
      font-size: 12px;
      color: #9ca3af;
    }

    .notif-badge {
      font-size: 12px;
      background: #e0f2fe;
      color: #0369a1;
      padding: 4px 8px;
      border-radius: 6px;
      font-weight: 600;
    }

    /* ---------------------------------- */
    /* 🔹 INBOX DESIGN */
    /* ---------------------------------- */
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

    .message-header {
      display: flex;
      justify-content: space-between;
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
  </style>
</head>

<body>
  <?php include('sidebar.php'); ?>

  <div id="content-area" class="content-area">
    <!-- Default dashboard -->
    <?php include('dashboard.php'); ?>
  </div>

  <!-- jQuery page loader -->
  <script>
    $(document).ready(function(){
      $(".nav-link").click(function(e){
        e.preventDefault();
        var page = $(this).data("page");
        $("#content-area").load(page);
        $(".nav-link").removeClass("active");
        $(this).addClass("active");
      });
    });
  </script>

  <!-- Notification Template -->
  <template id="notification-template">
    <div class="notif-container">
      <div class="notif-header">
        <h2><i class="fas fa-bell text-yellow-500"></i> Notifications</h2>
        <div class="notif-actions">
          <button id="deleteSelected"><i class="fas fa-trash-alt mr-1"></i> Delete Selected</button>
        </div>
      </div>

      <div class="notif-list">
        <div class="notif-item">
          <input type="checkbox">
          <div class="notif-content">
            <h3>Appointment Approved</h3>
            <p>Your counseling appointment has been approved by admin.</p>
            <span class="notif-time">2 hours ago</span>
          </div>
          <span class="notif-badge">New</span>
        </div>

        <div class="notif-item">
          <input type="checkbox">
          <div class="notif-content">
            <h3>Reminder</h3>
            <p>You have a guidance meeting tomorrow at 10 AM.</p>
            <span class="notif-time">Yesterday</span>
          </div>
          <span class="notif-badge" style="background:#dcfce7;color:#166534;">Read</span>
        </div>

        <div class="notif-item">
          <input type="checkbox">
          <div class="notif-content">
            <h3>System Maintenance</h3>
            <p>The portal will be unavailable tonight from 12–2 AM.</p>
            <span class="notif-time">2 days ago</span>
          </div>
          <span class="notif-badge">New</span>
        </div>
      </div>
    </div>
  </template>

  <script>
    // Example: Load notification design manually if needed
    function showNotifications() {
      const notifHTML = document.querySelector('#notification-template').content.cloneNode(true);
      const contentArea = document.getElementById('content-area');
      contentArea.innerHTML = '';
      contentArea.appendChild(notifHTML);
    }
  </script>

</body>
</html>
