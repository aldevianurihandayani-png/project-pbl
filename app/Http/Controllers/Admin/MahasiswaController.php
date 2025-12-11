<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $kelasFilter = $request->query('kelas');

        // statistik per kelas (berdasarkan data mahasiswa)
        $kelasStats = Mahasiswa::select(
                'kelas',
                DB::raw('COUNT(*) as total'),
                DB::raw('MIN(angkatan) as min_angkatan'),
                DB::raw('MAX(angkatan) as max_angkatan')
            )
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->get()
            ->keyBy('kelas');

        // 🔹 daftar kelas master dari tabel `kelas`
        // dipakai untuk:
        // - kartu "Data Mahasiswa per Kelas"
        // - dropdown filter kelas di view (kalau mau)
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();

        // data mahasiswa kalau user pilih 1 kelas
        $mahasiswas = null;
        if ($kelasFilter) {
            $mahasiswas = Mahasiswa::where('kelas', $kelasFilter)
                ->orderBy('nama')
                ->paginate(10);
        }

        return view('admins.mahasiswa.index', [
            'kelasStats'   => $kelasStats,
            'kelasFilter'  => $kelasFilter,
            'mahasiswas'   => $mahasiswas,
            'daftarKelas'  => $daftarKelas,   // 🔹 dikirim ke blade
        ]);
    }

    public function create(Request $request)
    {
        $kelas = $request->query('kelas');

        // gunakan get() agar object ->nama_kelas bisa dipakai di view
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();

        return view('admins.mahasiswa.create', compact('kelas', 'daftarKelas'));
    }

    public function store(Request $request)
    {
        // validasi harus pakai array string
        $daftarKelas = Kelas::orderBy('nama_kelas')
            ->pluck('nama_kelas')
            ->toArray();

        $data = $request->validate([
            'nim'      => 'required|string|max:50|unique:mahasiswas,nim',
            'nama'     => 'required|string|max:255',
            'email'    => 'nullable|email|max:255',
            'angkatan' => 'nullable|digits:4',
            'no_hp'    => 'nullable|string|max:50',
            'kelas'    => ['required', Rule::in($daftarKelas)],
        ]);

        Mahasiswa::create($data);

        return redirect()
            ->route('admins.mahasiswa.index', ['kelas' => $data['kelas']])
            ->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        // dropdown kelas (pakai model supaya bisa $row->nama_kelas)
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();

        return view('admins.mahasiswa.edit', compact('mahasiswa', 'daftarKelas'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        // validasi pakai array string
        $daftarKelas = Kelas::orderBy('nama_kelas')
            ->pluck('nama_kelas')
            ->toArray();

        $data = $request->validate([
            'nim' => [
                'required',
                'string',
                'max:50',
                Rule::unique('mahasiswas', 'nim')->ignore($mahasiswa->nim, 'nim'),
            ],
            'nama'     => 'required|string|max:255',
            'email'    => 'nullable|email|max:255',
            'angkatan' => 'nullable|digits:4',
            'no_hp'    => 'nullable|string|max:50',
            'kelas'    => ['required', Rule::in($daftarKelas)],
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
