<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Dashboard Dosen')</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    :root{
      --navy:#0b1d54; --bg:#f5f7fb; --card:#fff;
    }
    body{background:var(--bg);}
    .sidebar{
      width:250px; background:var(--navy); color:#fff;
      position:fixed; top:0; bottom:0; left:0;
      padding:1rem; overflow-y:auto;
    }
    .sidebar a{
      display:flex; align-items:center; gap:8px;
      color:#cfd8ea; text-decoration:none;
      padding:.5rem .75rem; border-radius:8px;
    }
    .sidebar a.active,.sidebar a:hover{background:rgba(255,255,255,.1);color:#fff}
    .main{margin-left:250px;}
    .topbar{background:var(--navy);color:#fff;padding:.75rem 1rem;
      display:flex;justify-content:space-between;align-items:center;}
    .table thead th{background:var(--navy);color:#fff;}
  </style>
</head>
<body>
  @include('dosen.partials.sidebar')
  <div class="main">
    @include('dosen.partials.topbar')

    <div class="container-fluid py-4">
      @if(session('success'))<div class="alert alert-success">{{session('success')}}</div>@endif
      @yield('content')
    </div>
  </div>
</body>
</html>
