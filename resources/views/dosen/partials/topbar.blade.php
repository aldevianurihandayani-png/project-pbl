<header class="topbar">
  <div class="fw-semibold">Dashboard Dosen</div>
  <div class="d-flex align-items-center gap-3">
    <a href="#" class="text-white position-relative">
      <i class="fa-regular fa-bell"></i>
      <span class="position-absolute top-0 start-100 translate-middle badge bg-danger">3</span>
    </a>
    @php
      $user = auth()->user();
      $inisial = strtoupper(substr($user->nama ?? $user->name,0,2));
    @endphp
    <div class="rounded-circle bg-white text-dark fw-bold d-flex align-items-center justify-content-center"
         style="width:36px;height:36px">{{ $inisial }}</div>
  </div>
</header>
