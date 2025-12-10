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

        // dosen dari tabel dosens
        $dosenPembimbings = Dosen::orderBy('nama_dosen')->get();

        // mahasiswa (optional filter per kelas)
        $mahasiswas = Mahasiswa::query()
            ->when($kelasTerpilih, fn ($q) => $q->where('kelas', $kelasTerpilih))
            ->orderBy('nama')
            ->get();

        return view('dosen.kelompok.create', [
            'kelasTerpilih'    => $kelasTerpilih,
            'daftarKelas'      => $daftarKelas,
            'dosenPembimbings' => $dosenPembimbings,
            'mahasiswas'       => $mahasiswas,
        ]);
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
            'ketua_kelompok'      => 'required',           // NIM ketua
            'kelas'               => 'required',
            'anggota'             => 'required|array',     // <select multiple name="anggota[]">
            'anggota.*'           => 'distinct|exists:mahasiswas,nim',
            'dosen_pembimbing_id' => 'required|exists:dosens,id_dosen',
        ]);

        if (!str_starts_with($validatedData['kelas'], 'TI-')) {
            $validatedData['kelas'] = 'TI-' . $validatedData['kelas'];
        }

        $validatedData['judul'] = $validatedData['judul_proyek'];

        // simpan anggota sebagai string di kolom `anggota`
        $anggotaNim = $validatedData['anggota'];          // array NIM
        $validatedData['anggota'] = implode(', ', $anggotaNim);

        // SIMPAN KELOMPOK
        $kelompok = Kelompok::create($validatedData);

        // set kelompok_id untuk semua anggota
        if (!empty($anggotaNim)) {
            Mahasiswa::whereIn('nim', $anggotaNim)
                ->update(['kelompok_id' => $kelompok->id]);
        }

        // pastikan ketua juga punya kelompok_id
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

        $mahasiswas = Mahasiswa::where('kelas', $kelasTerpilih)
            ->orderBy('nama')
            ->get();

        // pakai kolom kelompok_id (bukan id_kelompok)
        $anggotaTerpilih = Mahasiswa::where('kelompok_id', $kelompok->id)
            ->pluck('nim')
            ->toArray();

        return view('dosen.kelompok.edit', [
            'kelompok'          => $kelompok,
            'daftarKelas'       => $daftarKelas,
            'kelasTerpilih'     => $kelasTerpilih,
            'dosenPembimbings'  => $dosenPembimbings,
            'mahasiswas'        => $mahasiswas,
            'anggotaTerpilih'   => $anggotaTerpilih,
        ]);
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

        // UPDATE DATA KELOMPOK
        $kelompok->update($validatedData);

        // lepas semua mahasiswa lama dari kelompok ini (kelompok_id -> null)
        Mahasiswa::where('kelompok_id', $kelompok->id)
            ->update(['kelompok_id' => null]);

        // set anggota baru
        if (!empty($anggotaNim)) {
            Mahasiswa::whereIn('nim', $anggotaNim)
                ->update(['kelompok_id' => $kelompok->id]);
        }

        // pastikan ketua juga masuk
        Mahasiswa::where('nim', $validatedData['ketua_kelompok'])
            ->update(['kelompok_id' => $kelompok->id]);

        return redirect()
            ->route('dosen.kelompok.kelas', $kelompok->kelas)
            ->with('success', 'Kelompok updated successfully.');
    }

    public function destroy(Kelompok $kelompok)
    {
        $kelas = $kelompok->kelas;

        // lepas mahasiswa dari kelompok ini
        Mahasiswa::where('kelompok_id', $kelompok->id)
            ->update(['kelompok_id' => null]);

        $kelompok->delete();

        return redirect()
            ->route('dosen.kelompok.kelas', $kelas)
            ->with('success', 'Kelompok deleted successfully.');
    }
}
