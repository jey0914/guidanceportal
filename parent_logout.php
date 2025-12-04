<?php
session_start();

if (isset($_SESSION['parent_email'])) {
    unset($_SESSION['parent_email']);
}

header("Location: parent_login.php");
exit;
?>
