<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title','Dashboard Admin')</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-body">
  <div class="layout">
    @include('admin.partials.sidebar')
    <main class="content">
      <header class="topbar">
        <div class="brand">Dashboard Admin</div>
        <a class="logout" href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
          @csrf
        </form>
      </header>
      <section class="page">@yield('content')</section>
    </main>
  </div>
</body>
</html>
