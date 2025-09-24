<?php
// Mulai session
session_start();

// Jika user sudah login, arahkan ke dashboard
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Company Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #00b4d8, #90e0ef);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: #fff;
            padding: 30px 25px;
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
            width: 350px;
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #0077b6;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .form-group input:focus {
            border-color: #00b4d8;
            outline: none;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: #0077b6;
            border: none;
            color: #fff;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-login:hover {
            background: #12417eff;
        }
        .register-link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        .register-link a {
            color: #0077b6;
            text-decoration: none;
            font-weight: bold;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="proses_login.php" method="POST">
            <div class="form-group">
                <label for="username">Username / Email</label>
                <input type="text" name="username" id="username" placeholder="Masukkan username atau email" required>
            </div>
            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input type="password" name="password" id="password" placeholder="Masukkan kata sandi" required>
            </div>
            <button type="submit" class="btn-login">Masuk</button>
        </form>
        <div class="register-link">
            Belum punya akun? <a href="register.php">Daftar di sini</a>
        </div>
    </div>
</body>
</html>
