@extends('layouts.admin')

@section('title', 'Kelola Feedback')
@section('page_title', 'Kelola Feedback')

@section('content')

<style>
    .feedback-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 18px 20px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
    }

    .feedback-card-hd {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
    }

    .feedback-card-hd-title {
        font-size: 18px;
        font-weight: 600;
        color: #0b1d54;
    }

    .page-header { margin-bottom: 24px; }
    .page-header h1 { font-size: 28px; font-weight: 700; color: var(--primary-color); margin: 0 0 8px 0; }
    .page-header p { font-size: 16px; color: #6c757d; margin: 0; }
    .filter-card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .filter-card-header h2 { font-size: 18px; font-weight: 600; margin: 0; }
    .add-feedback-btn { background-color: var(--secondary-color); color: var(--white-color); border: none; border-radius: 8px; padding: 10px 16px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background-color 0.3s ease; text-decoration: none; }
    .add-feedback-btn:hover { background-color: #2980b9; }
    .feedback-container {
        display: flex;
        gap: 24px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .main-content { flex-grow: 1; }
    .sidebar-summary { flex-basis: 300px; min-width: 300px; }
    .card {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border: 1px solid #e0e0e0;
        padding: 24px;
        margin-bottom: 24px;
    }
    .filter-form { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; align-items: flex-end; }
    .form-group { display: flex; flex-direction: column; }
    .form-group label { font-size: 14px; font-weight: 500; margin-bottom: 6px; color: #495057; }
    .form-control { padding: 10px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 14px; width: 100%; box-sizing: border-box; }
    .btn-primary { background-color: #2c3e50; color: #ffffff; border: none; border-radius: 8px; padding: 12px 16px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background-color 0.3s ease; width: 100%; }
    .btn-primary:hover { background-color: #1e2b37; }
    .feedback-table table { width: 100%; border-collapse: collapse; }
    .feedback-table th, .feedback-table td { padding: 16px; text-align: left; border-bottom: 1px solid #e0e0e0; vertical-align: top; }
    .feedback-table th { font-size: 12px; font-weight: 700; text-transform: uppercase; color: #6c757d; }
    .feedback-table td { font-size: 14px; }
    .info-cell .name { font-weight: 600; color: #2c3e50; }
    .info-cell .email, .info-cell .date, .info-cell .category { font-size: 12px; color: #6c757d; display: block; }
    .info-cell .category { font-weight: 500; }
    .status-badge { display: inline-block; padding: 4px 10px; border-radius: 15px; font-size: 12px; font-weight: 600; color: #ffffff; text-transform: capitalize; }
    .status-baru { background-color: #17a2b8; }
    .status-diproses { background-color: #ffc107; color: #333; }
    .status-selesai { background-color: #28a745; }
    .summary-card .summary-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #e0e0e0; }
    .summary-card .summary-item:last-child { border-bottom: none; }
    .summary-card .summary-item .label { font-weight: 600; }
    .summary-card .summary-item .count { font-size: 20px; font-weight: 700; color: #3498db; }
    .summary-card h3 { font-size: 18px; font-weight: 600; margin-top: 0; margin-bottom: 10px; }
    .quick-actions { margin-top: 20px; display: flex; flex-direction: column; gap: 10px; }
    .btn-quick-action { text-align: left; padding: 12px 16px; border: 1px solid #e0e0e0; background-color: #fdfdfd; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s ease; }
    .btn-quick-action:hover { background-color: #f4f6f9; color: #2c3e50; border-color: #3498db; }
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
      color: #2c3e50;
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
                    {{-- dropdown kategori DIHAPUS --}}
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
                                    <div class="name">
                                        {{ optional($feedback->user)->name ?? 'User ID: '.$feedback->id_user }}
                                    </div>
                                    <div class="email">
                                        {{ optional($feedback->user)->email ?? '-' }}
                                    </div>
                                    <div class="date">
                                        {{ $feedback->tanggal ? \Carbon\Carbon::parse($feedback->tanggal)->format('d M Y, H:i') : '-' }}
                                    </div>
                                    <div class="category">
                                        Kategori: <strong>-</strong>
                                    </div>
                                </td>
                                <td>{{ \Illuminate\Support\Str::limit($feedback->isi, 80) }}</td>
                                <td>—</td>
                                <td>
                                    <span class="status-badge status-{{ $feedback->status }}">
                                        {{ ucfirst($feedback->status) }}
                                    </span>
                                </td>
                                <td class="action-buttons">
                                    <a href="#"
                                       class="btn btn-info btn-sm mr-1 view-btn"
                                       title="Lihat Detail"
                                       data-nama="{{ optional($feedback->user)->name ?? 'User ID: '.$feedback->id_user }}"
                                       data-email="{{ optional($feedback->user)->email ?? '-' }}"
                                       data-tanggal="{{ $feedback->tanggal ? $feedback->tanggal->format('Y-m-d H:i:s') : '' }}"
                                       data-isi="{{ $feedback->isi }}"
                                       data-status="{{ $feedback->status }}"
                                    >
                                        <i class="fa-solid fa-eye"></i> Lihat
                                    </a>
                                    <a href="#" class="btn btn-warning btn-sm mr-1" title="Ubah Status Feedback">
                                        <i class="fa-solid fa-edit"></i> Status
                                    </a>
                                    <a href="#" class="btn btn-success btn-sm mr-1" title="Balas Feedback">
                                        <i class="fa-solid fa-reply"></i> Balas
                                    </a>

                                    {{-- ✅ FIX CUMA DI BARIS ACTION INI --}}
                                    <form action="{{ route('admins.feedback.destroy', ['feedback' => $feedback->id]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Anda yakin ingin menghapus feedback ini?');">
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
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal View Feedback --}}
<div id="viewFeedbackModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Detail Feedback</h2>
            <span class="close-btn-view">&times;</span>
        </div>
        <div class="modal-body" id="viewFeedbackBody">
            <!-- diisi via JS -->
        </div>
    </div>
</div>

{{-- Modal Tambah Feedback --}}
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
                    <label for="message">Pesan Feedback</label>
                    <textarea id="message" name="message" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn-primary">Kirim Feedback</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Modal tambah
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

    // Modal view
    const viewModal = document.getElementById("viewFeedbackModal");
    const closeViewBtn = viewModal.querySelector(".close-btn-view");

    document.querySelectorAll('.view-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const feedback = {
                nama: this.dataset.nama,
                email: this.dataset.email,
                tanggal: this.dataset.tanggal,
                isi: this.dataset.isi,
                status: this.dataset.status,
            };

            const feedbackBody = document.getElementById('viewFeedbackBody');
            feedbackBody.innerHTML = `
                <div class="form-group">
                    <label>Nama</label>
                    <p class="detail-text">${feedback.nama}</p>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <p class="detail-text">${feedback.email}</p>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <p class="detail-text">${feedback.status}</p>
                </div>
                <div class="form-group">
                    <label>Tanggal</label>
                    <p class="detail-text">${
                        feedback.tanggal
                          ? new Date(feedback.tanggal).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' })
                          : '-'
                    }</p>
                </div>
                <div class="form-group">
                    <label>Pesan</label>
                    <p class="detail-text" style="white-space: pre-wrap; word-wrap: break-word;">${feedback.isi}</p>
                </div>
            `;

            viewModal.style.display = "block";
        });
    });

    closeViewBtn.onclick = function() {
      viewModal.style.display = "none";
    }

    // Tutup modal kalau klik di luar
    window.onclick = function(event) {
      if (event.target == addModal) {
        addModal.style.display = "none";
      }
      if (event.target == viewModal) {
        viewModal.style.display = "none";
      }
    }

    // Tombol filter status cepat
    document.querySelectorAll('.filter-status-btn').forEach(button => {
        button.addEventListener('click', function() {
            const status = this.dataset.status;
            window.location.href = `{{ route('admins.feedback.index') }}?status=${status}`;
        });
    });
</script>

@endsection
