@include('admins.partials.header', ['title' => 'Profil Administrator'])

<div class="card shadow mb-4">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Informasi Profil</h6>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group d-flex align-items-center">
                        <div class="mr-3">
                            @if ($user->profile_photo_path)
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" 
                                     alt="Foto Profil" 
                                     class="img-thumbnail rounded-circle" 
                                     style="object-fit:cover; border-radius:50%!important;">
                            @else
                                <img src="{{ asset('images/default-profile.png') }}" 
                                     alt="Foto Profil Default" 
                                     class="img-thumbnail rounded-circle" 
                                     style="object-fit:cover; border-radius:50%!important;">
                            @endif
                        </div>
                        <div>
                            <input type="file" 
                                   class="form-control-file @error('profile_photo') is-invalid @enderror" 
                                   id="profile_photo" 
                                   name="profile_photo">
                            @error('profile_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
        
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
            <div class="form-group">
                <label for="email">Alamat Surel</label>
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ old('email', $user->email) }}" 
                       required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <hr>
            <p class="text-muted">Isi bagian di bawah ini hanya jika Anda ingin mengubah password.</p>

            <div class="form-group">
                <label for="password">Password Baru</label>
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password Baru</label>
                <input type="password" 
                       class="form-control" 
                       id="password_confirmation" 
                       name="password_confirmation">
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>

@include('admins.partials.footer')
