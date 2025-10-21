<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\Dosen;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;

class MataKuliahController extends Controller
{
    public function index() {
        $mataKuliah = MataKuliah::with('dosen')->paginate(10);
        return view('admins.matakuliah.index', compact('mataKuliah'));
    }

    public function create() {
        $dosen = Dosen::all();
        return view('admins.matakuliah.create', compact('dosen'));
    }

    public function store(Request $request) {
        $request->validate([
            'kode_mk' => 'required|unique:mata_kuliah',
            'nama_mk' => 'required',
            'sks' => 'required|integer',
            'semester' => 'required|integer',
            'id_dosen' => 'required'
        ]);

        MataKuliah::create($request->all());
        return redirect()->route('matakuliah.index')->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function edit($kode_mk) {
        $mk = MataKuliah::findOrFail($kode_mk);
        $dosen = Dosen::all();
        return view('admins.matakuliah.edit', compact('mk', 'dosen'));
    }

    public function update(Request $request, $kode_mk) {
        $mk = MataKuliah::findOrFail($kode_mk);
        $request->validate([
            'nama_mk' => 'required',
            'sks' => 'required|integer',
            'semester' => 'required|integer',
            'id_dosen' => 'required'
        ]);
        $mk->update($request->all());
        return redirect()->route('matakuliah.index')->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy($kode_mk) {
        $mk = MataKuliah::findOrFail($kode_mk);
        $mk->delete();
        return redirect()->route('matakuliah.index')->with('success', 'Mata kuliah berhasil dihapus.');
    }
}




class MataKuliahController extends Controller
{
    public function index()
    {
        // created_at ada di tabel => latest() oke
        $matakuliah = MataKuliah::with('dosen')->latest()->paginate(10);
        return view('admins.matakuliah.index', compact('matakuliah'));
    }

    public function create()
    {
        // ambil kandidat dosen (boleh sesuaikan rolenya)
        $dosens = User::whereIn('role', ['dosen', 'dosen_pembimbing'])
            ->orderBy('name')
            ->get(['id','name']);

        return view('admins.matakuliah.create', compact('dosens'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_mk'  => ['required','string','max:20','unique:mata_kuliah,kode_mk'],
            'nama_mk'  => ['required','string','max:255'],
            'sks'      => ['required','integer','min:1','max:8'],
            'semester' => ['required','integer','min:1','max:14'],
            // opsional sesuai migrasi (nullable). pakai id, bukan name
            'id_dosen' => ['nullable','integer','exists:users,id'],
        ]);

        MataKuliah::create($data);

        return redirect()
            ->route('admins.matakuliah.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function edit(MataKuliah $matakuliah) // binding by 'kode_mk' (model sudah getRouteKeyName)
    {
        $dosens = User::whereIn('role', ['dosen', 'dosen_pembimbing'])
            ->orderBy('name')
            ->get(['id','name']);

        return view('admins.matakuliah.edit', compact('matakuliah','dosens'));
    }

    public function update(Request $request, MataKuliah $matakuliah)
    {
        $data = $request->validate([
            // kalau kode_mk tidak ingin diubah, hilangkan field ini dari form; jika ingin bisa diubah pakai rule ignore:
            'kode_mk'  => [
                'required','string','max:20',
                Rule::unique('mata_kuliah','kode_mk')->ignore($matakuliah->kode_mk, 'kode_mk')
            ],
            'nama_mk'  => ['required','string','max:255'],
            'sks'      => ['required','integer','min:1','max:8'],
            'semester' => ['required','integer','min:1','max:14'],
            'id_dosen' => ['nullable','integer','exists:users,id'],
        ]);

        $matakuliah->update($data);

        return redirect()
            ->route('admins.matakuliah.index')
            ->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy(MataKuliah $matakuliah)
    {
        $matakuliah->delete();

        return redirect()
            ->route('admins.matakuliah.index')
            ->with('success', 'Mata kuliah berhasil dihapus.');
    }
}

