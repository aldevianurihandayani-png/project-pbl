<?php
namespace App\Http\Controllers\DosenPenguji;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $kelas   = $request->query('kelas');     // optional filter
        $search  = $request->query('q');         // optional search

        $mahasiswa = Mahasiswa::query()
            ->when($kelas,  fn($q)=>$q->where('kelas', $kelas))
            ->when($search, fn($q)=>$q->where(function($qq) use ($search){
                $qq->where('nama','like',"%$search%")
                   ->orWhere('nim','like',"%$search%");
            }))
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('dosenpenguji.mahasiswa', compact('mahasiswa'));
    }
}
