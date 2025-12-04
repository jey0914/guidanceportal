<?php
session_start();
include 'db.php';

$parent_email = $_SESSION['parent_email'];

$sql = "SELECT fullname, email, contact, relationship FROM parents WHERE email = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $parent_email);
$stmt->execute();
$result = $stmt->get_result();
$parent = $result->fetch_assoc();

// Split fullname
$parts = explode(" ", $parent['fullname'], 2);
$first_name = $parts[0];
$last_name  = isset($parts[1]) ? $parts[1] : "";
?>

<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Settings - Parent Portal</title>
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

        /* Settings Sections */
        .settings-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #e9ecef;
        }

        .settings-section h4 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .settings-section h4 i {
            color: #667eea;
            font-size: 1.2rem;
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

        /* Switch Toggle */
        .form-switch .form-check-input {
            width: 3rem;
            height: 1.5rem;
            border-radius: 3rem;
            background-color: #dee2e6;
            border: none;
            cursor: pointer;
        }

        .form-switch .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        .form-switch .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }

        /* Profile Picture */
        .profile-picture {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            margin: 0 auto 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .profile-picture img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-picture .upload-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            cursor: pointer;
        }

        .profile-picture:hover .upload-overlay {
            opacity: 1;
        }

        .form-control[readonly] {
        background-color: #e9ecef;
        cursor: not-allowed;
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

            .settings-section {
                padding: 1.5rem;
            }
        }

        /* Animation for form elements */
        .settings-section, .btn {
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

        /* Success Message */
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

        .alert-info {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
            color: white;
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
     <li><a href="parent_settings.php" class="active"><i class="fas fa-cog"></i> Settings</a></li>
    </ul>
   </nav>

  </div><!-- Main Content -->
  <div class="main-content">
   <div class="page-header">
    <h2><i class="fas fa-cog"></i> Settings</h2>
    <nav aria-label="breadcrumb">
     <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="parent_dashboard.php">Dashboard</a></li>
      <li class="breadcrumb-item active">Settings</li>
     </ol>
    </nav>
    
   </div><!-- Profile Settings -->
   <div class="content-card">
    <div class="settings-section">
     <h4><i class="fas fa-user"></i> Profile Information</h4>
     <div class="text-center mb-4">
      <div class="profile-picture"><i class="fas fa-user"></i>
       <div class="upload-overlay"><i class="fas fa-camera"></i>
       </div>
      </div>
     </div>
     <form method="POST" action="update_parent_profile.php">
      <div class="row">
       <div class="col-md-6">
    <div class="mb-3">
        <label for="first_name" class="form-label">
            <i class="fas fa-user me-2"></i>First Name
        </label>
        <input type="text" id="first_name" name="first_name" class="form-control"
               value="<?= $first_name ?>" readonly>
    </div>
</div>

<div class="col-md-6">
    <div class="mb-3">
        <label for="last_name" class="form-label">
            <i class="fas fa-user me-2"></i>Last Name
        </label>
        <input type="text" id="last_name" name="last_name" class="form-control"
               value="<?= $last_name ?>" readonly>
    </div>
</div>

      <div class="row">
   <div class="col-md-6">
      <div class="mb-3">
         <label for="email" class="form-label">
            <i class="fas fa-envelope me-2"></i>Email Address
         </label>
         <input type="email" id="email" name="email" class="form-control"
                value="<?= htmlspecialchars($parent['email']) ?>" required>
            </div>
   </div>

   <div class="col-md-6">
      <div class="mb-3">
         <label for="phone" class="form-label">
            <i class="fas fa-phone me-2"></i>Phone Number
         </label>
         <input type="tel" id="phone" name="contact" class="form-control"
                value="<?= htmlspecialchars($parent['contact']) ?>">
      </div>
   </div>
</div>
     <div class="mb-3">
   <label for="address" class="form-label">
      <i class="fas fa-map-marker-alt me-2"></i>Address
   </label>
   <textarea id="address" name="address" class="form-control" rows="3" placeholder="Enter your full address (optional)"></textarea>
</div>

          <div class="text-end">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Profile</button>
    </div>
</form>
     
    <div class="settings-section">
  <h4><i class="fas fa-shield-alt"></i> Security &amp; Privacy</h4>
  <form method="POST" action="update_security.php">
    <div class="row">
      <div class="col-md-6">
        <div class="mb-3">
          <label for="current_password" class="form-label"><i class="fas fa-lock me-2"></i>Current Password</label>
          <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Enter current password">
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <label for="new_password" class="form-label"><i class="fas fa-key me-2"></i>New Password</label>
          <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter new password">
        </div>
      </div>
    </div>
    <div class="mb-3">
      <label for="confirm_password" class="form-label"><i class="fas fa-check-circle me-2"></i>Confirm New Password</label>
      <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm new password">
    </div>
    <div class="text-end">
      <button type="submit" class="btn btn-primary"><i class="fas fa-shield-alt"></i> Update Security</button>
    </div>
  </form>
</div>
      </div>
     </div>
    </div>
   </div>
  </div>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
   
    const defaultConfig = {
        portal_title: "Parent Portal"
    };

    
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

    // Profile picture upload simulation
    document.addEventListener('DOMContentLoaded', function() {
        const profilePicture = document.querySelector('.profile-picture');
        profilePicture.addEventListener('click', function() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.style.display = 'none';
            
            input.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profilePicture.innerHTML = `<img src="${e.target.result}" alt="Profile Picture">
                                                    <div class="upload-overlay"><i class="fas fa-camera"></i></div>`;
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
            
            document.body.appendChild(input);
            input.click();
            document.body.removeChild(input);
        });
    });

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
