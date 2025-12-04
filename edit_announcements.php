<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

include("db.php");

if (!isset($_GET['id'])) {
    header("Location: admin_announcements.php");
    exit();
}

$id = intval($_GET['id']);
$stmt = $con->prepare("SELECT * FROM exam_announcements WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$announcement = $result->fetch_assoc();

if (!$announcement) {
    header("Location: admin_announcements.php");
    exit();
}

// Update logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_title = trim($_POST['announce_title']);
    $new_message = trim($_POST['announce_message']);

    if (!empty($new_title) && !empty($new_message)) {
        $update = $con->prepare("UPDATE exam_announcements SET title = ?, message = ? WHERE id = ?");
        $update->bind_param("ssi", $new_title, $new_message, $id);
        $update->execute();
        header("Location: admin_announcements.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Announcement</title>
    <link rel="stylesheet" href="stylesheet2.css">
    <style>
      body {
        font-family: sans-serif;
        padding: 50px;
        background-color: #f8f9fa;
      }

      .edit-box {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 10px;
        max-width: 600px;
        margin: auto;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      }

      input, textarea {
        width: 100%;
        padding: 12px;
        margin-top: 10px;
        margin-bottom: 20px;
        border-radius: 6px;
        border: 1px solid #ccc;
      }

      button {
        background-color: #1d3557;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
      }

      a {
        display: inline-block;
        margin-top: 15px;
        color: #333;
        text-decoration: none;
      }
    </style>
</head>
<body>

<div class="edit-box">
  <h2>Edit Announcement</h2>
  <form method="POST">
    <label>Title</label>
    <input type="text" name="announce_title" value="<?= htmlspecialchars($announcement['title']) ?>" required>

    <label>Message</label>
    <textarea name="announce_message" rows="6" required><?= htmlspecialchars($announcement['message']) ?></textarea>

    <button type="submit">Save Changes</button>
  </form>
  <a href="admin_announcements.php">← Back to Announcements</a>
</div>

</body>
</html>