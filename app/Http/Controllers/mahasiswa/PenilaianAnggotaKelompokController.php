<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PenilaianAnggotaKelompok;

class PenilaianAnggotaKelompokController extends Controller
{
    public function index(Request $request, $kelompok_id)
    {
        $user = auth()->user();

        // Ambil NIM (robust)
        $nim = $user->nim ?? null;
        if (!$nim && isset($user->mahasiswa)) {
            $nim = $user->mahasiswa->nim ?? null;
        }
        if (!$nim) abort(403, 'NIM tidak ditemukan.');

        // Ambil nama mahasiswa login
        $namaPenilai = DB::table('mahasiswas')
        ->where('nim', $nim)
        ->value('nama');


        // Ambil data kelompok
        $kelompok = DB::table('kelompoks')->where('id', $kelompok_id)->first();
        if (!$kelompok) abort(404, 'Kelompok tidak ditemukan.');

        // Pastikan mahasiswa ini anggota kelompok tsb (pakai kelompok_anggota)
        $isMember = DB::table('kelompok_anggota')
            ->where('kelompok_id', $kelompok_id)
            ->where('nim', $nim)
            ->exists();

        if (!$isMember) {
            abort(403, 'Kamu bukan anggota kelompok ini.');
        }

        // ===== Ambil anggota kelompok dari kelompok_anggota =====
        // Join ke mahasiswas untuk nama (kalau nama juga tersimpan di kelompok_anggota, boleh pakai itu)
        $anggotaQuery = DB::table('kelompok_anggota as ka')
            ->leftJoin('mahasiswas as m', 'm.nim', '=', 'ka.nim')
            ->where('ka.kelompok_id', $kelompok_id)
            ->select(
                'ka.nim',
                DB::raw('COALESCE(m.nama, ka.nama) as nama')
            )
            ->where('ka.nim', '!=', $nim); // tidak termasuk diri sendiri

        // OPTIONAL: kalau ketua tidak boleh dinilai
        // - harus ada kolom ketua_nim di tabel kelompoks
        if (isset($kelompok->ketua_nim) && $kelompok->ketua_nim) {
            $anggotaQuery->where('ka.nim', '!=', $kelompok->ketua_nim);
        }

        // Jangan pakai limit(5) biar tampil semua anggota sesuai data
        $anggota = $anggotaQuery->orderBy('ka.nim')->get();

        // Cek sudah isi?
        $sudahIsi = PenilaianAnggotaKelompok::where('kelompok_id', $kelompok_id)
            ->where('penilai_nim', $nim)
            ->exists();

        // Kalau sudah isi, ambil nilai sebelumnya biar tampil read-only
        $nilaiSaya = PenilaianAnggotaKelompok::where('kelompok_id', $kelompok_id)
            ->where('penilai_nim', $nim)
            ->get()
            ->keyBy('dinilai_nim');

        // OPTIONAL chip
        $mkNama = null;
        $kelasNama = null;

        return view('mahasiswa.kelompok.penilaian_anggota', compact(
            'kelompok',
            'kelompok_id',
            'anggota',
            'sudahIsi',
            'nilaiSaya',
            'nim',
            'namaPenilai',
            'mkNama',
            'kelasNama'
        ));
    }

    public function store(Request $request, $kelompok_id)
    {
        $user = auth()->user();

        // Ambil NIM (robust)
        $nim = $user->nim ?? null;
        if (!$nim && isset($user->mahasiswa)) {
            $nim = $user->mahasiswa->nim ?? null;
        }
        if (!$nim) abort(403, 'NIM tidak ditemukan.');

        // Ambil data kelompok
        $kelompok = DB::table('kelompoks')->where('id', $kelompok_id)->first();
        if (!$kelompok) abort(404, 'Kelompok tidak ditemukan.');

        // Pastikan mahasiswa anggota kelompok tsb
        $isMember = DB::table('kelompok_anggota')
            ->where('kelompok_id', $kelompok_id)
            ->where('nim', $nim)
            ->exists();

        if (!$isMember) {
            abort(403, 'Kamu bukan anggota kelompok ini.');
        }

        // Cegah submit 2x
        $sudahIsi = PenilaianAnggotaKelompok::where('kelompok_id', $kelompok_id)
            ->where('penilai_nim', $nim)
            ->exists();

        if ($sudahIsi) {
            return back()->with('error', 'Kamu sudah mengisi penilaian anggota. (Read-only)');
        }

        // Validasi input
        $validated = $request->validate([
            'dinilai_nim'   => ['required', 'array'],
            'dinilai_nim.*' => ['required', 'string', 'max:30'],
            'nilai'         => ['required', 'array'],
            'nilai.*'       => ['required', 'integer', 'min:80', 'max:100'],
            'keterangan'    => ['nullable', 'array'],
            'keterangan.*'  => ['nullable', 'string', 'max:1000'],
        ]);

        // Ambil daftar NIM anggota kelompok untuk validasi (tanpa limit)
        $anggotaKelompok = DB::table('kelompok_anggota')
            ->where('kelompok_id', $kelompok_id)
            ->pluck('nim')
            ->toArray();

        // OPTIONAL: exclude ketua dari target dinilai
        $ketuaNim = (isset($kelompok->ketua_nim) && $kelompok->ketua_nim) ? $kelompok->ketua_nim : null;

        foreach ($validated['dinilai_nim'] as $dinilaiNim) {
            if ($dinilaiNim === $nim) {
                return back()->with('error', 'Tidak boleh menilai diri sendiri.');
            }
            if ($ketuaNim && $dinilaiNim === $ketuaNim) {
                return back()->with('error', 'Ketua kelompok tidak dinilai.');
            }
            if (!in_array($dinilaiNim, $anggotaKelompok, true)) {
                return back()->with('error', 'Ada NIM yang bukan anggota kelompok.');
            }
        }

        DB::transaction(function () use ($kelompok_id, $nim, $validated) {
            foreach ($validated['dinilai_nim'] as $i => $dinilaiNim) {
                PenilaianAnggotaKelompok::create([
                    'kelompok_id' => (int) $kelompok_id,
                    'penilai_nim' => $nim,
                    'dinilai_nim' => $dinilaiNim,
                    'nilai'       => (int) $validated['nilai'][$i],
                    'keterangan'  => $validated['keterangan'][$i] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('mahasiswa.kelompok.penilaian_anggota', ['kelompok_id' => $kelompok_id])
            ->with('success', 'Penilaian anggota berhasil disimpan.');
    }
}
