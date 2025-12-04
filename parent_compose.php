<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Compose Message - Parent Portal</title>
  <script src="/_sdk/element_sdk.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
  <style>
        body {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100%;
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
            height: 100%;
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
            min-height: 100%;
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

        /* Compose Form Styling */
        .compose-form {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 2rem;
            border: 1px solid #e9ecef;
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.875rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
        }

        .form-control:hover, .form-select:hover {
            border-color: #667eea;
        }

        .message-textarea {
            min-height: 300px;
            resize: vertical;
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

        .btn-outline-secondary {
            border: 2px solid #6c757d;
            color: #6c757d;
            background: transparent;
        }

        .btn-outline-secondary:hover {
            background: #6c757d;
            color: white;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(220, 53, 69, 0.4);
        }

        /* Priority Selection */
        .priority-selector {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .priority-option {
            flex: 1;
            padding: 1rem;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }

        .priority-option:hover {
            border-color: #667eea;
        }

        .priority-option.selected {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }

        .priority-high.selected {
            border-color: #dc3545;
            background: rgba(220, 53, 69, 0.1);
        }

        .priority-low.selected {
            border-color: #6c757d;
            background: rgba(108, 117, 125, 0.1);
        }

        .priority-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .priority-high .priority-icon {
            color: #dc3545;
        }

        .priority-normal .priority-icon {
            color: #28a745;
        }

        .priority-low .priority-icon {
            color: #6c757d;
        }

        /* Attachment Area */
        .attachment-area {
            border: 2px dashed #e9ecef;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            background: white;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .attachment-area:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.05);
        }

        .attachment-area.dragover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }

        .attachment-list {
            margin-top: 1rem;
        }

        .attachment-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }

        .attachment-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .file-icon {
            width: 32px;
            height: 32px;
            background: #667eea;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.9rem;
        }

        /* Quick Templates */
        .template-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid #e9ecef;
        }

        .template-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .template-card {
            padding: 1rem;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .template-card:hover {
            border-color: #667eea;
            background: white;
            transform: translateY(-2px);
        }

        .template-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .template-preview {
            font-size: 0.85rem;
            color: #6c757d;
            line-height: 1.4;
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

            .compose-form {
                padding: 1.5rem;
            }

            .priority-selector {
                flex-direction: column;
            }

            .template-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Animations */
        .compose-form, .template-section, .btn {
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

        .alert-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }
        

        /* Character Counter */
        .character-counter {
            text-align: right;
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }

        .character-counter.warning {
            color: #ffc107;
        }

        .character-counter.danger {
            color: #dc3545;
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
    <h2><i class="fas fa-edit"></i> Compose Message</h2>
    <nav aria-label="breadcrumb">
     <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="parent_dashboard.php">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="parent_inbox.php">Inbox</a></li>
      <li class="breadcrumb-item active">Compose</li>
     </ol>
    </nav>
   </div><!-- Quick Templates -->
   <div class="content-card">
    <div class="template-section">
     <h4><i class="fas fa-magic me-2"></i>Quick Templates</h4>
     <p class="text-muted mb-3">Choose a template to get started quickly, or compose your own message below.</p>
     <div class="template-grid">
      <div class="template-card" onclick="useTemplate('meeting')">
       <div class="template-title">
        Request Meeting
       </div>
       <div class="template-preview">
        Schedule a meeting to discuss my child's progress and any concerns...
       </div>
      </div>
      <div class="template-card" onclick="useTemplate('absence')">
       <div class="template-title">
        Report Absence
       </div>
       <div class="template-preview">
        Inform about my child's absence due to illness or family emergency...
       </div>
      </div>
      <div class="template-card" onclick="useTemplate('homework')">
       <div class="template-title">
        Homework Inquiry
       </div>
       <div class="template-preview">
        Ask about homework assignments or request additional help...
       </div>
      </div>
      <div class="template-card" onclick="useTemplate('general')">
       <div class="template-title">
        General Question
       </div>
       <div class="template-preview">
        Ask a general question about school policies or activities...
       </div>
      </div>
     </div>
    </div><!-- Compose Form -->
    <div class="compose-form">
     <form id="composeForm"><!-- Recipient Selection -->
      <div class="mb-4"><label for="recipient" class="form-label"> <i class="fas fa-user"></i> To: </label> <select class="form-select" id="recipient" required> <option value="">Select recipient...</option> <optgroup label="Teachers"> <option value="ms_johnson">Ms. Sarah Johnson - Math Teacher</option> <option value="mr_rodriguez">Mr. David Rodriguez - English Teacher</option> <option value="ms_chen">Ms. Lisa Chen - Science Teacher</option> <option value="mr_williams">Mr. James Williams - History Teacher</option> </optgroup> <optgroup label="Administration"> <option value="dr_martinez">Dr. Robert Martinez - Principal</option> <option value="ms_davis">Ms. Jennifer Davis - Vice Principal</option> <option value="mr_thompson">Mr. Michael Thompson - Counselor</option> </optgroup> <optgroup label="Support Staff"> <option value="nurse_williams">Nurse Williams - School Nurse</option> <option value="coach_thompson">Coach Thompson - PE Teacher</option> <option value="ms_garcia">Ms. Maria Garcia - Librarian</option> </optgroup> </select>
      </div><!-- Subject -->
      <div class="mb-4"><label for="subject" class="form-label"> <i class="fas fa-tag"></i> Subject: </label> <input type="text" class="form-control" id="subject" placeholder="Enter message subject" required>
      </div><!-- Priority Selection -->
      <div class="mb-4"><label class="form-label"> <i class="fas fa-exclamation-circle"></i> Priority: </label>
       <div class="priority-selector">
        <div class="priority-option priority-high" data-priority="high">
         <div class="priority-icon">
          <i class="fas fa-exclamation-triangle"></i>
         </div>
         <div>
          <strong>High</strong>
         </div><small>Urgent matter</small>
        </div>
        <div class="priority-option priority-normal selected" data-priority="normal">
         <div class="priority-icon">
          <i class="fas fa-circle"></i>
         </div>
         <div>
          <strong>Normal</strong>
         </div><small>Standard priority</small>
        </div>
        <div class="priority-option priority-low" data-priority="low">
         <div class="priority-icon">
          <i class="fas fa-minus-circle"></i>
         </div>
         <div>
          <strong>Low</strong>
         </div><small>Non-urgent</small>
        </div>
       </div>
      </div><!-- Message Content -->
      <div class="mb-4"><label for="message" class="form-label"> <i class="fas fa-comment"></i> Message: </label> <textarea class="form-control message-textarea" id="message" placeholder="Type your message here..." required></textarea>
       <div class="character-counter" id="charCounter">
        0 / 2000 characters
       </div>
      </div><!-- Attachments -->
      <div class="mb-4"><label class="form-label"> <i class="fas fa-paperclip"></i> Attachments: </label>
       <div class="attachment-area" id="attachmentArea"><i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
        <p class="mb-2">Drag and drop files here or click to browse</p><small class="text-muted">Maximum file size: 10MB. Supported formats: PDF, DOC, DOCX, JPG, PNG</small> <input type="file" id="fileInput" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="display: none;">
       </div>
       <div class="attachment-list" id="attachmentList"></div>
      </div><!-- Action Buttons -->
      <div class="d-flex justify-content-between align-items-center">
       <div><button type="button" class="btn btn-outline-secondary me-2" onclick="saveDraft()"> <i class="fas fa-save"></i> Save Draft </button> <button type="button" class="btn btn-outline-secondary" onclick="clearForm()"> <i class="fas fa-trash"></i> Clear </button>
       </div>
       <div><button type="button" class="btn btn-success me-2" onclick="scheduleMessage()"> <i class="fas fa-clock"></i> Schedule </button> <button type="submit" class="btn btn-primary"> <i class="fas fa-paper-plane"></i> Send Message </button>
       </div>
      </div>
     </form>
    </div>
   </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
        // Configuration
        const defaultConfig = {
            portal_title: "Parent Portal"
        };

        // Message templates
        const templates = {
            meeting: {
                subject: "Request for Parent-Teacher Meeting",
                message: "Dear [Teacher Name],\n\nI would like to schedule a meeting to discuss my child's progress and address any concerns. Please let me know your available times.\n\nThank you for your time and dedication to my child's education.\n\nBest regards,\n[Your Name]"
            },
            absence: {
                subject: "Student Absence Notification",
                message: "Dear [Teacher Name],\n\nI am writing to inform you that my child will be absent from school on [Date] due to [Reason - illness/family emergency/appointment].\n\nPlease let me know if there are any assignments or materials that need to be made up.\n\nThank you for your understanding.\n\nBest regards,\n[Your Name]"
            },
            homework: {
                subject: "Homework Assistance Request",
                message: "Dear [Teacher Name],\n\nI am reaching out regarding my child's homework in your class. [Specific question or concern about homework/assignments].\n\nCould you please provide some guidance or suggest additional resources that might help?\n\nThank you for your support.\n\nBest regards,\n[Your Name]"
            },
            general: {
                subject: "General Inquiry",
                message: "Dear [Teacher/Staff Name],\n\nI hope this message finds you well. I have a question regarding [Topic/Subject].\n\n[Your specific question or concern]\n\nI would appreciate any information or guidance you can provide.\n\nThank you for your time.\n\nBest regards,\n[Your Name]"
            }
        };

        let selectedPriority = 'normal';
        let attachedFiles = [];

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

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            setupPrioritySelector();
            setupAttachments();
            setupCharacterCounter();
            setupFormSubmission();
        });

        // Priority selector
        function setupPrioritySelector() {
            const priorityOptions = document.querySelectorAll('.priority-option');
            
            priorityOptions.forEach(option => {
                option.addEventListener('click', function() {
                    priorityOptions.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    selectedPriority = this.dataset.priority;
                });
            });
        }

        // Use template
        function useTemplate(templateType) {
            const template = templates[templateType];
            if (template) {
                document.getElementById('subject').value = template.subject;
                document.getElementById('message').value = template.message;
                updateCharacterCounter();
                
                // Scroll to form
                document.querySelector('.compose-form').scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }

        // Character counter
        function setupCharacterCounter() {
            const messageTextarea = document.getElementById('message');
            const charCounter = document.getElementById('charCounter');
            
            messageTextarea.addEventListener('input', updateCharacterCounter);
        }

        function updateCharacterCounter() {
            const messageTextarea = document.getElementById('message');
            const charCounter = document.getElementById('charCounter');
            const currentLength = messageTextarea.value.length;
            const maxLength = 2000;
            
            charCounter.textContent = `${currentLength} / ${maxLength} characters`;
            
            if (currentLength > maxLength * 0.9) {
                charCounter.className = 'character-counter danger';
            } else if (currentLength > maxLength * 0.8) {
                charCounter.className = 'character-counter warning';
            } else {
                charCounter.className = 'character-counter';
            }
        }

        // Attachment handling
        function setupAttachments() {
            const attachmentArea = document.getElementById('attachmentArea');
            const fileInput = document.getElementById('fileInput');
            
            // Click to browse
            attachmentArea.addEventListener('click', () => fileInput.click());
            
            // File input change
            fileInput.addEventListener('change', handleFileSelect);
            
            // Drag and drop
            attachmentArea.addEventListener('dragover', handleDragOver);
            attachmentArea.addEventListener('dragleave', handleDragLeave);
            attachmentArea.addEventListener('drop', handleDrop);
        }

        function handleDragOver(e) {
            e.preventDefault();
            document.getElementById('attachmentArea').classList.add('dragover');
        }

        function handleDragLeave(e) {
            e.preventDefault();
            document.getElementById('attachmentArea').classList.remove('dragover');
        }

        function handleDrop(e) {
            e.preventDefault();
            document.getElementById('attachmentArea').classList.remove('dragover');
            const files = Array.from(e.dataTransfer.files);
            addFiles(files);
        }

        function handleFileSelect(e) {
            const files = Array.from(e.target.files);
            addFiles(files);
        }

        function addFiles(files) {
            files.forEach(file => {
                if (validateFile(file)) {
                    attachedFiles.push(file);
                    displayAttachment(file);
                }
            });
        }

        function validateFile(file) {
            const maxSize = 10 * 1024 * 1024; // 10MB
            const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/jpg', 'image/png'];
            
            if (file.size > maxSize) {
                showAlert('File size must be less than 10MB', 'danger');
                return false;
            }
            
            if (!allowedTypes.includes(file.type)) {
                showAlert('File type not supported. Please use PDF, DOC, DOCX, JPG, or PNG files.', 'danger');
                return false;
            }
            
            return true;
        }

        function displayAttachment(file) {
            const attachmentList = document.getElementById('attachmentList');
            const attachmentItem = document.createElement('div');
            attachmentItem.className = 'attachment-item';
            
            const fileExtension = file.name.split('.').pop().toUpperCase();
            const fileSize = (file.size / 1024).toFixed(1) + ' KB';
            
            attachmentItem.innerHTML = `
                <div class="attachment-info">
                    <div class="file-icon">${fileExtension}</div>
                    <div>
                        <div class="fw-medium">${file.name}</div>
                        <small class="text-muted">${fileSize}</small>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeAttachment('${file.name}', this)">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            attachmentList.appendChild(attachmentItem);
        }

        function removeAttachment(fileName, button) {
            attachedFiles = attachedFiles.filter(file => file.name !== fileName);
            button.closest('.attachment-item').remove();
        }

        // Form submission
        function setupFormSubmission() {
            const form = document.getElementById('composeForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                sendMessage();
            });
        }

        function sendMessage() {
            const recipient = document.getElementById('recipient').value;
            const subject = document.getElementById('subject').value;
            const message = document.getElementById('message').value;
            
            if (!recipient || !subject || !message) {
                showAlert('Please fill in all required fields.', 'danger');
                return;
            }
            
            const submitBtn = document.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
            submitBtn.disabled = true;
            
            // Simulate sending
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                
                showAlert('Message sent successfully!', 'success');
                clearForm();
                
                // Redirect to inbox after 2 seconds
                setTimeout(() => {
                    window.location.href = 'parent_inbox.php';
                }, 2000);
            }, 2000);
        }

        function saveDraft() {
            const recipient = document.getElementById('recipient').value;
            const subject = document.getElementById('subject').value;
            const message = document.getElementById('message').value;
            
            if (!subject && !message) {
                showAlert('Nothing to save as draft.', 'danger');
                return;
            }
            
            showAlert('Draft saved successfully!', 'success');
        }

        function scheduleMessage() {
            const recipient = document.getElementById('recipient').value;
            const subject = document.getElementById('subject').value;
            const message = document.getElementById('message').value;
            
            if (!recipient || !subject || !message) {
                showAlert('Please fill in all required fields before scheduling.', 'danger');
                return;
            }
            
            // In a real application, this would open a date/time picker
            showAlert('Message scheduled for later delivery!', 'success');
        }

        function clearForm() {
            document.getElementById('composeForm').reset();
            attachedFiles = [];
            document.getElementById('attachmentList').innerHTML = '';
            
            // Reset priority to normal
            document.querySelectorAll('.priority-option').forEach(opt => opt.classList.remove('selected'));
            document.querySelector('[data-priority="normal"]').classList.add('selected');
            selectedPriority = 'normal';
            
            updateCharacterCounter();
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
            }, 4000);
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
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'997340fc94550dc9',t:'MTc2MTkxNDc3Mi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>