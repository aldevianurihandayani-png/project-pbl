<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MahasiswaKelompokController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Ambil NIM (sesuai gaya robust kamu)
        $nim = $user->nim ?? ($user->mahasiswa->nim ?? null);
        if (!$nim) abort(403, 'NIM tidak ditemukan.');

        /**
         * Ambil kelompok mahasiswa dari:
         * kelompok_anggota (pivot) -> kelompoks
         * NOTE: sesuaikan nama kolom di kelompok_anggota:
         * - kelompok_id
         * - nim
         */
        $kelompoks = DB::table('kelompok_anggota as ka')
            ->join('kelompoks as k', 'k.id', '=', 'ka.kelompok_id')
            ->where('ka.nim', $nim)
            ->select(
                'k.id',
                'k.nama_kelompok as nama',
                'k.semester',
                'k.status',
                'k.kelas_id'
            )
            ->get();

        // Ambil nama kelas dari tabel `kelas` kalau ada kelas_id
        // (optional)
        $kelasMap = [];
        $kelasIds = $kelompoks->pluck('kelas_id')->filter()->unique()->values();
        if ($kelasIds->count()) {
            $kelasMap = DB::table('kelas')
                ->whereIn('id', $kelasIds)
                ->pluck('nama_kelas', 'id')
                ->toArray();
        }

        // Tambahin field kelas (string) biar gampang di blade
        $kelompoks = $kelompoks->map(function($k) use ($kelasMap) {
            $k->kelas = $k->kelas_id ? ($kelasMap[$k->kelas_id] ?? '-') : '-';
            $k->dosen_pembimbing = '-'; // isi kalau kamu punya kolom/relasi dosen pembimbing
            return $k;
        });

        return view('mahasiswa.kelompok', compact('kelompoks'));
    }
}
