<?php
session_start();
include("db.php");

$errorMsg = ""; // For displaying error below form

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $identifier = trim($_POST['mail']);
    $password = trim($_POST['password']);

    if (!empty($identifier) && !empty($password)) {
        if (ctype_digit($identifier)) {
            $short_no = ltrim($identifier, '0');
            $short_no = substr($short_no, -6);
            $stmt = $con->prepare("SELECT * FROM form WHERE RIGHT(TRIM(LEADING '0' FROM student_no), 6) = ? LIMIT 1");
            $stmt->bind_param("s", $short_no);
        } else {
            if (preg_match('/^(.+)\.(\d+)@guidanceportal\.rosario\.sti\.edu\.ph$/', $identifier, $matches)) {
                $raw_lname = $matches[1];
                $orig_no = $matches[2];
                $short_no = ltrim($orig_no, '0');
                $short_no = substr($short_no, -6);
                $identifier = $raw_lname . "." . $short_no . "@guidanceportal.rosario.sti.edu.ph";
            }
            $stmt = $con->prepare("SELECT * FROM form WHERE email = ? LIMIT 1");
            $stmt->bind_param("s", $identifier);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $expected_default = strtolower($user['lname']) . date("Ymd", strtotime($user['bday']));
            $tempPassUsed = false;

            if (password_verify($password, $user['pass'])) {
                $validPassword = true;
            } elseif (!empty($user['temp_pass']) && password_verify($password, $user['temp_pass'])) {
                $validPassword = true;
                $tempPassUsed = true;
            } elseif ($password === $expected_default) {
                $validPassword = true;
                $tempPassUsed = true;
            } else {
                $validPassword = false;
            }

            if ($validPassword) {
                // ✅ Login success: set session
                $_SESSION['email'] = $user['email'];
                $_SESSION['fname'] = $user['fname'];
                $_SESSION['lname'] = $user['lname'];
                $_SESSION['student_no'] = $user['student_no'];
                $_SESSION['user_id'] = $user['id']; // optional if table supports
                $_SESSION['session_id'] = session_id(); // store current session id
                
                if ($tempPassUsed) {
                    $_SESSION['temp_pass_used'] = true;
                }

                // 🧩 LOGIN ACTIVITY TRACKING
                $ip = $_SERVER['REMOTE_ADDR'];
                $userAgent = $_SERVER['HTTP_USER_AGENT'];

                // Detect Browser
                if (strpos($userAgent, 'Chrome') !== false) {
                    $browser = 'Chrome';
                } elseif (strpos($userAgent, 'Firefox') !== false) {
                    $browser = 'Firefox';
                } elseif (strpos($userAgent, 'Safari') !== false) {
                    $browser = 'Safari';
                } else {
                    $browser = 'Other';
                }

                // Detect Device
                if (stripos($userAgent, 'Windows') !== false) {
                    $device = 'Windows';
                } elseif (stripos($userAgent, 'Android') !== false) {
                    $device = 'Android';
                } elseif (stripos($userAgent, 'iPhone') !== false) {
                    $device = 'iPhone';
                } elseif (stripos($userAgent, 'Mac') !== false) {
                    $device = 'MacOS';
                } else {
                    $device = 'Unknown';
                }

                // Detect Location using free API (ip-api)
                $details = @json_decode(file_get_contents("http://ip-api.com/json/{$ip}"));
                $location = ($details && $details->status == "success") ? "{$details->city}, {$details->country}" : "Unknown";

                // Insert login activity (matches your table columns)
                $log_stmt = $con->prepare("INSERT INTO login_activity (user_email, device, browser, location, ip_address, is_active)
                                           VALUES (?, ?, ?, ?, ?, ?)");
                if (!$log_stmt) {
                    die("Prepare failed: " . $con->error);
                }

                $is_active = 1;
                $log_stmt->bind_param("sssssi", $_SESSION['email'], $device, $browser, $location, $ip, $is_active);
                $log_stmt->execute();
                $log_stmt->close();

                header("Location: dashboard.php");
                exit();
            } else {
                $errorMsg = "Incorrect password.";
            }
        } else {
            $errorMsg = "Account not found. Please contact Guidance Office.";
        }

    } else {
        $errorMsg = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login | Guidance Portal</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }
    
    /* Custom animations */
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
    
    @keyframes slideInLeft {
      from {
        opacity: 0;
        transform: translateX(-50px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }
    
    @keyframes float {
      0%, 100% {
        transform: translateY(0px);
      }
      50% {
        transform: translateY(-10px);
      }
    }
    
    @keyframes pulse {
      0%, 100% {
        transform: scale(1);
      }
      50% {
        transform: scale(1.05);
      }
    }
    
    @keyframes shimmer {
      0% {
        background-position: -200px 0;
      }
      100% {
        background-position: calc(200px + 100%) 0;
      }
    }
    
    .animate-fadeInUp {
      animation: fadeInUp 0.8s ease-out;
    }
    
    .animate-slideInLeft {
      animation: slideInLeft 0.8s ease-out;
    }
    
    .animate-float {
      animation: float 3s ease-in-out infinite;
    }
    
    .animate-pulse-custom {
      animation: pulse 2s ease-in-out infinite;
    }
    
    .animate-shimmer {
      animation: shimmer 2s linear infinite;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      background-size: 200px 100%;
    }
     /* Gradient backgrounds */
    .gradient-bg {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    }
    
    .gradient-text {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    .login-gradient {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .form-gradient {
      background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    }
    
    /* Glass morphism effect */
    .glass {
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.18);
    }
    
    /* Input field enhancements */
    .input-group {
      position: relative;
    }
    .input-field {
      transition: all 0.3s ease;
      border: 2px solid transparent;
      background: rgba(255, 255, 255, 0.9);
    }
    
    .input-field:focus {
      border-color: #667eea;
      background: rgba(255, 255, 255, 1);
      box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }
    
    .input-label {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      background: white;
      padding: 0 8px;
      color: #6b7280;
      transition: all 0.3s ease;
      pointer-events: none;
    }
    
    .input-field:focus + .input-label,
    .input-field:not(:placeholder-shown) + .input-label {
      top: 0;
      font-size: 12px;
      color: #667eea;
      font-weight: 500;
    }
    
    /* Button enhancements */
    .login-btn {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .login-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }
    
    .login-btn:active {
      transform: translateY(0);
    }
    
    .login-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }
    
    .login-btn:hover::before {
      left: 100%;
    }
    
    /* Mobile menu animation */
    .mobile-menu {
      transform: translateX(-100%);
      transition: transform 0.3s ease;
    }
    .mobile-menu.active {
      transform: translateX(0);
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }
    
    ::-webkit-scrollbar-track {
      background: #f1f5f9;
    }
    
    ::-webkit-scrollbar-thumb {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
      background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    }
    
    /* Loading animation */
    .loading-spinner {
      display: none;
      width: 20px;
      height: 20px;
      border: 2px solid #ffffff;
      border-top: 2px solid transparent;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }
     @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    /* Error message styling */
    .error-message {
      background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
      border: 1px solid #f87171;
      animation: shake 0.5s ease-in-out;
    }
    
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      75% { transform: translateX(5px); }
    }
    
    /* Success message styling */
    .success-message {
      background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
      border: 1px solid #34d399;
    }
    
    /* Floating elements */
    .floating-element {
      position: absolute;
      opacity: 0.1;
      pointer-events: none;
    }
    
    .floating-1 {
      top: 10%;
      left: 10%;
      animation: float 6s ease-in-out infinite;
    }
    
    .floating-2 {
      top: 20%;
      right: 15%;
      animation: float 8s ease-in-out infinite reverse;
    }
    
    .floating-3 {
      bottom: 15%;
      left: 20%;
      animation: float 7s ease-in-out infinite;
      animation-delay: 2s;
    }
    
    .floating-4 {
      bottom: 25%;
      right: 10%;
      animation: float 9s ease-in-out infinite reverse;
      animation-delay: 1s;
    }

    /* Modal transitions */
    .modal-slide-out {
      transform: translateX(-100%);
      opacity: 0;
    }
    
    .modal-slide-in {
      transform: translateX(0);
      opacity: 1;
    }
  </style>
</head>
<body class="min-h-screen gradient-bg relative overflow-hidden">
<!-- Floating Background Elements -->
  <div class="floating-element floating-1 w-32 h-32 bg-white rounded-full"></div>
  <div class="floating-element floating-2 w-24 h-24 bg-white rounded-full"></div>
  <div class="floating-element floating-3 w-40 h-40 bg-white rounded-full"></div>
  <div class="floating-element floating-4 w-28 h-28 bg-white rounded-full"></div>

  <!-- Header -->
  <header class="glass fixed top-0 left-0 w-full z-50 border-b border-white/20">
    <div class="container mx-auto flex justify-between items-center py-4 px-6">
      <div class="flex items-center space-x-3 animate-slideInLeft">
        <div class="w-10 h-10 login-gradient rounded-xl flex items-center justify-center shadow-lg">
          <span class="text-white font-bold text-xl">🎯</span>
        </div>
        <h1 class="text-2xl font-bold text-white">Guidance Portal</h1>
      </div>
      
      <!-- Desktop Navigation -->
      <nav class="hidden md:flex space-x-6 animate-fadeInUp">
        <a href="index.php" class="text-white/90 hover:text-white transition-colors font-medium relative group px-4 py-2 rounded-lg hover:bg-white/10">
          Home
          <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-white transition-all group-hover:w-full"></span>
        </a>
        <a href="about.php" class="text-white/90 hover:text-white transition-colors font-medium relative group px-4 py-2 rounded-lg hover:bg-white/10">
          About
          <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-white transition-all group-hover:w-full"></span>
        </a>
        <a href="contact.php" class="text-white/90 hover:text-white transition-colors font-medium relative group px-4 py-2 rounded-lg hover:bg-white/10">
          Contact
          <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-white transition-all group-hover:w-full"></span>
        </a>
        <a href="help-center.php" class="text-white/90 hover:text-white transition-colors font-medium relative group px-4 py-2 rounded-lg hover:bg-white/10">
          Help
          <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-white transition-all group-hover:w-full"></span>
        </a>
      </nav>
      
      <!-- Mobile Menu Button -->
      <button id="mobileMenuBtn" class="md:hidden text-white hover:text-white/80 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
      </button>
    </div>
    
    <!-- Mobile Menu -->
    <div id="mobileMenu" class="mobile-menu fixed top-0 left-0 w-full h-full glass z-40 md:hidden">
      <div class="flex flex-col p-6 space-y-6 mt-20">
        <a href="index.php" class="text-xl font-medium text-white hover:text-white/80 transition-colors">Home</a>
        <a href="about.php" class="text-xl font-medium text-white hover:text-white/80 transition-colors">About</a>
        <a href="contact.php" class="text-xl font-medium text-white hover:text-white/80 transition-colors">Contact</a>
        <a href="help-center.php" class="text-xl font-medium text-white hover:text-white/80 transition-colors">Help</a>
      </div>
    </div>
  </header>

  <!-- Main Content - Split Screen Layout -->
  <div class="flex min-h-screen pt-20 relative z-10">
    
    <!-- Left Side - Welcome Section -->
    <div class="hidden lg:flex lg:w-1/2 items-center justify-center p-12 relative">
      <div class="max-w-lg text-center animate-slideInLeft">
        <div class="w-48 h-48 mx-auto mb-8 animate-float">
          <svg viewBox="0 0 400 400" class="w-full h-full">
            <!-- Background gradient circle -->
            <defs>
              <linearGradient id="bgGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:rgba(102,126,234,0.1);stop-opacity:1" />
                <stop offset="100%" style="stop-color:rgba(118,75,162,0.1);stop-opacity:1" />
              </linearGradient>
              <linearGradient id="studentGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#ffffff;stop-opacity:0.95" />
                <stop offset="100%" style="stop-color:#f8fafc;stop-opacity:0.95" />
              </linearGradient>
              <linearGradient id="capGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />
              </linearGradient>
              <linearGradient id="bookGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#1d4ed8;stop-opacity:1" />
              </linearGradient>
            </defs>
            
            <!-- Background circle with subtle border -->
            <circle cx="200" cy="200" r="180" fill="url(#bgGrad)" stroke="rgba(255,255,255,0.2)" stroke-width="3"/>
            
            <!-- Floating academic elements -->
            <circle cx="120" cy="120" r="8" fill="rgba(255,255,255,0.3)" opacity="0.7"/>
            <circle cx="300" cy="140" r="6" fill="rgba(255,255,255,0.3)" opacity="0.5"/>
            <circle cx="320" cy="280" r="10" fill="rgba(255,255,255,0.3)" opacity="0.6"/>
            <circle cx="80" cy="300" r="7" fill="rgba(255,255,255,0.3)" opacity="0.4"/>
            
            <!-- Student figure - head -->
            <circle cx="200" cy="140" r="35" fill="url(#studentGrad)" stroke="rgba(102,126,234,0.3)" stroke-width="2"/>
            
            <!-- Student figure - body -->
            <ellipse cx="200" cy="220" rx="45" ry="55" fill="url(#studentGrad)" stroke="rgba(102,126,234,0.3)" stroke-width="2"/>
            
            <!-- Arms -->
            <ellipse cx="160" cy="200" rx="15" ry="35" fill="url(#studentGrad)" stroke="rgba(102,126,234,0.3)" stroke-width="1" transform="rotate(-20 160 200)"/>
            <ellipse cx="240" cy="200" rx="15" ry="35" fill="url(#studentGrad)" stroke="rgba(102,126,234,0.3)" stroke-width="1" transform="rotate(20 240 200)"/>
            
            <!-- Book in hands -->
            <rect x="175" y="240" width="50" height="35" rx="4" fill="url(#bookGrad)" stroke="rgba(29,78,216,0.5)" stroke-width="2"/>
            <rect x="175" y="240" width="50" height="35" rx="4" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1"/>
            
            <!-- Book pages -->
            <line x1="185" y1="250" x2="215" y2="250" stroke="rgba(255,255,255,0.8)" stroke-width="1.5"/>
            <line x1="185" y1="255" x2="210" y2="255" stroke="rgba(255,255,255,0.8)" stroke-width="1.5"/>
            <line x1="185" y1="260" x2="205" y2="260" stroke="rgba(255,255,255,0.8)" stroke-width="1.5"/>
            <line x1="185" y1="265" x2="215" y2="265" stroke="rgba(255,255,255,0.8)" stroke-width="1.5"/>
            
            <!-- Book spine -->
            <line x1="200" y1="240" x2="200" y2="275" stroke="rgba(29,78,216,0.8)" stroke-width="2"/>
            
            <!-- Graduation cap -->
            <path d="M150 130 L250 130 L260 120 L140 120 Z" fill="url(#capGrad)" stroke="rgba(102,126,234,0.5)" stroke-width="2"/>
            
            <!-- Cap button -->
            <circle cx="200" cy="125" r="4" fill="rgba(118,75,162,0.8)"/>
            
            <!-- Tassel -->
            <circle cx="260" cy="125" r="5" fill="url(#capGrad)"/>
            <path d="M260 130 Q265 140 260 150 Q255 145 260 135" fill="url(#capGrad)" opacity="0.8"/>
            
            <!-- Academic symbols floating around -->
            <g opacity="0.6">
              <!-- Diploma -->
              <rect x="300" y="200" width="20" height="15" rx="2" fill="rgba(255,255,255,0.7)" transform="rotate(15 310 207)"/>
              <circle cx="315" cy="207" r="3" fill="none" stroke="rgba(102,126,234,0.6)" stroke-width="1" transform="rotate(15 315 207)"/>
              
              <!-- Star -->
              <path d="M100 200 L105 210 L115 210 L107 217 L110 227 L100 220 L90 227 L93 217 L85 210 L95 210 Z" fill="rgba(255,255,255,0.5)"/>
              
              <!-- Academic achievement badge -->
              <circle cx="320" cy="160" r="12" fill="rgba(255,215,0,0.3)" stroke="rgba(255,215,0,0.6)" stroke-width="2"/>
              <path d="M315 160 L320 155 L325 160 L320 165 Z" fill="rgba(255,215,0,0.8)"/>
            </g>
            
            <!-- Subtle glow effect -->
            <circle cx="200" cy="200" r="170" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1" opacity="0.5"/>
          </svg>
        </div>
        <p class="text-xl text-white/90 mb-8 leading-relaxed">Sign in to access your guidance portal and continue your academic journey with us.</p>
        <div class="flex items-center justify-center space-x-8 text-white/80">
          <div class="text-center">
            <div class="text-2xl font-bold">24/7</div>
            <div class="text-sm">Support</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold">100%</div>
            <div class="text-sm">Secure</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold">Fast</div>
            <div class="text-sm">Access</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Side - Login Modals -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 lg:p-12">
      <div class="w-full max-w-md relative">

        <!-- Mobile Welcome (visible on small screens) -->
        <div class="lg:hidden text-center mb-8 animate-fadeInUp">
          <div class="w-32 h-32 mx-auto mb-6 animate-float">
            <svg viewBox="0 0 400 400" class="w-full h-full">
              <!-- Background gradient circle -->
              <defs>
                <linearGradient id="bgGradMobile" x1="0%" y1="0%" x2="100%" y2="100%">
                  <stop offset="0%" style="stop-color:rgba(102,126,234,0.1);stop-opacity:1" />
                  <stop offset="100%" style="stop-color:rgba(118,75,162,0.1);stop-opacity:1" />
                </linearGradient>
                <linearGradient id="studentGradMobile" x1="0%" y1="0%" x2="100%" y2="100%">
                  <stop offset="0%" style="stop-color:#ffffff;stop-opacity:0.95" />
                  <stop offset="100%" style="stop-color:#f8fafc;stop-opacity:0.95" />
                </linearGradient>
                <linearGradient id="capGradMobile" x1="0%" y1="0%" x2="100%" y2="100%">
                  <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
                  <stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />
                </linearGradient>
                <linearGradient id="bookGradMobile" x1="0%" y1="0%" x2="100%" y2="100%">
                  <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
                  <stop offset="100%" style="stop-color:#1d4ed8;stop-opacity:1" />
                </linearGradient>
              </defs>
              
              <!-- Background circle -->
              <circle cx="200" cy="200" r="180" fill="url(#bgGradMobile)" stroke="rgba(255,255,255,0.2)" stroke-width="3"/>
              
              <!-- Student figure - head -->
              <circle cx="200" cy="140" r="35" fill="url(#studentGradMobile)" stroke="rgba(102,126,234,0.3)" stroke-width="2"/>
              
              <!-- Student figure - body -->
              <ellipse cx="200" cy="220" rx="45" ry="55" fill="url(#studentGradMobile)" stroke="rgba(102,126,234,0.3)" stroke-width="2"/>
              
              <!-- Arms -->
              <ellipse cx="160" cy="200" rx="15" ry="35" fill="url(#studentGradMobile)" stroke="rgba(102,126,234,0.3)" stroke-width="1" transform="rotate(-20 160 200)"/>
              <ellipse cx="240" cy="200" rx="15" ry="35" fill="url(#studentGradMobile)" stroke="rgba(102,126,234,0.3)" stroke-width="1" transform="rotate(20 240 200)"/>
              
              <!-- Book in hands -->
              <rect x="175" y="240" width="50" height="35" rx="4" fill="url(#bookGradMobile)" stroke="rgba(29,78,216,0.5)" stroke-width="2"/>
              <line x1="185" y1="250" x2="215" y2="250" stroke="rgba(255,255,255,0.8)" stroke-width="1.5"/>
              <line x1="185" y1="255" x2="210" y2="255" stroke="rgba(255,255,255,0.8)" stroke-width="1.5"/>
              <line x1="185" y1="260" x2="205" y2="260" stroke="rgba(255,255,255,0.8)" stroke-width="1.5"/>
              
              <!-- Graduation cap -->
              <path d="M150 130 L250 130 L260 120 L140 120 Z" fill="url(#capGradMobile)" stroke="rgba(102,126,234,0.5)" stroke-width="2"/>
              <circle cx="200" cy="125" r="4" fill="rgba(118,75,162,0.8)"/>
              
              <!-- Tassel -->
              <circle cx="260" cy="125" r="5" fill="url(#capGradMobile)"/>
              <path d="M260 130 Q265 140 260 150 Q255 145 260 135" fill="url(#capGradMobile)" opacity="0.8"/>
            </svg>
          </div>
          <p class="text-white/80 text-lg">Sign in to access your guidance portal</p>
        </div>

        <!-- Step 1: Username Modal -->
        <div id="usernameModal" class="form-gradient rounded-2xl shadow-2xl p-8 lg:p-10 border border-white/20 animate-fadeInUp transition-all duration-500" style="animation-delay: 0.2s; box-shadow: 0 25px 50px rgba(0,0,0,0.25);">
          
          <!-- Progress Indicator -->
          <div class="flex items-center justify-center mb-8">
            <div class="flex items-center space-x-2">
              <div class="w-3 h-3 login-gradient rounded-full animate-pulse"></div>
              <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
              <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
              <div class="w-16 h-1 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full login-gradient rounded-full w-1/2 animate-pulse"></div>
              </div>
            </div>
          </div>

          <!-- Form Header -->
          <div class="text-center mb-8">
            <h3 class="text-2xl font-bold gradient-text mb-2">Student Login</h3>
            <p class="text-gray-600">Enter your student number or email</p>
          </div>

          <!-- Error Message -->
          <?php if (!empty($errorMsg)): ?>
            <div class="error-message rounded-xl p-4 mb-6 text-red-700 text-center font-medium">
              <div class="flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span><?php echo $errorMsg; ?></span>
              </div>
            </div>
          <?php endif; ?>

          <!-- Username Form -->
          <form id="usernameForm" class="space-y-6">
            
            <!-- Student ID/Email Field -->
            <div class="input-group">
              <input 
                type="text" 
                name="mail" 
                id="usernameInput"
                placeholder=" "
                class="input-field w-full px-4 py-4 rounded-xl border-2 focus:outline-none text-gray-800 font-medium"
                required
              >
              <label for="usernameInput" class="input-label">Student No. or Email</label>
              <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
              </div>
            </div>

            <!-- Continue Button -->
            <button 
              type="submit" 
              class="login-btn w-full py-4 px-6 rounded-xl text-white font-semibold text-lg shadow-lg relative overflow-hidden"
              id="continueButton"
            >
              <span>Continue</span>
            </button>

          </form>

          <!-- Help Options -->
          <div class="text-center mt-8">
            <p class="text-gray-600 text-sm mb-4">Need help accessing your account?</p>
            <div class="flex justify-center space-x-6 text-sm">
              <a href="help-center.php" class="text-blue-600 hover:text-blue-800 font-medium transition-colors flex items-center space-x-1">
                <span>📞</span>
                <span>Contact Support</span>
              </a>
              <a href="faq.php" class="text-blue-600 hover:text-blue-800 font-medium transition-colors flex items-center space-x-1">
                <span>❓</span>
                <span>View FAQ</span>
              </a>
            </div>
          </div>

        </div>

        <!-- Step 2: Password Modal (Hidden Initially) -->
        <div id="passwordModal" class="form-gradient rounded-2xl shadow-2xl p-8 lg:p-10 border border-white/20 transition-all duration-500 transform translate-x-full opacity-0 absolute inset-0" style="box-shadow: 0 25px 50px rgba(0,0,0,0.25);">
          
          <!-- Progress Indicator -->
          <div class="flex items-center justify-center mb-8">
            <div class="flex items-center space-x-2">
              <div class="w-3 h-3 bg-green-500 rounded-full"></div>
              <div class="w-3 h-3 login-gradient rounded-full animate-pulse"></div>
              <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
              <div class="w-16 h-1 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full login-gradient rounded-full w-full animate-pulse"></div>
              </div>
            </div>
          </div>

          <!-- Form Header -->
          <div class="text-center mb-8">
            <h3 class="text-2xl font-bold gradient-text mb-2">Enter Password</h3>
            <p class="text-gray-600">Welcome back, <span id="displayUsername" class="font-semibold text-blue-600"></span></p>
          </div>

          <!-- Main Login Form (Hidden, for PHP submission) -->
          <form method="POST" id="loginForm" class="hidden">
            <input type="hidden" name="mail" id="hiddenMail">
            <input type="hidden" name="password" id="hiddenPassword">
          </form>

          <!-- Password Form -->
          <form id="passwordForm" class="space-y-6">
            
            <!-- Password Field -->
            <div class="input-group">
              <input 
                type="password" 
                name="password" 
                id="passwordInput"
                placeholder=" "
                class="input-field w-full px-4 py-4 rounded-xl border-2 focus:outline-none text-gray-800 font-medium pr-12"
                required
              >
              <label for="passwordInput" class="input-label">Password</label>
              <button 
                type="button" 
                id="togglePassword"
                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eyeIcon">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
              </button>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between text-sm">
              <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <span class="text-gray-600">Remember me</span>
              </label>
              <a href="forgot-password.php" class="text-blue-600 hover:text-blue-800 font-medium transition-colors">
                Forgot password?
              </a>
            </div>

            <!-- Login Button -->
            <button 
              type="submit" 
              class="login-btn w-full py-4 px-6 rounded-xl text-white font-semibold text-lg shadow-lg relative overflow-hidden"
              id="loginButton"
            >
              <span id="buttonText">Sign In to Portal</span>
              <div class="loading-spinner" id="loadingSpinner"></div>
            </button>

            <!-- Back Button -->
            <button 
              type="button" 
              id="backButton"
              class="w-full py-3 px-6 rounded-xl text-gray-600 font-medium border-2 border-gray-300 hover:border-gray-400 transition-colors"
            >
              ← Back to Username
            </button>

          </form>

        </div>

      </div>
    </div>
  </div>

<script>
    // Mobile menu functionality
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');

    mobileMenuBtn.addEventListener('click', () => {
      mobileMenu.classList.toggle('active');
    });

    // Close mobile menu when clicking on links
    const mobileLinks = mobileMenu.querySelectorAll('a');
    mobileLinks.forEach(link => {
      link.addEventListener('click', () => {
        mobileMenu.classList.remove('active');
      });
    });

    // Two-step modal functionality
    const usernameModal = document.getElementById('usernameModal');
    const passwordModal = document.getElementById('passwordModal');
    const usernameForm = document.getElementById('usernameForm');
    const passwordForm = document.getElementById('passwordForm');
    const usernameInput = document.getElementById('usernameInput');
    const passwordInput = document.getElementById('passwordInput');
    const displayUsername = document.getElementById('displayUsername');
    const backButton = document.getElementById('backButton');
    const continueButton = document.getElementById('continueButton');
    const loginButton = document.getElementById('loginButton');
    const buttonText = document.getElementById('buttonText');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const loginForm = document.getElementById('loginForm');
    const hiddenMail = document.getElementById('hiddenMail');
    const hiddenPassword = document.getElementById('hiddenPassword');

    // Step 1: Username submission
    usernameForm.addEventListener('submit', (e) => {
      e.preventDefault();
      
      const username = usernameInput.value.trim();
      if (username) {
        // Show loading state on continue button
        continueButton.disabled = true;
        continueButton.innerHTML = '<div class="loading-spinner inline-block"></div> Checking...';
        
        setTimeout(() => {
          // Animate to password modal
          usernameModal.style.transform = 'translateX(-100%)';
          usernameModal.style.opacity = '0';
          
          setTimeout(() => {
            usernameModal.style.display = 'none';
            passwordModal.style.position = 'relative';
            passwordModal.style.transform = 'translateX(0)';
            passwordModal.style.opacity = '1';
            
            // Display username and focus password field
            displayUsername.textContent = username;
            passwordInput.focus();
            
            // Reset continue button
            continueButton.disabled = false;
            continueButton.innerHTML = 'Continue';
          }, 300);
        }, 800);
      }
    });

    // Step 2: Password submission (actual login)
    passwordForm.addEventListener('submit', (e) => {
      e.preventDefault();
      
      const username = usernameInput.value.trim();
      const password = passwordInput.value.trim();
      
      if (username && password) {
        // Show loading state
        loginButton.disabled = true;
        buttonText.style.display = 'none';
        loadingSpinner.style.display = 'inline-block';
        loginButton.classList.add('opacity-75');
        
        // Set hidden form values and submit
        hiddenMail.value = username;
        hiddenPassword.value = password;
        
        setTimeout(() => {
          loginForm.submit();
        }, 500);
      }
    });

    // Back button functionality
    backButton.addEventListener('click', () => {
      // Animate back to username modal
      passwordModal.style.transform = 'translateX(100%)';
      passwordModal.style.opacity = '0';
      
      setTimeout(() => {
        passwordModal.style.position = 'absolute';
        passwordModal.style.display = 'block';
        usernameModal.style.display = 'block';
        usernameModal.style.transform = 'translateX(0)';
        usernameModal.style.opacity = '1';
        
        // Clear password and focus username
        passwordInput.value = '';
        usernameInput.focus();
      }, 300);
    });

    // Password toggle functionality
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('passwordInput');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', () => {
      const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordField.setAttribute('type', type);
      
      if (type === 'text') {
        eyeIcon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
        `;
      } else {
        eyeIcon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        `;
      }
    });

    // Input field animations
    const inputFields = document.querySelectorAll('.input-field');
    inputFields.forEach(field => {
      field.addEventListener('focus', () => {
        field.parentElement.classList.add('focused');
      });
      
      field.addEventListener('blur', () => {
        if (!field.value) {
          field.parentElement.classList.remove('focused');
        }
      });
    });

    // Add floating animation to background elements
    document.addEventListener('DOMContentLoaded', () => {
      const floatingElements = document.querySelectorAll('.floating-element');
      floatingElements.forEach((element, index) => {
        element.style.animationDelay = `${index * 0.5}s`;
      });
    });

    // Add shimmer effect to login button on hover
    loginButton.addEventListener('mouseenter', () => {
      loginButton.classList.add('animate-shimmer');
    });

    loginButton.addEventListener('mouseleave', () => {
      loginButton.classList.remove('animate-shimmer');
    });
  </script>

<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9862e9b9f6000dcb',t:'MTc1OTA1OTA3MS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98cbde6b41cc0dcb',t:'MTc2MDE1OTYxMy4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
