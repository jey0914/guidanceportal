<?php
session_start();
include("db.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];
$successMsg = $errorMsg = "";
$lastChangedAt = ""; 

// ✅ Added: Fetch password_changed_at for display (kahit hindi nagpo-post)
$stmt = $con->prepare("SELECT password_changed_at FROM form WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($lastChangedAt);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Update display name
    if (isset($_POST['newName'])) {
        $newName = trim($_POST['newName']);
        if (!empty($newName)) {
            $nameParts = explode(" ", $newName);
            $fname = $nameParts[0];
            $lname = isset($nameParts[1]) ? $nameParts[1] : "";

            $stmt = $con->prepare("UPDATE form SET fname = ?, lname = ? WHERE email = ?");
            $stmt->bind_param("sss", $fname, $lname, $email);
            $stmt->execute() ? $successMsg = "Display name updated successfully." : $errorMsg = "Failed to update name.";
        }
    }
}

// Change password with validation
if (isset($_POST['currentPass'], $_POST['newPass'], $_POST['confirmPass'])) {
    $currentPass = $_POST['currentPass'];
    $newPass = $_POST['newPass'];
    $confirmPass = $_POST['confirmPass'];

    $result = $con->prepare("SELECT pass, password_changed_at, temp_pass FROM form WHERE email = ?");
    $result->bind_param("s", $email);
    $result->execute();
    $result->store_result();
    $result->bind_result($storedPass, $lastChangedAt, $tempPass);

    if ($result->num_rows > 0 && $result->fetch()) {
        $now = new DateTime();
        $lastChangeDate = $lastChangedAt ? new DateTime($lastChangedAt) : null;

        // If user is using a temporary password, allow immediate change
        $isUsingTemp = ($tempPass && password_verify($currentPass, $tempPass));

        if (!$isUsingTemp && $lastChangeDate && $now->diff($lastChangeDate)->days < 10) {
            $errorMsg = "You can only change your password again after 10 days.";
        } elseif (!password_verify($currentPass, $storedPass) && !$isUsingTemp) {
            $errorMsg = "Current password is incorrect.";
        } elseif ($newPass !== $confirmPass) {
            $errorMsg = "New password and confirm password do not match.";
        } elseif (strlen($newPass) < 8) {
            $errorMsg = "Password must be at least 8 characters.";
        } else {
            $hashedPass = password_hash($newPass, PASSWORD_DEFAULT);
            $update = $con->prepare("UPDATE form SET pass = ?, password_changed_at = NOW(), temp_pass = NULL WHERE email = ?");
            $update->bind_param("ss", $hashedPass, $email);
            $update->execute() ? $successMsg = "Password changed successfully." : $errorMsg = "Failed to change password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Settings</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    
    
    .settings-card {
      background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
      border: 1px solid #e2e8f0;
      transition: all 0.3s ease;
    }
    
    .tab-button {
      transition: all 0.3s ease;
      position: relative;
    }
    
    .tab-button.active {
      background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
      color: white;
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .tab-button:not(.active):hover {
      background: #f1f5f9;
      color: #3b82f6;
    }
    
    .form-input {
      transition: all 0.3s ease;
      border: 2px solid #e2e8f0;
    }
    
    .form-input:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
      outline: none;
    }
    
    .password-strength {
      height: 4px;
      border-radius: 2px;
      background: #e2e8f0;
      overflow: hidden;
      margin-top: 8px;
    }
    
    .password-strength-bar {
      height: 100%;
      transition: all 0.3s ease;
      border-radius: 2px;
    }
    
    .strength-weak { background: #ef4444; width: 25%; }
    .strength-fair { background: #f59e0b; width: 50%; }
    .strength-good { background: #10b981; width: 75%; }
    .strength-strong { background: #059669; width: 100%; }
    
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .animate-fadeInUp {
      animation: fadeInUp 0.6s ease-out;
    }
    
    .notification-toast {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
      transform: translateX(400px);
      transition: transform 0.3s ease;
    }
    
    .notification-toast.show {
      transform: translateX(0);
    }
    
    .tab-content {
      display: none;
      animation: fadeInUp 0.4s ease-out;
    }
    
    .tab-content.active {
      display: block;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen">

  <!-- Sidebar -->
  <div class="sidebar fixed left-0 top-0 h-full w-64 text-white z-50">
    <div class="p-6">
      <div class="flex items-center space-x-3 mb-8">
        <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center">
          <i class="fas fa-graduation-cap text-white"></i>
        </div>
        <div>
          <h2 class="text-xl font-bold">Guidance Office</h2>
          <p class="text-sm text-slate-300">Student Portal</p>
        </div>
      </div>
      
      <nav class="space-y-2">
        <a href="dashboard.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl">
          <i class="fas fa-home w-5"></i>
          <span class="font-medium">Dashboard</span>
        </a>
        
        <?php if (stripos($_SESSION['strand_course'], 'SHS') !== false || stripos($_SESSION['year_level'], 'Grade') !== false): ?>
        <a href="student_records.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl">
          <i class="fas fa-clipboard-check w-5"></i>
          <span class="font-medium">Attendance</span>
        </a>
        <?php endif; ?>
        
        <a href="appointments.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl">
          <i class="fas fa-calendar-check w-5"></i>
          <span class="font-medium">Appointments</span>
        </a>
        
        <a href="student_reports.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl">
          <i class="fas fa-file-alt w-5"></i>
          <span class="font-medium">Reports</span>
        </a>
        
        <a href="settings.php" class="sidebar-link active flex items-center space-x-3 px-4 py-3 rounded-xl">
          <i class="fas fa-cog w-5"></i>
          <span class="font-medium">Settings</span>
        </a>
      </nav>
    </div>
  </div>

  <!-- Main Content -->
  <div class="ml-64 min-h-screen">
    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-md shadow-lg sticky top-0 z-40">
      <div class="px-8 py-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-800">⚙️ Settings</h1>
            <p class="text-gray-600 mt-1">Manage your account preferences and security</p>
          </div>
          
          <div class="flex items-center space-x-4">
            <div class="bg-blue-100 text-blue-700 px-4 py-2 rounded-xl font-medium">
        
            </div>
            
            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
              <i class="fas fa-user text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </header>

    <div class="p-8">
      <!-- Success/Error Messages -->
      <?php if ($successMsg): ?>
        <div class="notification-toast show bg-green-500 text-white px-6 py-4 rounded-xl shadow-2xl mb-8" id="successMessage">
          <div class="flex items-center space-x-3">
            <i class="fas fa-check-circle text-xl"></i>
            <div>
              <div class="font-semibold">Success!</div>
              <div class="text-sm opacity-90"><?php echo $successMsg; ?></div>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <?php if ($errorMsg): ?>
        <div class="notification-toast show bg-red-500 text-white px-6 py-4 rounded-xl shadow-2xl mb-8" id="errorMessage">
          <div class="flex items-center space-x-3">
            <i class="fas fa-exclamation-circle text-xl"></i>
            <div>
              <div class="font-semibold">Error!</div>
              <div class="text-sm opacity-90"><?php echo $errorMsg; ?></div>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <div class="max-w-4xl mx-auto">
        <!-- Tab Navigation -->
        <div class="settings-card p-2 mb-8 animate-fadeInUp">
          <div class="flex space-x-2">
            <button class="tab-button active flex-1 px-6 py-3 rounded-xl font-medium transition-all" onclick="switchTab('profileTab', this)">
              <i class="fas fa-user mr-2"></i>
              Profile Info
            </button>
            <button class="tab-button flex-1 px-6 py-3 rounded-xl font-medium transition-all" onclick="switchTab('passwordTab', this)">
              <i class="fas fa-lock mr-2"></i>
              Change Password
            </button>
            <button class="tab-button flex-1 px-6 py-3 rounded-xl font-medium transition-all" onclick="switchTab('securityTab', this)">
              <i class="fas fa-shield-alt mr-2"></i>
              Security Settings
            </button>
          </div>
        </div>

        <!-- Profile Tab -->
        <div id="profileTab" class="tab-content active">
          <div class="settings-card p-8 animate-fadeInUp">
            <div class="flex items-center space-x-3 mb-6">
              <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-user text-blue-600 text-xl"></i>
              </div>
              <div>
                <h2 class="text-2xl font-bold text-gray-800">Profile Information</h2>
                <p class="text-gray-600">Update your personal details</p>
              </div>
            </div>

            <form method="POST" action="#" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">New Display Name</label>
                  <input type="text" name="firstName" class="form-input w-full px-4 py-3 rounded-xl bg-white" placeholder="Enter first name">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                  <input type="text" name="newName" class="form-input w-full px-4 py-3 rounded-xl bg-white" placeholder="Enter last name">
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" class="form-input w-full px-4 py-3 rounded-xl bg-white" 
                  value="<?= htmlspecialchars($_SESSION['email']) ?>" readonly>
                <p class="text-sm text-gray-500 mt-1">Student ID cannot be changed</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Student ID</label>
                <input type="text" class="form-input w-full px-4 py-3 rounded-xl bg-gray-100" 
                  value="<?= htmlspecialchars($_SESSION['student_no']) ?>" readonly>
                <p class="text-sm text-gray-500 mt-1">Student ID cannot be changed</p>
              </div>

              <div class="flex items-center justify-end pt-6">
                <button type="submit" 
                        class="bg-gradient-to-r from-blue-600 to-purple-700 text-white px-8 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                  <i class="fas fa-save mr-2"></i>
                  Update Profile
                </button>
              </div>
            </form>
          </div>
        </div>


        <!-- Password Tab -->
        <div id="passwordTab" class="tab-content">
          <div class="settings-card p-8 animate-fadeInUp">
            <div class="flex items-center space-x-3 mb-6">
              <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-lock text-orange-600 text-xl"></i>
              </div>
              <div>
                <h2 class="text-2xl font-bold text-gray-800">Change Password</h2>
                <p class="text-gray-600">Update your account password</p>
              </div>
            </div>
            
    <!-- ✅ LAST PASSWORD CHANGED INFO (moved inside settings-card) -->
    <?php if (!empty($lastChangedAt)): ?>
      <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
        <div class="flex items-center space-x-3">
          <i class="fas fa-clock text-gray-600 text-lg"></i>
          <p class="text-sm text-gray-700">
            <strong>Last changed:</strong>
            <?= date("j M Y", strtotime($lastChangedAt)) ?>
          </p>
        </div>
      </div>
    <?php endif; ?>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
              <div class="flex items-center space-x-2">
                <i class="fas fa-info-circle text-yellow-600"></i>
                <p class="text-sm text-yellow-800">
                  <strong>Security Note:</strong> Password must be at least 8 characters. You can only change it again after 10 days.
                </p>
              </div>
            </div>

            <form method="POST" action="#" onsubmit="return validatePassword()" class="space-y-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                <div class="relative">
                  <input type="password" id="currentPass" name="currentPass" class="form-input w-full px-4 py-3 pr-12 rounded-xl bg-white" required>
                  <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700" onclick="togglePassword('currentPass', this)">
                    <i class="fas fa-eye"></i>
                  </button>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                <div class="relative">
                  <input type="password" id="newPass" name="newPass" class="form-input w-full px-4 py-3 pr-12 rounded-xl bg-white" required minlength="8" oninput="checkPasswordStrength()">
                  <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700" onclick="togglePassword('newPass', this)">
                    <i class="fas fa-eye"></i>
                  </button>
                </div>
                <div class="password-strength">
                  <div id="strengthBar" class="password-strength-bar"></div>
                </div>
                <p id="strengthText" class="text-sm text-gray-500 mt-1">Password strength will appear here</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                <div class="relative">
                  <input type="password" id="confirmPass" name="confirmPass" class="form-input w-full px-4 py-3 pr-12 rounded-xl bg-white" required>
                  <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700" onclick="togglePassword('confirmPass', this)">
                    <i class="fas fa-eye"></i>
                  </button>
                </div>
              </div>

              <p id="errorMsg" class="text-red-600 text-sm hidden"></p>

              <div class="flex items-center justify-end pt-6">
                <button type="submit" 
                        class="bg-gradient-to-r from-orange-600 to-red-700 text-white px-8 py-3 rounded-xl font-semibold hover:from-orange-700 hover:to-red-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                  <i class="fas fa-key mr-2"></i>
                  Change Password
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Security Tab -->
        <div id="securityTab" class="tab-content">
          <div class="space-y-6">
            
            <!-- Two-Factor Authentication -->
            <div class="settings-card p-8 animate-fadeInUp">
              <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                  <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-mobile-alt text-green-600 text-xl"></i>
                  </div>
                  <div>
                    <h3 class="text-xl font-bold text-gray-800">Two-Factor Authentication</h3>
                    <p class="text-gray-600">Add an extra layer of security to your account</p>
                  </div>
                </div>
                <div class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-medium">
                  Disabled
                </div>
              </div>
              <p class="text-gray-600 mb-4">Protect your account with an additional security step when signing in.</p>
              <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-medium transition-colors">
                <i class="fas fa-shield-alt mr-2"></i>
                Enable 2FA
              </button>
            </div>

            <!-- Login Activity -->
            <div class="settings-card p-8 animate-fadeInUp" style="animation-delay: 0.1s;">
              <div class="flex items-center space-x-3 mb-6">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                  <i class="fas fa-history text-blue-600 text-xl"></i>
                </div>
                <div>
                  <h3 class="text-xl font-bold text-gray-800">Login Activity</h3>
                  <p class="text-gray-600">Monitor recent login sessions and devices</p>
                </div>
              </div>
              
              <div class="space-y-4 mb-6">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                  <div class="flex items-center space-x-3">
                    <i class="fas fa-desktop text-gray-600"></i>
                    <div>
                      <p class="font-medium text-gray-800">Current Session</p>
                      <p class="text-sm text-gray-600">Windows • Chrome • Philippines</p>
                    </div>
                  </div>
                  <span class="text-green-600 text-sm font-medium">Active Now</span>
                </div>
              </div>
              

              <a href="login_activity.php"><button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium transition-colors">
                <i class="fas fa-list mr-2"></i>
                View All Activity
              </button>
            </div>

            <!-- Email Notifications -->
            <div class="settings-card p-8 animate-fadeInUp" style="animation-delay: 0.2s;">
              <div class="flex items-center space-x-3 mb-6">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                  <i class="fas fa-bell text-purple-600 text-xl"></i>
                </div>
                <div>
                  <h3 class="text-xl font-bold text-gray-800">Notification Preferences</h3>
                  <p class="text-gray-600">Control how you receive updates and alerts</p>
                </div>
              </div>
              
              <div class="space-y-4 mb-6">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="font-medium text-gray-800">Security Alerts</p>
                    <p class="text-sm text-gray-600">Get notified about important security events</p>
                  </div>
                  <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                  </label>
                </div>
                
                <div class="flex items-center justify-between">
                  <div>
                    <p class="font-medium text-gray-800">Appointment Reminders</p>
                    <p class="text-sm text-gray-600">Receive reminders about upcoming appointments</p>
                  </div>
                  <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                  </label>
                </div>
              </div>
              
              <button class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-colors">
                <i class="fas fa-cog mr-2"></i>
                Manage All Notifications
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Tab switching functionality
    function switchTab(tabId, button) {
      // Hide all tab contents
      document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
      });
      
      // Remove active class from all buttons
      document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
      });
      
      // Show selected tab and activate button
      document.getElementById(tabId).classList.add('active');
      button.classList.add('active');
    }

    // Password visibility toggle
    function togglePassword(inputId, button) {
      const input = document.getElementById(inputId);
      const icon = button.querySelector('i');
      
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    }

    // Password strength checker
    function checkPasswordStrength() {
      const password = document.getElementById('newPass').value;
      const strengthBar = document.getElementById('strengthBar');
      const strengthText = document.getElementById('strengthText');
      
      let strength = 0;
      let feedback = '';
      
      if (password.length >= 8) strength++;
      if (password.match(/[a-z]/)) strength++;
      if (password.match(/[A-Z]/)) strength++;
      if (password.match(/[0-9]/)) strength++;
      if (password.match(/[^a-zA-Z0-9]/)) strength++;
      
      strengthBar.className = 'password-strength-bar';
      
      switch (strength) {
        case 0:
        case 1:
          strengthBar.classList.add('strength-weak');
          feedback = 'Weak password';
          break;
        case 2:
        case 3:
          strengthBar.classList.add('strength-fair');
          feedback = 'Fair password';
          break;
        case 4:
          strengthBar.classList.add('strength-good');
          feedback = 'Good password';
          break;
        case 5:
          strengthBar.classList.add('strength-strong');
          feedback = 'Strong password';
          break;
      }
      
      strengthText.textContent = feedback;
    }

    // Password validation
    function validatePassword() {
      const newPass = document.getElementById('newPass').value;
      const confirmPass = document.getElementById('confirmPass').value;
      const errorMsg = document.getElementById('errorMsg');
      
      if (newPass !== confirmPass) {
        errorMsg.textContent = 'Passwords do not match!';
        errorMsg.classList.remove('hidden');
        return false;
      }
      
      if (newPass.length < 8) {
        errorMsg.textContent = 'Password must be at least 8 characters long!';
        errorMsg.classList.remove('hidden');
        return false;
      }
      
      errorMsg.classList.add('hidden');
      return true;
    }

    // Hide notifications after 5 seconds
    setTimeout(() => {
      const notifications = document.querySelectorAll('.notification-toast');
      notifications.forEach(notification => {
        notification.classList.remove('show');
      });
    }, 5000);
  </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'987cdc6b32b50dcd',t:'MTc1OTMzMTE1Ni4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
