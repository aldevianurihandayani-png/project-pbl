<?php
// app/Http/Controllers/DosenPenguji/KelompokController.php
namespace App\Http\Controllers\DosenPenguji;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelompokController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string)$request->get('search'));

        // Ambil kelompok + dosen pembimbing + daftar anggota
        $q = DB::table('kelompok as k')
            ->leftJoin('dosens as d', 'd.id', '=', 'k.dosen_id')
            ->leftJoin('mahasiswas as m', 'm.kelompok_id', '=', 'k.id')
            ->selectRaw('k.id, k.nama_kelompok,
                         COALESCE(d.nama, "-") as dosen_pembimbing,
                         GROUP_CONCAT(m.nama ORDER BY m.nama SEPARATOR ", ") as anggota')
            ->groupBy('k.id', 'k.nama_kelompok', 'd.nama')
            ->orderBy('k.id');

        if ($search !== '') {
            $like = "%{$search}%";
            // pakai HAVING karena anggota hasil group_concat
            $q->havingRaw('k.nama_kelompok LIKE ? OR dosen_pembimbing LIKE ? OR anggota LIKE ?', [$like,$like,$like]);
        }

        $kelompok = $q->paginate(10)->withQueryString();

        // kirim ke blade (pakai layout existing dashboard penguji)
        return view('penguji.kelompok.index', [
            'kelompok' => $kelompok,
            'search'   => $search,
        ]);
    }
}
