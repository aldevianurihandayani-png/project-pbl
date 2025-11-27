<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    /**
     * Halaman index: kartu-kartu kelas + filter.
     */
    public function index(Request $request)
    {
        $query = Kelompok::query();

        // filter berdasarkan semester + kelas (A/B/C/D/E)
        if ($request->has('semester') && $request->semester != '') {
            $kelasFilter = 'TI-' . $request->semester;

            if ($request->has('kelas') && $request->kelas != '') {
                $kelasFilter .= $request->kelas; // contoh: TI-3E
            }

            $query->where('kelas', 'like', $kelasFilter . '%');
        }

        // optional: pencarian nama kelompok / judul proyek
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('judul_proyek', 'like', "%{$search}%");
            });
        }

        $kelompoks = $query->get();

        return view('dosen.kelompok', [
            'kelompoks' => $kelompoks,
            'request'   => $request,
        ]);
    }

    /**
     * Halaman detail: daftar CRUD kelompok untuk 1 kelas saja (misal: TI-3E).
     */
    public function kelas($kelas)
    {
        // Ambil hanya kelompok pada kelas ini
        $kelompoks = Kelompok::where('kelas', $kelas)->get();

        // ⬅️ PENTING: pakai view 'dosen.kelompok.kelas'
        return view('dosen.kelompok.kelas', [
            'kelompoks' => $kelompoks,
            'kelas'     => $kelas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * Bisa terima query ?kelas=TI-3E dari tombol "Tambah Kelompok".
     */
    public function create(Request $request)
    {
        $kelas = $request->query('kelas'); // bisa null

        return view('dosen.kelompok.create', compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama'             => 'required',
            'judul_proyek'     => 'required',
            'nama_klien'       => 'required',
            'ketua_kelompok'   => 'required',
            'kelas'            => 'required',
            'anggota'          => 'required',
            'dosen_pembimbing' => 'nullable|string',
        ]);

        // pastikan kelas selalu "TI-3E" dll
        if (!str_starts_with($validatedData['kelas'], 'TI-')) {
            $validatedData['kelas'] = 'TI-' . $validatedData['kelas'];
        }

        $validatedData['judul'] = $validatedData['judul_proyek'];

        $kelompok = Kelompok::create($validatedData);

        // setelah buat, balik ke halaman kelas (bukan index kartu)
        return redirect()
            ->route('dosen.kelompok.kelas', $kelompok->kelas)
            ->with('success', 'Kelompok created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelompok $kelompok)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelompok $kelompok)
    {
        return view('dosen.kelompok.edit', compact('kelompok'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kelompok $kelompok)
    {
        $validatedData = $request->validate([
            'nama'             => 'required',
            'judul_proyek'     => 'required',
            'nama_klien'       => 'required',
            'ketua_kelompok'   => 'required',
            'kelas'            => 'required',
            'anggota'          => 'required',
            'dosen_pembimbing' => 'nullable|string',
        ]);

        if (!str_starts_with($validatedData['kelas'], 'TI-')) {
            $validatedData['kelas'] = 'TI-' . $validatedData['kelas'];
        }

        $validatedData['judul'] = $validatedData['judul_proyek'];

        $kelompok->update($validatedData);

        // balik ke halaman kelasnya
        return redirect()
            ->route('dosen.kelompok.kelas', $kelompok->kelas)
            ->with('success', 'Kelompok updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelompok $kelompok)
    {
        $kelas = $kelompok->kelas; // simpan dulu sebelum dihapus
        $kelompok->delete();

        // setelah hapus, tetap di halaman kelas itu
        return redirect()
            ->route('dosen.kelompok.kelas', $kelas)
            ->with('success', 'Kelompok deleted successfully');
    }
}
