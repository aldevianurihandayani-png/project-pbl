<?php

namespace App\Http\Controllers\JaminanMutu;

use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use App\Models\MataKuliah;
use App\Models\Kelas;
use Illuminate\Http\Request;

class JmPenilaianController extends Controller
{
    public function index(Request $request)
    {
        $mkKode  = $request->query('matakuliah'); // ini isinya kode_mk (string)
        $kelasId = $request->query('kelas');      // ini isinya kelas_id (angka)

        // dropdown MK dari tabel mata_kuliah (sesuai relasi di model Penilaian)
        $matakuliah = MataKuliah::orderBy('nama_mk')->get(['kode_mk','nama_mk']);

        // ✅ FIX: tabel `kelas` kamu tidak punya kolom `kode_kelas`, jadi jangan di-select
        $kelasList = Kelas::orderBy('nama_kelas')->get(['id','nama_kelas']);

        $query = Penilaian::with(['mahasiswa','matakuliah','kelas','dosen'])
            ->latest();

        if ($mkKode) {
            $query->where('matakuliah_kode', $mkKode); // ✅ sesuai DB
        }

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);       // ✅ sesuai DB
        }

        $penilaian = $query->paginate(10)->withQueryString();

        return view('jaminanmutu.penilaian.index', compact(
            'penilaian', 'matakuliah', 'kelasList', 'mkKode', 'kelasId'
        ));
    }

    public function show(Penilaian $penilaian)
    {
        $penilaian->load(['mahasiswa','matakuliah','kelas','dosen']);
        return view('jaminanmutu.penilaian.show', compact('penilaian'));
    }
}
