<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Function para sa restriction
function restrictToOwner() {
    if ($_SESSION['user_role'] !== 'Owner') {
        echo "<script>alert('Access Denied: Owner only!'); window.location.href='user_page.php';</script>";
        exit();
    }
}
?>