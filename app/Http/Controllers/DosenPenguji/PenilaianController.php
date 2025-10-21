<?php

namespace App\Http\Controllers\DosenPenguji;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\RubrikPenilaian;
use App\Models\Rubrik;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // <-- tambah

class PenilaianController extends Controller
{
    /**
     * Menampilkan halaman gradebook penilaian.
     */
    public function index(Request $request)
    {
        // Dropdown MK selalu terisi
        $matakuliah = MataKuliah::orderBy('nama_mk')->get();

        $kodeMk = $request->query('matakuliah'); // ex: IF184201
        $kelas  = $request->query('kelas');      // ex: A/B/C/...

        // default untuk view
        $rubrics    = collect();
        $mahasiswa  = collect();
        $totalBobot = 0;

        if (!empty($kodeMk)) {
            // ===== Ambil rubrik utk MK terpilih (deteksi nama kolom secara aman)
            $rubrikTable = (new Rubrik)->getTable();
            $rubricsQ = Rubrik::query();

            if (Schema::hasColumn($rubrikTable, 'kode_mk')) {
                $rubricsQ->where('kode_mk', $kodeMk);
            } elseif (Schema::hasColumn($rubrikTable, 'mata_kuliah_kode')) {
                $rubricsQ->where('mata_kuliah_kode', $kodeMk);
            }
            $rubrics = $rubricsQ->orderBy('id')->get();
            $totalBobot = (int) $rubrics->sum('bobot');

            // ===== Ambil mahasiswa (opsional filter kelas)
            $mhsQ = Mahasiswa::select('nim','nama','kelas')
                ->when($kelas, fn($q) => $q->where('kelas', $kelas))
                ->orderBy('nama');

            // gunakan pagination agar kompatibel dengan blade kamu
            $mahasiswa = $mhsQ->paginate(15)->withQueryString();

            // ===== Sisipkan nilai yg sudah ada ke setiap mahasiswa
            $nimList   = $mahasiswa->pluck('nim')->all();
            $rubricIds = $rubrics->pluck('id')->all();

            $nilaiMap = RubrikPenilaian::select('mahasiswa_nim as nim','rubrik_id','nilai')
                ->whereIn('mahasiswa_nim', $nimList ?: ['-'])
                ->whereIn('rubrik_id', $rubricIds ?: ['-'])
                ->get()
                ->groupBy('nim');

            // tambahkan properti koleksi "penilaian" agar blade bisa firstWhere('rubric_id', ...)
            $mahasiswa->getCollection()->transform(function ($m) use ($nilaiMap) {
                $m->penilaian = collect($nilaiMap->get($m->nim, []));
                return $m;
            });
        }

        return view('dosenpenguji.penilaian', [
            'matakuliah' => $matakuliah,
            'rubrics'    => $rubrics,
            'mahasiswa'  => $mahasiswa,
            'totalBobot' => $totalBobot,
            'mk'         => $kodeMk,
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
