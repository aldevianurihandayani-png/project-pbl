<?php

namespace App\Http\Controllers\DosenPenguji;

use App\Http\Controllers\Controller;
use App\Models\Rubrik;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class RubrikController extends Controller
{
    /** Cari nama kolom penghubung MK di tabel rubrik (berbasis KODE MK) */
    private function mkCodeColumns(): array
    {
        $t = (new Rubrik)->getTable(); // misal: 'rubrik' atau 'rubriks'
        $have = [];

        foreach (['kode_mk','mata_kuliah_kode','matakuliah_kode','kode_matakuliah','mk_kode','kodeMK'] as $col) {
            if (Schema::hasColumn($t, $col)) {
                $have[] = $col;
            }
        }

        return $have;
    }

    /** Apakah rubrik memakai FK id -> mata_kuliah.id */
    private function usesMkId(): bool
    {
        $t = (new Rubrik)->getTable();
        return Schema::hasColumn($t, 'mata_kuliah_id') && Schema::hasTable('mata_kuliah');
    }

    /**
     * LIST + Filter per mata kuliah (?matakuliah=KODE_MK)
     * Route: GET /dosenpenguji/rubrik  (name: dosenpenguji.rubrik.index)
     */
    public function index(Request $request)
    {
        $mk = $request->query('matakuliah'); // contoh: MK-004

        // Dropdown mata kuliah
        $matakuliah = MataKuliah::orderBy('nama_mk')->get(['kode_mk','nama_mk']);

        $rubriks    = collect();
        $totalBobot = 0;
        $mkName     = null;

        if ($mk) {
            $rubrikTable = (new Rubrik)->getTable();

            // ---- Percobaan 1: pakai kolom berbasis KODE MK (kode_mk / sejenis)
            $codeCols = $this->mkCodeColumns();
            $q1       = Rubrik::query()->select($rubrikTable.'.*');

            if (!empty($codeCols)) {
                $q1->where(function ($q) use ($codeCols, $mk) {
                    foreach ($codeCols as $col) {
                        $q->orWhere($col, $mk);
                    }
                });
            } else {
                // paksa kosong agar lanjut percobaan 2
                $q1->whereRaw('1=2');
            }

            $temp = (clone $q1)->limit(1)->get();

            if ($temp->count() > 0) {
                // Skema berbasis KODE MK
                $rubriks = $q1
                    ->when(
                        Schema::hasColumn($rubrikTable,'urutan'),
                        fn($q) => $q->orderBy('urutan')
                    )
                    ->orderBy($rubrikTable.'.id')
                    ->paginate(10, ['id','nama_rubrik','deskripsi','bobot','urutan'])
                    ->withQueryString();

            } elseif ($this->usesMkId()) {
                // ---- Percobaan 2: pakai FK id (join ke mata_kuliah lalu cocokkan kode_mk)
                $rubriks = Rubrik::query()
                    ->join('mata_kuliah as mkTbl', 'mkTbl.id', '=', $rubrikTable.'.mata_kuliah_id')
                    ->where('mkTbl.kode_mk', $mk)
                    ->select($rubrikTable.'.*')
                    ->when(
                        Schema::hasColumn($rubrikTable,'urutan'),
                        fn($q) => $q->orderBy('urutan')
                    )
                    ->orderBy($rubrikTable.'.id')
                    ->paginate(10, ['id','nama_rubrik','deskripsi','bobot','urutan'])
                    ->withQueryString();

            } else {
                // ---- Percobaan 3: fallback cari kolom mirip 'mk' / 'matakuliah'
                $maybe = collect(Schema::getColumnListing($rubrikTable))
                    ->first(fn($c) =>
                        stripos($c,'mk')!==false ||
                        stripos($c,'mata_kuliah')!==false ||
                        stripos($c,'matakuliah')!==false
                    );

                $rubriks = Rubrik::query()
                    ->when(
                        $maybe,
                        fn($q) => $q->where($maybe,$mk),
                        fn($q) => $q->whereRaw('1=2')
                    )
                    ->when(
                        Schema::hasColumn($rubrikTable,'urutan'),
                        fn($q) => $q->orderBy('urutan')
                    )
                    ->orderBy($rubrikTable.'.id')
                    ->paginate(10, ['id','nama_rubrik','deskripsi','bobot','urutan'])
                    ->withQueryString();
            }

            // total bobot (aman untuk paginator)
            $totalBobot = method_exists($rubriks, 'sum')
                ? (int)$rubriks->sum('bobot')
                : (int)collect($rubriks)->sum('bobot');

            $mkName = optional($matakuliah->firstWhere('kode_mk', $mk))->nama_mk ?? $mk;
        }

        // VIEW: resources/views/dosenpenguji/rubrik.blade.php
        return view('dosenpenguji.rubrik', compact(
            'matakuliah',
            'rubriks',
            'mk',
            'totalBobot',
            'mkName'
        ));
    }

    /**
     * SIMPAN BARU
     * Route: POST /dosenpenguji/rubrik (name: dosenpenguji.rubrik.store)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mk'     => ['required','string','max:20'],
            'nama_rubrik' => ['required','string','max:255'],
            'bobot'       => ['required','integer','min:0','max:100'],
            'urutan'      => ['required','integer','min:0'],
            'deskripsi'   => ['nullable','string'],
        ]);

        // Petakan input kode_mk ke kolom yang tersedia di tabel 'rubrik'
        $data   = $validated;
        $t      = (new Rubrik)->getTable();
        $mapped = false;

        foreach (['kode_mk','mata_kuliah_kode','matakuliah_kode','kode_matakuliah'] as $col) {
            if (Schema::hasColumn($t, $col)) {
                $data[$col] = $validated['kode_mk'];
                $mapped     = true;
                break;
            }
        }

        // Kalau skema pakai mata_kuliah_id (FK ke tabel mata_kuliah)
        if (!$mapped && Schema::hasColumn($t,'mata_kuliah_id') && Schema::hasTable('mata_kuliah')) {
            $mkId = MataKuliah::where('kode_mk',$validated['kode_mk'])->value('id');
            if ($mkId) {
                $data['mata_kuliah_id'] = $mkId;
                $mapped = true;
            }
        }

        // Jika kolom asli bukan 'kode_mk', buang field input 'kode_mk'
        if ($mapped && !Schema::hasColumn($t,'kode_mk')) {
            unset($data['kode_mk']);
        }

        Rubrik::create($data);

        return redirect()
            ->route('dosenpenguji.rubrik.index', ['matakuliah'=>$request->kode_mk])
            ->with('success', 'Rubrik berhasil ditambahkan.');
    }

    /**
     * UPDATE
     * Route: PUT /dosenpenguji/rubrik/{rubrik} (name: dosenpenguji.rubrik.update)
     */
    public function update(Request $request, Rubrik $rubrik)
    {
        $validated = $request->validate([
            'kode_mk'     => ['required','string','max:20'],
            'nama_rubrik' => ['required','string','max:255'],
            'bobot'       => ['required','integer','min:0','max:100'],
            'urutan'      => ['required','integer','min:0'],
            'deskripsi'   => ['nullable','string'],
        ]);

        $data   = $validated;
        $t      = (new Rubrik)->getTable();
        $mapped = false;

        foreach (['kode_mk','mata_kuliah_kode','matakuliah_kode','kode_matakuliah'] as $col) {
            if (Schema::hasColumn($t, $col)) {
                $data[$col] = $validated['kode_mk'];
                $mapped     = true;
                break;
            }
        }

        if (!$mapped && Schema::hasColumn($t,'mata_kuliah_id') && Schema::hasTable('mata_kuliah')) {
            $mkId = MataKuliah::where('kode_mk',$validated['kode_mk'])->value('id');
            if ($mkId) {
                $data['mata_kuliah_id'] = $mkId;
                $mapped = true;
            }
        }

        if ($mapped && !Schema::hasColumn($t,'kode_mk')) {
            unset($data['kode_mk']);
        }

        $rubrik->update($data);

        return redirect()
            ->route('dosenpenguji.rubrik.index', ['matakuliah'=>$request->kode_mk])
            ->with('success', 'Rubrik berhasil diperbarui.');
    }

    /**
     * DELETE
     * Route: DELETE /dosenpenguji/rubrik/{rubrik} (name: dosenpenguji.rubrik.destroy)
     */
    public function destroy(Rubrik $rubrik)
    {
        $rubrik->delete();

        return redirect()
            ->route('dosenpenguji.rubrik.index')
            ->with('success', 'Rubrik berhasil dihapus.');
    }
}
