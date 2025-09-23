<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h1>Form Register</h1>

    {{-- Tampilkan pesan sukses jika ada --}}
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    {{-- Tampilkan error validasi --}}
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/register" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Name" value="{{ old('name') }}"><br><br>
        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}"><br><br>
        <input type="password" name="password" placeholder="Password"><br><br>
        <button type="submit">Register</button>
    </form>

    <br>
    <a href="/login">Sudah punya akun? Login di sini</a>
</body>
</html>
