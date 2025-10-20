<<<<<<< HEAD

=======
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Logbook</title>
  <link rel="stylesheet" href="{{ asset('css/logbook.css') }}">
</head>
<body>
<div class="page">
  <h1 class="page-title">DAFTAR LOGBOOK</h1>

  <div class="card">
    <div class="card-header">
      <a class="btn btn-primary" href="{{ route('logbook.create') }}">Tambah Logbook</a>
    </div>

    @if(session('success'))
      <div class="alert success">{{ session('success') }}</div>
    @endif

    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>No</th><th>Tanggal</th><th>Aktivitas</th><th>Keterangan</th><th>Foto</th><th class="text-right">Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse($logbooks as $i => $l)
          <tr>
            <td>{{ $logbooks->firstItem() + $i }}</td>
            <td>{{ \Carbon\Carbon::parse($l->tanggal)->format('d/m/Y') }}</td>
            <td>{{ $l->aktivitas }}</td>
            <td>{{ Str::limit($l->keterangan, 60) }}</td>
            <td>
              @if($l->foto)
                <img src="{{ asset('storage/'.$l->foto) }}" alt="foto" class="thumb">
              @else
                —
              @endif
            </td>
            <td class="text-right actions">
              <a href="{{ route('logbook.show',$l) }}" class="chip chip-blue">View</a>
              <a href="{{ route('logbook.edit',$l) }}" class="chip chip-yellow">Edit</a>
              <form action="{{ route('logbook.destroy',$l) }}" method="POST" class="inline">
                @csrf @method('DELETE')
                <button class="chip chip-red" onclick="return confirm('Hapus logbook ini?')">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="empty">Belum ada data.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>

    <div class="pagination">{{ $logbooks->links() }}</div>
  </div>
</div>
</body>
</html>
>>>>>>> bbcfba2 (commit noorma)
