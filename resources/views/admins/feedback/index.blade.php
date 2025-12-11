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

    .filter-row {
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr auto;
        gap: 10px;
        margin-bottom: 16px;
    }

    .form-control-simap {
        width: 100%;
        padding: 8px 10px;
        border-radius: 9px;
        border: 1px solid #d5d9ee;
        font-size: 14px;
        background: #f8f9ff;
        transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
    }

    .form-control-simap:focus {
        outline: none;
        border-color: #0b1d54;
        box-shadow: 0 0 0 2px rgba(11, 29, 84, 0.15);
        background: #ffffff;
    }

    .btn-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 16px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid transparent;
        cursor: pointer;
        text-decoration: none;
        white-space: nowrap;
        transition: background .15s ease, color .15s ease, border-color .15s ease,
                    box-shadow .15s ease, transform .05s ease;
    }

    .btn-pill-primary {
        background: #0b1d54;
        border-color: #0b1d54;
        color: #ffffff;
        box-shadow: 0 6px 18px rgba(11, 29, 84, 0.25);
    }

    .btn-pill-primary:hover {
        background: #13246b;
        border-color: #13246b;
        transform: translateY(-1px);
        box-shadow: 0 8px 22px rgba(11, 29, 84, 0.3);
        color: #ffffff;
    }

    .btn-pill-secondary {
        background: #ffffff;
        border-color: #cfd5f0;
        color: #0b1d54;
    }

    .btn-pill-secondary:hover {
        background: #f3f5ff;
        border-color: #9aa4d4;
        color: #0b1d54;
    }

    .btn-pill-danger {
        background: #fff5f5;
        border-color: #f5c2c0;
        color: #c0392b;
    }

    .btn-pill-danger:hover {
        background: #ffe3e3;
        border-color: #e2877a;
        color: #a1251c;
    }

    .feedback-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .feedback-table thead tr {
        background: #f3f5ff;
    }

    .feedback-table th,
    .feedback-table td {
        padding: 8px 10px;
        border-bottom: 1px solid #e3e7f5;
        vertical-align: top;
    }

    .feedback-table th {
        text-align: left;
        font-weight: 600;
        color: #0b1d54;
        font-size: 13px;
    }

    .feedback-table tbody tr:hover {
        background: #f8f9ff;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        padding: 3px 9px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
    }
    .badge-status-baru {
        background: #fff7e6;
        color: #b36b00;
        border: 1px solid #ffd48a;
    }
    .badge-status-diproses {
        background: #e7f3ff;
        color: #1459a6;
        border: 1px solid #b2cdf5;
    }
    .badge-status-selesai {
        background: #e7f6ec;
        color: #1b7841;
        border: 1px solid #b7e2c5;
    }

    .actions {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .text-muted-sm {
        font-size: 12px;
        color: #6b7280;
    }
</style>

<div class="feedback-card">

    <div class="feedback-card-hd">
        <span class="feedback-card-hd-title">Daftar Feedback</span>

        {{-- tombol tambah feedback (modal) --}}
        <button type="button"
                class="btn-pill btn-pill-primary"
                data-bs-toggle="modal"
                data-bs-target="#modalTambahFeedback">
            + Tambah Feedback
        </button>
    </div>

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success mb-2">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mb-2">
            {{ session('error') }}
        </div>
    @endif

    {{-- FILTER / PENCARIAN --}}
    <form method="GET" class="mb-2">
        <div class="filter-row">
            <input type="text"
                   name="q"
                   value="{{ request('q') }}"
                   class="form-control-simap"
                   placeholder="Cari isi feedback…">

            <select name="status" class="form-control-simap">
                <option value="">Status: Semua</option>
                <option value="baru"      {{ request('status') === 'baru' ? 'selected' : '' }}>Baru</option>
                <option value="diproses"  {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="selesai"   {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>

            <select name="category" class="form-control-simap">
                <option value="">Kategori: Semua</option>
                {{-- kalau nanti ada kolom kategori di tabel feedback, tinggal isi di sini --}}
            </select>

            <button type="submit" class="btn-pill btn-pill-secondary">
                Cari
            </button>
        </div>
    </form>

    {{-- TABEL FEEDBACK --}}
    <table class="feedback-table">
        <thead>
            <tr>
                <th style="width: 70px;">ID</th>
                <th>Isi Feedback</th>
                <th style="width: 130px;">Status</th>
                <th style="width: 170px;">Tanggal</th>
                <th style="width: 210px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($displayFeedbacks as $fb)
                <tr>
                    <td>#{{ $fb->id_feedback ?? $fb->id }}</td>

                    <td>
                        {{ \Illuminate\Support\Str::limit($fb->isi ?? $fb->message ?? '-', 120) }}
                    </td>

                    <td>
                        @php
                            $status = $fb->status ?? 'baru';
                        @endphp

                        <span class="badge-status
                            {{ $status === 'baru' ? 'badge-status-baru' : '' }}
                            {{ $status === 'diproses' ? 'badge-status-diproses' : '' }}
                            {{ $status === 'selesai' ? 'badge-status-selesai' : '' }}
                        ">
                            {{ ucfirst($status) }}
                        </span>
                    </td>

                    <td>
                        @if(!empty($fb->tanggal))
                            {{ \Carbon\Carbon::parse($fb->tanggal)->format('d M Y H:i') }}
                        @elseif(!empty($fb->created_at))
                            {{ \Carbon\Carbon::parse($fb->created_at)->format('d M Y H:i') }}
                        @else
                            <span class="text-muted-sm">-</span>
                        @endif
                    </td>

                    <td>
                        <div class="actions">

                            {{-- ubah status: Baru / Diproses / Selesai --}}
                            <form action="{{ route('admins.feedback.updateStatus', $fb) }}"
                                  method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status"
                                       value="{{ $status === 'selesai' ? 'diproses' : 'selesai' }}">

                                <button type="submit"
                                        class="btn-pill btn-pill-secondary">
                                    {{ $status === 'selesai' ? 'Tandai Diproses' : 'Tandai Selesai' }}
                                </button>
                            </form>

                            {{-- hapus --}}
                            <form action="{{ route('admins.feedback.destroy', $fb) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus feedback ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn-pill btn-pill-danger">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding:16px;">
                        Tidak ada feedback.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

{{-- MODAL TAMBAH FEEDBACK --}}
<div class="modal fade" id="modalTambahFeedback" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admins.feedback.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Isi Feedback</label>
                        <textarea name="isi"
                                  class="form-control"
                                  rows="4"
                                  required>{{ old('isi') }}</textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">Batal</button>
                    <button type="submit"
                            class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
