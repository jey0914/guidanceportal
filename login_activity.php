<?php
session_start();
include("db.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch login activities
$stmt = $con->prepare("SELECT device, browser, location, ip_address, login_time, is_active 
                       FROM login_activity 
                       WHERE user_email = ? 
                       ORDER BY login_time DESC 
                       LIMIT 10");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login Activity</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: "f7f8fa";
    margin: 0;
    padding: 30px;
}
.cointainer {
    background: #fff;
    border-radius: 10px;
    padding: 25px;
    max-width: auto;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}
h2 {
  margin-bottom: 20px;
  color: #333;
}
.session {
  border-bottom: 1px solid #eee;
  padding: 10px 0;
  display: flex;
  justify-content: space-between;
  color: #555;
}
.session:last-child {
  border-bottom: none;
}
.active {
  color: green;
  font-weight: bold;
}
</style>
</head>
<body>
    <div class="container">
        <h2>Login Activity</h2>
        <p>Monitor recent login session and devices</p>
          <?php
  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          $status = $row['is_active'] ? '<span class="active">Active Now</span>' : '';
          echo "
          <div class='session'>
              <div>{$row['device']} • {$row['browser']} • {$row['location']}<br><small>{$row['login_time']}</small></div>
              <div>$status</div>
          </div>";
      }
  } else {
      echo "<p>No login activity found.</p>";
  }
  ?>
</div>
</body>
</html>