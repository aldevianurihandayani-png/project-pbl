<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Logout</title>
</head>
<body>
    <h2>Logout</h2>
    <p>Klik tombol di bawah untuk keluar.</p>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>

    <a href="{{ url('/') }}">Batal</a>
</body>
</html>
