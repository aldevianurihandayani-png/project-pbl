<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem PBL</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #0077b6, #90e0ef);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background: #fff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 18px rgba(0,0,0,0.2);
            width: 360px;
        }
        .login-box h2 {
            text-align: center;
            color: #0077b6;
            margin-bottom: 25px;
        }
        .error-box {
            background: #ffe6e6;
            border: 1px solid #ff4d4d;
            color: #b30000;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .login-box input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
        }
        .login-box input:focus {
            border-color: #0077b6;
            outline: none;
        }
        .login-box button {
            width: 100%;
            padding: 12px;
            background: #0077b6;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }
        .login-box button:hover {
            background: #005f8d;
        }
        .extra-links {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        .extra-links a {
            color: #0077b6;
            text-decoration: none;
            font-weight: bold;
        }
        .extra-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login Sistem PBL</h2>

        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.authenticate') }}" method="POST">
            @csrf
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Masuk</button>
        </form>

        <div class="extra-links">
            <p>Belum punya akun? <a href="/register">Daftar</a></p>
        </div>
    </div>
</body>
</html>
