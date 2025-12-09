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
                    <th style="width: 140px;">Role</th>
                    <th style="width: 200px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        {{-- ID berurutan 1,2,3... --}}
                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $user->nama ?? $user->name ?? '-' }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('admins.users.show', $user->id) }}"
                                   class="btn-pill btn-pill-ghost">
                                    Detail
                                </a>

                                <a href="{{ route('admins.users.edit', $user->id) }}"
                                   class="btn-pill btn-pill-ghost">
                                    Edit
                                </a>

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
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:16px;">
                            Tidak ada data akun pengguna.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

@endsection
