<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use App\Models\Kelas;
use App\Models\User; // <-- tambah ini (atau ganti dengan model dosenmu)
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    /**
     * Halaman index: kartu-kartu kelas + filter + search.
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

        // pencarian nama kelompok / judul proyek
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
     * Halaman CRUD kelompok untuk satu kelas (misal: TI-3E)
     */
    public function kelas($kelas)
    {
        $kelompoks = Kelompok::where('kelas', $kelas)->get();

        return view('dosen.kelompok.kelas', [
            'kelompoks' => $kelompoks,
            'kelas'     => $kelas,
        ]);
    }

    /**
     * Show form create.
     */
    public function create(Request $request)
    {
        $kelasTerpilih = $request->query('kelas'); // misal TI-3E
        $daftarKelas   = Kelas::orderBy('nama_kelas')->get();

        // ambil semua dosen pembimbing untuk dropdown
        $dosenPembimbings = User::where('role', 'pembimbing')->orderBy('name')->get();

        return view('dosen.kelompok.create', [
            'kelasTerpilih'    => $kelasTerpilih,
            'daftarKelas'      => $daftarKelas,
            'dosenPembimbings' => $dosenPembimbings,
        ]);
    }

    /**
     * Store new kelompok.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama'                => 'required',
            'judul_proyek'        => 'required',
            'nama_klien'          => 'required',
            'ketua_kelompok'      => 'required',
            'kelas'               => 'required',
            'anggota'             => 'required',
            'dosen_pembimbing_id' => 'required|exists:users,id', // relasi ke tabel users
        ]);

        if (!str_starts_with($validatedData['kelas'], 'TI-')) {
            $validatedData['kelas'] = 'TI-' . $validatedData['kelas'];
        }

        $validatedData['judul'] = $validatedData['judul_proyek'];

        $kelompok = Kelompok::create($validatedData);

        return redirect()
            ->route('dosen.kelompok.kelas', $kelompok->kelas)
            ->with('success', 'Kelompok created successfully.');
    }

    /**
     * Show form edit.
     */
    public function edit(Kelompok $kelompok)
    {
        $daftarKelas   = Kelas::orderBy('nama_kelas')->get();
        $kelasTerpilih = $kelompok->kelas;

        // dropdown dosen pembimbing juga di halaman edit
        $dosenPembimbings = User::where('role', 'pembimbing')->orderBy('name')->get();

        return view('dosen.kelompok.edit', [
            'kelompok'          => $kelompok,
            'daftarKelas'       => $daftarKelas,
            'kelasTerpilih'     => $kelasTerpilih,
            'dosenPembimbings'  => $dosenPembimbings,
        ]);
    }

    /**
     * Update kelompok.
     */
    public function update(Request $request, Kelompok $kelompok)
    {
        $validatedData = $request->validate([
            'nama'                => 'required',
            'judul_proyek'        => 'required',
            'nama_klien'          => 'required',
            'ketua_kelompok'      => 'required',
            'kelas'               => 'required',
            'anggota'             => 'required',
            'dosen_pembimbing_id' => 'required|exists:users,id',
        ]);

        if (!str_starts_with($validatedData['kelas'], 'TI-')) {
            $validatedData['kelas'] = 'TI-' . $validatedData['kelas'];
        }

        $validatedData['judul'] = $validatedData['judul_proyek'];

        $kelompok->update($validatedData);

        return redirect()
            ->route('dosen.kelompok.kelas', $kelompok->kelas)
            ->with('success', 'Kelompok updated successfully.');
    }

    /**
     * Delete kelompok.
     */
    public function destroy(Kelompok $kelompok)
    {
        $kelas = $kelompok->kelas;
        $kelompok->delete();

        return redirect()
            ->route('dosen.kelompok.kelas', $kelas)
            ->with('success', 'Kelompok deleted successfully.');
    }
}
