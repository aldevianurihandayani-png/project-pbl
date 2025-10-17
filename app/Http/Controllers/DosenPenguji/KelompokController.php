<?php
namespace App\Http\Controllers\DosenPenguji;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelompok;

class KelompokController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q');

        $kelompok = Kelompok::query()
            ->when($search, function($q) use ($search) {
                $q->where('nama_kelompok', 'like', "%{$search}%")
                  ->orWhereHas('proyek', fn($qp) => $qp->where('nama_proyek', 'like', "%{$search}%"));
            })
            ->orderBy('nama_kelompok')
            ->paginate(10)
            ->withQueryString();

        return view('dosenpenguji.kelompok', compact('kelompok'));
    }
}
