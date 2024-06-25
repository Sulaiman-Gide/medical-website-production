<?php
session_start();

$defaultAdminUsername = 'admin';
$defaultAdminPassword = 'adminpassword';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $defaultAdminUsername && $password === $defaultAdminPassword) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        .login .inner {
            font-family: 'Poppins', sans-serif;
            box-shadow: 0px 0px 10px #00000024;
            border-radius: 5px;
            overflow: hidden;
            max-width: 500px;
            margin: 80px auto;
        }
        .login .login-form {
            padding: 50px 40px;
        }
        .login .login-form h2 {
            position: relative;
            font-size: 32px;
            color: #333;
            font-weight: 600;
            line-height: 27px;
            text-transform: capitalize;
            margin-bottom: 12px;
            padding-bottom: 20px;
            text-align: left;
        }
        .login .login-form h2:before {
            position: absolute;
            content: "";
            left: 0;
            bottom: 0;
            height: 2px;
            width: 50px;
            background: #1A76D1;
        }
        .login .login-form p {
            font-size: 14px;
            color: #333;
            font-weight: 400;
            text-align: left;
            margin-bottom: 50px;
        }
        .login .login-form p a {
            display: inline-block;
            margin-left: 5px;
            color: #1A76D1;
        }
        .login .login-form p a:hover {
            color: #2C2D3F;
        }
        .login .form {
            margin-top: 30px;
        }
        .login .form .form-group {
            margin-bottom: 22px;
        }
        .login .form .form-group input {
            width: 90%;
            height: 50px;
            border: 1px solid #eee;
            padding: 0px 18px;
            color: #555;
            font-size: 14px;
            font-weight: 400;
            border-radius: 4px;
        }
        .login .form .form-group.login-btn {
            margin: 0;
        }
        .login .form button {
            border: none;
        }
        .login .form .btn {
            display: inline-block;
            margin-right: 10px;
            color: #fff;
            line-height: 20px;
            width: 100%;
            padding: 15px 0;
            background: #1A76D1;
            border-radius: 4px;
        }
        .login .form .btn:hover {
            background: #145b9c;
            color: #fff;
        }
        .login .login-form .lost-pass {
            display: inline-block;
            margin-left: 20px;
            color: #1A76D1;
            font-size: 14px;
            font-weight: 400;
            margin-top: 20px;
        }
        .login .login-form .lost-pass:hover {
            color: #1A76D1;
        }
    </style>
</head>
<body>
    <div class="login">
        <div class="inner">
            <div class="login-form">
                <h2>Admin Login</h2>
                <p>Please login to your admin account.</p>
                <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
                <form class="form" method="POST">
                    <div class="form-group">
                        <input type="text" name="username" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="form-group login-btn">
                        <button type="submit" class="btn">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
