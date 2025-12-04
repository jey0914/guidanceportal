<?php
session_start();
include("db.php");

if (!isset($_SESSION['parent_email'])) {
    header("Location: parent_login.php");
    exit();
}

$email = $_SESSION['parent_email'];
$query = $con->prepare("SELECT id, fullname, email, contact, relationship, student_id, student_name, student_birthday, created_at, avatar_choice FROM parents WHERE email = ?");
if (!$query) {
    die("Query failed: " . $con->error);
}
$query->bind_param("s", $email);
$query->execute();
$result = $query->get_result();
$parent = $result->fetch_assoc();

$parentId    = $parent['id'];
$fullName    = htmlspecialchars($parent['fullname'] ?? 'Not set');
$parentEmail = htmlspecialchars($parent['email'] ?? 'Not set');
$contactNo   = htmlspecialchars($parent['contact'] ?? 'Not set');
$relation    = htmlspecialchars($parent['relationship'] ?? 'Not set');
$studentId   = htmlspecialchars($parent['student_id'] ?? 'Not set');
$studentName = htmlspecialchars($parent['student_name'] ?? 'Not set');
$studentBday = htmlspecialchars($parent['student_birthday'] ?? 'Not set');
$createdAt   = htmlspecialchars($parent['created_at'] ?? '');
$avatarFile  = htmlspecialchars($parent['avatar_choice'] ?? 'default_avatar.png');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Profile - Parent Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #334155;
        }

        html, body {
            height: 100%;
        }

        /* Sidebar Styles */
        .sidebar {
          width: 280px;
          background: #2c3e50;
          box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
          padding: 0;
          position: fixed;
          height: 100vh;
          overflow-y: auto;
          z-index:100;
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
            min-height: 100vh;
            background: #f8fafc;
        }

        /* Header */
        .content-header {
            background: white;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid #e2e8f0;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-subtitle {
            color: #64748b;
            margin: 0.5rem 0 0;
            font-size: 1rem;
        }

        /* Content Area */
        .content {
            padding: 2rem;
        }

        /* Enhanced Profile Styles */
        .profile-header {
            margin-bottom: 2rem;
        }

        .profile-banner {
            position: relative;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }

        .banner-gradient {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%);
        }

        .profile-info-container {
            position: relative;
            padding: 3rem 2rem;
            display: flex;
            align-items: center;
            gap: 2rem;
            z-index: 2;
        }

        .avatar-container {
            position: relative;
        }

        .avatar-wrapper {
            position: relative;
            display: inline-block;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .profile-avatar:hover {
            transform: scale(1.05);
            border-color: rgba(255, 255, 255, 0.6);
        }

        .avatar-edit-indicator {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: #4f46e5;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .avatar-edit-indicator:hover {
            background: #3730a3;
            transform: scale(1.1);
        }

        .profile-details {
            flex: 1;
            color: white;
        }

        .profile-name {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0 0 0.5rem;
            color: white;
        }

        .profile-role {
            font-size: 1.2rem;
            margin: 0 0 1rem;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .profile-badges {
            display: flex;
            gap: 0.5rem;
        }

        .profile-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Enhanced Avatar Popup */
        .avatar-popup {
            display: none;
            position: absolute;
            top: 130px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            min-width: 320px;
            animation: popupSlideIn 0.3s ease-out;
        }

        @keyframes popupSlideIn {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }

        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .popup-header h6 {
            margin: 0;
            font-weight: 600;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .popup-close {
            background: none;
            border: none;
            color: #6b7280;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .popup-close:hover {
            background: #f3f4f6;
            color: #374151;
        }

        .avatar-options {
            display: flex;
            gap: 1rem;
            padding: 1.5rem;
            justify-content: center;
        }

        .avatar-option {
            position: relative;
            cursor: pointer;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .avatar-option img {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .avatar-option:hover img {
            transform: scale(1.05);
        }

        .option-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(79, 70, 229, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .avatar-option input:checked + img + .option-overlay {
            opacity: 1;
        }

        .popup-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            padding: 1.5rem;
            border-top: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        /* Enhanced Profile Body */
        .profile-body {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .profile-container {
            padding: 0;
        }

        .profile-nav-wrapper {
            background: #f8fafc;
            padding: 1.5rem 2rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .profile-nav {
            border: none;
            gap: 0.5rem;
        }

        .profile-nav .nav-link {
            background: transparent;
            border: none;
            color: #6b7280;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .profile-nav .nav-link:hover {
            background: rgba(79, 70, 229, 0.1);
            color: #4f46e5;
        }

        .profile-nav .nav-link.active {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }

        .profile-tab-content {
            padding: 2rem;
        }

        /* Enhanced Info Cards */
        .info-card {
            background: #f8fafc;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem 2rem;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-body {
            padding: 2rem;
        }

        .info-grid {
            display: grid;
            gap: 2rem;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.5rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .info-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .info-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #6b7280;
            margin: 0 0 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0 0 0.75rem;
        }

        .contact-action {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #4f46e5;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .contact-action:hover {
            color: #3730a3;
            transform: translateX(2px);
        }

        /* Mobile Responsive */
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
            }

            .content-header {
                padding: 1.5rem;
            }

            .content {
                padding: 1rem;
            }

            .profile-info-container {
                flex-direction: column;
                text-align: center;
                padding: 2rem 1.5rem;
            }

            .profile-name {
                font-size: 2rem;
            }

            .avatar-popup {
                left: 1rem;
                right: 1rem;
                transform: none;
                min-width: auto;
            }

            .avatar-options {
                flex-wrap: wrap;
                gap: 0.75rem;
            }

            .avatar-option img {
                width: 60px;
                height: 60px;
            }

            .profile-nav .nav-link span {
                display: none;
            }

            .profile-tab-content {
                padding: 1.5rem;
            }

            .card-body {
                padding: 1.5rem;
            }

            .info-item {
                padding: 1rem;
            }
        }

        /* Mobile Menu Toggle */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: #4f46e5;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.75rem;
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
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
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-graduation-cap"></i> Parent Portal</h3>
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
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="content-header">
            <h1 class="page-title">
                <i class="fas fa-user-circle"></i>
                Parent Profile
            </h1>
            <p class="page-subtitle">Manage your profile information and settings</p>
        </div>

        <!-- Content -->
        <div class="content fade-in">
            <!-- ENHANCED PROFILE HEADER -->
            <div class="profile-header">
                <div class="profile-banner">
                    <div class="banner-gradient"></div>
                    <div class="profile-info-container">
                        
                        <div class="avatar-container">
                            <div class="avatar-wrapper">
                                <img src="avatars/<?= $avatarFile ?>" alt="Avatar" id="profilePic" onclick="togglePopup()" class="profile-avatar">
                                <div class="avatar-edit-indicator">
                                    <i class="fas fa-camera"></i>
                                </div>
                            </div>
                            
                            <!-- Enhanced Avatar Popup -->
                            <div class="avatar-popup" id="avatarPopup">
                                <div class="popup-header">
                                    <h6><i class="fas fa-user-circle"></i> Choose Your Avatar</h6>
                                    <button type="button" class="popup-close" onclick="togglePopup()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <form method="POST" action="update_parent_avatar.php">
                                    <div class="avatar-options">
                                        <label class="avatar-option">
                                            <input type="radio" name="avatar" value="avatar1.png" hidden <?= $avatarFile == 'avatar1.png' ? 'checked' : '' ?>>
                                            <img src="avatars/avatar1.png" alt="Avatar 1">
                                            <div class="option-overlay">
                                                <i class="fas fa-check"></i>
                                            </div>
                                        </label>
                                        <label class="avatar-option">
                                            <input type="radio" name="avatar" value="avatar2.png" hidden <?= $avatarFile == 'avatar2.png' ? 'checked' : '' ?>>
                                            <img src="avatars/avatar2.png" alt="Avatar 2">
                                            <div class="option-overlay">
                                                <i class="fas fa-check"></i>
                                            </div>
                                        </label>
                                        <label class="avatar-option">
                                            <input type="radio" name="avatar" value="default_avatar.png" hidden <?= $avatarFile == 'default_avatar.png' ? 'checked' : '' ?>>
                                            <img src="avatars/default_avatar.png" alt="Default Avatar">
                                            <div class="option-overlay">
                                                <i class="fas fa-check"></i>
                                            </div>
                                        </label>
                                    </div>
                                    <input type="hidden" name="parent_id" value="<?= $parentId ?>">
                                    <div class="popup-footer">
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="togglePopup()">Cancel</button>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-save"></i> Save Avatar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="profile-details">
                            <h1 class="profile-name"><?= htmlspecialchars($fullName) ?></h1>
                            <p class="profile-role">
                                <i class="fas fa-users"></i>
                                <?= htmlspecialchars($relation) ?> of student
                            </p>
                            <div class="profile-badges">
                                <span class="badge profile-badge">
                                    <i class="fas fa-shield-alt"></i> Verified Parent
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ENHANCED PROFILE BODY -->
            <div class="profile-body">
                <div class="profile-container">
                    <!-- Enhanced Navigation Tabs -->
                    <div class="profile-nav-wrapper">
                        <ul class="nav nav-pills profile-nav" id="profileTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                                    <i class="fas fa-user"></i>
                                    <span>Personal Information</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab">
                                    <i class="fas fa-address-book"></i>
                                    <span>Contact Details</span>
                                </button>
                            </li>
                        </ul>
                    </div>

                    <!-- Enhanced Tab Content -->
                    <div class="tab-content profile-tab-content" id="profileTabsContent">
                        <!-- Enhanced Info Tab -->
                        <div class="tab-pane fade show active" id="info" role="tabpanel">
                            <div class="info-card">
                                <div class="card-header">
                                    <h5><i class="fas fa-id-card"></i> Personal Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div class="info-content">
                                                <label class="info-label">Full Name</label>
                                                <p class="info-value"><?= htmlspecialchars($fullName) ?></p>
                                            </div>
                                        </div>
                                        
                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fas fa-heart"></i>
                                            </div>
                                            <div class="info-content">
                                                <label class="info-label">Relationship</label>
                                                <p class="info-value"><?= htmlspecialchars($relation) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Enhanced Contact Tab -->
                        <div class="tab-pane fade" id="contact" role="tabpanel">
                            <div class="info-card">
                                <div class="card-header">
                                    <h5><i class="fas fa-address-book"></i> Contact Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                            <div class="info-content">
                                                <label class="info-label">Email Address</label>
                                                <p class="info-value"><?= htmlspecialchars($parentEmail) ?></p>
                                                <a href="mailto:<?= htmlspecialchars($parentEmail) ?>" class="contact-action">
                                                    <i class="fas fa-external-link-alt"></i> Send Email
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                            <div class="info-content">
                                                <label class="info-label">Contact Number</label>
                                                <p class="info-value"><?= htmlspecialchars($contactNo) ?></p>
                                                <a href="tel:<?= htmlspecialchars($contactNo) ?>" class="contact-action">
                                                    <i class="fas fa-phone-alt"></i> Call Now
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-toggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });

        // Avatar popup functions
        function togglePopup(event) {
    if (event) event.stopPropagation(); // Prevent immediate closing
    const popup = document.getElementById("avatarPopup");
    popup.style.display = (popup.style.display === "block") ? "none" : "block";
}


        document.addEventListener("click", function(event) {
            const popup = document.getElementById("avatarPopup");
            const pic = document.getElementById("profilePic");
            if (!popup.contains(event.target) && !pic.contains(event.target)) {
                popup.style.display = "none";
            }
        });

        // Enhanced interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to info items
            const infoItems = document.querySelectorAll('.info-item');
            infoItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                    this.style.boxShadow = '0 15px 35px rgba(0, 0, 0, 0.15)';
                });
                
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.1)';
                });
            });

            // Avatar option selection visual feedback
            const avatarOptions = document.querySelectorAll('.avatar-option');
            avatarOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Remove active class from all options
                    avatarOptions.forEach(opt => opt.classList.remove('active'));
                    // Add active class to clicked option
                    this.classList.add('active');
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98c4cb74713a0dcb',t:'MTc2MDA4NTQzNi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
