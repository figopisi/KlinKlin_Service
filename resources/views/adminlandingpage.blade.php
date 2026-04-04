<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700&family=Poppins:wght@300;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('asset/css/adminlanding.css') }}">

<title>Admin Login</title>
</head>

<body>

<div class="circle"></div>

<div class="container">

<img src="../image/Logo.png" alt="Logo">

<h1>Admin Login</h1>

<form action="{{ route('login.process') }}" method="POST">
    @csrf

    <div class="input-box">
        <input type="text" name="username" placeholder="Username" required>
    </div>

    <div class="input-box">
        <input type="password" name="password" placeholder="Password" required>
    </div>

    @if(session('error'))
        <p style="color:red;">{{ session('error') }}</p>
    @endif

    <button type="submit" class="masuk">
        MASUK
    </button>
</form>

</div>

</body>
</html>