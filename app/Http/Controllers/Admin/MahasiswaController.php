<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $kelasFilter = $request->query('kelas');

        $kelasStats = Mahasiswa::select(
                'kelas',
                DB::raw('COUNT(*) as total'),
                DB::raw('MIN(angkatan) as min_angkatan'),
                DB::raw('MAX(angkatan) as max_angkatan')
            )
            ->groupBy('kelas')
            ->get()
            ->keyBy('kelas');

        $mahasiswas = null;
        if ($kelasFilter) {
            $mahasiswas = Mahasiswa::where('kelas', $kelasFilter)
                ->orderBy('nama')
                ->paginate(10);
        }

        return view('admins.mahasiswa.index', [
            'kelasStats'  => $kelasStats,
            'kelasFilter' => $kelasFilter,
            'mahasiswas'  => $mahasiswas,
        ]);
    }

    public function create(Request $request)
    {
        $kelas = $request->query('kelas');
        return view('admins.mahasiswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nim'      => 'required|string|max:50|unique:mahasiswas,nim',
            'nama'     => 'required|string|max:255',
            'email'    => 'nullable|email|max:255',
            'angkatan' => 'nullable|digits:4',
            'no_hp'    => 'nullable|string|max:50',
            'kelas'    => 'required|in:A,B,C,D,E',
        ]);

        Mahasiswa::create($data);

        return redirect()
            ->route('admins.mahasiswa.index', ['kelas' => $data['kelas']])
            ->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        return view('admins.mahasiswa.edit', [
            'mahasiswa' => $mahasiswa,
        ]);
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $data = $request->validate([
            'nim'      => 'required|string|max:50|unique:mahasiswas,nim,' . $mahasiswa->id,
            'nama'     => 'required|string|max:255',
            'email'    => 'nullable|email|max:255',
            'angkatan' => 'nullable|digits:4',
            'no_hp'    => 'nullable|string|max:50',
            'kelas'    => 'required|in:A,B,C,D,E',
        ]);

        $mahasiswa->update($data);

        return redirect()
            ->route('admins.mahasiswa.index', ['kelas' => $data['kelas']])
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $kelas = $mahasiswa->kelas;
        $mahasiswa->delete();

        return redirect()
            ->route('admins.mahasiswa.index', ['kelas' => $kelas])
            ->with('success', 'Mahasiswa berhasil dihapus.');
    }
}
