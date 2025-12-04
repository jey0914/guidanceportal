<?php
session_start();
include("db.php");

$announcementId = intval($_POST['id']);
$userId = $_SESSION['user_id'];

// check kung may like na
$check = $con->query("SELECT * FROM announcement_likes WHERE announcement_id=$announcementId AND user_id=$userId");

if ($check->num_rows == 0) {
    $con->query("INSERT INTO announcement_likes (announcement_id, user_id) VALUES ($announcementId, $userId)");
    $con->query("UPDATE exam_announcements SET likes = likes + 1 WHERE id=$announcementId");
    echo "liked";
} else {
    // optional: pwede rin un-like
    $con->query("DELETE FROM announcement_likes WHERE announcement_id=$announcementId AND user_id=$userId");
    $con->query("UPDATE exam_announcements SET likes = likes - 1 WHERE id=$announcementId");
    echo "unliked";
}
