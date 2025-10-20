@include('admins.partials.header', ['title' => 'Buat Notifikasi Baru'])

<div class="card shadow mb-4">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Form Notifikasi Baru</h6>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admins.notifikasi.store') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="type">Tipe Notifikasi</label>
                <select id="type" name="type" class="form-control @error('type') is-invalid @enderror" required>
                    <option value="">— Pilih Tipe —</option>
                    <option value="materi" {{ old('type') == 'materi' ? 'selected' : '' }}>Materi</option>
                    <option value="tugas" {{ old('type') == 'tugas' ? 'selected' : '' }}>Tugas</option>
                    <option value="info" {{ old('type') == 'info' ? 'selected' : '' }}>Info</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="title">Judul Notifikasi</label>
                <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="course">Mata Kuliah (opsional)</label>
                <input type="text" id="course" name="course" class="form-control @error('course') is-invalid @enderror" value="{{ old('course') }}">
                @error('course')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="link_url">URL Tautan (opsional)</label>
                <input type="url" id="link_url" name="link_url" class="form-control @error('link_url') is-invalid @enderror" value="{{ old('link_url') }}">
                @error('link_url')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="user_id">Penerima (opsional, kosongkan untuk semua user)</label>
                <select id="user_id" name="user_id" class="form-control @error('user_id') is-invalid @enderror">
                    <option value="">— Semua User —</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Kirim Notifikasi</button>
            <a href="{{ route('admins.notifikasi.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

@include('admins.partials.footer')