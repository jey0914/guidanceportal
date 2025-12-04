<?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Guidance Office Dashboard</title>
  <link rel="stylesheet" href="stylesheet2.css">
</head>
<body>
  <div class="dashboard">
    <div class="sidebar">
      <h2>Guidance Office</h2>
      <ul>
        <li><a href="#">Dashboard</a></li>
        <li><a href="#">Student Records</a></li>
        <li><a href="#">Appointments</a></li>
        <li><a href="#">Reports</a></li>
        <li><a href="#">Settings</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </div>

    <div class="content">
      <h1>Welcome, <?php echo $_SESSION['username'] ?? 'User'; ?>!</h1>
      <p>This is your dashboard. Use the menu on the left to navigate.</p>
    </div>
  </div>
</body>
</html>
