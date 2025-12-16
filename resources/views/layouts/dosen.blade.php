<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Dashboard Dosen')</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  {{-- semua tampilan/style dosen dipindah ke partial ini --}}
  @include('dosen.partials.styles')
</head>

<body>
  @include('dosen.partials.sidebar')

  <div class="main">
    @include('dosen.partials.topbar')

    <div class="container-fluid py-4">
      @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
      @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

      @yield('content')
    </div>
  </div>
</body>
</html>
