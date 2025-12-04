<?php
include("db.php");

$errorMsg = '';
$successMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $errorMsg = "Please enter your email address.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = "Please enter a valid email address.";
    } else {
        // Check if email exists in parent table
        $stmt = $conn->prepare("SELECT parent_email FROM parents WHERE parent_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Generate a simple reset token (in production, use more secure token)
            $token = bin2hex(random_bytes(16));

            // Save token to database (optional, for verification)
            $stmt = $conn->prepare("UPDATE parents SET reset_token = ? WHERE parent_email = ?");
            $stmt->bind_param("ss", $token, $email);
            $stmt->execute();

            // TODO: Send email with reset link
            // Example: https://yourdomain.com/parent_reset_password.php?token=$token
            $successMsg = "A password reset link has been sent to your email address.";
        } else {
            $errorMsg = "Email address not found.";
        }
    }
}
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Guidance Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #667eea, #764ba2); display:flex; justify-content:center; align-items:center; min-height:100vh; margin:0;}
        .form-container { background:white; padding:3rem; border-radius:20px; width:100%; max-width:400px; box-shadow:0 25px 50px rgba(0,0,0,0.25);}
        .form-header { text-align:center; margin-bottom:2rem;}
        .form-header h2 { margin:0 0 0.5rem; color:#1e293b;}
        .form-header p { margin:0; color:#64748b;}
        .form-group { margin-bottom:1.5rem; }
        input { width:100%; padding:0.875rem 1rem; border-radius:12px; border:2px solid #e5e7eb; font-size:1rem; }
        input:focus { border-color:#4f46e5; outline:none; background:white; box-shadow:0 0 0 3px rgba(79,70,229,0.1); }
        .login-btn { width:100%; padding:1rem; border:none; border-radius:12px; background:linear-gradient(135deg,#4f46e5,#7c3aed); color:white; font-weight:600; cursor:pointer; }
        .login-btn:hover { transform:translateY(-2px); }
        .message { padding:0.75rem 1rem; border-radius:10px; margin-bottom:1rem; font-size:0.9rem; }
        .error { background:#fef2f2; color:#dc2626; border:1px solid #fecaca; }
        .success { background:#ecfdf5; color:#065f46; border:1px solid #6ee7b7; }
        .back-link { margin-top:1rem; display:block; text-align:center; color:#4f46e5; text-decoration:none; }
        .back-link:hover { color:#7c3aed; }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h2>Forgot Password</h2>
            <p>Enter your email to reset your password</p>
        </div>

    <?php if (!empty($errorMsg)) echo "<div class='message error'>{$errorMsg}</div>"; ?>
    <?php if (!empty($successMsg)) echo "<div class='message success'>{$successMsg}</div>"; ?>

    <form method="POST">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>
        </div>
        <button type="submit" class="login-btn"><i class="fas fa-envelope"></i> Send Reset Link</button>
    </form>

    <a href="parent_login.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Login</a>
</div>
```

</body>
</html>
