<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use App\Models\Mahasiswa;
use App\Models\ProyekPbl;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelompokController extends Controller
{
    /**
     * Tampilkan daftar kelompok + pencarian sederhana.
     */
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));

        $kelompok = Kelompok::query()
            ->with(['mahasiswa','proyek','dosen'])
            ->when($q, function ($query) use ($q) {
                $query->where('judul', 'like', "%{$q}%")
                      ->orWhere('topik', 'like', "%{$q}%")
                      ->orWhere('nim', 'like', "%{$q}%");
            })
            ->orderByDesc('id_kelompok')
            ->paginate(10)
            ->withQueryString();

        return view('kelompok.index', compact('kelompok', 'q'));
    }

    /**
     * Form tambah.
     */
    public function create()
    {
        return view('kelompok.create', [
            'mahasiswa' => Mahasiswa::select('nim','nama')->orderBy('nama')->get(),
            'proyek'    => ProyekPbl::select('id_proyek_pbl','nama_proyek')->orderBy('nama_proyek')->get(),
            'dosen'     => Dosen::select('id_dosen','nama')->orderBy('nama')->get(),
        ]);
    }

    /**
     * Simpan data baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'         => ['required','string','max:255'],
            'topik'         => ['nullable','string','max:255'],
            'nim'           => ['required','integer','exists:mahasiswa,nim'],
            'id_proyek_pbl' => ['required','integer','exists:proyek_pbl,id_proyek_pbl'],
            'id_dosen'      => ['required','integer','exists:dosen,id_dosen'],
        ]);

        DB::transaction(function () use ($data) {
            Kelompok::create($data);
        });

        return redirect()->route('kelompok.index')->with('success', 'Kelompok berhasil ditambahkan.');
    }

    /**
     * Detail satu kelompok (opsional).
     */
    public function show(Kelompok $kelompok)
    {
        $kelompok->load(['mahasiswa','proyek','dosen']);
        return view('kelompok.show', compact('kelompok'));
    }

    /**
     * Form edit.
     */
    public function edit(Kelompok $kelompok)
    {
        return view('kelompok.edit', [
            'kelompok' => $kelompok->load(['mahasiswa','proyek','dosen']),
            'mahasiswa' => Mahasiswa::select('nim','nama')->orderBy('nama')->get(),
            'proyek'    => ProyekPbl::select('id_proyek_pbl','nama_proyek')->orderBy('nama_proyek')->get(),
            'dosen'     => Dosen::select('id_dosen','nama')->orderBy('nama')->get(),
        ]);
    }

    /**
     * Update data.
     */
    public function update(Request $request, Kelompok $kelompok)
    {
        $data = $request->validate([
            'judul'         => ['required','string','max:255'],
            'topik'         => ['nullable','string','max:255'],
            'nim'           => ['required','integer','exists:mahasiswa,nim'],
            'id_proyek_pbl' => ['required','integer','exists:proyek_pbl,id_proyek_pbl'],
            'id_dosen'      => ['required','integer','exists:dosen,id_dosen'],
        ]);

        DB::transaction(function () use ($kelompok, $data) {
            $kelompok->update($data);
        });

        return redirect()->route('kelompok.index')->with('success', 'Kelompok berhasil diperbarui.');
    }

    /**
     * Hapus data.
     */
    public function destroy(Kelompok $kelompok)
    {
        $kelompok->delete();
        return back()->with('success', 'Kelompok berhasil dihapus.');
    }

    public function indexDosenPenguji()
    {
        $kelompok = DB::table('kelompoks')->paginate(10);
        return view('dosenpenguji.kelompok', compact('kelompok'));
    }
}
