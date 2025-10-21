<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelompokPengujiController extends Controller
{
    public function index(Request $request)
    {
        $q        = trim((string) $request->get('q', ''));
        $kelas    = $request->get('kelas', 'all');
        $semester = $request->get('semester', 'all');

        $query = DB::table('kelompok as k')
            ->leftJoin('dosens as d', 'd.id', '=', 'k.dosen_id')
            ->leftJoin('mahasiswas as m', 'm.kelompok_id', '=', 'k.id')
            ->selectRaw('
                k.id,
                k.nama_kelompok,
                k.angkatan,
                k.kelas,
                k.semester,
                COALESCE(k.klien,"-") as klien,
                COALESCE(d.nama,"-") as dosen_pembimbing,
                GROUP_CONCAT(DISTINCT m.nim ORDER BY m.nim SEPARATOR ", ") as nims,
                GROUP_CONCAT(DISTINCT m.nama ORDER BY m.nama SEPARATOR ", ") as anggota
            ')
            ->when($kelas !== 'all', fn($qq) => $qq->where('k.kelas',$kelas))
            ->when($semester !== 'all', fn($qq) => $qq->where('k.semester',$semester))
            ->groupBy('k.id','k.nama_kelompok','k.angkatan','k.kelas','k.semester','k.klien','d.nama')
            ->orderBy('k.id');

        if($q !== ''){
            $like = "%{$q}%";
            $query->havingRaw('(k.nama_kelompok LIKE ? OR dosen_pembimbing LIKE ? OR anggota LIKE ? OR k.klien LIKE ?)',
                [$like,$like,$like,$like]);
        }

        $kelompok = $query->paginate(10)->appends($request->query());

        // Pastikan nama variabel sama dengan yang dipakai di Blade
        return view('dosenpenguji.kelompok', [
            'kelompok' => $kelompok,
            'q'        => $q,
            'kelas'    => $kelas,
            'semester' => $semester,
        ]);
    }
}
