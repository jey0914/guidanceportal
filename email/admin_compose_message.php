<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Compose Message</title>
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
        
        .compose-container {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 2rem;
        }
        
        .compose-toolbar {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 0.75rem;
            margin-bottom: 1rem;
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .toolbar-group {
            display: flex;
            gap: 0.25rem;
            padding-right: 0.75rem;
            border-right: 1px solid #dee2e6;
        }
        
        .toolbar-group:last-child {
            border-right: none;
        }
        
        .toolbar-btn {
            border: none;
            background: none;
            padding: 0.375rem 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .toolbar-btn:hover {
            background-color: #e9ecef;
        }
        
        .toolbar-btn.active {
            background-color: #007bff;
            color: white;
        }
        
        .editor-container {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            min-height: 300px;
            padding: 1rem;
            background-color: #fff;
            outline: none;
        }
        
        .editor-container:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .attachment-area {
            border: 2px dashed #dee2e6;
            border-radius: 0.375rem;
            padding: 2rem;
            text-align: center;
            background-color: #f8f9fa;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .attachment-area:hover {
            border-color: #007bff;
            background-color: #f0f8ff;
        }
        
        .attachment-area.dragover {
            border-color: #007bff;
            background-color: #e3f2fd;
        }
        
        .attachment-list {
            margin-top: 1rem;
        }
        
        .attachment-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            margin-bottom: 0.5rem;
        }
        
        .attachment-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .auto-save-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #28a745;
            font-size: 0.875rem;
        }
        
        .template-selector {
            margin-bottom: 1.5rem;
        }
        
        .template-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .template-card {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .template-card:hover {
            border-color: #007bff;
            background-color: #f0f8ff;
        }
        
        .template-card.selected {
            border-color: #007bff;
            background-color: #e3f2fd;
        }
        
        .recipient-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        
        .recipient-tag {
            background-color: #e3f2fd;
            color: #1976d2;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .char-counter {
            font-size: 0.75rem;
            color: #6c757d;
            text-align: right;
            margin-top: 0.25rem;
        }
        
        .schedule-section {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-top: 1rem;
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
                    <h1><i class="bi bi-pencil-square me-2"></i>Compose Message</h1>
                    <p class="text-muted mb-0">Create and send messages to parents, teachers, and staff</p>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <div class="auto-save-status" id="autoSaveStatus">
                        <i class="bi bi-check-circle"></i>
                        <span>Draft saved</span>
                    </div>
                    <a href="email/inbox.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to Inbox
                    </a>
                </div>
            </div>

            <!-- Compose Container -->
            <div class="compose-container">
                <!-- Template Selector -->
                <div class="template-selector">
                    <h5><i class="bi bi-layout-text-window me-2"></i>Choose Template</h5>
                    <div class="template-grid">
                        <div class="template-card" onclick="selectTemplate(this, 'blank')">
                            <h6><i class="bi bi-file-earmark me-2"></i>Blank Message</h6>
                            <p class="text-muted small mb-0">Start with a blank message</p>
                        </div>
                        <div class="template-card" onclick="selectTemplate(this, 'newsletter')">
                            <h6><i class="bi bi-newspaper me-2"></i>Newsletter</h6>
                            <p class="text-muted small mb-0">Weekly/monthly newsletter template</p>
                        </div>
                        <div class="template-card" onclick="selectTemplate(this, 'announcement')">
                            <h6><i class="bi bi-megaphone me-2"></i>Announcement</h6>
                            <p class="text-muted small mb-0">Important school announcements</p>
                        </div>
                        <div class="template-card" onclick="selectTemplate(this, 'reminder')">
                            <h6><i class="bi bi-bell me-2"></i>Reminder</h6>
                            <p class="text-muted small mb-0">Event or deadline reminders</p>
                        </div>
                    </div>
                </div>

                <form id="composeForm">
                    <!-- Recipients -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="recipients" class="form-label">To</label>
                            <select class="form-select" id="recipients" multiple onchange="updateRecipientTags()">
                                <option value="all_teachers">All Student</option>
                                <option value="grade_11">Grade 11</option>
                                <option value="grade_12">Grade 12</option>
                                <option value="bsa">BSA Students</option>
                                 <option value="bsit">BSIT Students</option>
                            </select>
                            <div class="recipient-tags" id="recipientTags"></div>
                            
                            <!-- Specific email option -->
                            <input 
                                type="email" 
                                class="form-control mt-2" 
                                id="specificEmail" 
                                name="specificEmail" 
                                placeholder="Or enter a specific email address..."
                                onkeypress="handleSpecificEmail(event)"
                                onblur="addSpecificEmail()"
                            >
                            <small class="text-muted">Press Enter or click away to add individual email addresses</small>
                        </div>
                        <div class="col-md-3">
                            <label for="cc" class="form-label">CC</label>
                            <input type="email" class="form-control" id="cc" name="cc" placeholder="Optional CC recipients">
                        </div>
                        <div class="col-md-3">
                            <label for="bcc" class="form-label">BCC</label>
                            <input type="email" class="form-control" id="bcc" name="bcc" placeholder="Optional BCC recipients">
                        </div>
                    </div>

                    <!-- Subject -->
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" placeholder="Enter message subject" oninput="updateCharCounter('subject', 'subjectCounter', 100)">
                        <div class="char-counter" id="subjectCounter">0/100 characters</div>
                    </div>

                    <!-- Priority and Options -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-select" id="priority">
                                <option value="normal">Normal</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Options</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="requestReceipt">
                                <label class="form-check-label" for="requestReceipt">
                                    Request read receipt
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Delivery</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="scheduleEmail" onchange="toggleSchedule()">
                                <label class="form-check-label" for="scheduleEmail">
                                    Schedule for later
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Section -->
                    <div class="schedule-section d-none" id="scheduleSection">
                        <h6><i class="bi bi-calendar-event me-2"></i>Schedule Delivery</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="scheduleDate" class="form-label">Date</label>
                                <input type="date" class="form-control" id="scheduleDate">
                            </div>
                            <div class="col-md-6">
                                <label for="scheduleTime" class="form-label">Time</label>
                                <input type="time" class="form-control" id="scheduleTime">
                            </div>
                        </div>
                    </div>

                    <!-- Rich Text Editor Toolbar -->
                    <div class="mb-2">
                        <label class="form-label">Message Content</label>
                    </div>
                    <div class="compose-toolbar">
                        <div class="toolbar-group">
                            <button type="button" class="toolbar-btn" onclick="formatText('bold')" title="Bold">
                                <i class="bi bi-type-bold"></i>
                            </button>
                            <button type="button" class="toolbar-btn" onclick="formatText('italic')" title="Italic">
                                <i class="bi bi-type-italic"></i>
                            </button>
                            <button type="button" class="toolbar-btn" onclick="formatText('underline')" title="Underline">
                                <i class="bi bi-type-underline"></i>
                            </button>
                        </div>
                        <div class="toolbar-group">
                            <button type="button" class="toolbar-btn" onclick="formatText('insertUnorderedList')" title="Bullet List">
                                <i class="bi bi-list-ul"></i>
                            </button>
                            <button type="button" class="toolbar-btn" onclick="formatText('insertOrderedList')" title="Numbered List">
                                <i class="bi bi-list-ol"></i>
                            </button>
                        </div>
                        <div class="toolbar-group">
                            <button type="button" class="toolbar-btn" onclick="formatText('justifyLeft')" title="Align Left">
                                <i class="bi bi-text-left"></i>
                            </button>
                            <button type="button" class="toolbar-btn" onclick="formatText('justifyCenter')" title="Align Center">
                                <i class="bi bi-text-center"></i>
                            </button>
                            <button type="button" class="toolbar-btn" onclick="formatText('justifyRight')" title="Align Right">
                                <i class="bi bi-text-right"></i>
                            </button>
                        </div>
                        <div class="toolbar-group">
                            <select class="form-select form-select-sm" onchange="formatText('fontSize', this.value)" style="width: auto;">
                                <option value="3">Normal</option>
                                <option value="1">Small</option>
                                <option value="4">Large</option>
                                <option value="6">Heading</option>
                            </select>
                        </div>
                    </div>

                    <!-- Message Editor -->
                    <div class="editor-container" contenteditable="true" id="messageEditor" oninput="autoSave()" onkeyup="updateCharCounter('messageEditor', 'messageCounter', 5000)">
                        <p>Start typing your message here...</p>
                    </div>
                    <div class="char-counter" id="messageCounter">0/5000 characters</div>

                    <!-- Attachments -->
                    <div class="mt-4">
                        <label class="form-label">Attachments</label>
                        <div class="attachment-area" onclick="document.getElementById('fileInput').click()" ondrop="handleDrop(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)">
                            <i class="bi bi-cloud-upload fs-1 text-muted"></i>
                            <p class="mb-2">Drag and drop files here or click to browse</p>
                            <p class="text-muted small mb-0">Supported formats: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (Max 10MB each)</p>
                        </div>
                        <input type="file" id="fileInput" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" style="display: none;" onchange="handleFileSelect(event)">
                        <div class="attachment-list" id="attachmentList"></div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary" onclick="saveDraft()">
                                <i class="bi bi-save me-1"></i>Save Draft
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="previewMessage()">
                                <i class="bi bi-eye me-1"></i>Preview
                            </button>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-danger" onclick="discardMessage()">
                                <i class="bi bi-trash me-1"></i>Discard
                            </button>
                            <button type="submit" class="btn btn-primary" onclick="sendMessage(event)">
                                <i class="bi bi-send me-1"></i>Send Message
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let attachments = [];
        let autoSaveTimer;
        let specificEmails = [];

        // ===== Recipients handling =====
    function handleSpecificEmail(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            addSpecificEmail();
        }
    }

    function addSpecificEmail() {
        const emailInput = document.getElementById('specificEmail');
        const email = emailInput.value.trim();
        
        if (email && isValidEmail(email)) {
            if (!specificEmails.includes(email)) {
                specificEmails.push(email);
                addEmailTag(email);
                emailInput.value = '';
            } else {
                showToast('Email already added', 'warning');
            }
        } else if (email) {
            showToast('Please enter a valid email address', 'warning');
        }
    }

    function addEmailTag(email) {
        const tagsContainer = document.getElementById('recipientTags');
        const tag = document.createElement('span');
        tag.className = 'recipient-tag';
        tag.innerHTML = `
            ${email}
            <i class="bi bi-x" onclick="removeSpecificEmail('${email}')" style="cursor: pointer;"></i>
        `;
        tagsContainer.appendChild(tag);
    }

    function removeSpecificEmail(email) {
        specificEmails = specificEmails.filter(e => e !== email);
        updateRecipientTags();
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function updateRecipientTags() {
        const select = document.getElementById('recipients');
        const tagsContainer = document.getElementById('recipientTags');
        const selectedOptions = Array.from(select.selectedOptions);
        
        tagsContainer.innerHTML = '';
        
        // Add group recipient tags
        selectedOptions.forEach(option => {
            const tag = document.createElement('span');
            tag.className = 'recipient-tag';
            tag.innerHTML = `
                ${option.text}
                <i class="bi bi-x" onclick="removeRecipient('${option.value}')" style="cursor: pointer;"></i>
            `;
            tagsContainer.appendChild(tag);
        });
        
        // Add specific email tags
        specificEmails.forEach(email => {
            const tag = document.createElement('span');
            tag.className = 'recipient-tag';
            tag.innerHTML = `
                ${email}
                <i class="bi bi-x" onclick="removeSpecificEmail('${email}')" style="cursor: pointer;"></i>
            `;
            tagsContainer.appendChild(tag);
        });
    }

    function removeRecipient(value) {
        const select = document.getElementById('recipients');
        const option = select.querySelector(`option[value="${value}"]`);
        if (option) {
            option.selected = false;
            updateRecipientTags();
        }
    }

    // ===== NEW: Load recipients from backend =====
    function loadRecipients() {
        const select = document.getElementById("recipients");
        const container = document.getElementById("recipientTags");
        container.innerHTML = "<small>Loading...</small>";

        const selectedValues = Array.from(select.selectedOptions).map(opt => opt.value);

        fetch("fetch_recipients.php?categories=" + selectedValues.join(','))
            .then(response => response.json())
            .then(data => {
                container.innerHTML = '';
                if (!data || data.length === 0) {
                    container.innerHTML = "<small>No recipients found.</small>";
                    return;
                }

                data.forEach(email => {
                    const tag = document.createElement("span");
                    tag.className = "recipient-tag";
                    tag.innerHTML = `
                        ${email}
                        <i class="bi bi-x" onclick="removeSpecificEmail('${email}')" style="cursor:pointer;"></i>
                    `;
                    container.appendChild(tag);
                });
            })
            .catch(err => {
                console.error("Error fetching recipients:", err);
                container.innerHTML = "<small>Error loading recipients.</small>";
            });
    }


        function handleSpecificEmail(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                addSpecificEmail();
            }
        }

        function addSpecificEmail() {
            const emailInput = document.getElementById('specificEmail');
            const email = emailInput.value.trim();
            
            if (email && isValidEmail(email)) {
                if (!specificEmails.includes(email)) {
                    specificEmails.push(email);
                    addEmailTag(email);
                    emailInput.value = '';
                } else {
                    showToast('Email already added', 'warning');
                }
            } else if (email) {
                showToast('Please enter a valid email address', 'warning');
            }
        }

        function addEmailTag(email) {
            const tagsContainer = document.getElementById('recipientTags');
            const tag = document.createElement('span');
            tag.className = 'recipient-tag';
            tag.innerHTML = `
                ${email}
                <i class="bi bi-x" onclick="removeSpecificEmail('${email}')" style="cursor: pointer;"></i>
            `;
            tagsContainer.appendChild(tag);
        }

        function removeSpecificEmail(email) {
            specificEmails = specificEmails.filter(e => e !== email);
            updateRecipientTags();
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function selectTemplate(card, templateType) {
            // Remove previous selection
            document.querySelectorAll('.template-card').forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');

            const editor = document.getElementById('messageEditor');
            const subject = document.getElementById('subject');

            switch(templateType) {
                case 'newsletter':
                    subject.value = 'Weekly Newsletter - ' + new Date().toLocaleDateString();
                    editor.innerHTML = `
                        <h3>Weekly Newsletter</h3>
                        <p>Dear Parents and Guardians,</p>
                        <p>We hope this message finds you well. Here are the important updates for this week:</p>
                        <h4>Upcoming Events</h4>
                        <ul>
                            <li>Event 1 - Date</li>
                            <li>Event 2 - Date</li>
                        </ul>
                        <h4>Academic Updates</h4>
                        <p>Add your academic updates here...</p>
                        <p>Best regards,<br>School Administration</p>
                    `;
                    break;
                case 'announcement':
                    subject.value = 'Important School Announcement';
                    editor.innerHTML = `
                        <h3>Important Announcement</h3>
                        <p>Dear School Community,</p>
                        <p>We would like to inform you about an important update:</p>
                        <p><strong>Add your announcement details here...</strong></p>
                        <p>If you have any questions, please don't hesitate to contact us.</p>
                        <p>Thank you for your attention.</p>
                        <p>Sincerely,<br>School Administration</p>
                    `;
                    break;
                case 'reminder':
                    subject.value = 'Friendly Reminder';
                    editor.innerHTML = `
                        <h3>Reminder</h3>
                        <p>Dear Parents,</p>
                        <p>This is a friendly reminder about:</p>
                        <p><strong>Add reminder details here...</strong></p>
                        <p>Please mark your calendars and let us know if you have any questions.</p>
                        <p>Thank you,<br>School Administration</p>
                    `;
                    break;
                default:
                    subject.value = '';
                    editor.innerHTML = '<p>Start typing your message here...</p>';
            }
            
            updateCharCounter('subject', 'subjectCounter', 100);
            updateCharCounter('messageEditor', 'messageCounter', 5000);
            autoSave();
        }

        function updateRecipientTags() {
            const select = document.getElementById('recipients');
            const tagsContainer = document.getElementById('recipientTags');
            const selectedOptions = Array.from(select.selectedOptions);
            
            tagsContainer.innerHTML = '';
            
            // Add group recipient tags
            selectedOptions.forEach(option => {
                const tag = document.createElement('span');
                tag.className = 'recipient-tag';
                tag.innerHTML = `
                    ${option.text}
                    <i class="bi bi-x" onclick="removeRecipient('${option.value}')" style="cursor: pointer;"></i>
                `;
                tagsContainer.appendChild(tag);
            });
            
            // Add specific email tags
            specificEmails.forEach(email => {
                const tag = document.createElement('span');
                tag.className = 'recipient-tag';
                tag.innerHTML = `
                    ${email}
                    <i class="bi bi-x" onclick="removeSpecificEmail('${email}')" style="cursor: pointer;"></i>
                `;
                tagsContainer.appendChild(tag);
            });
        }

        function removeRecipient(value) {
            const select = document.getElementById('recipients');
            const option = select.querySelector(`option[value="${value}"]`);
            if (option) {
                option.selected = false;
                updateRecipientTags();
            }
        }

        function toggleSchedule() {
            const checkbox = document.getElementById('scheduleEmail');
            const section = document.getElementById('scheduleSection');
            
            if (checkbox.checked) {
                section.classList.remove('d-none');
                // Set default to tomorrow at 9 AM
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                document.getElementById('scheduleDate').value = tomorrow.toISOString().split('T')[0];
                document.getElementById('scheduleTime').value = '09:00';
            } else {
                section.classList.add('d-none');
            }
        }

        function formatText(command, value = null) {
            document.execCommand(command, false, value);
            document.getElementById('messageEditor').focus();
            autoSave();
        }

        function handleDragOver(event) {
            event.preventDefault();
            event.currentTarget.classList.add('dragover');
        }

        function handleDragLeave(event) {
            event.currentTarget.classList.remove('dragover');
        }

        function handleDrop(event) {
            event.preventDefault();
            event.currentTarget.classList.remove('dragover');
            const files = event.dataTransfer.files;
            handleFiles(files);
        }

        function handleFileSelect(event) {
            const files = event.target.files;
            handleFiles(files);
        }

        function handleFiles(files) {
            Array.from(files).forEach(file => {
                if (file.size > 10 * 1024 * 1024) {
                    showToast(`File ${file.name} is too large. Maximum size is 10MB.`, 'warning');
                    return;
                }
                
                attachments.push(file);
                addAttachmentToList(file);
            });
        }

        function addAttachmentToList(file) {
            const list = document.getElementById('attachmentList');
            const item = document.createElement('div');
            item.className = 'attachment-item';
            item.innerHTML = `
                <div class="attachment-info">
                    <i class="bi bi-paperclip"></i>
                    <span>${file.name}</span>
                    <small class="text-muted">(${formatFileSize(file.size)})</small>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeAttachment('${file.name}')">
                    <i class="bi bi-x"></i>
                </button>
            `;
            list.appendChild(item);
        }

        function removeAttachment(fileName) {
            attachments = attachments.filter(file => file.name !== fileName);
            const items = document.querySelectorAll('.attachment-item');
            items.forEach(item => {
                if (item.textContent.includes(fileName)) {
                    item.remove();
                }
            });
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function updateCharCounter(elementId, counterId, maxLength) {
            const element = document.getElementById(elementId);
            const counter = document.getElementById(counterId);
            let length;
            
            if (element.contentEditable === 'true') {
                length = element.textContent.length;
            } else {
                length = element.value.length;
            }
            
            counter.textContent = `${length}/${maxLength} characters`;
            
            if (length > maxLength * 0.9) {
                counter.style.color = '#dc3545';
            } else if (length > maxLength * 0.7) {
                counter.style.color = '#ffc107';
            } else {
                counter.style.color = '#6c757d';
            }
        }

        function autoSave() {
            clearTimeout(autoSaveTimer);
            
            const status = document.getElementById('autoSaveStatus');
            status.innerHTML = '<i class="bi bi-clock"></i><span>Saving...</span>';
            status.style.color = '#6c757d';
            
            autoSaveTimer = setTimeout(() => {
                // Simulate save
                status.innerHTML = '<i class="bi bi-check-circle"></i><span>Draft saved</span>';
                status.style.color = '#28a745';
            }, 1000);
        }

        function saveDraft() {
            showToast('Draft saved successfully!', 'success');
            autoSave();
        }

        function previewMessage() {
            const subject = document.getElementById('subject').value;
            const content = document.getElementById('messageEditor').innerHTML;
            
            // Create preview modal
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.innerHTML = `
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Message Preview</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <h6>Subject: ${subject || 'No subject'}</h6>
                            <hr>
                            <div>${content}</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            
            modal.addEventListener('hidden.bs.modal', () => {
                modal.remove();
            });
        }

        function sendMessage(event) {
            event.preventDefault();
            
            const recipients = document.getElementById('recipients').selectedOptions;
            const subject = document.getElementById('subject').value;
            const content = document.getElementById('messageEditor').textContent;
            
            if (recipients.length === 0 && specificEmails.length === 0) {
                showToast('Please select at least one recipient or enter a specific email address.', 'warning');
                return;
            }
            
            if (!subject.trim()) {
                showToast('Please enter a subject.', 'warning');
                return;
            }
            
            if (!content.trim() || content.trim() === 'Start typing your message here...') {
                showToast('Please enter message content.', 'warning');
                return;
            }
            
            const isScheduled = document.getElementById('scheduleEmail').checked;
            
            if (isScheduled) {
                showToast('Message scheduled successfully!', 'success');
            } else {
                showToast('Message sent successfully!', 'success');
            }
            
            // Reset form after delay
            setTimeout(() => {
                document.getElementById('composeForm').reset();
                document.getElementById('messageEditor').innerHTML = '<p>Start typing your message here...</p>';
                document.getElementById('recipientTags').innerHTML = '';
                document.getElementById('attachmentList').innerHTML = '';
                attachments = [];
            }, 2000);
        }

        function discardMessage() {
            if (confirm('Are you sure you want to discard this message? All changes will be lost.')) {
                document.getElementById('composeForm').reset();
                document.getElementById('messageEditor').innerHTML = '<p>Start typing your message here...</p>';
                document.getElementById('recipientTags').innerHTML = '';
                document.getElementById('attachmentList').innerHTML = '';
                attachments = [];
                showToast('Message discarded.', 'info');
            }
        }

        function showToast(message, type = 'success') {
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

        // Initialize auto-save
        setInterval(autoSave, 30000); // Auto-save every 30 seconds
        
        // Initialize character counters
        updateCharCounter('subject', 'subjectCounter', 100);
        updateCharCounter('messageEditor', 'messageCounter', 5000);
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98fe0c6b41220dc9',t:'MTc2MDY4NTc4NS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>