<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelompok::query();

        if ($request->has('semester') && $request->semester != '') {
            $kelasFilter = 'TI-' . $request->semester;

            if ($request->has('kelas') && $request->kelas != '') {
                $kelasFilter .= $request->kelas;
            }

            $query->where('kelas', 'like', $kelasFilter . '%');
        }

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

    public function kelas($kelas)
    {
        $kelompoks = Kelompok::where('kelas', $kelas)->get();

        return view('dosen.kelompok.kelas', [
            'kelompoks' => $kelompoks,
            'kelas'     => $kelas,
        ]);
    }

    /**
     * FORM TAMBAH KELOMPOK
     */
    public function create(Request $request)
    {
        $kelasTerpilih = $request->query('kelas');
        $daftarKelas   = Kelas::orderBy('nama_kelas')->get();
        $dosenPembimbings = Dosen::orderBy('nama_dosen')->get();

        // Mahasiswa per kelas yang BELUM punya kelompok
        $mahasiswas = Mahasiswa::query()
            ->when($kelasTerpilih, fn ($q) => $q->where('kelas', $kelasTerpilih))
            ->whereNull('kelompok_id')   // <-- PAKAI kelompok_id
            ->orderBy('nama')
            ->get();

        return view('dosen.kelompok.create', compact(
            'kelasTerpilih', 'daftarKelas', 'dosenPembimbings', 'mahasiswas'
        ));
    }

    /**
     * STORE KELOMPOK BARU
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama'                => 'required',
            'judul_proyek'        => 'required',
            'nama_klien'          => 'required',
            'ketua_kelompok'      => 'required',
            'kelas'               => 'required',
            'anggota'             => 'required|array',
            'anggota.*'           => 'distinct|exists:mahasiswas,nim',
            'dosen_pembimbing_id' => 'required|exists:dosens,id_dosen',
        ]);

        if (!str_starts_with($validatedData['kelas'], 'TI-')) {
            $validatedData['kelas'] = 'TI-' . $validatedData['kelas'];
        }

        $validatedData['judul'] = $validatedData['judul_proyek'];

        $anggotaNim = $validatedData['anggota'];
        $validatedData['anggota'] = implode(', ', $anggotaNim);

        // SIMPAN KELOMPOK
        $kelompok = Kelompok::create($validatedData);

        // UPDATE anggota → kelompok_id
        Mahasiswa::whereIn('nim', $anggotaNim)
            ->update(['kelompok_id' => $kelompok->id]);

        // pastikan ketua masuk juga
        Mahasiswa::where('nim', $validatedData['ketua_kelompok'])
            ->update(['kelompok_id' => $kelompok->id]);

        return redirect()
            ->route('dosen.kelompok.kelas', $kelompok->kelas)
            ->with('success', 'Kelompok created successfully.');
    }

    /**
     * FORM EDIT KELOMPOK
     */
    public function edit(Kelompok $kelompok)
    {
        $daftarKelas   = Kelas::orderBy('nama_kelas')->get();
        $kelasTerpilih = $kelompok->kelas;
        $dosenPembimbings = Dosen::orderBy('nama_dosen')->get();

        // Mahasiswa:
        // - belum punya kelompok
        // - atau anggota kelompok ini
        $mahasiswas = Mahasiswa::query()
            ->where('kelas', $kelasTerpilih)
            ->where(function ($q) use ($kelompok) {
                $q->whereNull('kelompok_id')
                  ->orWhere('kelompok_id', $kelompok->id);
            })
            ->orderBy('nama')
            ->get();

        $anggotaTerpilih = Mahasiswa::where('kelompok_id', $kelompok->id)
            ->pluck('nim')
            ->toArray();

        return view('dosen.kelompok.edit', compact(
            'kelompok', 'daftarKelas', 'kelasTerpilih',
            'dosenPembimbings', 'mahasiswas', 'anggotaTerpilih'
        ));
    }

    /**
     * UPDATE KELOMPOK
     */
    public function update(Request $request, Kelompok $kelompok)
    {
        $validatedData = $request->validate([
            'nama'                => 'required',
            'judul_proyek'        => 'required',
            'nama_klien'          => 'required',
            'ketua_kelompok'      => 'required',
            'kelas'               => 'required',
            'anggota'             => 'required|array',
            'anggota.*'           => 'distinct|exists:mahasiswas,nim',
            'dosen_pembimbing_id' => 'required|exists:dosens,id_dosen',
        ]);

        if (!str_starts_with($validatedData['kelas'], 'TI-')) {
            $validatedData['kelas'] = 'TI-' . $validatedData['kelas'];
        }

        $validatedData['judul'] = $validatedData['judul_proyek'];

        $anggotaNim = $validatedData['anggota'];
        $validatedData['anggota'] = implode(', ', $anggotaNim);

        // UPDATE KELOMPOK
        $kelompok->update($validatedData);

        // lepaskan semua mahasiswa dari kelompok sebelumnya
        Mahasiswa::where('kelompok_id', $kelompok->id)
            ->update(['kelompok_id' => null]);

        // masukkan anggota baru
        Mahasiswa::whereIn('nim', $anggotaNim)
            ->update(['kelompok_id' => $kelompok->id]);

        // pastikan ketua masuk
        Mahasiswa::where('nim', $validatedData['ketua_kelompok'])
            ->update(['kelompok_id' => $kelompok->id]);

        return redirect()
            ->route('dosen.kelompok.kelas', $kelompok->kelas)
            ->with('success', 'Kelompok updated successfully.');
    }

    public function destroy(Kelompok $kelompok)
    {
        $kelas = $kelompok->kelas;

        // hapus relasi mahasiswa dari kelompok ini
        Mahasiswa::where('kelompok_id', $kelompok->id)
            ->update(['kelompok_id' => null]);

        $kelompok->delete();

        return redirect()
            ->route('dosen.kelompok.kelas', $kelas)
            ->with('success', 'Kelompok deleted successfully.');
    }
}
