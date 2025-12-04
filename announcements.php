<?php
session_start();
include("db.php");

if (isset($_GET['view_id'])) {
  $id = intval($_GET['view_id']);
  $con->query("UPDATE exam_announcements SET views = views + 1 WHERE id = $id");
}

$announcement_q = $con->query("SELECT * FROM exam_announcements ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Announcements</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }
    
    .heart-btn {
      transition: all 0.3s ease;
      cursor: pointer;
      user-select: none;
    }
    
    .heart-btn:hover {
      transform: scale(1.1);
    }
    
    .heart-btn.liked {
      color: #ef4444;
      animation: heartPulse 0.6s ease-in-out;
    }
    
    .heart-btn.unliked {
      color: #9ca3af;
    }
    
    @keyframes heartPulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.3); }
      100% { transform: scale(1); }
    }
    
    .announcement-card {
      transition: all 0.3s ease;
      border-left: 4px solid transparent;
    }
    
    .announcement-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .announcement-card.priority-high {
      border-left-color: #ef4444;
      background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    }
    
    .announcement-card.priority-medium {
      border-left-color: #f59e0b;
      background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    }
    
    .announcement-card.priority-normal {
      border-left-color: #3b82f6;
      background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    }
    
    .stats-container {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-top: 0.75rem;
      padding-top: 0.75rem;
      border-top: 1px solid rgba(0,0,0,0.1);
    }
    
    .stat-item {
      display: flex;
      align-items: center;
      gap: 0.25rem;
      font-size: 0.875rem;
      color: #6b7280;
    }
    
    .engagement-actions {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-left: auto;
    }
    
    .share-btn {
      padding: 0.25rem 0.5rem;
      background: #f3f4f6;
      border-radius: 0.375rem;
      font-size: 0.75rem;
      color: #6b7280;
      cursor: pointer;
      transition: all 0.2s ease;
    }
    
    .share-btn:hover {
      background: #e5e7eb;
      color: #374151;
    }
    
    .priority-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.25rem;
      padding: 0.25rem 0.5rem;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 500;
    }
    
    .priority-high {
      background: #fee2e2;
      color: #dc2626;
    }
    
    .priority-medium {
      background: #fef3c7;
      color: #d97706;
    }
    
    .priority-normal {
      background: #dbeafe;
      color: #2563eb;
    }
    
    .header-gradient {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 2rem;
      border-radius: 1rem 1rem 0 0;
      margin: -1.5rem -1.5rem 2rem -1.5rem;
    }
    
    .floating-action {
      position: fixed;
      bottom: 2rem;
      right: 2rem;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 1rem;
      border-radius: 50%;
      box-shadow: 0 4px 20px rgba(0,0,0,0.3);
      cursor: pointer;
      transition: all 0.3s ease;
      z-index: 50;
    }
    
    .floating-action:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 30px rgba(0,0,0,0.4);
    }
  </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen">
  <div class="max-w-4xl mx-auto pt-8 pb-20 px-4">
    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
      <!-- Enhanced Header -->
      <div class="header-gradient">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold mb-2">📢 School Announcements</h1>
            <p class="text-blue-100">Stay updated with the latest news and updates</p>
          </div>
          <div class="text-right">
            <div class="text-2xl font-bold" id="totalAnnouncements">0</div>
            <div class="text-sm text-blue-100">Total Posts</div>
          </div>
        </div>
      </div>

      <div class="p-6">
        <?php if ($announcement_q && $announcement_q->num_rows > 0): ?>
          <div class="space-y-6">
            <?php 
            $priorities = ['normal', 'medium', 'high'];
            $icons = ['📝', '⚠️', '🚨'];
            $counter = 0;
            while ($row = $announcement_q->fetch_assoc()): 
              $priority = $priorities[$counter % 3];
              $icon = $icons[$counter % 3];
              $counter++;
            ?>
              <div class="announcement-card priority-<?= $priority ?> p-6 rounded-xl shadow-lg" data-id="<?= $row['id'] ?>">
                <!-- Header with Priority Badge -->
                <div class="flex items-start justify-between mb-4">
                  <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                      <span class="text-2xl"><?= $icon ?></span>
                      <h2 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($row['title']) ?></h2>
                      <span class="priority-badge priority-<?= $priority ?>">
                        <?= ucfirst($priority) ?> Priority
                      </span>
                    </div>
                    <div class="text-sm text-gray-500 flex items-center gap-2">
                      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                      </svg>
                      Posted on <?= date("F d, Y • h:i A", strtotime($row['created_at'])) ?>
                    </div>
                  </div>
                </div>

                <!-- Content -->
                <div class="mb-4">
                  <p class="text-gray-700 leading-relaxed"><?= nl2br(htmlspecialchars($row['message'])) ?></p>
                </div>

                <!-- Stats and Actions -->
                <div class="stats-container">
                  <div class="stat-item">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                      <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="view-count"><?= $row['views'] ?></span> views

                  </div>
                  

                  <div class="engagement-actions">
                    <button class="heart-btn unliked flex items-center gap-1" onclick="toggleHeart(this, <?= $row['id'] ?>)">
                      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                      </svg>
                      <span class="like-count"><?= $row['likes'] ?></span>
                    </button>
                    
                    <button class="share-btn" onclick="shareAnnouncement(<?= $row['id'] ?>, '<?= addslashes($row['title']) ?>')">
                      <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z"></path>
                      </svg>
                      Share
                    </button>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-16">
            <div class="text-6xl mb-4">📭</div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No Announcements Yet</h3>
            <p class="text-gray-500">Check back later for updates and news!</p>
          </div>
        <?php endif; ?>

        <!-- Back Button -->
        <div class="mt-8 text-center">
          <a href="dashboard.php" class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-red-500 text-white py-3 px-6 rounded-xl hover:from-orange-600 hover:to-red-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
            </svg>
            Back to Dashboard
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Floating Refresh Button -->
  <div class="floating-action" onclick="refreshAnnouncements()" title="Refresh Announcements">
    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
    </svg>
  </div>

  <script>
  // Heart functionality with DB + localStorage
  function toggleHeart(button, announcementId) {
    const likeCountSpan = button.querySelector('.like-count');
    let currentCount = parseInt(likeCountSpan.textContent);

    fetch("like_announcement.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "id=" + announcementId
    })
    .then(res => res.text())
    .then(data => {
      let likedPosts = JSON.parse(localStorage.getItem('likedAnnouncements') || '[]');

      if (data === "liked") {
        button.classList.remove("unliked");
        button.classList.add("liked");
        likeCountSpan.textContent = currentCount + 1;

        if (!likedPosts.includes(announcementId)) {
          likedPosts.push(announcementId);
          localStorage.setItem('likedAnnouncements', JSON.stringify(likedPosts));
        }
      } else if (data === "unliked") {
        button.classList.remove("liked");
        button.classList.add("unliked");
        likeCountSpan.textContent = currentCount - 1;

        likedPosts = likedPosts.filter(id => id !== announcementId);
        localStorage.setItem('likedAnnouncements', JSON.stringify(likedPosts));
      }
    });
  }

  // Share functionality
  function shareAnnouncement(id, title) {
    if (navigator.share) {
      navigator.share({
        title: title,
        text: 'Check out this announcement: ' + title,
        url: window.location.href + '#announcement-' + id
      });
    } else {
      const url = window.location.href + '#announcement-' + id;
      navigator.clipboard.writeText(url).then(() => {
        alert('Link copied to clipboard!');
      });
    }
  }

  // Refresh announcements
  function refreshAnnouncements() {
    location.reload();
  }

  // Update total announcements count
  function updateTotalCount() {
    const count = document.querySelectorAll('.announcement-card').length;
    document.getElementById('totalAnnouncements').textContent = count;
  }

  // Load liked posts from localStorage (para naka-red pa rin yung heart)
  function loadLikedPosts() {
    const likedPosts = JSON.parse(localStorage.getItem('likedAnnouncements') || '[]');
    
    document.querySelectorAll('.heart-btn').forEach(button => {
      const card = button.closest('.announcement-card');
      const announcementId = parseInt(card.dataset.id);

      if (likedPosts.includes(announcementId)) {
        button.classList.remove('unliked');
        button.classList.add('liked');
      }
    });
  }

  // Initialize on page load
  document.addEventListener('DOMContentLoaded', function() {
    updateTotalCount();
    loadLikedPosts();

    // Smooth scrolling for anchors
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({ behavior: 'smooth' });
        }
      });
    });
  });
</script>
