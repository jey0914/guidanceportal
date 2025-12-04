<?php
session_start();
include("db.php");

// Redirect if not logged in
if (!isset($_SESSION['parent_email'])) {
    header("Location: parent_login.php");
    exit();
}

$parent_email = $_SESSION['parent_email'];

// Get appointment ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: parent_appointments.php");
    exit();
}

$appointment_id = $_GET['id'];

// Fetch appointment details
$stmt = $con->prepare("SELECT * FROM parent_appointments WHERE id = ? AND email = ?");
$stmt->bind_param("is", $appointment_id, $parent_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Appointment not found or does not belong to this parent
    header("Location: parent_appointments.php");
    exit();
}

$appointment = $result->fetch_assoc();

// Handle form submission
$successMsg = "";
$errorMsg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $interest = $_POST['interest'] ?? '';
    $reason = $_POST['reason'] ?? '';

    if (empty($date) || empty($time) || empty($interest) || empty($reason)) {
        $errorMsg = "All fields are required!";
    } else {
        $updateStmt = $con->prepare("UPDATE parent_appointments SET date = ?, time = ?, interest = ?, reason = ? WHERE id = ? AND email = ?");
        $updateStmt->bind_param("ssssis", $date, $time, $interest, $reason, $appointment_id, $parent_email);
        if ($updateStmt->execute()) {
            $successMsg = "Appointment updated successfully!";
            // Refresh appointment data
            $stmt->execute();
            $appointment = $stmt->get_result()->fetch_assoc();
        } else {
            $errorMsg = "Failed to update appointment. Please try again.";
        }
    }
}
?>
<DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Appointment - Parent Portal</title>
  <script src="/_sdk/element_sdk.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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

        /* Enhanced Form Styling */
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }

        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.875rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
        }

        .form-control:hover, .form-select:hover {
            border-color: #667eea;
            background: white;
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

        .btn-secondary {
            background: #6c757d;
            color: white;
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3);
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(108, 117, 125, 0.4);
        }

        /* Enhanced Alerts */
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
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 2rem;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
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

        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }
        }

        /* Animation for form elements */
        .form-control, .btn {
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
    </style>
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
  <script src="https://cdn.tailwindcss.com" type="text/javascript"></script>
 </head>

 <body><!-- Mobile Toggle Button --> <button class="mobile-toggle" onclick="toggleSidebar()"> <i class="fas fa-bars"></i> </button> <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
   <div class="sidebar-header">
    <h3 id="portal_title"><i class="fas fa-graduation-cap"></i> Parent Portal</h3>
   </div>
   <nav class="sidebar-nav">
    <ul>
     <li><a href="parent_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
     <li><a href="child_info.php"><i class="fas fa-child"></i> Child Info</a></li>
     <li><a href="parent_appointments.php" class="active"><i class="fas fa-calendar-check"></i> Appointments</a></li>
     <li><a href="parent_reports.php"><i class="fas fa-file-alt"></i> Reports</a></li>
     <li><a href="parent_settings.php"><i class="fas fa-cog"></i> Settings</a></li>
    </ul>
   </nav>
  </div>
  
  <!-- Main Content -->
  <div class="main-content">
   <div class="page-header">
    <h2><i class="fas fa-edit"></i> Edit Appointment</h2>
    <nav aria-label="breadcrumb">
     <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="parent_dashboard.php">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="parent_appointments.php">Appointments</a></li>
      <li class="breadcrumb-item active">Edit Appointment</li>
     </ol>
    </nav>
   </div>

   <div class="content-card"><!--?php if ($successMsg): ?-->
    <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i><!--?= $successMsg ?-->
    </div><!--?php endif; ?--> <!--?php if ($errorMsg): ?-->
    <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i><!--?= $errorMsg ?-->
    </div><!--?php endif; ?-->
    <form method="POST">
     <div class="row">
      <div class="col-md-6">
       <div class="form-group"><label for="date" class="form-label"> <i class="fas fa-calendar me-2"></i>Appointment Date </label> <input type="date" id="date" name="date" class="form-control" value="<?= htmlspecialchars($appointment['date']) ?>" required>
       </div>
      </div>
      <div class="col-md-6">
       <div class="form-group"><label for="time" class="form-label"> <i class="fas fa-clock me-2"></i>Appointment Time </label> <input type="time" id="time" name="time" class="form-control" value="<?= htmlspecialchars($appointment['time']) ?>" required>
       </div>
      </div>
     </div>
     <div class="form-group"><label for="interest" class="form-label"> <i class="fas fa-tag me-2"></i>Type / Interest </label> <input type="text" id="interest" name="interest" class="form-control" value="<?= htmlspecialchars($appointment['interest']) ?>" placeholder="e.g., Academic Progress, Behavioral Concerns, etc." required>
     </div>
     <div class="form-group"><label for="reason" class="form-label"> <i class="fas fa-comment me-2"></i>Reason for Appointment </label> <textarea id="reason" name="reason" class="form-control" rows="5" placeholder="Please provide detailed information about the reason for this appointment..." required><?= htmlspecialchars($appointment['reason']) ?></textarea>
     </div>
     <div class="d-flex gap-3 justify-content-end"><a href="parent_appointments.php" class="btn btn-secondary"> <i class="fas fa-arrow-left"></i> Back to Appointments </a> <button type="submit" class="btn btn-primary"> <i class="fas fa-save"></i> Update Appointment </button>
     </div>
    </form>
   </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
        // Configuration
        const defaultConfig = {
            portal_title: "Parent Portal",
            primary_color: "#667eea",
            secondary_color: "#764ba2",
            background_color: "#2c3e50"
        };

        // Initialize Flatpickr for better date/time picking
        flatpickr("#date", {
            minDate: "today",
            dateFormat: "Y-m-d"
        });

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

        // Form validation and enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const inputs = form.querySelectorAll('input, textarea');

            // Add floating label effect
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });

                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.classList.remove('focused');
                    }
                });

                // Check if input has value on load
                if (input.value) {
                    input.parentElement.classList.add('focused');
                }
            });

            // Form submission with loading state
            form.addEventListener('submit', function() {
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
                submitBtn.disabled = true;

                // Re-enable after 3 seconds (in case of error)
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 3000);
            });
        });

        // Element SDK Configuration
        async function onConfigChange(config) {
            document.getElementById('portal_title').innerHTML = `<i class="fas fa-graduation-cap"></i> ${config.portal_title || defaultConfig.portal_title}`;
            
            // Apply colors
            const primaryColor = config.primary_color || defaultConfig.primary_color;
            const secondaryColor = config.secondary_color || defaultConfig.secondary_color;
            const backgroundColor = config.background_color || defaultConfig.background_color;
            
            // Update CSS custom properties for dynamic theming
            document.documentElement.style.setProperty('--primary-color', primaryColor);
            document.documentElement.style.setProperty('--secondary-color', secondaryColor);
            document.documentElement.style.setProperty('--background-color', backgroundColor);
        }

        // Initialize Element SDK
        if (window.elementSdk) {
            window.elementSdk.init({
                defaultConfig,
                onConfigChange,
                mapToCapabilities: (config) => ({
                    recolorables: [
                        {
                            get: () => config.primary_color || defaultConfig.primary_color,
                            set: (value) => {
                                config.primary_color = value;
                                window.elementSdk.setConfig({ primary_color: value });
                            }
                        },
                        {
                            get: () => config.secondary_color || defaultConfig.secondary_color,
                            set: (value) => {
                                config.secondary_color = value;
                                window.elementSdk.setConfig({ secondary_color: value });
                            }
                        },
                        {
                            get: () => config.background_color || defaultConfig.background_color,
                            set: (value) => {
                                config.background_color = value;
                                window.elementSdk.setConfig({ background_color: value });
                            }
                        }
                    ],
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
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'996a77e287920dc9',t:'MTc2MTgyMjY0OS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>