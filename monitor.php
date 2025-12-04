<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Attendance Monitor</title>
  <script src="/_sdk/element_sdk.js"></script>
  <style>
        body {
            box-sizing: border-box;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Georgia', 'Times New Roman', serif;
        }

        body {
            background: #f5f5f5;
            color: #333333;
            height: 100%;
            min-height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
            overflow: hidden;
        }

        /* MAIN CONTAINER - Resume Style */
        .resume-container {
            width: 100%;
            max-width: 1400px;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            height: calc(100% - 20px);
            max-height: calc(100% - 20px);
        }

        /* LEFT SIDEBAR - Resume Style */
        .sidebar {
            width: 300px;
            background: linear-gradient(135deg, #003366 0%, #004080 100%);
            color: white;
            padding: 25px 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            overflow-y: auto;
        }

        .college-header {
            text-align: center;
            border-bottom: 2px solid rgba(255,255,255,0.3);
            padding-bottom: 25px;
        }

        .college-name {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 8px;
            color: #ffc107;
        }

        .college-subtitle {
            font-size: 14px;
            color: rgba(255,255,255,0.8);
            font-style: italic;
        }

        .profile-section {
            text-align: center;
        }

        .profile-pic {
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            position: relative;
            overflow: hidden;
        }

        .profile-pic img {
            width: 180px;
            height: 180px;
            border-radius: 6px;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .profile-pic.active {
            border-color: #ffc107;
            box-shadow: 0 0 20px rgba(255,193,7,0.5);
        }

        .scanning-line {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #ffc107, transparent);
            animation: scanMove 2s linear infinite;
            opacity: 0;
        }

        .profile-pic.scanning .scanning-line {
            opacity: 1;
        }

        @keyframes scanMove {
            0% { top: 0; }
            100% { top: 100%; }
        }

        .status-display {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 12px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .status-display.active {
            background: #28a745;
            border-color: #28a745;
        }

        .status-display.error {
            background: #dc3545;
            border-color: #dc3545;
        }

        .status-display.processing {
            background: #ffc107;
            color: #000;
            border-color: #ffc107;
        }

        .system-info {
            background: rgba(255,255,255,0.1);
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #ffc107;
        }

        .system-info h3 {
            font-size: 16px;
            margin-bottom: 15px;
            color: #ffc107;
        }

        .clock-display {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .date-display {
            font-size: 14px;
            color: rgba(255,255,255,0.8);
        }

        /* RIGHT CONTENT AREA - Resume Style */
        .content-area {
            flex: 1;
            padding: 25px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            overflow-y: auto;
        }

        .header-section {
            border-bottom: 3px solid #003366;
            padding-bottom: 20px;
        }

        .student-name {
            font-size: 32px;
            font-weight: 700;
            color: #003366;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }

        .student-subtitle {
            font-size: 16px;
            color: #666;
            font-style: italic;
        }

        /* INFORMATION SECTIONS */
        .info-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 5px solid #003366;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #003366;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 8px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .info-label {
            font-weight: 600;
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            color: #333;
            font-size: 18px;
            font-weight: 500;
        }

        .time-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        .time-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-top: 4px solid #28a745;
        }

        .time-card.time-out {
            border-top-color: #dc3545;
        }

        .time-card.empty {
            border-top-color: #dee2e6;
            background: #f8f9fa;
        }

        .time-label {
            font-size: 14px;
            font-weight: 600;
            color: #666;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .time-value {
            font-size: 24px;
            font-weight: 700;
            color: #333;
        }

        .time-card.empty .time-value {
            color: #999;
        }

        /* ERROR MESSAGE */
        .error-message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #dc3545;
            color: white;
            padding: 30px 50px;
            border-radius: 10px;
            font-size: 24px;
            font-weight: 600;
            box-shadow: 0 8px 32px rgba(220,53,69,0.4);
            z-index: 1000;
            opacity: 0;
            animation: errorFade 3s ease-in-out;
        }

        @keyframes errorFade {
            0% { opacity: 0; transform: translate(-50%, -50%) scale(0.8); }
            20% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
            80% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
            100% { opacity: 0; transform: translate(-50%, -50%) scale(0.8); }
        }

        /* SUCCESS FLASH */
        .success-flash {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(40, 167, 69, 0.2);
            pointer-events: none;
            opacity: 0;
            animation: successFlash 0.6s ease-out;
        }

        @keyframes successFlash {
            0% { opacity: 0; }
            50% { opacity: 1; }
            100% { opacity: 0; }
        }

        /* RESPONSIVE DESIGN */
        @media (max-width: 768px) {
            .resume-container {
                flex-direction: column;
                max-width: 100%;
            }
            
            .sidebar {
                width: 100%;
                padding: 30px 20px;
            }
            
            .profile-pic {
                width: 150px;
                height: 150px;
            }
            
            .profile-pic img {
                width: 130px;
                height: 130px;
            }
            
            .content-area {
                padding: 30px 20px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .time-section {
                grid-template-columns: 1fr;
            }
        }
    </style>

  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
  <script src="https://cdn.tailwindcss.com" type="text/javascript"></script>
 </head>
 <body>
  <div class="resume-container">
    
  <!-- LEFT SIDEBAR - Resume Style -->
   <div class="sidebar">
    <div class="college-header">
     <div class="college-name" id="school_name">
      STI COLLEGE
     </div>
     <div class="college-name">
      ROSARIO
     </div>
     <div class="college-subtitle" id="department">
      Senior High Department
     </div>
    </div>
    <div class="profile-section">
     <div class="profile-pic" id="profile_container">
      <div class="scanning-line"></div><img id="student_photo" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='180' height='180' viewBox='0 0 180 180'%3E%3Crect width='180' height='180' fill='%23f8f9fa' rx='6'/%3E%3Ccircle cx='90' cy='65' r='25' fill='%23dee2e6'/%3E%3Cpath d='M45 135 Q45 110 90 110 Q135 110 135 135 L135 180 L45 180 Z' fill='%23dee2e6'/%3E%3Ctext x='90' y='150' text-anchor='middle' fill='%236c757d' font-size='12' font-family='Arial'%3EReady%3C/text%3E%3C/svg%3E" alt="Student Profile">
     </div>
     <div class="status-display" id="status_display">
      System Ready
     </div>
    </div>
    <div class="system-info">
     <h3>System Status</h3>
     <div class="clock-display" id="live_clock">
      00:00:00
     </div>
     <div class="date-display" id="date_display">
      Loading...
     </div>
    </div>
   </div>
   
   <!-- RIGHT CONTENT AREA - Resume Style -->
   <div class="content-area">
    <div class="header-section">
     <div class="student-name" id="student_name">
      Please Tap Your RFID Card
     </div>
     <div class="student-subtitle" id="student_subtitle">
      Waiting for student identification...
     </div>
    </div>
    <div class="info-section">
     <div class="section-title">
      Student Information
     </div>
     <div class="info-grid">
      <div class="info-item"><span class="info-label">Student Number</span> <span class="info-value" id="student_number">Waiting...</span>
      </div>
      <div class="info-item"><span class="info-label">Full Name</span> <span class="info-value" id="full_name">Waiting...</span>
      </div>
      <div class="info-item"><span class="info-label">Grade Level</span> <span class="info-value" id="grade_level">Waiting...</span>
      </div>
      <div class="info-item"><span class="info-label">Strand/Track</span> <span class="info-value" id="strand">Waiting...</span>
      </div>
     </div>
    </div>
    <div class="info-section">
     <div class="section-title">
      Attendance Record
     </div>
     <div class="time-section">
      <div class="time-card empty" id="time_in_card">
       <div class="time-label">
        Time In
       </div>
       <div class="time-value" id="time_in">
        --:--:--
       </div>
      </div>
      <div class="time-card time-out empty" id="time_out_card">
       <div class="time-label">
        Time Out
       </div>
       <div class="time-value" id="time_out">
        --:--:--
       </div>
      </div>
     </div>
    </div>
    <div class="info-section">
     <div class="section-title">
      System Message
     </div>
     <div class="info-item"><span class="info-label">Current Status</span> <span class="info-value" id="attendance_status">Waiting for RFID card scan...</span>
     </div>
    </div>
   </div>
  </div>
  
  <script>
    // Configuration object
    const defaultConfig = {
        school_name: "STI COLLEGE",
        department: "Senior High Department",
        sidebar_color: "#003366",
        accent_color: "#ffc107",
        success_color: "#28a745",
        error_color: "#dc3545",
        background_color: "#f5f5f5"
    };

    // Student database
    const studentDatabase = {
        'RFID001': {
            studentNumber: '2024-001234',
            fullName: 'Maria Santos',
            grade: 'Grade 12',
            strand: 'STEM',
            course: 'Science, Technology, Engineering & Mathematics',
            photo: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='180' height='180' viewBox='0 0 180 180'%3E%3Crect width='180' height='180' fill='%23fff3cd' rx='6'/%3E%3Ccircle cx='90' cy='65' r='25' fill='%23856404'/%3E%3Cpath d='M45 135 Q45 110 90 110 Q135 110 135 135 L135 180 L45 180 Z' fill='%23856404'/%3E%3Ctext x='90' y='150' text-anchor='middle' fill='%23664d03' font-size='10' font-family='Arial'%3EMaria S.%3C/text%3E%3C/svg%3E"
        },
        'RFID002': {
            studentNumber: '2024-001235',
            fullName: 'Juan Dela Cruz',
            grade: 'Grade 11',
            strand: 'ABM',
            course: 'Accountancy, Business & Management',
            photo: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='180' height='180' viewBox='0 0 180 180'%3E%3Crect width='180' height='180' fill='%23d1ecf1' rx='6'/%3E%3Ccircle cx='90' cy='65' r='25' fill='%23155160'/%3E%3Cpath d='M45 135 Q45 110 90 110 Q135 110 135 135 L135 180 L45 180 Z' fill='%23155160'/%3E%3Ctext x='90' y='150' text-anchor='middle' fill='%230c5460' font-size='10' font-family='Arial'%3EJuan D.%3C/text%3E%3C/svg%3E"
        },
        'RFID003': {
            studentNumber: '2024-001236',
            fullName: 'Ana Rodriguez',
            grade: 'Grade 12',
            strand: 'HUMSS',
            course: 'Humanities & Social Sciences',
            photo: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='180' height='180' viewBox='0 0 180 180'%3E%3Crect width='180' height='180' fill='%23f8d7da' rx='6'/%3E%3Ccircle cx='90' cy='65' r='25' fill='%23721c24'/%3E%3Cpath d='M45 135 Q45 110 90 110 Q135 110 135 135 L135 180 L45 180 Z' fill='%23721c24'/%3E%3Ctext x='90' y='150' text-anchor='middle' fill='%23491217' font-size='10' font-family='Arial'%3EAna R.%3C/text%3E%3C/svg%3E"
        },
        'RFID004': {
            studentNumber: '2024-001237',
            fullName: 'Carlos Mendoza',
            grade: 'Grade 11',
            strand: 'ICT',
            course: 'Information & Communications Technology',
            photo: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='180' height='180' viewBox='0 0 180 180'%3E%3Crect width='180' height='180' fill='%23cce5ff' rx='6'/%3E%3Ccircle cx='90' cy='65' r='25' fill='%23004085'/%3E%3Cpath d='M45 135 Q45 110 90 110 Q135 110 135 135 L135 180 L45 180 Z' fill='%23004085'/%3E%3Ctext x='90' y='150' text-anchor='middle' fill='%23002752' font-size='10' font-family='Arial'%3ECarlos M.%3C/text%3E%3C/svg%3E"
        }
    };

    // Attendance tracking
    let attendanceLog = {};
    let isProcessing = false;

    // Get current time
    function getCurrentTime() {
        const now = new Date();
        return now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });
    }

    // Update live clock
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        });
        const dateString = now.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        document.getElementById('live_clock').textContent = timeString;
        document.getElementById('date_display').textContent = dateString;
    }

    // Simulate RFID card detection
    function startRFIDSimulation() {
        const rfidTags = ['RFID001', 'RFID002', 'RFID003', 'RFID004', 'INVALID'];
        let currentIndex = 0;

        setInterval(() => {
            if (!isProcessing) {
                const rfidTag = rfidTags[currentIndex];
                processRFIDScan(rfidTag);
                currentIndex = (currentIndex + 1) % rfidTags.length;
            }
        }, 8000);
    }

    // Process RFID scan
    function processRFIDScan(rfidTag) {
        if (isProcessing) return;
        
        isProcessing = true;
        const studentData = studentDatabase[rfidTag];
        
        if (!studentData) {
            showError('RFID Not Recognized');
            return;
        }

        showScanningAnimation();
        
        setTimeout(() => {
            processAttendance(rfidTag, studentData);
        }, 2000);
    }

    function showScanningAnimation() {
        const profileContainer = document.getElementById('profile_container');
        const status = document.getElementById('status_display');
        
        profileContainer.classList.add('scanning');
        status.textContent = 'Scanning Card...';
        status.className = 'status-display processing';
    }

    function processAttendance(rfidTag, studentData) {
        const currentTime = getCurrentTime();
        const isLoggedIn = attendanceLog[rfidTag];
        
        // Update student information
        document.getElementById('student_name').textContent = studentData.fullName;
        document.getElementById('student_subtitle').textContent = `${studentData.grade} - ${studentData.strand} Student`;
        document.getElementById('student_number').textContent = studentData.studentNumber;
        document.getElementById('full_name').textContent = studentData.fullName;
        document.getElementById('grade_level').textContent = studentData.grade;
        document.getElementById('strand').textContent = studentData.strand;
        document.getElementById('student_photo').src = studentData.photo;
        
        const profileContainer = document.getElementById('profile_container');
        const status = document.getElementById('status_display');
        const timeInCard = document.getElementById('time_in_card');
        const timeOutCard = document.getElementById('time_out_card');
        const timeInElement = document.getElementById('time_in');
        const timeOutElement = document.getElementById('time_out');
        
        profileContainer.classList.remove('scanning');
        profileContainer.classList.add('active');
        
        showSuccessFlash();
        
        if (!isLoggedIn) {
            // Time In
            timeInElement.textContent = currentTime;
            timeInCard.className = 'time-card';
            timeOutElement.textContent = '--:--:--';
            timeOutCard.className = 'time-card time-out empty';
            
            status.textContent = 'Time In Recorded';
            status.className = 'status-display active';
            
            document.getElementById('attendance_status').textContent = 'Successfully logged in for today';
            
            attendanceLog[rfidTag] = {
                timeIn: currentTime,
                timeOut: null
            };
        } else {
            // Time Out
            timeOutElement.textContent = currentTime;
            timeOutCard.className = 'time-card time-out';
            
            status.textContent = 'Time Out Recorded';
            status.className = 'status-display active';
            
            document.getElementById('attendance_status').textContent = 'Successfully logged out for today';
            
            attendanceLog[rfidTag].timeOut = currentTime;
        }
        
        setTimeout(() => {
            resetDisplay();
        }, 6000);
    }

    function showSuccessFlash() {
        const flash = document.createElement('div');
        flash.className = 'success-flash';
        document.body.appendChild(flash);
        
        setTimeout(() => {
            if (document.body.contains(flash)) {
                document.body.removeChild(flash);
            }
        }, 600);
    }

    function showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        document.body.appendChild(errorDiv);
        
        const status = document.getElementById('status_display');
        status.textContent = 'Invalid Card';
        status.className = 'status-display error';
        
        setTimeout(() => {
            if (document.body.contains(errorDiv)) {
                document.body.removeChild(errorDiv);
            }
            resetDisplay();
        }, 3000);
    }

    function resetDisplay() {
        document.getElementById('student_name').textContent = 'Please Tap Your RFID Card';
        document.getElementById('student_subtitle').textContent = 'Waiting for student identification...';
        document.getElementById('student_number').textContent = 'Waiting...';
        document.getElementById('full_name').textContent = 'Waiting...';
        document.getElementById('grade_level').textContent = 'Waiting...';
        document.getElementById('strand').textContent = 'Waiting...';
        document.getElementById('time_in').textContent = '--:--:--';
        document.getElementById('time_out').textContent = '--:--:--';
        document.getElementById('attendance_status').textContent = 'Waiting for RFID card scan...';
        
        document.getElementById('time_in_card').className = 'time-card empty';
        document.getElementById('time_out_card').className = 'time-card time-out empty';
        
        document.getElementById('student_photo').src = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='180' height='180' viewBox='0 0 180 180'%3E%3Crect width='180' height='180' fill='%23f8f9fa' rx='6'/%3E%3Ccircle cx='90' cy='65' r='25' fill='%23dee2e6'/%3E%3Cpath d='M45 135 Q45 110 90 110 Q135 110 135 135 L135 180 L45 180 Z' fill='%23dee2e6'/%3E%3Ctext x='90' y='150' text-anchor='middle' fill='%236c757d' font-size='12' font-family='Arial'%3EReady%3C/text%3E%3C/svg%3E";
        
        const profileContainer = document.getElementById('profile_container');
        const status = document.getElementById('status_display');
        
        profileContainer.classList.remove('scanning', 'active');
        status.textContent = 'System Ready';
        status.className = 'status-display';
        
        isProcessing = false;
    }

    // Initialize Element SDK
    async function onConfigChange(config) {
        document.getElementById('school_name').textContent = config.school_name || defaultConfig.school_name;
        document.getElementById('department').textContent = config.department || defaultConfig.department;
        
        // Apply colors
        const sidebarColor = config.sidebar_color || defaultConfig.sidebar_color;
        const accentColor = config.accent_color || defaultConfig.accent_color;
        const backgroundColor = config.background_color || defaultConfig.background_color;
        
        document.querySelector('.sidebar').style.background = `linear-gradient(135deg, ${sidebarColor} 0%, ${sidebarColor}dd 100%)`;
        document.body.style.backgroundColor = backgroundColor;
    }

    // Initialize SDK
    if (window.elementSdk) {
        window.elementSdk.init({
            defaultConfig,
            onConfigChange,
            mapToCapabilities: (config) => ({
                recolorables: [
                    {
                        get: () => config.sidebar_color || defaultConfig.sidebar_color,
                        set: (value) => {
                            config.sidebar_color = value;
                            window.elementSdk.setConfig({ sidebar_color: value });
                        }
                    },
                    {
                        get: () => config.accent_color || defaultConfig.accent_color,
                        set: (value) => {
                            config.accent_color = value;
                            window.elementSdk.setConfig({ accent_color: value });
                        }
                    },
                    {
                        get: () => config.background_color || defaultConfig.background_color,
                        set: (value) => {
                            config.background_color = value;
                            window.elementSdk.setConfig({ background_color: value });
                        }
                    },
                    {
                        get: () => config.success_color || defaultConfig.success_color,
                        set: (value) => {
                            config.success_color = value;
                            window.elementSdk.setConfig({ success_color: value });
                        }
                    },
                    {
                        get: () => config.error_color || defaultConfig.error_color,
                        set: (value) => {
                            config.error_color = value;
                            window.elementSdk.setConfig({ error_color: value });
                        }
                    }
                ],
                borderables: [],
                fontEditable: undefined,
                fontSizeable: undefined
            }),
            mapToEditPanelValues: (config) => new Map([
                ["school_name", config.school_name || defaultConfig.school_name],
                ["department", config.department || defaultConfig.department]
            ])
        });
    }

    // Initialize system
    updateClock();
    setInterval(updateClock, 1000);
    resetDisplay();
    
    setTimeout(() => {
        startRFIDSimulation();
    }, 3000);
</script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'99696f4247820dc9',t:'MTc2MTgxMTgxMC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>