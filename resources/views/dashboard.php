<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.blade.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
        }

        header {
            background: #003399;
            color: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h2 {
            margin: 0;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            padding: 40px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: auto;
            text-align: center;
        }

        .btn-logout {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: red;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-logout:hover {
            background: darkred;
        }
    </style>
</head>
<body>
    <header>
        <h2>Dashboard</h2>
        <nav>
            <a href="index.php">Beranda</a>
            <a href="about.php">Tentang</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="container">
        <div class="card">
            <h1>Halo, <?php echo $_SESSION['username']; ?> 👋</h1>
            <p>Selamat datang di dashboard Anda.  
            Di sini Anda bisa mengelola data, melihat informasi, dan navigasi ke halaman lain.</p>

            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </div>
</body>
</html>
