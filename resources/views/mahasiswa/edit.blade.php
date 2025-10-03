@extends('layouts.app')

@section('content')
<div class="wrap" style="max-width:1100px; margin:24px auto; padding:0 12px;">
    <h2 class="page-title" style="margin-bottom:12px;">Edit Mahasiswa</h2>

    {{-- Alert error global --}}
    @if ($errors->any())
        <div class="card" style="border-left:4px solid #ef4444; margin-bottom:12px; background:#fef2f2; padding:10px 14px; border-radius:10px;">
            <strong>Gagal:</strong>
            <div style="margin-top:6px;">
                @foreach ($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <form method="post" action="{{ route('mahasiswa.update', $mahasiswa->id) }}" class="card" style="max-width:520px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px;">
        @csrf
        @method('PUT')

        <div style="display:flex;flex-direction:column;gap:10px;">
            <label>
                NIM:
                <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim) }}" required
                       style="width:100%;padding:8px;border:1px solid #cbd5e1;border-radius:8px;">
                @error('nim') <div style="color:#dc2626; font-size:13px; margin-top:4px;">{{ $message }}</div> @enderror
            </label>

            <label>
                Nama:
                <input type="text" name="nama" value="{{ old('nama', $mahasiswa->nama) }}" required
                       style="width:100%;padding:8px;border:1px solid #cbd5e1;border-radius:8px;">
                @error('nama') <div style="color:#dc2626; font-size:13px; margin-top:4px;">{{ $message }}</div> @enderror
            </label>

            <label>
                Angkatan:
                <input type="text" name="angkatan" value="{{ old('angkatan', $mahasiswa->angkatan) }}" required
                       style="width:100%;padding:8px;border:1px solid #cbd5e1;border-radius:8px;">
                @error('angkatan') <div style="color:#dc2626; font-size:13px; margin-top:4px;">{{ $message }}</div> @enderror
            </label>

            <label>
                No HP:
                <input type="text" name="no_hp" value="{{ old('no_hp', $mahasiswa->no_hp) }}" required
                       style="width:100%;padding:8px;border:1px solid #cbd5e1;border-radius:8px;">
                @error('no_hp') <div style="color:#dc2626; font-size:13px; margin-top:4px;">{{ $message }}</div> @enderror
            </label>

            <div style="display:flex;gap:10px;margin-top:10px;">
                <button type="submit"
                        style="background:#0e2771;color:#fff;padding:10px 14px;border:none;border-radius:8px;font-weight:700;cursor:pointer">
                    Simpan Perubahan
                </button>
                <a href="{{ route('mahasiswa.index') }}"
                   style="background:#6b7280;color:#fff;padding:10px 14px;border-radius:8px;font-weight:700;text-decoration:none">
                    Batal
                </a>
            </div>
        </div>
    </form>

    <footer style="margin-top:16px; text-align:center; color:#6b7280;">
        © {{ now()->year }} SIMAP Politala. All rights reserved.
    </footer>
</div>
@endsection
