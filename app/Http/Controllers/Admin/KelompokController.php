<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kelompok;
use App\Models\Mahasiswa;
use App\Models\ProyekPbl;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class KelompokController extends Controller
{
    /**
     * Tampilkan daftar kelompok + pencarian sederhana.
     */
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));

        // tabel: kelompoks (kolom: id, judul, nama, judul_proyek, nama_klien, ketua_kelompok, kelas, anggota, dosen_pembimbing, ...)
        $kelompoks = Kelompok::query()
            ->when($q, function ($query) use ($q) {
                $query->where('nama', 'like', "%{$q}%")
                      ->orWhere('judul', 'like', "%{$q}%")
                      ->orWhere('judul_proyek', 'like', "%{$q}%")
                      ->orWhere('kelas', 'like', "%{$q}%")
                      ->orWhere('dosen_pembimbing', 'like', "%{$q}%");
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        // ← perhatikan: admins.kelompok.index dan variabel $kelompoks
        return view('admins.kelompok.index', compact('kelompoks', 'q'));
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
            'nama'          => ['required', 'string', 'max:255'],
            'kelas'         => ['required', 'string', 'max:20'],
            'judul'         => ['required', 'string', 'max:255'],
            'ketua_kelompok'=> ['required', 'string', 'max:255'],
            'dosen_pembimbing' => ['required', 'string', 'max:255'],
        ]);

        Kelompok::create($data);

        return redirect()->route('kelompok.index')->with('success', 'Kelompok berhasil ditambahkan.');
    }

    public function show(Kelompok $kelompok)
    {
        $kelompok->load(['mahasiswa','proyek','dosen']);
        return view('kelompok.show', compact('kelompok'));
    }

    public function edit(Kelompok $kelompok)
    {
        return view('kelompok.edit', [
            'kelompok'  => $kelompok->load(['mahasiswa','proyek','dosen']),
            'mahasiswa' => Mahasiswa::select('nim','nama')->orderBy('nama')->get(),
            'proyek'    => ProyekPbl::select('id_proyek_pbl','nama_proyek')->orderBy('nama_proyek')->get(),
            'dosen'     => Dosen::select('id_dosen','nama')->orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, Kelompok $kelompok)
    {
        $data = $request->validate([
            'nama'             => ['required','string','max:255'],
            'judul'            => ['required','string','max:255'],
            'judul_proyek'     => ['nullable','string','max:255'],
            'nama_klien'       => ['nullable','string','max:255'],
            'ketua_kelompok'   => ['required','string','max:255'],
            'kelas'            => ['required','string','max:20'],
            'anggota'          => ['nullable','string'],
            'dosen_pembimbing' => ['required','string','max:255'],
        ]);

        $kelompok->update($data);

        return redirect()->route('kelompok.index')->with('success', 'Kelompok berhasil diperbarui.');
    }

    public function destroy(Kelompok $kelompok)
    {
        $kelompok->delete();
        return back()->with('success', 'Kelompok berhasil dihapus.');
    }
}
