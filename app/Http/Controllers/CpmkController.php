<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MataKuliah;
use App\Models\Cpmk;

class CpmkController extends Controller
{
    /**
     * Tampilkan halaman CPMK + filter per mata kuliah.
     * URL: GET /dosenpenguji/cpmk?matakuliah=KODE_MK
     */
    public function index(Request $request)
    {
        // kode mk yang dipilih dari query string ?matakuliah=IF123
        $mk = $request->query('matakuliah');

        // Dropdown mata kuliah
        $matakuliah = MataKuliah::orderBy('nama_mk')
                        ->get(['kode_mk', 'nama_mk']);

        // Default: belum ada CPMK
        $cpmk = collect();

        // Kalau sudah ada MK yang dipilih, ambil CPMK-nya
        if ($mk) {
            $cpmk = Cpmk::where('kode_mk', $mk)
                    ->orderBy('urutan')
                    ->get();
        }

        // Blade: resources/views/dosenpenguji/cpmk.blade.php
        return view('dosenpenguji.cpmk', compact('matakuliah', 'cpmk', 'mk'));
    }

    /**
     * Simpan CPMK baru.
     * Dipanggil dari form "Tambah CPMK"
     * URL: POST /dosenpenguji/cpmk
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_mk'   => ['required', 'string', 'max:10'],
            'kode'      => ['required', 'string', 'max:20'],
            'deskripsi' => ['required', 'string'],
            'bobot'     => ['required', 'integer', 'min:0', 'max:100'],
            'urutan'    => ['required', 'integer', 'min:1'],
        ]);

        Cpmk::create($data);

        // Setelah simpan, balik ke halaman CPMK untuk MK yang sama
        return redirect('/dosenpenguji/cpmk?matakuliah=' . $data['kode_mk'])
            ->with('success', 'CPMK berhasil ditambahkan.');
    }

    /**
     * UPDATE standar by ID (kalau suatu saat pakai form biasa).
     * URL: PUT /dosenpenguji/cpmk/{cpmk}
     */
    public function update(Request $request, Cpmk $cpmk)
    {
        $data = $request->validate([
            'kode_mk'   => ['required', 'string', 'max:10'],
            'kode'      => ['required', 'string', 'max:20'],
            'deskripsi' => ['required', 'string'],
            'bobot'     => ['required', 'integer', 'min:0', 'max:100'],
            'urutan'    => ['required', 'integer', 'min:1'],
        ]);

        $cpmk->update($data);

        return redirect('/dosenpenguji/cpmk?matakuliah=' . $data['kode_mk'])
            ->with('success', 'CPMK berhasil diubah.');
    }

    /**
     * QUICK UPDATE
     * Dipakai JS di cpmk.blade.php:
     *   form.action = /dosenpenguji/cpmk/{kode_mk}/{kode}
     * URL: PUT /dosenpenguji/cpmk/{kode_mk}/{kode}
     */
    public function quickUpdate(Request $request, $kode_mk, $kode)
    {
        $data = $request->validate([
            'deskripsi' => ['required', 'string'],
            'bobot'     => ['required', 'numeric', 'min:0', 'max:100'],
            'urutan'    => ['required', 'integer', 'min:1'],
        ]);

        $updated = Cpmk::where('kode_mk', $kode_mk)
                    ->where('kode', $kode)
                    ->update($data);

        return redirect('/dosenpenguji/cpmk?matakuliah=' . $kode_mk)
            ->with(
                $updated ? 'success' : 'error',
                $updated ? 'CPMK berhasil diperbarui.' : 'CPMK tidak ditemukan / gagal diperbarui.'
            );
    }

    /**
     * Hapus CPMK by ID.
     * URL: DELETE /dosenpenguji/cpmk/{cpmk}
     */
    public function destroy(Cpmk $cpmk)
    {
        $kodeMk = $cpmk->kode_mk;
        $cpmk->delete();

        return redirect('/dosenpenguji/cpmk?matakuliah=' . $kodeMk)
            ->with('success', 'CPMK berhasil dihapus.');
    }

    // Biar kalau route-resource kepanggil, diarahkan balik ke index saja.

    public function create()
    {
        return redirect('/dosenpenguji/cpmk');
    }

    public function show(Cpmk $cpmk)
    {
        return redirect('/dosenpenguji/cpmk?matakuliah=' . $cpmk->kode_mk);
    }

    public function edit(Cpmk $cpmk)
    {
        return redirect('/dosenpenguji/cpmk?matakuliah=' . $cpmk->kode_mk);
    }
}
