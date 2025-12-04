<?php
session_start();
include("db.php");

if (!isset($_SESSION['admin_email'])) {
    exit("Unauthorized access.");
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipient = $_POST['recipient'];
    $reply_body = trim($_POST['reply_body']);

    if (!empty($recipient) && !empty($reply_body)) {
        $stmt = $con->prepare("INSERT INTO messages (sender, recipient, subject, body, sent_at, is_read)
                               VALUES (?, ?, ?, ?, NOW(), 0)");

        $admin_sender = "Admin";
        $subject = "Reply from Admin";

        $stmt->bind_param("ssss", $admin_sender, $recipient, $subject, $reply_body);

        if ($stmt->execute()) {
            header("Location: admin_messages.php?user=" . urlencode($recipient));
            exit();
        } else {
            echo "Error sending reply.";
        }
    } else {
        echo "Please fill out the reply field.";
    }
} else {
    echo "Invalid request.";
}
?>