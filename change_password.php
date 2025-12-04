<?php
session_start();
include("db.php");

if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current = $_POST['current_pass'];
    $new = $_POST['new_pass'];
    $confirm = $_POST['confirm_pass'];

    $admin_email = $_SESSION['admin_email'];

    $stmt = $con->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $admin_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($result->num_rows === 1) {
        if (!password_verify($current, $admin['password'])) {
            $error = "Incorrect current password.";
        } elseif ($new !== $confirm) {
            $error = "New passwords do not match.";
        } else {
            $hashed = password_hash($new, PASSWORD_DEFAULT);
            $update = $con->prepare("UPDATE admin SET password = ? WHERE email = ?");
            $update->bind_param("ss", $hashed, $admin_email);
            if ($update->execute()) {
                $success = "Password updated successfully.";
            } else {
                $error = "Failed to update password.";
            }
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
  <title>Change Password</title>
  <link rel="stylesheet" href="stylesheet2.css">
  <style>
    .container {
      max-width: 500px;
      margin: 80px auto;
      padding: 40px;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.15);
    }

    h2 {
      text-align: center;
      color: #1d3557;
      margin-bottom: 30px;
    }

    input[type="password"],
    input[type="submit"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    input[type="submit"] {
      background-color: #1d3557;
      color: white;
      border: none;
      font-weight: bold;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #16324f;
    }

    .success {
      color: green;
      text-align: center;
      margin-bottom: 20px;
    }

    .error {
      color: red;
      text-align: center;
      margin-bottom: 20px;
    }

    a.back {
      text-align: center;
      display: block;
      margin-top: 10px;
      color: #1d3557;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Change Admin Password</h2>
    <?php if ($success) echo "<div class='success'>$success</div>"; ?>
    <?php if ($error) echo "<div class='error'>$error</div>"; ?>
    <form method="POST">
      <input type="password" name="current_pass" placeholder="Current Password" required>
      <input type="password" name="new_pass" placeholder="New Password" required>
      <input type="password" name="confirm_pass" placeholder="Confirm New Password" required>
      <input type="submit" value="Change Password">
    </form>
    <a class="back" href="admin_dashboard.php">← Back to Dashboard</a>
  </div>
</body>
</html>