<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Form Register</h2>

    {{-- Form kirim ke route POST /register --}}
    <form action="{{ url('/register') }}" method="POST">
        @csrf

        <div>
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" placeholder="Name" required>
        </div>

        <div>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" placeholder="Email" required>
        </div>

        <div>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" placeholder="Password" required>
        </div>

        <button type="submit">Register</button>
    </form>
</body>
</html>