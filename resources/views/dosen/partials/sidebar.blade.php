@php
  function activeMenu($route){ return request()->routeIs($route) ? 'active' : ''; }
@endphp

<aside class="sidebar">
  <div class="fw-bold mb-3">SIMAP<br><small>Dosen Pembimbing</small></div>

  <nav class="d-flex flex-column gap-1">
    <a href="{{route('dosen.dashboard')}}" class="{{activeMenu('dosen.dashboard')}}"><i class="fa-solid fa-house"></i> Dashboard</a>
    <a href="{{route('dosen.kelompok.index')}}" class="{{activeMenu('dosen.kelompok.*')}}"><i class="fa-solid fa-users"></i> Kelompok</a>
    <a href="{{route('dosen.milestone.index')}}" class="{{activeMenu('dosen.milestone.*')}}"><i class="fa-solid fa-flag-checkered"></i> Milestone</a>
    <a href="{{route('dosen.logbook.index')}}" class="{{activeMenu('dosen.logbook.*')}}"><i class="fa-solid fa-book"></i> Logbook</a>
    <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
      <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>
    <form id="logout-form" method="POST" action="{{route('logout')}}" class="d-none">@csrf</form>
  </nav>
</aside>
