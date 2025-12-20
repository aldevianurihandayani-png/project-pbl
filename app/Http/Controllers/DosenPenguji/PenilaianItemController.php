<?php

namespace App\Http\Controllers\DosenPenguji;

use App\Http\Controllers\Controller;
// use App\Models\Penilaian;  // TIDAK dipakai untuk gradebook per-rubrik
use App\Models\MataKuliah;
use App\Models\Rubrik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PenilaianItemController extends Controller
{
    /** Nama tabel mahasiswa yang tersedia (mahasiswas | mahasiswa) */
    private function studentTable(): string
    {
        if (Schema::hasTable('mahasiswas')) return 'mahasiswas';
        if (Schema::hasTable('mahasiswa'))  return 'mahasiswa';
        // fallback nama paling umum
        return 'mahasiswas';
    }

    /**
     * ✅ Ambil list mahasiswa:
     * - TANPA filter MK (biar pilih MK tetap munculin semua mahasiswa)
     * - Filter kelas hanya kalau query kelas diisi
     */
    private function getMahasiswaByMkKelas(?string $mk, ?string $kelas)
    {
        $mTable   = $this->studentTable();
        $hasKelas = Schema::hasColumn($mTable, 'kelas');

        $q = DB::table($mTable);

        // filter kelas kalau diisi
        if (!empty($kelas) && $hasKelas) {
            $q->where('kelas', $kelas);
        }

        $select = ['nim', 'nama'];
        if ($hasKelas) $select[] = 'kelas';

        // Tidak dibatasi 50 supaya semua muncul (kalau mau tetap limit, bilang)
        return $q->orderBy('nama')->get($select);
    }

    /** FORM TAMBAH */
    public function create(Request $request)
    {
        $mk    = $request->query('matakuliah');
        $kelas = $request->query('kelas');

        $matakuliah = MataKuliah::orderBy('nama_mk')->get(['kode_mk','nama_mk']);
        $mhs        = $this->getMahasiswaByMkKelas($mk, $kelas);

        $rubrikQ = Rubrik::query()->orderBy('urutan')->orderBy('id');
        if ($mk) {
            $rubrikTable = (new Rubrik)->getTable();
            if (Schema::hasColumn($rubrikTable, 'kode_mk')) {
                $rubrikQ->where('kode_mk', $mk);
            } elseif (Schema::hasColumn($rubrikTable, 'mata_kuliah_kode')) {
                $rubrikQ->where('mata_kuliah_kode', $mk);
            }
        }
        $rubriks = $rubrikQ->get(['id','nama_rubrik','bobot']);

        return view('dosenpenguji.penilaian-item-form', [
            'mode'       => 'create',
            'item'       => (object) ['id' => null, 'mahasiswa_nim' => null, 'rubrik_id' => null, 'nilai' => null],
            'matakuliah' => $matakuliah,
            'mk'         => $mk,
            'kelas'      => $kelas,
            'mhs'        => $mhs,
            'rubriks'    => $rubriks,
        ]);
    }

    /** SIMPAN BARU */
    public function store(Request $request)
    {
        $mTable = $this->studentTable();

        $data = $request->validate([
            'mahasiswa_nim' => ['required','string','exists:'.$mTable.',nim'],
            // nama tabel rubrik di Laravel biasanya "rubriks"
            'rubrik_id'     => ['required','integer','exists:rubrik,id'],
            'nilai'         => ['nullable','numeric','min:0','max:100'],
        ]);

        // gunakan tabel gradebook LAMA: "penilaian" (tanpa s)
        $existId = DB::table('penilaian_items')
            ->where('mahasiswa_nim', $data['mahasiswa_nim'])
            ->where('rubrik_id', $data['rubrik_id'])
            ->value('id');

        if ($existId) {
            return back()->withErrors([
                'mahasiswa_nim' => 'Nilai untuk mahasiswa & komponen ini sudah ada. Silakan gunakan Edit.'
            ])->withInput();
        }

        DB::table('penilaian_items')->insert([
            'mahasiswa_nim' => $data['mahasiswa_nim'],
            'rubrik_id'     => $data['rubrik_id'],
            'nilai'         => $data['nilai'],
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return redirect()
            ->route('dosenpenguji.penilaian', $request->only('matakuliah','kelas'))
            ->with('success','Nilai berhasil ditambahkan.');
    }

    /** FORM EDIT */
    public function edit($id, Request $request)
    {
        $mk    = $request->query('matakuliah');
        $kelas = $request->query('kelas');

        $matakuliah = MataKuliah::orderBy('nama_mk')->get(['kode_mk','nama_mk']);
        $mhs        = $this->getMahasiswaByMkKelas($mk, $kelas);

        $rubrikQ = Rubrik::query()->orderBy('urutan')->orderBy('id');
        if ($mk) {
            $rubrikTable = (new Rubrik)->getTable();
            if (Schema::hasColumn($rubrikTable, 'kode_mk')) {
                $rubrikQ->where('kode_mk', $mk);
            } elseif (Schema::hasColumn($rubrikTable, 'mata_kuliah_kode')) {
                $rubrikQ->where('mata_kuliah_kode', $mk);
            }
        }
        $rubriks = $rubrikQ->get(['id','nama_rubrik','bobot']);

        // ambil baris dari tabel penilaian
        $item = DB::table('penilaian_items')->where('id', $id)->first();
        if (!$item) {
            abort(404);
        }

        return view('dosenpenguji.penilaian-item-form', [
            'mode'       => 'edit',
            'item'       => $item,
            'matakuliah' => $matakuliah,
            'mk'         => $mk,
            'kelas'      => $kelas,
            'mhs'        => $mhs,
            'rubriks'    => $rubriks,
        ]);
    }

    /** UPDATE */
    public function update(Request $request, $id)
    {
        $mTable = $this->studentTable();

        $data = $request->validate([
            'mahasiswa_nim' => ['required','string','exists:'.$mTable.',nim'],
            'rubrik_id'     => ['required','integer','exists:rubrik,id'],
            'nilai'         => ['nullable','numeric','min:0','max:100'],
        ]);

        // cek duplikasi nim + rubrik kecuali baris ini sendiri
        $dupe = DB::table('penilaian_items')
            ->where('mahasiswa_nim', $data['mahasiswa_nim'])
            ->where('rubrik_id', $data['rubrik_id'])
            ->where('id', '<>', $id)
            ->exists();

        if ($dupe) {
            return back()->withErrors([
                'mahasiswa_nim' => 'Nilai untuk mahasiswa & komponen ini sudah ada.'
            ])->withInput();
        }

        DB::table('penilaian_items')
            ->where('id', $id)
            ->update([
                'mahasiswa_nim' => $data['mahasiswa_nim'],
                'rubrik_id'     => $data['rubrik_id'],
                'nilai'         => $data['nilai'],
                'updated_at'    => now(),
            ]);

        return redirect()
            ->route('dosenpenguji.penilaian', $request->only('matakuliah','kelas'))
            ->with('success','Nilai berhasil diperbarui.');
    }

    /** HAPUS */
    public function destroy($id)
    {
        DB::table('penilaian_items')->where('id', $id)->delete();

        return redirect()
            ->route('dosenpenguji.penilaian', request()->only('matakuliah','kelas'))
            ->with('success','Nilai berhasil dihapus.');
    }
}
