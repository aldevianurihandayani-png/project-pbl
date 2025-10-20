<<<<<<< HEAD
@include('admins.partials.header', ['title' => 'Manajemen Feedback'])

<style>
    .feedback-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 20px;
        padding: 20px;
        border: 1px solid #eef1f6;
    }
    .feedback-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .feedback-sender {
        font-weight: bold;
        color: var(--navy-2);
    }
    .feedback-email {
        font-size: 0.9em;
        color: var(--muted);
    }
    .feedback-timestamp {
        font-size: 0.8em;
        color: var(--muted);
    }
    .feedback-subject {
        font-size: 1.1em;
        font-weight: bold;
        margin-bottom: 10px;
        color: #333;
    }
    .feedback-message {
        line-height: 1.6;
        color: #555;
    }
</style>

<div class="card shadow mb-4">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Feedback</h6>
    </div>
    <div class="card-body">
        @forelse ($feedbacks as $feedback)
            <div class="feedback-card">
                <div class="feedback-header">
                    <div>
                        <div class="feedback-sender">{{ $feedback->name }}</div>
                        <div class="feedback-email">Surel Pengirim: {{ $feedback->email }}</div>
                    </div>
                    <div class="feedback-timestamp">{{ $feedback->created_at->format('d M Y H:i') }}</div>
                </div>
                <div class="feedback-subject">{{ $feedback->subject }}</div>
                <div class="feedback-message">{{ $feedback->message }}</div>
                {{-- Anda bisa menambahkan tombol aksi di sini jika diperlukan --}}
                {{-- <div class="mt-3 text-right">
                    <a href="#" class="btn btn-sm btn-primary">Balas</a>
                    <a href="#" class="btn btn-sm btn-danger">Hapus</a>
                </div> --}}
            </div>
        @empty
            <div class="text-center text-muted">
                Tidak ada data feedback.
            </div>
        @endforelse

        <div class="d-flex justify-content-center mt-4">
            {{ $feedbacks->links() }}
        </div>
    </div>
</div>

