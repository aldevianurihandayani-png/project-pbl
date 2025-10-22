<?php
namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use Illuminate\Http\Request;

class LogbookController extends Controller
{
    public function index()
    {
        // ❌ JANGAN eager-load relasi yang belum ada
        // $logbooks = Logbook::with('kelompok','mahasiswa')->orderBy('tanggal','desc')->paginate(10);

        // ✅ Versi aman: ambil langsung dari tabel logbooks
        $logbooks = Logbook::orderBy('tanggal', 'desc')->paginate(10);

        // CATATAN:
        // Di Blade JANGAN akses $log->kelompok atau $log->mahasiswa.
        // Tampilkan kolom yang memang ada di tabel logbooks (mis. kelompok_id, kelas, nim, dst.)
        // Contoh di Blade: {{ $log->kelompok_id ?? '-' }}

        return view('dosen.logbook.index', compact('logbooks'));
    }

    public function edit(Logbook $logbook)
    {
        return view('dosen.logbook.edit', compact('logbook'));
    }

    public function update(Request $request, Logbook $logbook)
    {
        $request->validate([
            'aktivitas'  => 'required|string',
            'keterangan' => 'nullable|string',
            // tambahkan field lain sesuai kolom yang boleh diupdate
        ]);

        $logbook->update($request->only(['aktivitas','keterangan']));

        return redirect()
            ->route('dosen.logbook.index')
            ->with('success','Logbook diperbarui.');
    }

    public function destroy(Logbook $logbook)
    {
        $logbook->delete();

        return redirect()
            ->route('dosen.logbook.index')
            ->with('success','Logbook dihapus.');
    }

    // Toggle status (approved <-> pending)
    public function toggleStatus(Logbook $logbook)
    {
        $logbook->status = $logbook->status === 'approved' ? 'pending' : 'approved';
        $logbook->save();

        return back()->with('success','Status diubah.');
    }
}
