<?php
// 1. Start session and load config
session_start();
require 'config.php';

// 2. Define the helper functions so the HTML below doesn't crash
$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? ''
];

$activeForm = $_SESSION['active_form'] ?? 'login';

// Clear sessions so errors don't stay forever
unset($_SESSION['login_error'], $_SESSION['register_error'], $_SESSION['active_form']);

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

function isActiveFormStyle($formName, $activeForm) {
    return $formName === $activeForm ? 'block' : 'none';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CraveCast | Login & Register</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css"> <link rel="stylesheet" href="css/Dstyle.css"> ```
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #fff9c4 0%, #d1d9ff 50%, #b3cde0 100%);
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .logo-img {
            height: 50px; 
            width: auto;
            margin-right: 12px;
            object-fit: contain;
        }

        .logo-container h1 {
            font-size: 32px;
            color: #000000ff;
            font-weight: 700;
            letter-spacing: -1px;
        }

        .logo-blue {
            color: #1a70d3ff; 
        }

        .form-card {
            background: rgba(255, 255, 255, 0.7); 
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 400px;
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .form-card h2 {
            margin-bottom: 25px;
            font-weight: 700;
            color: #333;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 20px;
        }

        .input-group {
            text-align: left;
            margin-bottom: 18px;
        }

        .input-group label {
            display: block;
            font-weight: 700;
            font-size: 13px;
            margin-bottom: 8px;
            color: #444;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper input, .input-wrapper select {
            width: 100%;
            padding: 12px 45px 12px 15px;
            border: 1px solid #000;
            border-radius: 8px;
            background: #f0f0f0;
            font-size: 15px;
            outline: none;
            transition: 0.3s;
        }

        .input-wrapper input:focus {
            background: #fff;
            border-color: #7042ff;
        }

        .input-wrapper i {
            position: absolute;
            right: 15px;
            color: #555;
            font-size: 18px;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background-color: #7042ff; 
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(112, 66, 255, 0.2);
        }

        .submit-btn:hover {
            background-color: #5b32e0;
            transform: translateY(-1px);
        }

        .error-message {
            color: #d32f2f;
            background: #ffebee;
            padding: 10px;
            border-radius: 5px;
            font-size: 13px;
            margin-bottom: 15px;
            font-weight: 600;
            border-left: 4px solid #d32f2f;
        }

        .toggle-link {
            margin-top: 25px;
            font-size: 14px;
            color: #666;
        }

        .toggle-link a {
            color: #7042ff;
            text-decoration: none;
            font-weight: 700;
            cursor: pointer;
        }

        .toggle-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="logo-container">
        <img src="logo.png" alt="CraveCast Logo" class="logo-img">
        <h1>Crave<span class="logo-blue">Cast</span></h1>
    </div>

    <div class="form-card">
        
        <div id="login-form" style="display: <?= isActiveFormStyle('login', $activeForm); ?>;">
            <h2>Welcome!</h2>
            <?= showError($errors['login']); ?>
            
            <form action="login_register.php" method="post">
                <div class="input-group">
                    <label>Email:</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" placeholder="Your Email" required>
                        <i class="fa-solid fa-at"></i>
                    </div>
                </div>

                <div class="input-group">
                    <label>Password:</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" placeholder="Your Password" required>
                        <i class="fa-solid fa-lock" id="togglePassword" style="cursor: pointer;"></i>
                    </div>
                </div>

               <button type="submit" name="login" class="submit-btn">Login</button>
            </form>
            <p class="toggle-link">Don't have an account? <a onclick="showForm('register-form')">Register</a></p>
        </div>

        <div id="register-form" style="display: <?= isActiveFormStyle('register', $activeForm); ?>;">
            <h2>Register</h2>
            <?= showError($errors['register']); ?>
            
            <form action="login_register.php" method="post">
                <div class="input-group">
                    <label>Full Name:</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" placeholder="John Doe" required>
                        <i class="fa-solid fa-user"></i>
                    </div>
                </div>

                <div class="input-group">
                    <label>Email:</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" placeholder="email@example.com" required>
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                </div>

                <div class="input-group">
                    <label>Password:</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" placeholder="Min. 8 characters" required>
                        <i class="fa-solid fa-lock" id="togglePassword" style="cursor: pointer;"></i>
                    </div>
                </div>

                <div class="input-group">
                    <label>Role:</label>
                    <div class="input-wrapper">
                        <select name="role" required>
                            <option value="">--Select Role--</option>
                            <option value="User">User</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                </div>

                <button type="submit" name="register" class="submit-btn">Register</button>
            </form>
            <p class="toggle-link">Already have an account? <a onclick="showForm('login-form')">Login</a></p>
        </div>

    </div>

   <script>
    // Toggle between Login and Register forms
    function showForm(formId) {
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        
        if (formId === 'login-form') {
            loginForm.style.display = 'block';
            registerForm.style.display = 'none';
        } else {
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
        }
    }

    // Toggle Password Visibility Logic
    const togglePasswordIcons = document.querySelectorAll('[id^="togglePassword"]');
    togglePasswordIcons.forEach(icon => {
        icon.addEventListener('click', function() {
            const passwordInput = this.previousElementSibling;
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Palitan ang icon (optional kung gumagamit ka ng FontAwesome classes)
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    });
</script>
</body>
</html>