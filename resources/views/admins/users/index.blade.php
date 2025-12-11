@extends('layouts.admin')

@section('title', 'Manajemen Akun')
@section('page_title', 'Manajemen Akun')

@section('content')

{{-- Styling khusus halaman ini saja --}}
<style>
    .user-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .user-table thead tr {
        background: #f3f5ff;
    }

    .user-table th,
    .user-table td {
        padding: 8px 12px;
        border-bottom: 1px solid #e3e7f5;
    }

    .user-table th {
        text-align: left;
        font-weight: 600;
        color: #0b1d54;
    }

    .user-table tbody tr:hover {
        background: #f8f9ff;
    }

    .btn-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        border: 1px solid transparent;
        text-decoration: none;
        cursor: pointer;
        transition: 0.15s;
        white-space: nowrap;
    }

    .btn-pill-primary {
        background: #0b1d54;
        color: #fff;
        border-color: #0b1d54;
    }

    .btn-pill-primary:hover {
        background: #122872;
    }

    .btn-pill-ghost {
        background: #ffffff;
        color: #0b1d54;
        border-color: #cfd5f0;
    }

    .btn-pill-ghost:hover {
        background: #f3f5ff;
    }

    .btn-pill-danger {
        background: #fff5f5;
        color: #c0392b;
        border-color: #f5c2c0;
    }

    .btn-pill-danger:hover {
        background: #ffe3e3;
    }

    .actions {
        display: flex;
        gap: 6px;
        justify-content: flex-start;
        flex-wrap: wrap;
    }

    .alert-soft {
        padding: 8px 12px;
        border-radius: 10px;
        font-size: 13px;
        margin-bottom: 10px;
    }

    .alert-soft-success {
        background: #e3f7ea;
        border: 1px solid #b9e2c4;
        color: #256b3f;
    }

    .alert-soft-error {
        background: #ffe5e5;
        border: 1px solid #f5bbbb;
        color: #b42020;
    }

    .badge-status {
        display: inline-flex;
        padding: 3px 8px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
    }
    .badge-status-pending {
        background: #fff7e6;
        color: #b36b00;
        border: 1px solid #ffd48a;
    }
    .badge-status-active {
        background: #e7f6ec;
        color: #1b7841;
        border: 1px solid #b7e2c5;
    }
    .badge-status-rejected {
        background: #ffe5e5;
        color: #b42020;
        border: 1px solid #f5bbbb;
    }

    .role-select-sm {
        padding: 3px 6px;
        font-size: 12px;
        border-radius: 999px;
        border: 1px solid #cfd5f0;
    }
</style>

<div class="card">
    <div class="card-hd" style="justify-content: space-between;">
        <span>Daftar Akun Pengguna</span>

        <a href="{{ route('admins.users.create') }}" class="btn-pill btn-pill-primary">
            + Tambah Akun
        </a>
    </div>

    <div class="card-bd">

        {{-- Flash message --}}
        @if(session('success'))
            <div class="alert-soft alert-soft-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert-soft alert-soft-error">
                {{ session('error') }}
            </div>
        @endif

        <table class="user-table">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th style="width: 220px;">Nama</th>
                    <th>Email</th>
                    <th style="width: 160px;">Role</th>
                    <th style="width: 120px;">Status</th>
                    <th style="width: 260px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        {{-- ID berurutan 1,2,3... --}}
                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $user->nama ?? $user->name ?? '-' }}</td>
                        <td>{{ $user->email }}</td>

                        {{-- Role: kalau masih pending, tampilkan role yang diminta --}}
                        <td>
                            @if($user->status === 'pending')
                                <strong>{{ $user->requested_role ?? '-' }}</strong>
                                <span style="font-size:11px;color:#6b7280;">(diminta)</span>
                            @else
                                {{ $user->role ?? '-' }}
                            @endif
                        </td>

                        {{-- Status badge --}}
                        <td>
                            @if($user->status === 'pending')
                                <span class="badge-status badge-status-pending">Pending</span>
                            @elseif($user->status === 'active')
                                <span class="badge-status badge-status-active">Active</span>
                            @elseif($user->status === 'rejected')
                                <span class="badge-status badge-status-rejected">Rejected</span>
                            @else
                                <span class="badge-status">{{ $user->status ?? '-' }}</span>
                            @endif
                        </td>

                        <td>
                            <div class="actions">

                                {{-- JIKA MASIH PENDING: FORM PERSETUJUAN --}}
                                {{-- ✅ tambahan: jangan proses admin --}}
                                @if($user->status === 'pending' && $user->role !== 'admin')

                                    {{-- Setujui + pilih role final --}}
                                    <form action="{{ route('admins.users.approve', $user->id) }}"
                                          method="POST"
                                          class="d-flex align-items-center"
                                          style="gap:6px;">
                                        @csrf
                                        <select name="role" class="role-select-sm" required>
                                            <option value="">Pilih role…</option>
                                            <option value="mahasiswa"        {{ $user->requested_role == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                            <option value="dosen_pembimbing" {{ $user->requested_role == 'dosen_pembimbing' ? 'selected' : '' }}>Dosen Pembimbing</option>
                                            <option value="dosen_penguji"    {{ $user->requested_role == 'dosen_penguji' ? 'selected' : '' }}>Dosen Penguji</option>
                                            <option value="koordinator"      {{ $user->requested_role == 'koordinator' ? 'selected' : '' }}>Koordinator PBL</option>
                                            <option value="jaminan_mutu"     {{ $user->requested_role == 'jaminan_mutu' ? 'selected' : '' }}>Jaminan Mutu</option>
                                            {{-- kalau admin tidak boleh diminta, jangan tampilkan di sini --}}
                                            {{-- <option value="admin">Admin</option> --}}
                                        </select>

                                        <button type="submit" class="btn-pill btn-pill-primary">
                                            Setujui
                                        </button>
                                    </form>

                                    {{-- Tolak akun --}}
                                    <form action="{{ route('admins.users.reject', $user->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Tolak akun ini?');">
                                        @csrf
                                        <button type="submit"
                                                class="btn-pill btn-pill-danger">
                                            Tolak
                                        </button>
                                    </form>

                                    {{-- Opsional: tetap bisa lihat detail --}}
                                    <a href="{{ route('admins.users.show', $user->id) }}"
                                       class="btn-pill btn-pill-ghost">
                                        Detail
                                    </a>

                                @else
                                    {{-- SUDAH ACTIVE / REJECTED: tombol normal --}}
                                    <a href="{{ route('admins.users.show', $user->id) }}"
                                       class="btn-pill btn-pill-ghost">
                                        Detail
                                    </a>

                                    <a href="{{ route('admins.users.edit', $user->id) }}"
                                       class="btn-pill btn-pill-ghost">
                                        Edit
                                    </a>

                                    {{-- ✅ tambahan: jangan tampilkan tombol HAPUS untuk akun yang sedang login --}}
                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('admins.users.destroy', $user->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus akun ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn-pill btn-pill-danger">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                @endif

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:16px;">
                            Tidak ada data akun pengguna.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

@endsection
