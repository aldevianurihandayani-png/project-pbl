{{-- resources/views/dosenpenguji/penilaian.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Penilaian — Dosen Penguji</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root{
      --navy:#0b1d54; --navy-2:#0e257a; --bg:#f5f7fb; --card:#ffffff;
      --muted:#6c7a8a; --ring:rgba(13,23,84,.10); --shadow:0 6px 20px rgba(13,23,84,.08);
      --radius:16px;
      --blue:#2f73ff; --green:#00b167; --orange:#ff8c00;
    }
    *{box-sizing:border-box}
    body{
      margin:0; font-family:Arial,Helvetica,sans-serif; background:var(--bg);
      display:grid; grid-template-columns:260px 1fr; min-height:100vh;
      color:#233042;
    }

    /* ========== SIDEBAR ========== */
    .sidebar{ background:var(--navy); color:#e9edf7; padding:18px 16px; display:flex; flex-direction:column }
    .brand{ display:flex; align-items:center; gap:10px; margin-bottom:22px }
    .brand-badge{ width:36px;height:36px; border-radius:10px; background:#1a2a6b; display:grid; place-items:center; font-weight:700 }
    .brand-title{ line-height:1.1 }
    .brand-title strong{ font-size:18px }
    .brand-title small{ display:block; font-size:12px; opacity:.85 }
    .nav-title{ font-size:12px; letter-spacing:.6px; text-transform:uppercase; opacity:.7; margin:16px 10px 6px }
    .menu a{
      display:flex; align-items:center; gap:12px; text-decoration:none; color:#e9edf7;
      padding:10px 12px; border-radius:12px; margin:4px 6px; transition:background .18s, transform .18s;
    }
    .menu a:hover{ background:#11245f; transform:translateX(2px) }
    .menu a.active{ background:#1c3d86 }
    .menu i{ width:18px; text-align:center }
    .logout{ margin-top:auto }
    .logout a{ color:#ffb2b2; display:block; padding:10px 12px; border-radius:12px; text-decoration:none }
    .logout a:hover{ background:#5c1020 }

    /* ========== MAIN ========== */
    main{ display:flex; flex-direction:column; min-width:0 }
    header.topbar{
      background:#0a1a54; color:#fff; padding:12px 22px; display:flex; align-items:center; justify-content:space-between;
      position:sticky; top:0; z-index:5000; box-shadow:var(--shadow);
    }
    .welcome h1{ margin:0; font-size:18px; letter-spacing:.2px }
    .topbar-btn{ display:none; border:0; background:transparent; color:#fff; font-size:20px; cursor:pointer }

    .page{ padding:26px; display:grid; gap:24px }
    .card{ background:var(--card); border-radius:var(--radius); border:1px solid var(--ring); box-shadow:var(--shadow) }
    .card-hd{ padding:14px 18px; border-bottom:1px solid #eef1f6; display:flex; align-items:center; justify-content:space-between; font-weight:700; color:var(--navy-2) }
    .card-bd{ padding:16px 18px; }
    .card-ft{ padding:12px 18px; border-top:1px solid #eef1f6; background:#fcfdff; }

    .toolbar{ display:flex; align-items:center; justify-content:space-between; gap:12px; }
    .filters{ display:flex; align-items:center; gap:10px }
    .filters label{ font-size:14px; color:var(--navy); font-weight:700 }
    .filters select, .filters input { padding:8px 12px; border:1px solid #d8dfeb; border-radius:8px; background:#fff; font-size:14px; }
    .btn{ border:0; padding:8px 16px; border-radius:8px; font-size:14px; font-weight:700; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
    .btn-primary{ background:var(--navy-2); color:#fff; } .btn-primary:hover{ background:var(--navy); }
    .btn-secondary{ background:#eef3fa; color:var(--navy-2); } .btn-secondary:hover{ background:#e3eaf5; }
    .btn-success{ background:var(--green); color:#fff; } .btn-success:hover{ background:#009354; }
    .btn-warning{ background:var(--orange); color:#fff; } .btn-warning:hover{ background:#e07b00; }

    .table-wrap{ overflow:auto; }
    table{ width:100%; border-collapse:collapse; min-width:1000px }
    th,td{ padding:10px 12px; font-size:14px; border-bottom:1px solid #eef1f6; vertical-align:middle; }
    thead th{ background:#eef3fa; color:#navy; text-align:left; font-size:12px; text-transform:uppercase; }
    tbody tr:hover td{ background:#f9fbff }
    .grade-cell{ position:relative; }
    .grade-input{ width:60px; padding:6px 8px; border:1px solid #d8dfeb; border-radius:6px; text-align:center; font-size:14px; transition: border .2s; }
    .grade-input:focus{ border-color:var(--blue); outline:2px solid var(--blue); outline-offset:-1px; }
    .grade-input.dirty{ border-color:var(--orange); }
    .btn-delete-grade{ position:absolute; top:50%; right:14px; transform:translateY(-50%); border:0; background:transparent; color:#aaa; font-size:18px; cursor:pointer; display:none; }
    .grade-cell:hover .btn-delete-grade{ display:block; }
    .final-grade{ font-weight:700; color:var(--navy-2); }
    .weight-total.error{ color:#e53935; font-weight:700; }

    @media (max-width: 980px){
      body{ grid-template-columns:1fr }
      .sidebar{ position:fixed; inset:0 auto 0 0; width:240px; transform:translateX(-102%); transition:transform .2s; z-index:10 }
      .sidebar.show{ transform:none }
      .topbar-btn{ display:inline-flex }
      .toolbar{ flex-direction:column; align-items:stretch; gap:16px; }
    }

    /* ===== Notifikasi + User Menu (dropdown) ===== */
    #topActions{ display:flex; align-items:center; gap:14px; }
    .bell{ position:relative; cursor:pointer; }
    .bell i{ font-size:18px }
    .bell .dot{
      position:absolute; top:-6px; right:-6px; min-width:18px; height:18px;
      padding:0 4px; border-radius:10px; font-size:10px;
      background:#e53935; color:#fff; display:grid; place-items:center;
    }
    .notif-dd{
      position:absolute; right:0; top:44px; width:320px; background:#fff;
      border:1px solid #e7ecf6; border-radius:14px; box-shadow:0 12px 30px rgba(13,23,84,.18);
      display:none; z-index:6000; overflow:hidden;
    }
    .notif-dd.active{ display:block; }
    .notif-hd{ display:flex; justify-content:space-between; align-items:center; padding:12px 14px; font-weight:700; color:#0e257a; background:#f8fbff }
    .notif-list{ max-height:300px; overflow:auto }
    .notif-item{ display:flex; gap:10px; padding:10px 12px; border-top:1px solid #f0f2f7 }
    .notif-item:hover{ background:#f7f9ff }
    .notif-icon{ width:28px; height:28px; border-radius:8px; background:#eef3ff; display:grid; place-items:center; color:#0e257a }
    .notif-meta{ font-size:12px; color:#6c7a8a }
    .notif-empty{ padding:16px; color:#6c7a8a; text-align:center }
    .notif-ft{ padding:10px 12px; border-top:1px solid #f0f2f7; text-align:center; background:#fafcff }
    .notif-ft a{ color:#0e257a; text-decoration:none; font-weight:700 }
    .userbox{ position:relative; }
    .userbtn{
      display:flex; align-items:center; gap:10px; cursor:pointer; background:transparent; border:0; color:#fff; font-weight:700;
    }
    .userbtn .ava{
      width:32px;height:32px;border-radius:50%; display:grid; place-items:center;
      background:#e3e9ff; color:#31408a; font-weight:700; font-size:12px;
    }
    .userbtn i{ opacity:.85; transition:transform .15s }
    .userbtn[aria-expanded="true"] i{ transform:rotate(180deg) }

    .user-dd{
      position:absolute; top:44px; right:0; width:260px; background:#fff; border:1px solid #e7ecf6; border-radius:14px;
      box-shadow:0 12px 30px rgba(13,23,84,.18); padding:10px; display:none; z-index:6500;
    }
    .user-dd.active{ display:block }
    .user-dd .hd{
      display:flex; align-items:center; gap:10px; padding:10px 8px 12px; border-bottom:1px dashed #eef1f6;
    }
    .user-dd .bigava{
      width:40px;height:40px;border-radius:50%; background:#e3e9ff; color:#31408a; display:grid; place-items:center; font-weight:800;
    }
    .user-dd .item{
      display:flex; align-items:center; gap:10px; padding:10px 8px; border-radius:10px;
      color:#233042; text-decoration:none;
    }
    .user-dd .item:hover{ background:#f4f7ff }
    .user-dd .item i{ width:18px; text-align:center; color:#0e257a }
    .user-dd .logout{ color:#b42318 }
  </style>
</head>
<body>

  {{-- Notifikasi dummy --}}
  @php
    $notifications = $notifications ?? [
      ['icon'=>'fa-bell', 'title'=>'Milestone baru dibuka', 'meta'=>'2 jam lalu'],
      ['icon'=>'fa-clipboard-check', 'title'=>'Logbook Minggu 3 disetujui', 'meta'=>'Kemarin'],
      ['icon'=>'fa-star', 'title'=>'Nilai komponen dirilis', 'meta'=>'3 hari lalu'],
    ];
    $notifCount = count($notifications ?? []);
  @endphp

  <!-- ========== SIDEBAR ========== -->
  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <div class="brand-badge">SI</div>
      <div class="brand-title">
        <strong>SIMAP</strong>
        <small>Politala</small>
      </div>
    </div>
    <div class="menu">
      <div class="nav-title">Menu</div>
      <a href="{{ url('/dosenpenguji/dashboard') }}"><i class="fa-solid fa-house"></i>Dashboard</a>
      <a href="{{ url('/dosenpenguji/mahasiswa') }}"><i class="fa-solid fa-user-graduate"></i>Mahasiswa</a>
      <a href="{{ url('/dosenpenguji/kelompok') }}"><i class="fa-solid fa-users"></i> Kelompok</a>
      <a href="{{ url('/dosenpenguji/penilaian') }}" class="active"><i class="fa-solid fa-clipboard-check"></i> Penilaian</a>
      <a href="{{ url('/dosenpenguji/rubrik') }}"><i class="fa-solid fa-table-list"></i> Rubrik</a>
      <a href="{{ url('/dosenpenguji/cpmk') }}"><i class="fa-solid fa-bullseye"></i> CPMK</a>
      <div class="nav-title">Akun</div>
      <a href="{{ url('/profile') }}"><i class="fa-solid fa-id-badge"></i>Profil</a>
    </div>
    <div class="logout">
      <a href="{{ url('/logout') }}"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
  </aside>

  <!-- ========== MAIN ========== -->
  <main>
    <header class="topbar">
      <button class="topbar-btn" onclick="document.getElementById('sidebar').classList.toggle('show')"><i class="fa-solid fa-bars"></i></button>
      <div class="welcome"><h1>Penilaian</h1></div>

      {{-- ====== Actions: Lonceng + User Menu ====== --}}
      <div id="topActions">
        <div class="bell" id="bellBtn" aria-label="Notifikasi">
          <i class="fa-solid fa-bell"></i>
          @if($notifCount>0)
            <span class="dot" id="notifDot">{{ $notifCount }}</span>
          @endif
        </div>

        <div class="notif-dd" id="notifDd" role="menu" aria-hidden="true">
          <div class="notif-hd">
            <span>Notifikasi</span>
            <small style="color:#6c7a8a">{{ $notifCount }} baru</small>
          </div>

          @if($notifCount>0)
            <div class="notif-list" id="notifList">
              @foreach($notifications as $n)
                <div class="notif-item">
                  <div class="notif-icon"><i class="fa-solid {{ $n['icon'] }}"></i></div>
                  <div>
                    <div style="font-weight:700;color:#0e257a">{{ $n['title'] }}</div>
                    <div class="notif-meta">{{ $n['meta'] }}</div>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <div class="notif-empty">Belum ada notifikasi.</div>
          @endif

          <div class="notif-ft"><a href="#">Lihat semua pemberitahuan</a></div>
        </div>

        <div class="userbox">
          @php $u = auth()->user(); $initial = strtoupper(substr($u->name ?? 'AL',0,2)); @endphp
          <button id="userMenuBtn" class="userbtn" type="button" aria-expanded="false" aria-controls="userMenuDd">
            <span class="ava">{{ $initial }}</span>
            <span>{{ $u->name ?? 'Aldevianuri Handayani' }}</span>
            <i class="fa-solid fa-chevron-down"></i>
          </button>

          <div id="userMenuDd" class="user-dd" role="menu" aria-labelledby="userMenuBtn">
            <div class="hd">
              <div class="bigava">{{ $initial }}</div>
              <div style="min-width:0">
                <div style="font-weight:800;color:#0e257a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                  {{ $u->name ?? 'Aldevianuri Handayani' }}
                </div>
                <div style="font-size:12px;color:#6c7a8a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                  {{ $u->email ?? 'email@example.com' }}
                </div>
              </div>
            </div>

            <a class="item" href="{{ route('dosenpenguji.profile') }}"><i class="fa-solid fa-id-badge"></i> Lihat Profil</a>
            <a class="item" href="{{ route('dosenpenguji.profile.edit') }}"><i class="fa-solid fa-user-gear"></i> Edit Profil</a>
            <a class="item" href="#"><i class="fa-solid fa-circle-question"></i> Bantuan</a>

            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="item logout" style="width:100%;background:none;border:0;cursor:pointer">
                <i class="fa-solid fa-right-from-bracket"></i> Keluar
              </button>
            </form>
          </div>
        </div>
      </div>
      {{-- ====== /Actions ====== --}}
    </header>

    <div class="page">
      <div class="toolbar">
        <form id="filter-form" method="GET" action="{{ route('dosenpenguji.penilaian') }}" class="filters">
          <label for="filter-mk">Mata Kuliah:</label>
          <select id="filter-mk" name="matakuliah" onchange="this.form.submit()">
            <option value="">Pilih MK</option>
            @isset($matakuliah)
              @foreach($matakuliah as $mk)
                <option value="{{ $mk->kode_mk }}" @selected(request('matakuliah') == $mk->kode_mk)>{{ $mk->nama_mk }}</option>
              @endforeach
            @endisset
          </select>
          <label for="filter-kelas">Kelas:</label>
          <select id="filter-kelas" name="kelas" onchange="this.form.submit()">
            <option value="">Semua Kelas</option>
            @foreach (['A','B','C','D','E'] as $kelas)
              <option value="{{ $kelas }}" @selected(request('kelas') == $kelas)>Kelas {{ $kelas }}</option>
            @endforeach
          </select>
        </form>

        {{-- ====== BLOK ACTIONS (Import/Export/Tambah/Simpan) ====== --}}
        <div style="display:flex; gap:10px;">
          {{-- Export Excel --}}
          <a class="btn btn-secondary"
             href="{{ route('dosenpenguji.penilaian.export.excel', request()->only('matakuliah','kelas')) }}">
            <i class="fa-solid fa-file-excel"></i> Export Excel
          </a>

          {{-- Export PDF --}}
          <a class="btn btn-secondary"
             href="{{ route('dosenpenguji.penilaian.export.pdf', request()->only('matakuliah','kelas')) }}">
            <i class="fa-solid fa-file-pdf"></i> Export PDF
          </a>

          {{-- Import Excel --}}
          <form id="importForm"
                action="{{ route('dosenpenguji.penilaian.import', request()->only('matakuliah','kelas')) }}"
                method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" id="importFile"
                   accept=".xlsx,.xls"
                   style="display:none"
                   onchange="document.getElementById('importForm').submit()">
            <button type="button" class="btn btn-secondary"
                    onclick="document.getElementById('importFile').click()">
              <i class="fa-solid fa-upload"></i> Import Excel
            </button>
          </form>

          {{-- Tambah Nilai --}}
          <a href="{{ route('dosenpenguji.penilaian.item.create', request()->query()) }}" class="btn btn-warning">
            <i class="fa-solid fa-plus"></i> Tambah Nilai
          </a>

          {{-- Simpan Semua --}}
          <button type="submit" form="grade-form" class="btn btn-success">
            <i class="fa-solid fa-save"></i> Simpan Semua
          </button>
        </div>
        {{-- ====== /BLOK ACTIONS ====== --}}
      </div>

      <form id="grade-form" action="{{ route('dosenpenguji.penilaian.bulkSave') }}" method="POST">
        @csrf
        <input type="hidden" name="matakuliah" value="{{ request('matakuliah') }}">
        <input type="hidden" name="kelas" value="{{ request('kelas') }}">

        <div class="card">
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th style="width:180px">Mahasiswa</th>
                  @php $totalBobot = 0; @endphp
                  @forelse (($rubrics ?? collect()) as $rubric)
                    @php $totalBobot += $rubric->bobot; @endphp
                    <th class="text-center">
                      {{ $rubric->nama_rubrik }}
                      (<input type="number" name="bobot[{{ $rubric->id }}]" value="{{ $rubric->bobot }}" class="weight-input" style="width:50px;" data-id="{{ $rubric->id }}">%)
                    </th>
                  @empty
                    <th>Komponen Penilaian Belum Ada</th>
                  @endforelse
                  <th style="width:100px">Nilai Akhir</th>
                </tr>
              </thead>
              <tbody>
                @forelse (($mahasiswa ?? collect()) as $m)
                <tr class="student-row" data-nim="{{ $m->nim }}">
                  <td>
                    <strong>{{ $m->nama }}</strong><br>
                    <small>{{ $m->nim }}</small>
                  </td>
                  @forelse (($rubrics ?? collect()) as $rubric)
                  <td>
                    <div class="grade-cell">
                      <input type="number" class="grade-input"
                             name="nilai[{{ $m->nim }}][{{ $rubric->id }}]"
                             value="{{ $m->penilaian->firstWhere('rubric_id', $rubric->id)->nilai ?? '' }}"
                             min="0" max="100"
                             data-nim="{{ $m->nim }}" data-rubric-id="{{ $rubric->id }}">
                      <button type="button" class="btn-delete-grade">&times;</button>
                    </div>
                  </td>
                  @empty
                    <td>-</td>
                  @endforelse
                  <td class="final-grade" id="final-grade-{{ $m->nim }}">0.00</td>
                </tr>
                @empty
                <tr>
                  <td colspan="{{ count($rubrics ?? []) + 2 }}" style="text-align:center; padding: 20px;">
                    Pilih Mata Kuliah untuk menampilkan mahasiswa dan komponen penilaian.
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="card-ft">
            <div class="toolbar">
              <div id="total-bobot-container">Total Bobot: <strong id="total-bobot">{{ $totalBobot }}</strong>%</div>
              @if (isset($mahasiswa) && method_exists($mahasiswa, 'hasPages') && $mahasiswa->hasPages())
                {{ $mahasiswa->links() }}
              @endif
            </div>
          </div>
        </div>
      </form>
    </div>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
        const gradeForm = document.getElementById('grade-form');
        if (!gradeForm) return;

        const csrfToken = '{{ csrf_token() }}';

        // init
        document.querySelectorAll('.student-row').forEach(row => {
            calculateFinalGrade(row);
            row.querySelectorAll('.grade-input').forEach(input => {
                updateDeleteButtonVisibility(input);
            });
        });
        updateTotalBobot();

        gradeForm.addEventListener('input', function (e) {
            if (e.target.classList.contains('grade-input')) {
                const input = e.target;
                input.classList.add('dirty');
                updateDeleteButtonVisibility(input);
                calculateFinalGrade(input.closest('.student-row'));
            }
            if (e.target.classList.contains('weight-input')) {
                updateTotalBobot();
                document.querySelectorAll('.student-row').forEach(row => calculateFinalGrade(row));
            }
        });

        gradeForm.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-delete-grade')) {
                handleDeleteGrade(e.target);
            }
        });

        function handleDeleteGrade(button) {
            const input = button.previousElementSibling;
            const nim = input.dataset.nim;
            const rubricId = input.dataset.rubricId;

            if (!confirm(`Anda yakin ingin menghapus nilai ini? Aksi ini tidak dapat dibatalkan.`)) return;

            const url = `/dosenpenguji/penilaian/grade/${nim}/${rubricId}`;

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(err.message || 'Gagal menghubungi server.') });
                }
                return response.json();
            })
            .then(() => {
                input.value = '';
                input.classList.remove('dirty');
                updateDeleteButtonVisibility(input);
                calculateFinalGrade(input.closest('.student-row'));
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan: ' + error.message);
            });
        }

        function updateDeleteButtonVisibility(input) {
            const deleteBtn = input.nextElementSibling;
            if (deleteBtn) {
                deleteBtn.style.display = input.value.trim() !== '' ? 'block' : 'none';
            }
        }

        function calculateFinalGrade(studentRow) {
            let totalNilaiBerbobot = 0;
            let totalBobot = 0;
            const gradeInputs = studentRow.querySelectorAll('.grade-input');

            gradeInputs.forEach(input => {
                const rubricId = input.dataset.rubricId;
                const weightInput = document.querySelector(`.weight-input[data-id='${rubricId}']`);
                const bobot = parseFloat(weightInput.value) || 0;
                const nilai = parseFloat(input.value) || 0;

                if (input.value !== '') {
                    totalNilaiBerbobot += (nilai * bobot);
                    totalBobot += bobot;
                }
            });

            const finalGrade = totalBobot > 0 ? (totalNilaiBerbobot / totalBobot) : 0;
            const finalGradeCell = studentRow.querySelector('.final-grade');
            finalGradeCell.textContent = finalGrade.toFixed(2);
        }

        function updateTotalBobot() {
            let total = 0;
            document.querySelectorAll('.weight-input').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            const totalBobotEl = document.getElementById('total-bobot');
            totalBobotEl.textContent = total;
            const container = document.getElementById('total-bobot-container');
            if (total !== 100) {
                container.classList.add('error');
            } else {
                container.classList.remove('error');
            }
        }
    });

    // Tutup sidebar ketika klik di luar (mobile)
    document.addEventListener('click', (e) => {
      const sb = document.getElementById('sidebar');
      if(!sb.classList.contains('show')) return;
      const btn = e.target.closest('.topbar-btn');
      if(!btn && !e.target.closest('#sidebar')) sb.classList.remove('show');
    });
  </script>

  {{-- Script dropdown Notifikasi --}}
  <script>
  document.addEventListener('DOMContentLoaded', function(){
    const bell = document.getElementById('bellBtn');
    const dd   = document.getElementById('notifDd');
    const dot  = document.getElementById('notifDot');
    if(!bell || !dd) return;

    bell.addEventListener('click', function(e){
      e.stopPropagation();
      dd.classList.toggle('active');
      if (dd.classList.contains('active') && dot) dot.style.display = 'none';
    });
    dd.addEventListener('click', e => e.stopPropagation());
    document.addEventListener('click', () => dd.classList.remove('active'));
  });
  </script>

  {{-- Script dropdown User --}}
  <script>
  document.addEventListener('DOMContentLoaded', function(){
    const btn = document.getElementById('userMenuBtn');
    const dd  = document.getElementById('userMenuDd');
    if(!btn || !dd) return;

    const open  = () => { dd.classList.add('active');  btn.setAttribute('aria-expanded','true');  };
    const close = () => { dd.classList.remove('active'); btn.setAttribute('aria-expanded','false'); };

    btn.addEventListener('click', function(e){
      e.stopPropagation();
      dd.classList.contains('active') ? close() : open();
    });
    dd.addEventListener('click', e => e.stopPropagation());
    document.addEventListener('click', close);
    document.addEventListener('keydown', e => { if(e.key === 'Escape') close(); });
  });
  </script>
</body>
</html>
