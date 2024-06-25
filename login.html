<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM doctors WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $doctor = $stmt->fetch();

    if ($doctor && password_verify($password, $doctor['password'])) {
        $_SESSION['doctor_id'] = $doctor['id'];
        header("Location: doctor_dashboard.php");
        exit;
    } else {
        $error = "Invalid email or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors Login Form</title>
    <style>
        .login .inner{
            font-family: 'Poppins', sans-serif;
            box-shadow: 0px 0px 10px #00000024;
            border-radius: 5px;
            overflow:hidden;
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
        .login .login-form h2:before{
            position:absolute;
            content:"";
            left:0;
            bottom:0;
            height:2px;
            width:50px;
            background:#1A76D1;
        }
        .login .login-form p {
            font-size: 14px;
            color: #333;
            font-weight: 400;
            text-align: left;
            margin-bottom:50px;
        }
        .login .login-form p a{
            display:inline-block;
            margin-left:5px;
            color:#1A76D1;
        }
        .login .login-form p a:hover{
            color:#2C2D3F;
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
            text-transform: capitalize;
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
            width:100%;
            padding: 15px 0;
            background: #1A76D1;
            border-radius: 4px;
        }
        .login .form .btn:hover{
            background:#145b9c;
            color:#fff;
        }
        .login .login-form .lost-pass{
            display:inline-block;
            margin-left:20px;
            color:#1A76D1;
            font-size:14px;
            font-weight:400;
            margin-top: 20px;
        }
        .login .login-form .lost-pass:hover{
            color:#1A76D1;
        }
    </style>
</head>
<body>
    <div class="login">
        <div class="inner">
            <div class="login-left"></div>
            <div class="login-form">
                <h2>Login</h2>
                <p>Welcome back! Please login to your account.</p>
                <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
                <form class="form" method="POST">
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="form-group login-btn">
                        <button type="submit" class="btn">Login</button>
                    </div>
                    <a href="#" class="lost-pass">Lost your password?</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
