<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $mahasiswa = Mahasiswa::when($search, function ($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('angkatan', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        // kirim variabel bernama $mahasiswa (bukan $mahasiswas)
        return view('mahasiswa.index', compact('mahasiswa', 'search'));
    }

    // method resource lain (create/store/edit/update/destroy) menyusul...
}
