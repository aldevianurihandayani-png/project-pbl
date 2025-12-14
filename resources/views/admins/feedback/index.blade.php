@extends('layouts.admin')

@section('title', 'Kelola Feedback')
@section('page_title', 'Kelola Feedback')

@section('content')

<style>
    :root{
        --bg: #f6f8fc;
        --text: #0f172a;
        --muted: #64748b;
        --border: #e5e7eb;
        --card: #ffffff;

        --primary: #2c3e50;
        --primary-2: #1e2b37;

        --blue: #3498db;
        --teal: #17a2b8;
        --amber: #f59e0b;
        --green: #22c55e;
        --red: #ef4444;

        --radius: 14px;
    }

    /* Base */
    .page{
        background: var(--bg);
        padding: 18px;
        border-radius: 16px;
        font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Arial, "Noto Sans", "Helvetica Neue";
        color: var(--text);
    }

    .page-header { margin-bottom: 24px; }
    .page-header h1 { font-size: 28px; font-weight: 800; color: var(--primary-color); margin: 0 0 8px 0; letter-spacing: -0.02em; }
    .page-header p { font-size: 15px; color: var(--muted); margin: 0; }

    .feedback-container{
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 20px;
        align-items: start;
    }

    @media (max-width: 1200px){
        .feedback-container{ grid-template-columns: 1fr; }
    }

    /* Card */
    .card{
        background: var(--card);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        padding: 22px;
        margin-bottom: 18px;
    }

    /* Header + button */
    .filter-card-header{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap: 12px;
        margin-bottom: 16px;
    }
    .filter-card-header h2{
        font-size: 18px;
        font-weight: 800;
        margin: 0;
        letter-spacing: -0.01em;
    }

    .add-feedback-btn{
        display:inline-flex;
        align-items:center;
        gap: 8px;
        background: linear-gradient(135deg, var(--blue), #2563eb);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
        text-decoration: none;
        box-shadow: 0 10px 18px rgba(37,99,235,0.18);
        white-space: nowrap;
    }
    .add-feedback-btn:hover{
        transform: translateY(-1px);
        filter: brightness(1.03);
    }

    /* Form */
    .filter-form{
        display:grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        align-items:end;
        margin-top: 4px;
    }
    @media (max-width: 768px){
        .filter-form{ grid-template-columns: 1fr; }
    }

    .form-group{ display:flex; flex-direction:column; }
    .form-group label{
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 6px;
        color: var(--muted);
    }

    .form-control{
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: 12px;
        font-size: 14px;
        width: 100%;
        box-sizing: border-box;
        background: #fff;
        transition: border-color .15s ease, box-shadow .15s ease;
        outline: none;
    }
    .form-control:focus{
        border-color: rgba(37,99,235,0.45);
        box-shadow: 0 0 0 4px rgba(37,99,235,0.12);
    }

    .btn-primary{
        background: var(--primary);
        color: #ffffff;
        border: none;
        border-radius: 12px;
        padding: 11px 14px;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        transition: transform .15s ease, background-color .15s ease, box-shadow .15s ease;
        width: 100%;
        box-shadow: 0 10px 18px rgba(15, 23, 42, 0.10);
    }
    .btn-primary:hover{
        background: var(--primary-2);
        transform: translateY(-1px);
    }

    /* Table */
    .feedback-table{ padding: 0; overflow: hidden; }
    .feedback-table table{
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .feedback-table thead th{
        background: #f8fafc;
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: var(--muted);
        padding: 14px 16px;
        border-bottom: 1px solid var(--border);
    }

    .feedback-table tbody td{
        padding: 16px;
        border-bottom: 1px solid var(--border);
        vertical-align: top;
        font-size: 14px;
        color: #0f172a;
        background: #fff;
    }
    .feedback-table tbody tr:nth-child(even) td{
        background: #fcfdff;
    }
    .feedback-table tbody tr:hover td{
        background: #f5f9ff;
    }

    .info-cell .name{
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 4px;
    }
    .info-cell .email,
    .info-cell .date,
    .info-cell .category{
        font-size: 12px;
        color: var(--muted);
        display:block;
        line-height: 1.35;
    }
    .info-cell .category strong{ color: #0f172a; }

    /* Status badge */
    .status-badge{
        display:inline-flex;
        align-items:center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 900;
        text-transform: capitalize;
        border: 1px solid rgba(15,23,42,0.08);
    }
    .status-baru{ background: rgba(23,162,184,0.12); color: #0e7490; }
    .status-diproses{ background: rgba(245,158,11,0.14); color: #92400e; }
    .status-selesai{ background: rgba(34,197,94,0.14); color: #166534; }

    /* ✅ Action buttons (UPDATED: no underline + konsisten) */
    .action-buttons{
        display:flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        min-width: 220px;
    }
    .action-buttons a,
    .action-buttons a:hover,
    .action-buttons a:focus,
    .action-buttons a:active{
        text-decoration: none !important;
    }
    .action-buttons .btn{
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-width: 92px;
        border-radius: 12px !important;
        padding: 8px 12px !important;
        font-weight: 800;
        border: 1px solid rgba(15,23,42,0.10);
        box-shadow: 0 8px 16px rgba(15,23,42,0.06);
        transition: transform .15s ease, filter .15s ease;
        white-space: nowrap;
    }
    .action-buttons .btn:hover{
        transform: translateY(-1px);
        filter: brightness(1.02);
    }
    .action-buttons .btn i{
        margin-right: 0 !important;
    }

    /* Sidebar summary */
    .summary-card h3{
        font-size: 16px;
        font-weight: 900;
        margin: 0 0 12px 0;
        letter-spacing: -0.01em;
    }
    .summary-card .summary-item{
        display:flex;
        justify-content:space-between;
        align-items:center;
        padding: 12px 0;
        border-bottom: 1px dashed var(--border);
    }
    .summary-card .summary-item:last-child{ border-bottom: none; }

    .summary-card .summary-item .label{
        font-weight: 800;
        color: var(--muted);
    }
    .summary-card .summary-item .count{
        font-size: 20px;
        font-weight: 1000;
        color: #2563eb;
    }

    .quick-actions{ margin-top: 16px; display:flex; flex-direction:column; gap: 10px; }
    .btn-quick-action{
        text-align:left;
        padding: 12px 14px;
        border: 1px solid var(--border);
        background-color: #ffffff;
        border-radius: 14px;
        cursor: pointer;
        font-weight: 900;
        color: #0f172a;
        transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
    }
    .btn-quick-action:hover{
        transform: translateY(-1px);
        border-color: rgba(37,99,235,0.35);
        box-shadow: 0 10px 18px rgba(37,99,235,0.10);
    }

    /* Modal */
    .modal{
        display:none;
        position: fixed;
        z-index: 1000;
        inset: 0;
        background-color: rgba(2,6,23,0.55);
        padding: 40px 14px;
        backdrop-filter: blur(4px);
    }

    .modal-content{
        background:#fff;
        margin: 0 auto;
        padding: 0;
        border: 1px solid rgba(15,23,42,0.10);
        width: 100%;
        max-width: 520px;
        border-radius: 18px;
        box-shadow: 0 18px 55px rgba(2,6,23,0.35);
        overflow: hidden;
        animation: modalPop .22s ease;
    }
    @keyframes modalPop{
        from{ transform: translateY(10px); opacity: 0; }
        to{ transform: translateY(0); opacity: 1; }
    }

    .modal-header{
        padding: 14px 16px;
        display:flex;
        justify-content:space-between;
        align-items:center;
        border-bottom: 1px solid var(--border);
        background: #f8fafc;
    }
    .modal-header h2{
        margin:0;
        font-size: 16px;
        font-weight: 1000;
        color: #0f172a;
    }
    .close-btn, .close-btn-view, .close-btn-status, .close-btn-reply{
        color: #64748b;
        font-size: 26px;
        font-weight: 900;
        cursor:pointer;
        line-height: 1;
        transition: color .15s ease, transform .15s ease;
        user-select: none;
    }
    .close-btn:hover, .close-btn-view:hover, .close-btn-status:hover, .close-btn-reply:hover{
        color:#0f172a;
        transform: scale(1.05);
    }

    .modal-body{
        padding: 16px;
    }

    /* Detail in modal */
    .detail-text{
        margin: 0;
        padding: 10px 12px;
        background: #f8fafc;
        border: 1px solid var(--border);
        border-radius: 12px;
        color: #0f172a;
        font-weight: 600;
        font-size: 14px;
    }

    .modal-body textarea{ resize: vertical; min-height: 110px; }

    /* Alert prettier */
    .alert{ border-radius: 14px; padding: 12px 14px; border: 1px solid rgba(15,23,42,0.08); }
</style>

<div class="page">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin:0; padding-left: 18px;">
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
                            <th style="width: 260px;">Aksi</th>
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
                                       class="btn btn-info btn-sm view-btn"
                                       title="Lihat Detail"
                                       data-id="{{ $feedback->id }}"
                                       data-nama="{{ optional($feedback->user)->name ?? 'User ID: '.$feedback->id_user }}"
                                       data-email="{{ optional($feedback->user)->email ?? '-' }}"
                                       data-tanggal="{{ $feedback->tanggal ? $feedback->tanggal->format('Y-m-d H:i:s') : '' }}"
                                       data-isi="{{ $feedback->isi }}"
                                       data-status="{{ $feedback->status }}"
                                    >
                                        <i class="fa-solid fa-eye"></i> Lihat
                                    </a>

                                    {{-- ✅ SEKARANG BISA DIKLIK (STATUS) --}}
                                    <a href="#"
                                       class="btn btn-warning btn-sm status-btn"
                                       title="Ubah Status Feedback"
                                       data-id="{{ $feedback->id }}"
                                       data-status="{{ $feedback->status }}"
                                    >
                                        <i class="fa-solid fa-pen-to-square"></i> Status
                                    </a>

                                    {{-- ✅ SEKARANG BISA DIKLIK (BALAS) --}}
                                    <a href="#"
                                       class="btn btn-success btn-sm reply-btn"
                                       title="Balas Feedback"
                                       data-id="{{ $feedback->id }}"
                                       data-nama="{{ optional($feedback->user)->name ?? 'User ID: '.$feedback->id_user }}"
                                       data-email="{{ optional($feedback->user)->email ?? '-' }}"
                                    >
                                        <i class="fa-solid fa-reply"></i> Balas
                                    </a>

                                    <form action="{{ route('admins.feedback.destroy', ['feedback' => $feedback->id]) }}"
                                          method="POST"
                                          style="display: inline;"
                                          onsubmit="return confirm('Anda yakin ingin menghapus feedback ini?');">
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
                                <td colspan="5" style="text-align: center; padding: 20px; color: var(--muted);">
                                    Tidak ada feedback.
                                </td>
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

{{-- ✅ Modal Ubah Status --}}
<div id="statusFeedbackModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Ubah Status Feedback</h2>
            <span class="close-btn-status">&times;</span>
        </div>
        <div class="modal-body">
            <form id="statusFeedbackForm" method="POST">
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <label for="statusSelect">Status</label>
                    <select id="statusSelect" name="status" class="form-control" required>
                        <option value="baru">Baru</option>
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>

                <button type="submit" class="btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>

{{-- ✅ Modal Balas --}}
<div id="replyFeedbackModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Balas Feedback</h2>
            <span class="close-btn-reply">&times;</span>
        </div>
        <div class="modal-body">
            <form id="replyFeedbackForm" method="POST">
                @csrf

                <div class="form-group">
                    <label>Penerima</label>
                    <p class="detail-text" id="replyReceiver">-</p>
                </div>

                <div class="form-group">
                    <label for="replyMessage">Pesan Balasan</label>
                    <textarea id="replyMessage" name="reply" class="form-control" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn-primary">Kirim Balasan</button>
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

    // ✅ Modal Status
    const statusModal = document.getElementById("statusFeedbackModal");
    const closeStatusBtn = statusModal.querySelector(".close-btn-status");
    const statusForm = document.getElementById("statusFeedbackForm");
    const statusSelect = document.getElementById("statusSelect");

    document.querySelectorAll('.status-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const id = this.dataset.id;
            const currentStatus = this.dataset.status || 'baru';

            statusSelect.value = currentStatus;

            // NOTE: sesuaikan jika route kamu berbeda
            statusForm.action = `/admins/feedback/${id}/status`;

            statusModal.style.display = "block";
        });
    });

    closeStatusBtn.onclick = function() {
        statusModal.style.display = "none";
    }

    // ✅ Modal Reply
    const replyModal = document.getElementById("replyFeedbackModal");
    const closeReplyBtn = replyModal.querySelector(".close-btn-reply");
    const replyForm = document.getElementById("replyFeedbackForm");
    const replyReceiver = document.getElementById("replyReceiver");

    document.querySelectorAll('.reply-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const id = this.dataset.id;
            const nama = this.dataset.nama || '-';
            const email = this.dataset.email || '-';

            replyReceiver.textContent = `${nama} (${email})`;

            // NOTE: sesuaikan jika route kamu berbeda
            replyForm.action = `/admins/feedback/${id}/reply`;

            replyModal.style.display = "block";
        });
    });

    closeReplyBtn.onclick = function() {
        replyModal.style.display = "none";
    }

    // Tutup modal kalau klik di luar
    window.onclick = function(event) {
      if (event.target == addModal) addModal.style.display = "none";
      if (event.target == viewModal) viewModal.style.display = "none";
      if (event.target == statusModal) statusModal.style.display = "none";
      if (event.target == replyModal) replyModal.style.display = "none";
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
