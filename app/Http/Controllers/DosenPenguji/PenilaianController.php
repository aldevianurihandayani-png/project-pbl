<?php

namespace App\Http\Controllers\DosenPenguji;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\RubrikPenilaian;
use App\Models\Rubrik;
use Illuminate\Support\Facades\DB;

class PenilaianController extends Controller
{
    /**
     * Menampilkan halaman gradebook penilaian.
     */
    public function index(Request $request)
    {
        $mk = $request->get('matakuliah');
        $matakuliah = \App\Models\MataKuliah::orderBy('nama_mk')->get();

        return view('dosenpenguji.penilaian', [
            'matakuliah' => $matakuliah,
            'mk'   => $mk,
        ]);
    }
    /**
     * Menyimpan beberapa nilai sekaligus (bulk).
     */
    public function bulkSave(Request $request)
    {
        $validated = $request->validate([
            'bobot' => ['required', 'array'],
            'bobot.*' => ['required', 'numeric', 'min:0'],
            'nilai' => ['nullable', 'array'],
            'nilai.*.*' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);
        // Validasi total bobot
        if (array_sum($validated['bobot']) != 100) {
            return back()->withErrors(['bobot' => 'Total bobot harus 100.'])->withInput();
        }
        DB::beginTransaction();
        try {
            // 1. Update Bobot Rubrik
            foreach ($validated['bobot'] as $rubricId => $bobot) {
                Rubrik::find($rubricId)->update(['bobot' => $bobot]);
            }
            // 2. Update atau Buat Nilai Mahasiswa
            if (isset($validated['nilai'])) {
                foreach ($validated['nilai'] as $nim => $grades) {
                    foreach ($grades as $rubricId => $nilai) {
                        if ($nilai !== null) {
                            RubrikPenilaian::updateOrInsert(
                                ['mahasiswa_nim' => $nim, 'rubrik_id' => $rubricId],
                                ['nilai' => $nilai]
                            );
                        }
                    }
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Nilai berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan nilai: ' . $e->getMessage());
        }
    }
    /**
     * Mengimpor nilai dari file.
     */
    public function import(Request $request)
    {
        // Logika untuk import akan ditambahkan di sini.
        return redirect()->back()->with('info', 'Fitur ini sedang dalam pengembangan.');
    }
    /**
     * Mengekspor nilai ke file.
     */
    public function export(Request $request)
    {
        // Logika untuk export akan ditambahkan di sini.
        return redirect()->back()->with('info', 'Fitur ini sedang dalam pengembangan.');
    }

    /**
     * Menghapus satu data nilai.
     */
    public function deleteGrade(Request $request, $nim, $rubric_id)
    {
        if (!$request->ajax()) {
            return response()->json(['message' => 'Invalid request'], 400);
        }

        try {
            DB::table('penilaian')
                ->where('mahasiswa_nim', $nim)
                ->where('rubrik_id', $rubric_id)
                ->delete();
            
            return response()->json(['message' => 'Nilai berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus nilai.'], 500);
        }
    }
}
