<?php

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $mahasiswas = ahasiswa::when($search, function ($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('angkatan', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // pakai nama variabel konsisten dgn view: $mahasiswas
        return view('mahasiswa.index', [
            'mahasiswas' => $mahasiswas,
            'search' => $search,
        ]);
    }

    public function dashboard()
    {
        // render view dashboard mahasiswa
        return view('mahasiswa.dashboard');
    }

    // create/store/edit/update/destroy ... (lanjutkan punyamu)
}
