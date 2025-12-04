<?php

namespace App\Http\Controllers\DosenPenguji;

use App\Http\Controllers\Controller;
use App\Models\Penilaian;
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

    /** Ambil list mahasiswa by MK & kelas (tahan banting nama kolom) */
    private function getMahasiswaByMkKelas(?string $mk, ?string $kelas)
    {
        $mTable = $this->studentTable();
        $hasKelas = Schema::hasColumn($mTable, 'kelas');

        // Jika ada struktur kelompok
        if ($mk && Schema::hasTable('kelompoks') && Schema::hasTable('kelompok_anggota')) {
            $kTable = 'kelompoks';

            $q = DB::table("$kTable as k")
                ->join('kelompok_anggota as ka', 'ka.kelompok_id', '=', 'k.id')
                ->join("$mTable as m", 'm.nim', '=', 'ka.nim');

            // Kemungkinan kolom penghubung MK pada kelompoks
            if (Schema::hasColumn($kTable, 'kode_mk')) {
                $q->where('k.kode_mk', $mk);
            } elseif (Schema::hasColumn($kTable, 'mata_kuliah_kode')) {
                $q->where('k.mata_kuliah_kode', $mk);
            } elseif (Schema::hasColumn($kTable, 'kode_matakuliah')) {
                $q->where('k.kode_matakuliah', $mk);
            } elseif (Schema::hasColumn($kTable, 'mata_kuliah_id') && Schema::hasTable('mata_kuliah')) {
                $q->join('mata_kuliah as mkTbl', 'mkTbl.id', '=', 'k.mata_kuliah_id')
                  ->where('mkTbl.kode_mk', $mk);
            }

            if (!empty($kelas) && $hasKelas) {
                $q->where('m.kelas', $kelas);
            }

            $select = ['m.nim as nim','m.nama as nama'];
            if ($hasKelas) $select[] = 'm.kelas as kelas';

            return $q->distinct()
                ->orderBy('m.nama')
                ->get($select);
        }

        // Fallback: tanpa struktur kelompok → pakai query builder biar netral
        $q = DB::table($mTable);
        if (!empty($kelas) && $hasKelas) $q->where('kelas', $kelas);

        $select = ['nim','nama'];
        if ($hasKelas) $select[] = 'kelas';

        return $q->orderBy('nama')->limit(50)->get($select);
    }

    /** FORM TAMBAH */
    public function create(Request $request)
    {
        $mk    = $request->query('matakuliah');
        $kelas = $request->query('kelas');

        $matakuliah = \App\Models\MataKuliah::orderBy('nama_mk')->get(['kode_mk','nama_mk']);
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
            'item'       => new Penilaian(),
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
            'rubrik_id'     => ['required','integer','exists:rubrik,id'],
            'nilai'         => ['nullable','numeric','min:0','max:100'],
        ]);

        $existId = Penilaian::where('mahasiswa_nim',$data['mahasiswa_nim'])
                    ->where('rubrik_id',$data['rubrik_id'])
                    ->value('id');

        if ($existId) {
            return back()->withErrors([
                'mahasiswa_nim' => 'Nilai untuk mahasiswa & komponen ini sudah ada. Silakan gunakan Edit.'
            ])->withInput();
        }

        Penilaian::create($data);

        return redirect()
            ->route('dosenpenguji.penilaian', $request->only('matakuliah','kelas'))
            ->with('success','Nilai berhasil ditambahkan.');
    }

    /** FORM EDIT */
    public function edit(Penilaian $item, Request $request)
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
    public function update(Request $request, Penilaian $item)
    {
        $mTable = $this->studentTable();

        $data = $request->validate([
            'mahasiswa_nim' => ['required','string','exists:'.$mTable.',nim'],
            'rubrik_id'     => ['required','integer','exists:rubrik,id'],
            'nilai'         => ['nullable','numeric','min:0','max:100'],
        ]);

        $dupe = Penilaian::where('mahasiswa_nim',$data['mahasiswa_nim'])
                    ->where('rubrik_id',$data['rubrik_id'])
                    ->where('id','<>',$item->id)
                    ->exists();

        if ($dupe) {
            return back()->withErrors([
                'mahasiswa_nim' => 'Nilai untuk mahasiswa & komponen ini sudah ada.'
            ])->withInput();
        }

        $item->update($data);

        return redirect()
            ->route('dosenpenguji.penilaian', $request->only('matakuliah','kelas'))
            ->with('success','Nilai berhasil diperbarui.');
    }

    /** HAPUS */
    public function destroy(Penilaian $item)
    {
        $item->delete();

        return redirect()
            ->route('dosenpenguji.penilaian', request()->only('matakuliah','kelas'))
            ->with('success','Nilai berhasil dihapus.');
    }
}
