<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\ProyekPbl;
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelompok::query();

        if ($request->filled('semester')) {
            $kelasFilter = 'TI-' . $request->semester;

            if ($request->filled('kelas')) {
                $kelasFilter .= $request->kelas;
            }

            $query->where('kelas', 'like', $kelasFilter . '%');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('judul', 'like', "%{$search}%");
            });
        }

        return view('dosen.kelompok', [
            'kelompoks' => $query->get(),
            'request'   => $request,
        ]);
    }

    /**
     * FORM TAMBAH KELOMPOK
     * ?kelas=Kelas A → mahasiswa otomatis terfilter
     */
    public function create(Request $request)
    {
        $kelasTerpilih = $request->query('kelas');

        $daftarKelas       = Kelas::orderBy('nama_kelas')->get();
        $dosenPembimbings  = Dosen::orderBy('nama_dosen')->get();
        $daftarJudulProyek = ProyekPbl::orderBy('judul')->get(['judul']);
        $daftarKlien       = Dosen::orderBy('nama_dosen')->get(['nama_dosen']);

        // 🔥 INI KUNCINYA: mahasiswa difilter berdasarkan kelas
        $mahasiswas = Mahasiswa::query()
            ->when($kelasTerpilih, fn ($q) => $q->where('kelas', $kelasTerpilih))
            ->whereNull('kelompok_id')
            ->orderBy('nama')
            ->get();

        return view('dosen.kelompok.create', compact(
            'kelasTerpilih',
            'daftarKelas',
            'dosenPembimbings',
            'mahasiswas',
            'daftarJudulProyek',
            'daftarKlien'
        ));
    }

    /**
     * STORE KELOMPOK BARU
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'           => 'required',
            'kelas'          => 'required',
            'judul'          => 'required',
            'nama_klien'     => 'required',
            'ketua_kelompok' => 'required',

            'anggota'   => 'required|array',
            'anggota.*' => 'distinct|exists:mahasiswas,nim',

            'id_dosen' => 'required|exists:dosen,id_dosen',
        ]);

        if (!str_starts_with($validated['kelas'], 'TI-')) {
            $validated['kelas'] = 'TI-' . $validated['kelas'];
        }

        $anggotaNim = $validated['anggota'];
        $validated['anggota'] = implode(', ', $anggotaNim);

        $kelompok = Kelompok::create($validated);

        Mahasiswa::whereIn('nim', $anggotaNim)
            ->update(['kelompok_id' => $kelompok->id]);

        Mahasiswa::where('nim', $validated['ketua_kelompok'])
            ->update(['kelompok_id' => $kelompok->id]);

        return redirect()
            ->route('dosen.kelompok.kelas', $kelompok->kelas)
            ->with('success', 'Kelompok berhasil dibuat.');
    }

    /**
     * FORM EDIT KELOMPOK
     */
    public function edit(Kelompok $kelompok)
    {
        $kelasTerpilih    = $kelompok->kelas;
        $daftarKelas      = Kelas::orderBy('nama_kelas')->get();
        $dosenPembimbings = Dosen::orderBy('nama_dosen')->get();
        $daftarJudulProyek = ProyekPbl::orderBy('judul')->get(['judul']);
        $daftarKlien       = Dosen::orderBy('nama_dosen')->get(['nama_dosen']);

        $mahasiswas = Mahasiswa::query()
            ->where('kelas', $kelasTerpilih)
            ->where(function ($q) use ($kelompok) {
                $q->whereNull('kelompok_id')
                  ->orWhere('kelompok_id', $kelompok->id);
            })
            ->orderBy('nama')
            ->get();

        $anggotaTerpilih = Mahasiswa::where('kelompok_id', $kelompok->id)
            ->pluck('nim')
            ->toArray();

        return view('dosen.kelompok.edit', compact(
            'kelompok',
            'kelasTerpilih',
            'daftarKelas',
            'dosenPembimbings',
            'mahasiswas',
            'anggotaTerpilih',
            'daftarJudulProyek',
            'daftarKlien'
        ));
    }

    /**
     * UPDATE KELOMPOK
     */
    public function update(Request $request, Kelompok $kelompok)
    {
        $validated = $request->validate([
            'nama'           => 'required',
            'kelas'          => 'required',
            'judul'          => 'required',
            'nama_klien'     => 'required',
            'ketua_kelompok' => 'required',

            'anggota'   => 'required|array',
            'anggota.*' => 'distinct|exists:mahasiswas,nim',

            'id_dosen' => 'required|exists:dosen,id_dosen',
        ]);

        if (!str_starts_with($validated['kelas'], 'TI-')) {
            $validated['kelas'] = 'TI-' . $validated['kelas'];
        }

        $anggotaNim = $validated['anggota'];
        $validated['anggota'] = implode(', ', $anggotaNim);

        $kelompok->update($validated);

        Mahasiswa::where('kelompok_id', $kelompok->id)
            ->update(['kelompok_id' => null]);

        Mahasiswa::whereIn('nim', $anggotaNim)
            ->update(['kelompok_id' => $kelompok->id]);

        Mahasiswa::where('nim', $validated['ketua_kelompok'])
            ->update(['kelompok_id' => $kelompok->id]);

        return redirect()
            ->route('dosen.kelompok.kelas', $kelompok->kelas)
            ->with('success', 'Kelompok berhasil diperbarui.');
    }

    public function destroy(Kelompok $kelompok)
    {
        $kelas = $kelompok->kelas;

        Mahasiswa::where('kelompok_id', $kelompok->id)
            ->update(['kelompok_id' => null]);

        $kelompok->delete();

        return redirect()
            ->route('dosen.kelompok.kelas', $kelas)
            ->with('success', 'Kelompok berhasil dihapus.');
    }
}
