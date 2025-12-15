<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class DosenPembimbingController extends Controller
{
    public function index(Request $request)
    {
        $kelas = $request->get('kelas', 'Semua');
        $angkatan = $request->get('angkatan', 'Semua');
        $q = $request->get('q');

        // ambil daftar kelas dari tabel kelas (nama_kelas: "Kelas A", dst)
        $kelasList = Kelas::query()
            ->orderBy('id')
            ->pluck('nama_kelas')
            ->toArray();

        $query = Mahasiswa::query();

        // ❌ JANGAN filter id_dosen dulu, karena di DB kamu NULL semua

        if ($angkatan !== 'Semua') {
            $query->where('angkatan', $angkatan);
        }

        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%")
                  ->orWhere('nim', 'like', "%{$q}%");
            });
        }

        $counts = (clone $query)
            ->selectRaw('kelas, COUNT(*) as total')
            ->groupBy('kelas')
            ->pluck('total', 'kelas');

        $kelasToShow = $kelas === 'Semua' ? $kelasList : [$kelas];

        $angkatanOptions = Mahasiswa::query()
            ->select('angkatan')
            ->distinct()
            ->orderBy('angkatan', 'desc')
            ->pluck('angkatan');

        return view('dosen.mahasiswa.index', compact(
            'kelasList',
            'kelasToShow',
            'counts',
            'angkatanOptions'
        ));
    }

    public function kelas(Request $request, string $kelas)
    {
        $angkatan = $request->get('angkatan', 'Semua');
        $q = $request->get('q');

        $data = Mahasiswa::query()
            ->where('kelas', $kelas)
            ->when($angkatan !== 'Semua', fn ($x) => $x->where('angkatan', $angkatan))
            ->when($q, function ($x) use ($q) {
                $x->where(function ($w) use ($q) {
                    $w->where('nama', 'like', "%{$q}%")
                      ->orWhere('nim', 'like', "%{$q}%");
                });
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('dosen.mahasiswa.kelas', compact('kelas', 'data'));
    }

    public function show($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        return view('dosen.mahasiswa.show', compact('mahasiswa'));
    }
}
