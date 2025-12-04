<?php
session_start();
include("db.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    $stmt = $con->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if ($pass === $row['password']) { // Consider using password_hash in future
            $_SESSION['admin_email'] = $email;
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Admin not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body {
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      padding: 20px;
    }
    .login-container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      padding: 3rem;
      width: 100%;
      max-width: 450px;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .admin-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, #667eea, #764ba2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 2rem;
      box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }
    .admin-icon i { font-size: 2rem; color: white; }
    .login-title { text-align: center; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1.75rem; }
    .login-subtitle { text-align: center; color: #718096; margin-bottom: 2.5rem; font-weight: 400; }
    .form-floating { margin-bottom: 1.5rem; }
    .form-floating > .form-control {
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      padding: 1rem 0.75rem;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: rgba(255, 255, 255, 0.8);
    }
    .form-floating > .form-control:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
      background: white;
    }
    .form-floating > label { color: #718096; font-weight: 500; }
    .input-group-text {
      background: rgba(255, 255, 255, 0.8);
      border: 2px solid #e2e8f0;
      border-right: none;
      border-radius: 12px 0 0 12px;
      color: #667eea;
    }
    .input-group .form-control { border-left: none; border-radius: 0 12px 12px 0; }
    .btn-login {
      background: linear-gradient(135deg, #667eea, #764ba2);
      border: none;
      border-radius: 12px;
      padding: 1rem;
      font-weight: 600;
      font-size: 1.1rem;
      width: 100%;
      color: white;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
      background: linear-gradient(135deg, #5a67d8, #6b46c1);
    }
    .btn-login:active { transform: translateY(0); }
    .error-alert {
      background: linear-gradient(135deg, #fed7d7, #feb2b2);
      border: 1px solid #fc8181;
      border-radius: 12px;
      color: #c53030;
      padding: 1rem;
      margin-bottom: 1.5rem;
      font-weight: 500;
      text-align: center;
      animation: shake 0.5s ease-in-out;
    }
    .forgot-password { text-align: center; margin-top: 1.5rem; }
    .forgot-password a { color: #667eea; text-decoration: none; font-weight: 500; transition: color 0.3s ease; }
    .forgot-password a:hover { color: #5a67d8; text-decoration: underline; }
    .security-badge { display: flex; align-items: center; justify-content: center; margin-top: 2rem; color: #718096; font-size: 0.875rem; }
    .security-badge i { margin-right: 0.5rem; color: #48bb78; }
    @media (max-width: 576px) { .login-container { padding: 2rem 1.5rem; margin: 1rem; } .login-title { font-size: 1.5rem; } }
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      75% { transform: translateX(5px); }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="admin-icon">
      <i class="bi bi-person-gear"></i>
    </div>
    <h1 class="login-title">Admin Portal</h1>
    <p class="login-subtitle">Sign in to access your dashboard</p>

    <form id="loginForm" method="POST" novalidate>
      <?php if(!empty($error)): ?>
        <div class="error-alert">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          <?= $error ?>
        </div>
      <?php endif; ?>

      <div class="form-floating mb-3">
        <input type="email" class="form-control" id="email" name="email" placeholder="admin@example.com" required>
        <label for="email"><i class="bi bi-envelope me-2"></i>Email Address</label>
        <div class="invalid-feedback">Please enter a valid email address.</div>
      </div>

      <div class="form-floating mb-4">
        <input type="password" class="form-control" id="password" name="pass" placeholder="Password" required>
        <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
        <div class="invalid-feedback">Please enter your password.</div>
      </div>

      <button type="submit" class="btn btn-login" id="loginBtn">
        <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
      </button>

      <div class="forgot-password">
        <a href="#" onclick="showForgotPassword()">
          <i class="bi bi-question-circle me-1"></i> Forgot your password?
        </a>
      </div>

      <div class="security-badge">
        <i class="bi bi-shield-check"></i> Secure Admin Access
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function showForgotPassword() {
      alert('Contact your system administrator to reset your password.');
    }

    // Enhanced input focus effects
    document.querySelectorAll('.form-control').forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'translateY(-2px)';
      });
      input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'translateY(0)';
      });
    });
  </script>
</body>
</html>
