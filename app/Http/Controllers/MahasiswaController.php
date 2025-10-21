<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $searchFields = ['nim', 'nama', 'angkatan', 'no_hp'];
        $mahasiswa = $this->getMahasiswa($request, $searchFields);
        $search = $request->search;

        return view('mahasiswa.index', compact('mahasiswa', 'search'));
    }

    public function indexDosenPenguji(Request $request)
    {
        $searchFields = ['nama', 'nim'];
        $with = ['kelompok.dosen', 'kelompok.proyek'];
        $mahasiswa = $this->getMahasiswa($request, $searchFields, $with);
        $search = $request->search;

        return view('dosenpenguji.mahasiswa', compact('mahasiswa', 'search'));
    }

    private function getMahasiswa(Request $request, array $searchFields, array $with = [])
    {
        $search = $request->search;

        $query = Mahasiswa::query();

        if (!empty($with)) {
            $query->with($with);
        }

        $query->when($search, function ($q) use ($search, $searchFields) {
            $q->where(function ($query) use ($search, $searchFields) {
                foreach ($searchFields as $field) {
                    $query->orWhere($field, 'like', "%{$search}%");
                }
            });
        });

        return $query->latest()->paginate(10);
    }
}
