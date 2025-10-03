<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
    // ====== INDEX ======
    public function index(Request $request)
    {
        $q = trim($request->query('q', ''));

        $query = Mahasiswa::query();
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('nim', 'like', "%{$q}%")
                    ->orWhere('nama', 'like', "%{$q}%")
                    ->orWhere('angkatan', 'like', "%{$q}%")
                    ->orWhere('no_hp', 'like', "%{$q}%");
            });
        }

        $mahasiswa = $query->orderByDesc('created_at')
            ->paginate(10)
            ->appends(['q' => $q]);

        return view('mahasiswa.index', compact('mahasiswa', 'q'));
    }

    // ====== CREATE ======
    public function create()
    {
        return view('mahasiswa.create');
    }

    // ====== STORE ======
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim'      => ['required', 'string', 'max:50', 'unique:mahasiswa,nim'],
            'nama'     => ['required', 'string', 'max:150'],
            'angkatan' => ['required', 'regex:/^\d{4}$/'],
            'no_hp'    => ['required', 'regex:/^[0-9+\-\s]{6,20}$/'],
        ], [
            'nim.required'      => 'NIM wajib diisi',
            'nim.unique'        => 'NIM sudah terdaftar',
            'nama.required'     => 'Nama wajib diisi',
            'angkatan.required' => 'Angkatan wajib diisi',
            'angkatan.regex'    => 'Angkatan harus 4 digit tahun (mis. 2024)',
            'no_hp.required'    => 'No HP wajib diisi',
            'no_hp.regex'       => 'Format No HP tidak valid',
        ]);

        Mahasiswa::create($validated);

        return redirect()
            ->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    // ====== EDIT ======
    // Route model binding: {mahasiswa:nim}
    public function edit(Mahasiswa $mahasiswa)
    {
        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    // ====== UPDATE ======
    // Route model binding: {mahasiswa:nim}
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $validated = $request->validate([
            'nim'      => [
                'required', 'string', 'max:50',
                Rule::unique('mahasiswa', 'nim')->ignore($mahasiswa->nim, 'nim'),
            ],
            'nama'     => ['required', 'string', 'max:150'],
            'angkatan' => ['required', 'regex:/^\d{4}$/'],
            'no_hp'    => ['required', 'regex:/^[0-9+\-\s]{6,20}$/'],
        ], [
            'nim.required'      => 'Semua kolom wajib diisi.',
            'nama.required'     => 'Semua kolom wajib diisi.',
            'angkatan.required' => 'Semua kolom wajib diisi.',
            'no_hp.required'    => 'Semua kolom wajib diisi.',
            'angkatan.regex'    => 'Angkatan harus 4 digit tahun (mis. 2024)',
            'no_hp.regex'       => 'Format No HP tidak valid',
            'nim.unique'        => 'NIM baru sudah terdaftar.',
        ]);

        $oldNim = $mahasiswa->nim;

        DB::beginTransaction();
        try {
            // update field biasa
            $mahasiswa->nama     = $validated['nama'];
            $mahasiswa->angkatan = $validated['angkatan'];
            $mahasiswa->no_hp    = $validated['no_hp'];

            // jika NIM berubah (update primary key)
            if ($oldNim !== $validated['nim']) {
                $mahasiswa->nim = $validated['nim'];
            }

            $mahasiswa->save();
            DB::commit();

            return redirect()
                ->route('mahasiswa.index')
                ->with('success', 'Data mahasiswa berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan perubahan: ' . $e->getMessage());
        }
    }

    // ====== DESTROY ======
    // Route model binding: {mahasiswa:nim}
    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();

        return redirect()
            ->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}