@include('admins.partials.footer')
=======
{{-- resources/views/admins/feedback/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Kelola Feedback — Admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root{
      --navy:#0b1d54; --navy-2:#0e257a; --bg:#f5f7fb; --card:#ffffff;
      --muted:#6c7a8a; --ring:rgba(13,23,84,.10); --shadow:0 6px 20px rgba(13,23,84,.08);
      --radius:16px;
      --primary-color: #2c3e50; --secondary-color: #3498db; --background-color: #f4f6f9;
      --white-color: #ffffff; --text-color: #333; --border-color: #e0e0e0;
      --shadow-color: rgba(0, 0, 0, 0.05); --success-color: #28a745;
      --warning-color: #ffc107; --info-color: #17a2b8;
    }
    *{box-sizing:border-box}
    body{
      margin:0; font-family:Arial,Helvetica,sans-serif; background:var(--bg);
      display:grid; grid-template-columns:260px 1fr; min-height:100vh;
    }

    /* ========== SIDEBAR ========== */
    .sidebar{
      background:var(--navy); color:#e9edf7; padding:18px 16px; display:flex; flex-direction:column;
    }
    .brand{
      display:flex; align-items:center; gap:10px; margin-bottom:22px;
    }
    .brand-badge{
      width:36px;height:36px; border-radius:10px; background:#1a2a6b; display:grid; place-items:center;
      font-weight:700; letter-spacing:.5px;
    }
    .brand-title{line-height:1.1}
    .brand-title strong{font-size:18px}
    .brand-title small{display:block; font-size:12px; opacity:.85}

    .nav-title{font-size:12px; letter-spacing:.6px; text-transform:uppercase; opacity:.7; margin:16px 10px 6px}
    .menu a{
      display:flex; align-items:center; gap:12px; text-decoration:none;
      color:#e9edf7; padding:10px 12px; border-radius:12px; margin:4px 6px;
      transition:background .18s, transform .18s;
    }
    .menu a:hover{ background:#11245f; transform:translateX(2px) }
    .menu a.active{ background:#1c3d86 }
    .menu i{ width:18px; text-align:center }

    .logout{ margin-top:auto }
    .logout a{ color:#ffb2b2 }
    .logout a:hover{ background:#5c1020 }

    /* ========== MAIN ========== */
    main{ display:flex; flex-direction:column; min-width:0 }
    header.topbar{
      background:#0a1a54; color:#fff; padding:12px 22px; display:flex; align-items:center; justify-content:space-between;
      position:sticky; top:0; z-index:3; box-shadow:var(--shadow);
    }
    .welcome h1{ margin:0; font-size:18px; letter-spacing:.2px }
    .userbox{ display:flex; align-items:center; gap:14px }
    .notif{ position:relative; }
    .notif i{ font-size:18px }
    .notif .badge{ position:absolute; top:-6px; right:-6px; background:#e53935; color:#fff; border-radius:10px; font-size:10px; padding:2px 5px }

    .page{ padding:26px; }

    @media (max-width: 980px){
      body{ grid-template-columns:1fr }
      .sidebar{ position:fixed; inset:0 auto 0 0; width:240px; transform:translateX(-102%); transition:transform .2s; z-index:10 }
      .sidebar.show{ transform:none }
      .topbar-btn{ display:inline-flex }
    }
    .topbar-btn{ display:none; border:0; background:transparent; color:#fff; font-size:20px; cursor:pointer }
    a.card-link{ text-decoration:none }

    /* Custom Feedback Page Styles */
    .feedback-container {
        display: flex;
        gap: 24px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .main-content { flex-grow: 1; }
    .sidebar-summary { flex-basis: 300px; min-width: 300px; }
    .card {
        background-color: var(--white-color);
        border-radius: 12px;
        box-shadow: 0 4px 12px var(--shadow-color);
        border: 1px solid var(--border-color);
        padding: 24px;
        margin-bottom: 24px;
    }
    .page-header { margin-bottom: 24px; }
    .page-header h1 { font-size: 28px; font-weight: 700; color: var(--primary-color); margin: 0 0 8px 0; }
    .page-header p { font-size: 16px; color: #6c757d; margin: 0; }
    .filter-card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .filter-card-header h2 { font-size: 18px; font-weight: 600; margin: 0; }
    .add-feedback-btn { background-color: var(--secondary-color); color: var(--white-color); border: none; border-radius: 8px; padding: 10px 16px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background-color 0.3s ease; text-decoration: none; }
    .add-feedback-btn:hover { background-color: #2980b9; }
    .filter-form { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; align-items: flex-end; }
    .form-group { display: flex; flex-direction: column; }
    .form-group label { font-size: 14px; font-weight: 500; margin-bottom: 6px; color: #495057; }
    .form-control { padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; width: 100%; box-sizing: border-box; }
    .btn-primary { background-color: var(--primary-color); color: var(--white-color); border: none; border-radius: 8px; padding: 12px 16px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background-color 0.3s ease; width: 100%; }
    .btn-primary:hover { background-color: #1e2b37; }
    .feedback-table table { width: 100%; border-collapse: collapse; }
    .feedback-table th, .feedback-table td { padding: 16px; text-align: left; border-bottom: 1px solid var(--border-color); vertical-align: top; }
    .feedback-table th { font-size: 12px; font-weight: 700; text-transform: uppercase; color: #6c757d; }
    .feedback-table td { font-size: 14px; }
    .info-cell .name { font-weight: 600; color: var(--primary-color); }
    .info-cell .email, .info-cell .date, .info-cell .category { font-size: 12px; color: #6c757d; display: block; }
    .info-cell .category { font-weight: 500; }
    .status-badge { display: inline-block; padding: 4px 10px; border-radius: 15px; font-size: 12px; font-weight: 600; color: var(--white-color); text-transform: capitalize; }
    .status-baru { background-color: var(--info-color); }
    .status-diproses { background-color: var(--warning-color); color: #333; }
    .status-selesai { background-color: var(--success-color); }
    .action-buttons .btn-action { background: none; border: none; cursor: pointer; padding: 5px; margin-right: 5px; font-size: 16px; color: #6c757d; transition: color 0.3s ease; }
    .action-buttons .btn-action:hover { color: var(--primary-color); }
    .summary-card .summary-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid var(--border-color); }
    .summary-card .summary-item:last-child { border-bottom: none; }
    .summary-card .summary-item .label { font-weight: 600; }
    .summary-card .summary-item .count { font-size: 20px; font-weight: 700; color: var(--secondary-color); }
    .summary-card h3 { font-size: 18px; font-weight: 600; margin-top: 0; margin-bottom: 10px; }
    .quick-actions { margin-top: 20px; display: flex; flex-direction: column; gap: 10px; }
    .btn-quick-action { text-align: left; padding: 12px 16px; border: 1px solid var(--border-color); background-color: #fdfdfd; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s ease; }
    .btn-quick-action:hover { background-color: var(--background-color); color: var(--primary-color); border-color: var(--secondary-color); }
    @media (max-width: 1200px) {
        .feedback-container { flex-direction: column-reverse; }
        .sidebar-summary { flex-basis: auto; min-width: 0; }
    }
        @media (max-width: 768px) { .filter-form { grid-template-columns: 1fr; } }
    
        /* Modal Styles */
        .modal {
          display: none; 
          position: fixed; 
          z-index: 1000; 
          left: 0;
          top: 0;
          width: 100%; 
          height: 100%; 
          overflow: auto; 
          background-color: rgba(0,0,0,0.5);
          padding-top: 60px;
        }
        .modal-content {
          background-color: #fefefe;
          margin: 5% auto;
          padding: 20px;
          border: 1px solid #888;
          width: 80%;
          max-width: 500px;
          border-radius: 12px;
          box-shadow: 0 5px 15px rgba(0,0,0,0.3);
          animation: animatetop 0.4s;
        }
        @keyframes animatetop {
          from {top: -300px; opacity: 0}
          to {top: 0; opacity: 1}
        }
        .modal-header {
          padding: 10px 16px;
          display: flex;
          justify-content: space-between;
          align-items: center;
          border-bottom: 1px solid #e5e5e5;
        }
        .modal-header h2 {
          margin: 0;
          font-size: 20px;
          color: var(--primary-color);
        }
        .close-btn {
          color: #aaa;
          font-size: 28px;
          font-weight: bold;
          cursor: pointer;
        }
        .close-btn:hover,
        .close-btn:focus {
          color: black;
        }
        .modal-body {
          padding: 16px;
        }
        .modal-body .form-group {
          margin-bottom: 15px;
        }
        .modal-body textarea {
            resize: vertical;
        }
      </style>
    </head>
    <body>
    
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
          <a href="{{ url('/admins/dashboard') }}"><i class="fa-solid fa-house"></i>Dashboard</a>
          <a href="{{ url('/admins/matakuliah') }}"><i class="fa-solid fa-user-graduate"></i>Mata Kuliah</a>
          <a href="{{ route('admins.feedback.index') }}" class="active"><i class="fa-solid fa-comments"></i>Feedback</a>
          <a href="#"><i class="fa-solid fa-bell"></i>Notifikasi</a>
          
          <div class="nav-title">Akun</div>
          <a href="#"><i class="fa-solid fa-id-badge"></i>Profil</a>
        </div>
    
        <div class="logout">
            <form action="{{ route('logout') }}" method="POST" style="display: none;" id="logout-form">
                @csrf
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="menu" style="display:block">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
      </aside>
    
      <!-- ========== MAIN ========== -->
      <main>
        <header class="topbar">
          <button class="topbar-btn" onclick="document.getElementById('sidebar').classList.toggle('show')">
            <i class="fa-solid fa-bars"></i>
          </button>
          <div class="welcome">
            <h1>Kelola Feedback</h1>
          </div>
          <div class="userbox">
            <div class="notif">
              <i class="fa-regular fa-bell"></i>
              <span class="badge">3</span>
            </div>
            <div style="display:flex;align-items:center;gap:10px">
              <div style="width:32px;height:32px;border-radius:50%;background:#e3e9ff;display:grid;place-items:center;color:#31408a;font-weight:700">
                {{ strtoupper(substr(auth()->user()->name ?? 'NU',0,2)) }}
              </div>
              <strong>{{ auth()->user()->name ?? 'Nama User' }}</strong>
            </div>
          </div>
        </header>
    
            <div class="page">
        
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
        
              <div class="feedback-container">
                <div class="main-content">
                    <div class="card filter-card">
                        <div class="filter-card-header">
                            <h2>Daftar Feedback</h2>
                            <a href="#" class="add-feedback-btn">+ Tambah Feedback</a>
                        </div>
                        <form class="filter-form">
                            <div class="form-group">
                                <label for="search">Pencarian</label>
                                <input type="text" id="search" class="form-control" placeholder="Cari nama, email, isi...">
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select id="status" class="form-control">
                                    <option value="semua">Semua</option>
                                    <option value="baru">Baru</option>
                                    <option value="diproses">Diproses</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="kategori">Kategori</label>
                                <select id="kategori" class="form-control">
                                    <option value="semua">Semua</option>
                                    <option value="umum">Umum</option>
                                    <option value="bug">Laporan Bug</option>
                                    <option value="fitur">Saran Fitur</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn-primary">Cari</button>
                            </div>
                        </form>
                    </div>
        
                    <div class="card feedback-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Info Pengirim</th>
                                    <th>Pesan</th>
                                    <th>Balasan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($displayFeedbacks as $feedback)
                                    <tr>
                                        <td class="info-cell">
                                            <div class="name">{{ $feedback->name }}</div>
                                            <div class="email">{{ $feedback->email }}</div>
                                            <div class="date">{{ $feedback->created_at->format('d M Y, H:i') }}</div>
                                            <div class="category">Kategori: <strong>{{ $feedback->category }}</strong></div>
                                        </td>
                                        <td>{{ Str::limit($feedback->message, 50) }}</td>
                                        <td>—</td>
                                        <td><span class="status-badge status-{{ $feedback->status }}">{{ $feedback->status }}</span></td>
                                        <td class="action-buttons">
                                            <a href="#" class="btn btn-info btn-sm mr-1 view-btn" title="Lihat Detail">
                                                <i class="fa-solid fa-eye"></i> Lihat
                                            </a>
                                            <a href="#" class="btn btn-warning btn-sm mr-1" title="Ubah Status Feedback">
                                                <i class="fa-solid fa-edit"></i> Status
                                            </a>
                                            <a href="#" class="btn btn-success btn-sm mr-1" title="Balas Feedback">
                                                <i class="fa-solid fa-reply"></i> Balas
                                            </a>
                                            <form action="{{ route('admins.feedback.destroy', $feedback) }}" method="POST" style="display: inline;" onsubmit="return confirm('Anda yakin ingin menghapus feedback ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus Feedback">
                                                    <i class="fa-solid fa-trash-alt"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 20px;">Tidak ada feedback.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
        
                <div class="sidebar-summary">
                    <div class="card summary-card">
                        <h3>Ringkasan Feedback</h3>
                        <div class="summary-item">
                            <span class="label">Baru</span>
                            <span class="count">{{ $allFeedbacks->where('status', 'baru')->count() }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Diproses</span>
                            <span class="count">{{ $allFeedbacks->where('status', 'diproses')->count() }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Selesai</span>
                            <span class="count">{{ $allFeedbacks->where('status', 'selesai')->count() }}</span>
                        </div>
                                                        <div class="quick-actions">
                                                            <h3>Aksi Cepat</h3>
                                                            <button class="btn-quick-action filter-status-btn" data-status="baru">Lihat yang Baru</button>
                                                            <button class="btn-quick-action filter-status-btn" data-status="diproses">Lihat yang Diproses</button>
                                                            <button class="btn-quick-action filter-status-btn" data-status="selesai">Lihat yang Selesai</button>
                                                        </div>                    </div>
                </div>
              </div>
            </div>
          </main>
        
          <!-- Modal View Feedback -->
          <div id="viewFeedbackModal" class="modal">
            <div class="modal-content">
              <div class="modal-header">
                <h2>Detail Feedback</h2>
                <span class="close-btn-view">&times;</span>
              </div>
              <div class="modal-body" id="viewFeedbackBody">
                <!-- Content will be populated by JS -->
              </div>
            </div>
          </div>

          <!-- Modal Tambah Feedback -->
          <div id="addFeedbackModal" class="modal">
            <div class="modal-content">
              <div class="modal-header">
                <h2>Tambah Feedback Baru</h2>
                <span class="close-btn">&times;</span>
              </div>
              <div class="modal-body">
                <form id="addFeedbackForm" action="{{ route('admins.feedback.store') }}" method="POST">
                  @csrf
                  <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                  </div>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                  </div>
                  <div class="form-group">
                    <label for="category">Kategori</label>
                    <select id="category" name="category" class="form-control">
                      <option value="umum">Umum</option>
                      <option value="bug">Laporan Bug</option>
                      <option value="fitur">Saran Fitur</option>
                      <option value="lainnya">Lainnya</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="message">Pesan Feedback</label>
                    <textarea id="message" name="message" class="form-control" rows="4" required></textarea>
                  </div>
                  <button type="submit" class="btn-primary">Kirim Feedback</button>
                </form>
              </div>
            </div>
          </div>
        
          <script>
            // Tutup sidebar ketika klik di luar (mobile)
            document.addEventListener('click', (e)=>{
              const sb = document.getElementById('sidebar');
              if(!sb.classList.contains('show')) return;
              const btn = e.target.closest('.topbar-btn');
              if(!btn && !e.target.closest('#sidebar')) sb.classList.remove('show');
            });
        
            // Add Modal Logic
            const addModal = document.getElementById("addFeedbackModal");
            const addBtn = document.querySelector(".add-feedback-btn");
            const closeAddBtn = addModal.querySelector(".close-btn");
        
            addBtn.onclick = function(e) {
              e.preventDefault();
              addModal.style.display = "block";
            }
        
            closeAddBtn.onclick = function() {
              addModal.style.display = "none";
            }
        
            // View Modal Logic
            const viewModal = document.getElementById("viewFeedbackModal");
            const closeViewBtn = viewModal.querySelector(".close-btn-view");

            document.querySelectorAll('.view-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const feedback = JSON.parse(this.dataset.feedback);
                    
                    const feedbackBody = document.getElementById('viewFeedbackBody');
                    feedbackBody.innerHTML = `
                        <div class="form-group">
                            <label>Nama</label>
                            <p class="detail-text">${feedback.name}</p>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <p class="detail-text">${feedback.email}</p>
                        </div>
                        <div class="form-group">
                            <label>Kategori</label>
                            <p class="detail-text">${feedback.category}</p>
                        </div>
                        <div class="form-group">
                            <label>Tanggal</label>
                            <p class="detail-text">${new Date(feedback.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' })}</p>
                        </div>
                        <div class="form-group">
                            <label>Pesan</label>
                            <p class="detail-text" style="white-space: pre-wrap; word-wrap: break-word;">${feedback.message}</p>
                        </div>
                    `;
                    
                    viewModal.style.display = "block";
                });
            });

            closeViewBtn.onclick = function() {
              viewModal.style.display = "none";
            }

            // Close modals if clicked outside
            window.onclick = function(event) {
              if (event.target == addModal) {
                addModal.style.display = "none";
              }
              if (event.target == viewModal) {
                viewModal.style.display = "none";
              }
            }

            // Quick Action Buttons Filtering
            document.querySelectorAll('.filter-status-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const status = this.dataset.status;
                    window.location.href = `{{ route('admins.feedback.index') }}?status=${status}`;
                });
            });
          </script>
        </body>
        </html>
        
        
        
>>>>>>> bbcfba2 (commit noorma)
