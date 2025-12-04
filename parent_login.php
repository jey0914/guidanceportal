<?php
session_start();
include("db.php");

$errorMsg = ""; // For displaying errors

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['pass']);

    if (!empty($email) && !empty($password)) {
        $stmt = $con->prepare("SELECT * FROM parents WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $parent = $result->fetch_assoc();
            if (password_verify($password, $parent['password'])) {
                $_SESSION['parent_email'] = $parent['email'];
                $_SESSION['parent_name'] = $parent['fullname'];

                // Clear previous OTP so it auto-sends
                unset($_SESSION['otp']);
                unset($_SESSION['otp_time']);

                header("Location: parent_otp.php");
                exit;
            } else {
                $errorMsg = "Incorrect password.";
            }
        } else {
            $errorMsg = "Parent account not found.";
        }
    } else {
        $errorMsg = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Login - Guidance Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        html, body {
            height: 100%;
        }

        /* Header Styles */
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        header h1 {
            color: #4f46e5;
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
        }

        header h1::before {
            content: "🎓";
            margin-right: 0.5rem;
            font-size: 1.5rem;
        }

        nav {
            display: flex;
            gap: 2rem;
        }

        nav a {
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        nav a:hover {
            background: #f1f5f9;
            color: #4f46e5;
            transform: translateY(-1px);
        }

        /* Main Section */
        .main-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            gap: 4rem;
        }

        .form-container {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 420px;
            position: relative;
            overflow: hidden;
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4f46e5, #7c3aed, #ec4899);
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-header .icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }

        .form-header h2 {
            color: #1e293b;
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0 0 0.5rem;
        }

        .form-header p {
            color: #64748b;
            margin: 0;
            font-size: 0.95rem;
        }

        /* Error Message */
        .error-msg {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .error-msg::before {
            content: "⚠️";
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            color: #374151;
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .input-container {
            position: relative;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fafafa;
            box-sizing: border-box;
        }

        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: #4f46e5;
            background: white;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #9ca3af;
            transition: color 0.3s ease;
            padding: 0.25rem;
        }

        .toggle-password:hover {
            color: #4f46e5;
        }

        .login-btn {
            width: 100%;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .signup-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
            color: #64748b;
            font-size: 0.9rem;
        }

        .signup-link a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .signup-link a:hover {
            color: #7c3aed;
        }

        /* Quote Section */
        .quote-section {
            max-width: 400px;
            color: white;
            text-align: center;
        }

        .quote-section .quote-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }

        .quote-section blockquote {
            font-size: 1.25rem;
            font-weight: 300;
            line-height: 1.6;
            margin: 0;
            font-style: italic;
            opacity: 0.95;
        }

        .quote-section .author {
            margin-top: 1rem;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-section {
                flex-direction: column;
                gap: 2rem;
                padding: 1rem;
            }

            .form-container {
                padding: 2rem;
                max-width: 100%;
            }

            .quote-section {
                order: -1;
                max-width: 100%;
            }

            nav {
                gap: 1rem;
            }

            nav a {
                padding: 0.5rem;
                font-size: 0.9rem;
            }

            header {
                padding: 1rem;
            }

            header h1 {
                font-size: 1.5rem;
            }
        }

        /* Loading Animation */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .loading .login-btn {
            background: #9ca3af;
            cursor: not-allowed;
        }

        /* Success Animation */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-container {
            animation: slideIn 0.6s ease-out;
        }
    </style>
</head>
<body>
    <header>
        <h1>Guidance Portal</h1>
        <nav>
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <a href="about.php"><i class="fas fa-info-circle"></i> About</a>
            <a href="contact.php"><i class="fas fa-envelope"></i> Contact</a>
        </nav>
    </header>

    <section class="main-section">
        <div class="form-container">
            <div class="form-header">
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <h2>Parent Login</h2>
                <p>Access your child's guidance information</p>
            </div>

            <?php if(!empty($errorMsg)): ?>
                <div class="error-msg"><?php echo htmlspecialchars($errorMsg); ?></div>
            <?php endif; ?>

            <form method="POST" id="loginForm">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-container">
                        <input type="email" name="email" id="email" placeholder="e.g. parent123@gmail.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-container">
                        <input type="password" name="pass" id="passwordInput" placeholder="Enter your password" required>
                        <span class="toggle-password" onclick="togglePassword()">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </span>
                    </div>
                </div>

                <div class="forgot-password">
    <a href="parent_forgot_password.php">Forgot Password?</a>
</div>


                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Login to Portal
                </button>
            </form>

            <div class="signup-link">
                Don't have an account? <a href="parent_signup.php">Sign up here</a>
            </div>
        </div>

        <div class="quote-section">
            <div class="quote-icon">👨‍👩‍👧‍👦</div>
            <blockquote>
                "Parents play a key role in their child's success — thank you for being involved."
            </blockquote>
            <div class="author">- Guidance Counseling Team</div>
        </div>
    </section>

    <script>
        function togglePassword() {
            const input = document.getElementById("passwordInput");
            const icon = document.getElementById("eyeIcon");
            
            if (input.type === "password") {
                input.type = "text";
                icon.className = "fas fa-eye-slash";
            } else {
                input.type = "password";
                icon.className = "fas fa-eye";
            }
        }

        // Form submission with loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const form = this;
            const button = form.querySelector('.login-btn');
            const originalText = button.innerHTML;
            
            // Add loading state
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing In...';
            form.classList.add('loading');
            
            // Let the form submit naturally, but show loading state
            setTimeout(() => {
                if (form.classList.contains('loading')) {
                    button.innerHTML = originalText;
                    form.classList.remove('loading');
                }
            }, 3000);
        });

        // Add focus animations
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98bd181683980dcb',t:'MTc2MDAwNDY4OC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
