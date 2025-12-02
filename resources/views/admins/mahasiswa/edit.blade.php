@include('admins.partials.header', ['title' => 'Edit Mahasiswa'])

<div class="card shadow mb-4">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Form Edit Mahasiswa</h6>
    </div>

    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admins.mahasiswa.update', $mahasiswa->nim) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- NIM --}}
            <div class="form-group">
                <label for="nim">NIM</label>
                <input
                    type="text"
                    class="form-control @error('nim') is-invalid @enderror"
                    id="nim"
                    name="nim"
                    value="{{ old('nim', $mahasiswa->nim) }}"
                    readonly
                >
                @error('nim')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Nama Lengkap --}}
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input
                    type="text"
                    class="form-control @error('nama') is-invalid @enderror"
                    id="nama"
                    name="nama"
                    value="{{ old('nama', $mahasiswa->nama) }}"
                    required
                >
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- EMAIL – HANYA SATU KOLOM --}}
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    id="email"
                    name="email"
                    value="{{ old('email', optional($mahasiswa->user)->email) }}"
                    required
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Angkatan --}}
            <div class="form-group">
                <label for="angkatan">Angkatan</label>
                <input
                    type="number"
                    class="form-control @error('angkatan') is-invalid @enderror"
                    id="angkatan"
                    name="angkatan"
                    value="{{ old('angkatan', $mahasiswa->angkatan) }}"
                    required
                    min="1900"
                    max="{{ date('Y') + 1 }}"
                >
                @error('angkatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- No HP --}}
            <div class="form-group">
                <label for="no_hp">No. HP (Opsional)</label>
                <input
                    type="text"
                    class="form-control @error('no_hp') is-invalid @enderror"
                    id="no_hp"
                    name="no_hp"
                    value="{{ old('no_hp', $mahasiswa->no_hp) }}"
                >
                @error('no_hp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <hr>
            <p class="text-muted">
                Isi bagian di bawah ini hanya jika Anda ingin mengubah password.
            </p>

            {{-- Password baru --}}
            <div class="form-group">
                <label for="password">Password Baru</label>
                <input
                    type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    id="password"
                    name="password"
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Konfirmasi password --}}
            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password Baru</label>
                <input
                    type="password"
                    class="form-control"
                    id="password_confirmation"
                    name="password_confirmation"
                >
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('admins.mahasiswa.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

@include('admins.partials.footer')
