<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sent Messages</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    body {
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }
    
    /* Modal Overlay */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(8px);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }
    
    .modal-overlay.active {
      opacity: 1;
      visibility: visible;
    }
    
    /* Modal Container */
    .modal-container {
      background: white;
      border-radius: 1.5rem;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
      width: 90%;
      max-width: 900px;
      max-height: 90vh;
      overflow: hidden;
      transform: scale(0.9) translateY(20px);
      transition: all 0.3s ease;
    }
    
    .modal-overlay.active .modal-container {
      transform: scale(1) translateY(0);
    }
    
    /* Modal Header */
    .modal-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      padding: 1.5rem;
      position: relative;
    }
    
    .close-btn {
      position: absolute;
      top: 1rem;
      right: 1rem;
      background: rgba(255, 255, 255, 0.2);
      border: none;
      color: white;
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 50%;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
    }
    
    .close-btn:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: rotate(90deg);
    }
    
    /* Modal Body */
    .modal-body {
      padding: 1.5rem;
      max-height: 60vh;
      overflow-y: auto;
    }
    
    .gradient-bg {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .message-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 0.75rem;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
      border-left: 3px solid transparent;
    }
    
    .message-card:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
    }
    
    .message-card.delivered { border-left-color: #10b981; }
    .message-card.read { border-left-color: #3b82f6; }
    .message-card.pending { border-left-color: #f59e0b; }
    
    .status-badge {
      padding: 0.125rem 0.5rem;
      border-radius: 9999px;
      font-size: 0.625rem;
      font-weight: 600;
      text-transform: uppercase;
    }
    
    .status-delivered { background-color: #d1fae5; color: #065f46; }
    .status-read { background-color: #dbeafe; color: #1e40af; }
    .status-pending { background-color: #fef3c7; color: #92400e; }
    
    .search-bar {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 0.75rem;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }
    
    /* Custom Scrollbar */
    .modal-body::-webkit-scrollbar {
      width: 6px;
    }
    
    .modal-body::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 3px;
    }
    
    .modal-body::-webkit-scrollbar-thumb {
      background: #c1c1c1;
      border-radius: 3px;
    }
    
    .modal-body::-webkit-scrollbar-thumb:hover {
      background: #a8a8a8;
    }
    
    /* Animation */
    @keyframes modalSlideIn {
      from {
        opacity: 0;
        transform: scale(0.8) translateY(30px);
      }
      to {
        opacity: 1;
        transform: scale(1) translateY(0);
      }
    }
    
    .modal-container.animate-in {
      animation: modalSlideIn 0.4s ease-out;
    }
  </style>
</head>
<body>
  
  <!-- Demo Button to Open Modal -->
  <div class="p-8 bg-gray-100 min-h-screen flex items-center justify-center">
    <button 
      onclick="openModal()" 
      class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center gap-3"
    >
      <i class="fas fa-paper-plane"></i>
      View Sent Messages
    </button>
  </div>

  <!-- Modal Overlay -->
  <div id="sentMessagesModal" class="modal-overlay">
    <div class="modal-container">
      
      <!-- Modal Header -->
      <div class="modal-header">
        <button class="close-btn" onclick="closeModal()">
          <i class="fas fa-times"></i>
        </button>
        
        <div class="text-center">
          <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-2">
            <i class="fas fa-paper-plane text-white text-lg"></i>
          </div>
          <h1 class="text-2xl font-bold text-white mb-1">Sent Messages</h1>
          <p class="text-white/80 text-sm">View and manage your sent messages</p>
        </div>
      </div>
      
      <!-- Modal Body -->
      <div class="modal-body">

    <!-- Compact Search and Filter Bar -->
    <div class="search-bar p-4 mb-4">
      <div class="flex flex-col sm:flex-row gap-3 items-center">
        
        <!-- Search Input -->
        <div class="flex-1 relative">
          <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
          <input 
            type="text" 
            id="searchInput"
            placeholder="Search messages..."
            class="w-full pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
        </div>
        
        <!-- Filters -->
        <select id="statusFilter" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option value="all">All Status</option>
          <option value="delivered">Delivered</option>
          <option value="read">Read</option>
          <option value="pending">Pending</option>
        </select>
        
        <select id="sortFilter" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option value="newest">Newest</option>
          <option value="oldest">Oldest</option>
        </select>
      </div>
      
      <!-- Results Counter -->
      <div class="mt-2 text-xs text-gray-600">
        <span id="resultsCount">8 messages found</span>
      </div>
    </div>

    <!-- Compact Messages List -->
    <div id="messagesList" class="space-y-3 max-h-96 overflow-y-auto">
      
      <!-- Message Card 1 -->
      <div class="message-card delivered p-4" data-status="delivered" data-recipient="admin" data-subject="grade inquiry" data-content="requesting information about my final grades">
        <div class="flex items-start justify-between gap-3">
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1">
              <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                <i class="fas fa-user-tie text-white text-xs"></i>
              </div>
              <span class="font-medium text-gray-800 text-sm">Admin Team</span>
              <span class="status-badge status-delivered">Delivered</span>
            </div>
            
            <h4 class="font-semibold text-gray-800 text-sm mb-1 truncate">Grade Inquiry for Final Semester</h4>
            
            <p class="text-gray-600 text-xs mb-2 line-clamp-2">
              Hello, I am writing to request information about my final grades for the semester...
              <button class="text-blue-600 hover:text-blue-800 ml-1" onclick="expandMessage(this)">...</button>
            </p>
            
            <div class="hidden full-content">
              <p class="text-gray-600 text-xs mb-2">
                Hello, I am writing to request information about my final grades for the semester. I would like to know when they will be available and if there are any issues with my submissions.
              </p>
            </div>
            
            <div class="flex items-center gap-3 text-xs text-gray-500">
              <span><i class="fas fa-calendar mr-1"></i>Dec 15, 2:30 PM</span>
              <span><i class="fas fa-paperclip mr-1"></i>1 file</span>
            </div>
          </div>
          
          <div class="flex gap-1">
            <button class="p-1 text-gray-400 hover:text-blue-600 rounded" title="Reply">
              <i class="fas fa-reply text-xs"></i>
            </button>
            <button class="p-1 text-gray-400 hover:text-green-600 rounded" title="Forward">
              <i class="fas fa-share text-xs"></i>
            </button>
            <button class="p-1 text-gray-400 hover:text-red-600 rounded" title="Delete">
              <i class="fas fa-trash text-xs"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Message Card 2 -->
      <div class="message-card read p-4" data-status="read" data-recipient="counselor" data-subject="course selection" data-content="need guidance on selecting courses">
        <div class="flex items-start justify-between gap-3">
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1">
              <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                <i class="fas fa-user-graduate text-white text-xs"></i>
              </div>
              <span class="font-medium text-gray-800 text-sm">Academic Counselor</span>
              <span class="status-badge status-read">Read</span>
            </div>
            
            <h4 class="font-semibold text-gray-800 text-sm mb-1 truncate">Course Selection Guidance</h4>
            
            <p class="text-gray-600 text-xs mb-2">
              Dear Counselor, I need guidance on selecting courses for next semester...
              <button class="text-blue-600 hover:text-blue-800 ml-1" onclick="expandMessage(this)">...</button>
            </p>
            
            <div class="hidden full-content">
              <p class="text-gray-600 text-xs mb-2">
                Dear Counselor, I need guidance on selecting courses for next semester. I'm particularly interested in advanced mathematics courses and prerequisites.
              </p>
            </div>
            
            <div class="flex items-center gap-3 text-xs text-gray-500">
              <span><i class="fas fa-calendar mr-1"></i>Dec 12, 10:15 AM</span>
            </div>
          </div>
          
          <div class="flex gap-1">
            <button class="p-1 text-gray-400 hover:text-blue-600 rounded" title="Reply">
              <i class="fas fa-reply text-xs"></i>
            </button>
            <button class="p-1 text-gray-400 hover:text-green-600 rounded" title="Forward">
              <i class="fas fa-share text-xs"></i>
            </button>
            <button class="p-1 text-gray-400 hover:text-red-600 rounded" title="Delete">
              <i class="fas fa-trash text-xs"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Message Card 3 -->
      <div class="message-card pending p-4" data-status="pending" data-recipient="support" data-subject="technical issue" data-content="experiencing problems with login">
        <div class="flex items-start justify-between gap-3">
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1">
              <div class="w-6 h-6 bg-orange-500 rounded-full flex items-center justify-center">
                <i class="fas fa-headset text-white text-xs"></i>
              </div>
              <span class="font-medium text-gray-800 text-sm">Technical Support</span>
              <span class="status-badge status-pending">Pending</span>
            </div>
            
            <h4 class="font-semibold text-gray-800 text-sm mb-1 truncate">Login Portal Issue</h4>
            
            <p class="text-gray-600 text-xs mb-2">
              Hi Support Team, I'm experiencing problems with the online portal...
              <button class="text-blue-600 hover:text-blue-800 ml-1" onclick="expandMessage(this)">...</button>
            </p>
            
            <div class="hidden full-content">
              <p class="text-gray-600 text-xs mb-2">
                Hi Support Team, I'm experiencing problems with the online portal login system. The page keeps timing out when I try to access my dashboard.
              </p>
            </div>
            
            <div class="flex items-center gap-3 text-xs text-gray-500">
              <span><i class="fas fa-calendar mr-1"></i>Dec 14, 4:45 PM</span>
              <span><i class="fas fa-paperclip mr-1"></i>2 files</span>
            </div>
          </div>
          
          <div class="flex gap-1">
            <button class="p-1 text-gray-400 hover:text-blue-600 rounded" title="Reply">
              <i class="fas fa-reply text-xs"></i>
            </button>
            <button class="p-1 text-gray-400 hover:text-green-600 rounded" title="Forward">
              <i class="fas fa-share text-xs"></i>
            </button>
            <button class="p-1 text-gray-400 hover:text-red-600 rounded" title="Delete">
              <i class="fas fa-trash text-xs"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Message Card 4 -->
      <div class="message-card delivered p-4" data-status="delivered" data-recipient="finance" data-subject="payment inquiry" data-content="question about tuition payment">
        <div class="flex items-start justify-between gap-3">
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1">
              <div class="w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center">
                <i class="fas fa-dollar-sign text-white text-xs"></i>
              </div>
              <span class="font-medium text-gray-800 text-sm">Finance Department</span>
              <span class="status-badge status-delivered">Delivered</span>
            </div>
            
            <h4 class="font-semibold text-gray-800 text-sm mb-1 truncate">Payment Plan Inquiry</h4>
            
            <p class="text-gray-600 text-xs mb-2">
              Hello Finance Team, I would like to inquire about payment plans...
              <button class="text-blue-600 hover:text-blue-800 ml-1" onclick="expandMessage(this)">...</button>
            </p>
            
            <div class="hidden full-content">
              <p class="text-gray-600 text-xs mb-2">
                Hello Finance Team, I would like to inquire about available payment plan options for the upcoming semester tuition and any associated fees.
              </p>
            </div>
            
            <div class="flex items-center gap-3 text-xs text-gray-500">
              <span><i class="fas fa-calendar mr-1"></i>Dec 10, 9:20 AM</span>
            </div>
          </div>
          
          <div class="flex gap-1">
            <button class="p-1 text-gray-400 hover:text-blue-600 rounded" title="Reply">
              <i class="fas fa-reply text-xs"></i>
            </button>
            <button class="p-1 text-gray-400 hover:text-green-600 rounded" title="Forward">
              <i class="fas fa-share text-xs"></i>
            </button>
            <button class="p-1 text-gray-400 hover:text-red-600 rounded" title="Delete">
              <i class="fas fa-trash text-xs"></i>
            </button>
          </div>
        </div>
      </div>

    </div>

    <!-- Empty State -->
    <div id="emptyState" class="text-center py-8 hidden">
      <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-3">
        <i class="fas fa-search text-white text-xl"></i>
      </div>
      <h3 class="text-lg font-semibold text-white mb-1">No messages found</h3>
      <p class="text-white/80 text-sm">Try adjusting your search criteria</p>
    </div>

        <!-- Modal Footer -->
        <div class="text-center mt-4 pt-4 border-t border-gray-200">
          <button 
            onclick="closeModal()" 
            class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:from-gray-600 hover:to-gray-700 transition-all duration-300 flex items-center gap-2 mx-auto"
          >
            <i class="fas fa-times"></i>
            Close Modal
          </button>
        </div>
        
      </div> <!-- End Modal Body -->
    </div> <!-- End Modal Container -->
  </div> <!-- End Modal Overlay -->

  <script>
    // Search and filter functionality
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const sortFilter = document.getElementById('sortFilter');
    const messagesList = document.getElementById('messagesList');
    const resultsCount = document.getElementById('resultsCount');
    const emptyState = document.getElementById('emptyState');

    function filterMessages() {
      const searchTerm = searchInput.value.toLowerCase();
      const statusValue = statusFilter.value;
      
      const messageCards = Array.from(messagesList.children);
      let visibleCount = 0;
      
      messageCards.forEach(card => {
        const recipient = card.dataset.recipient.toLowerCase();
        const subject = card.dataset.subject.toLowerCase();
        const content = card.dataset.content.toLowerCase();
        const status = card.dataset.status;
        
        const matchesSearch = searchTerm === '' || 
          recipient.includes(searchTerm) || 
          subject.includes(searchTerm) || 
          content.includes(searchTerm);
        
        const matchesStatus = statusValue === 'all' || status === statusValue;
        
        if (matchesSearch && matchesStatus) {
          card.style.display = 'block';
          visibleCount++;
        } else {
          card.style.display = 'none';
        }
      });
      
      resultsCount.textContent = `${visibleCount} message${visibleCount !== 1 ? 's' : ''} found`;
      
      if (visibleCount === 0) {
        emptyState.classList.remove('hidden');
        messagesList.style.display = 'none';
      } else {
        emptyState.classList.add('hidden');
        messagesList.style.display = 'block';
      }
    }

    // Event listeners
    searchInput.addEventListener('input', filterMessages);
    statusFilter.addEventListener('change', filterMessages);
    sortFilter.addEventListener('change', filterMessages);

    // Expand message function
    function expandMessage(button) {
      const messageCard = button.closest('.message-card');
      const fullContent = messageCard.querySelector('.full-content');
      const shortContent = button.parentElement;
      
      if (fullContent.classList.contains('hidden')) {
        fullContent.classList.remove('hidden');
        shortContent.style.display = 'none';
      }
    }

    // Modal control functions
    function openModal() {
      const modal = document.getElementById('sentMessagesModal');
      const container = modal.querySelector('.modal-container');
      
      modal.classList.add('active');
      container.classList.add('animate-in');
      document.body.style.overflow = 'hidden';
      
      // Remove animation class after animation completes
      setTimeout(() => {
        container.classList.remove('animate-in');
      }, 400);
    }
    
    function closeModal() {
      const modal = document.getElementById('sentMessagesModal');
      modal.classList.remove('active');
      document.body.style.overflow = '';
    }
    
    // Close modal when clicking outside
    document.getElementById('sentMessagesModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeModal();
      }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeModal();
      }
    });

    // Initialize
    filterMessages();
  </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98cd3ebb90ea0dcb',t:'MTc2MDE3NDA0My4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
