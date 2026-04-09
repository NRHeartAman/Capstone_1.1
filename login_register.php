<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
session_start();
require_once 'config.php';

// --- REGISTER LOGIC ---
if (isset($_POST['register'])) {
    $username = $_POST['username'] ?? $_POST['name']; 
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role']; // 'Admin' or 'User'

    $checkEmail = $conn->query("SELECT email FROM users WHERE email = '$email'");
    
    if ($checkEmail->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered!';
        $_SESSION['active_form'] = 'register';
    } else {
        $insertQuery = "INSERT INTO users (username, email, password, role) 
                        VALUES ('$username', '$email', '$password', '$role')";
        
        if($conn->query($insertQuery)) {
            $_SESSION['login_error'] = 'Registration successful! Please login.';
            $_SESSION['active_form'] = 'login';
        } else {
            $_SESSION['register_error'] = 'Database Error: ' . $conn->error;
        }
    }
    header("Location: index.php");
    exit();
}

// --- LOGIN LOGIC ---
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!isset($conn)) {
        die("Error: The database connection variable is missing.");
    }

    $result = $conn->query("SELECT * FROM users WHERE email = '$email'");

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id']; 
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // --- ACTIVITY LOG: I-trace ang pag-login nila ---
            $uid = $user['user_id'];
            $uname = $user['username'];
            $conn->query("INSERT INTO activity_log (user_id, username, action) VALUES ('$uid', '$uname', 'Logged In')");

            header("Location: user_page.php");
            exit();
        } else {
            $_SESSION['login_error'] = 'Maling password!';
        }
    } else {
        $_SESSION['login_error'] = 'Email not found!';
    }

    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}
?>

    $_SESSION['login_error'] = 'Incorrect email or password';
    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}
?>