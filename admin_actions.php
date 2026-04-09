<?php
session_start();
require 'config.php';

if (isset($_POST['add_user'])) {
    // 1. Kunin ang input
    $new_user = $_POST['new_username'];
    $new_email = $_POST['new_email'];
    $new_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $new_role = $_POST['new_role'];

    // 2. I-save sa users table
    $sql = "INSERT INTO users (username, email, password, role) VALUES ('$new_user', '$new_email', '$new_pass', '$new_role')";
    
    if ($conn->query($sql)) {
        // 3. I-LOG ANG ACTION (Para alam mo kung sino ang nag-add)
        $admin_name = $_SESSION['username'];
        $details = "Added new user: $new_user as $new_role";
        
        $conn->query("INSERT INTO activity_log (user_id, username, action, action_details) 
                      VALUES ('{$_SESSION['user_id']}', '$admin_name', 'Created User', '$details')");
        
        header("Location: user_page.php?success=UserAdded");
    } else {
        header("Location: user_page.php?error=FailedToAdd");
    }
}
?>