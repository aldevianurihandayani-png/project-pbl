<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\ProyekPbl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    // ✅ TAMBAHAN: halaman kelompok per kelas
    public function kelas(string $kelas, Request $request)
    {
        // contoh: $kelas = "TI-Kelas A"
        $kelompoks = Kelompok::where('kelas', $kelas)
            ->orderBy('nama')
            ->get();

        // pakai view yang sama dengan index (dosen.kelompok)
        return view('dosen.kelompok', [
            'kelompoks' => $kelompoks,
            'request'   => $request,
            'kelas'     => $kelas,
        ]);
    }

    public function create(Request $request)
    {
        $kelasTerpilih = $request->query('kelas');

        $daftarKelas       = Kelas::orderBy('nama_kelas')->get();
        $dosenPembimbings  = Dosen::orderBy('nama_dosen')->get();
        $daftarJudulProyek = ProyekPbl::orderBy('judul')->get(['judul']);
        $daftarKlien       = Dosen::orderBy('nama_dosen')->get(['nama_dosen']);

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'           => 'required|string',
            'kelas'          => 'required|string',
            'judul'          => 'required|string',
            'nama_klien'     => 'required|string',
            'ketua_kelompok' => 'required|exists:mahasiswas,nim',

            'anggota'   => 'required|array|min:1',
            'anggota.*' => 'distinct|exists:mahasiswas,nim',

            'id_dosen' => 'required|exists:dosen,id_dosen',
        ]);

        if (!str_starts_with($validated['kelas'], 'TI-')) {
            $validated['kelas'] = 'TI-' . $validated['kelas'];
        }

        $ketua = $validated['ketua_kelompok'];
        $anggotaNim = array_values(array_unique(array_filter($validated['anggota'], fn ($nim) => $nim !== $ketua)));

        $validated['anggota'] = implode(', ', $anggotaNim);

        DB::transaction(function () use ($validated, $anggotaNim, $ketua, &$kelompok) {
            $kelompok = Kelompok::create($validated);

            $semuaNim = array_values(array_unique(array_merge([$ketua], $anggotaNim)));

            Mahasiswa::whereIn('nim', $semuaNim)
                ->update(['kelompok_id' => $kelompok->id]);
        });

        return redirect()
            ->route('dosen.kelompok.kelas', $validated['kelas'])
            ->with('success', 'Kelompok berhasil dibuat.');
    }

    public function edit(Kelompok $kelompok)
    {
        $kelasTerpilih     = $kelompok->kelas;
        $daftarKelas       = Kelas::orderBy('nama_kelas')->get();
        $dosenPembimbings  = Dosen::orderBy('nama_dosen')->get();
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

    public function update(Request $request, Kelompok $kelompok)
    {
        $validated = $request->validate([
            'nama'           => 'required|string',
            'kelas'          => 'required|string',
            'judul'          => 'required|string',
            'nama_klien'     => 'required|string',
            'ketua_kelompok' => 'required|exists:mahasiswas,nim',

            'anggota'   => 'required|array|min:1',
            'anggota.*' => 'distinct|exists:mahasiswas,nim',

            'id_dosen' => 'required|exists:dosen,id_dosen',
        ]);

        if (!str_starts_with($validated['kelas'], 'TI-')) {
            $validated['kelas'] = 'TI-' . $validated['kelas'];
        }

        $ketua = $validated['ketua_kelompok'];
        $anggotaNim = array_values(array_unique(array_filter($validated['anggota'], fn ($nim) => $nim !== $ketua)));
        $validated['anggota'] = implode(', ', $anggotaNim);

        DB::transaction(function () use ($kelompok, $validated, $anggotaNim, $ketua) {
            $kelompok->update($validated);

            Mahasiswa::where('kelompok_id', $kelompok->id)
                ->update(['kelompok_id' => null]);

            $semuaNim = array_values(array_unique(array_merge([$ketua], $anggotaNim)));
            Mahasiswa::whereIn('nim', $semuaNim)
                ->update(['kelompok_id' => $kelompok->id]);
        });

        return redirect()
            ->route('dosen.kelompok.kelas', $validated['kelas'])
            ->with('success', 'Kelompok berhasil diperbarui.');
    }

    public function destroy(Kelompok $kelompok)
    {
        $kelas = $kelompok->kelas;

        DB::transaction(function () use ($kelompok) {
            Mahasiswa::where('kelompok_id', $kelompok->id)
                ->update(['kelompok_id' => null]);

            $kelompok->delete();
        });

        return redirect()
            ->route('dosen.kelompok.kelas', $kelas)
            ->with('success', 'Kelompok berhasil dihapus.');
    }
}
