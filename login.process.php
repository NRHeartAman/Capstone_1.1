<?php
session_start();
include 'config.php';

if (isset($_POST['login_btn'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; // Siguraduhing naka-hash ito sa production

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // --- DITO ANG PINAKAMAHALAGANG PART NG STRICT MODE ---
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['user_role'] = $row['role']; // Dito nanggagaling ang 'Owner' o 'Staff'

        header("Location: user_page.php");
        exit();
    } else {
        header("Location: admin_login.php?error=Invalid Credentials");
        exit();
    }
}
?>