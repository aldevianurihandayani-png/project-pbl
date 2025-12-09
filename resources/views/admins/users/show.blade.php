@extends('layouts.admin')

@section('title', 'Detail Akun')
@section('page_title', 'Detail Akun')

@section('content')
<div class="card">
    <div class="card-hd">
        <span>Detail Akun Pengguna</span>
    </div>
    <div class="card-bd" style="max-width:420px;">
        <p><strong>ID:</strong> {{ $user->id }}</p>
        <p><strong>Nama:</strong> {{ $user->nama ?? $user->name ?? '-' }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Role:</strong> {{ $user->role }}</p>

        <div style="margin-top:14px; display:flex; gap:8px;">
            <a href="{{ route('admins.users.edit', $user->id) }}"
               class="btn-pill btn-pill-primary">
                Edit
            </a>
            <a href="{{ route('admins.users.index') }}"
               class="btn-pill btn-pill-ghost">
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection
