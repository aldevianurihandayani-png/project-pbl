<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Mahasiswa</title>
  <link rel="stylesheet" href="{{ asset('css/mahasiswa.css') }}">
</head>
<body>
  @include('header')

  <div class="page">
    <h1 class="page-title">DAFTAR MAHASISWA</h1>

    <div class="card">
      <div class="card-header">
        <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary">Tambah Mahasiswa</a>
      </div>

      @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert error">{{ session('error') }}</div>
      @endif

      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>NIM</th>
              <th>Nama</th>
              <th>Angkatan</th>
              <th>No HP</th>
              <th class="text-right">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($mahasiswa as $idx => $mhs)
              <tr>
                <td>{{ $mahasiswa->firstItem() + $idx }}</td>
                <td>{{ $mhs->nim }}</td>
                <td>{{ $mhs->nama }}</td>
                <td>{{ $mhs->angkatan }}</td>
                <td>{{ $mhs->no_hp }}</td>
                <td class="text-right actions">
                  {{-- Hapus tombol View jika belum ada route show --}}
                  {{-- <a href="{{ route('mahasiswa.show', $mhs) }}" class="chip chip-blue">View</a> --}}

                  <a href="{{ route('mahasiswa.edit', $mhs) }}" class="chip chip-yellow">Edit</a>

                  <form action="{{ route('mahasiswa.destroy', $mhs) }}" method="POST" class="inline"
                        onsubmit="return confirm('Hapus mahasiswa ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="chip chip-red">Delete</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="empty">Belum ada data.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="pagination">
        {{ $mahasiswa->links() }}
      </div>
    </div>
  </div>
</body>
</html>
