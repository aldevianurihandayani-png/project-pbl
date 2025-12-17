<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    // ==== Skema baru (tabel plural) ====
    protected $table = 'penilaians';

    protected $fillable = [
        'mahasiswa_id',      // NOTE: pada setup kamu ini akan berisi NIM (karena Mahasiswa PK = nim)
        'matakuliah_kode',   // FK -> mata_kuliah.kode_mk (STRING)
        'kelas_id',
        'dosen_id',
        'komponen',          // JSON / LONGTEXT
        'nilai_akhir',
    ];

    protected $casts = [
        'komponen'    => 'array',
        'nilai_akhir' => 'decimal:2',
    ];

    /* ============================================================
     |  RELASI
     * ============================================================ */

    public function mahasiswa()
    {
        // ✅ Mahasiswa PK = nim, jadi FK di penilaians.mahasiswa_id harus cocok ke mahasiswas.nim
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id', 'nim');
    }

    public function matakuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'matakuliah_kode', 'kode_mk');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    /* ============================================================
     |  KOMPATIBILITAS DENGAN KODE LAMA (atribut virtual)
     |  - mahasiswa_nim  -> mapping ke mahasiswa_id (yang menyimpan NIM)
     |  - rubrik_id      -> disimpan/diambil dari komponen JSON (opsional)
     |  - nilai          -> mapping ke nilai_akhir
     * ============================================================ */

    // --- mahasiswa_nim (virtual) ---
    public function getMahasiswaNimAttribute()
    {
        // kalau relasi ter-load -> ambil nim, kalau tidak -> fallback ke mahasiswa_id
        return optional($this->mahasiswa)->nim ?? ($this->attributes['mahasiswa_id'] ?? null);
    }

    public function setMahasiswaNimAttribute($nim)
    {
        // ✅ karena Mahasiswa PK = nim, cukup set mahasiswa_id = nim
        if (!empty($nim)) {
            $this->attributes['mahasiswa_id'] = $nim;
        }
    }

    // --- rubrik_id (virtual, opsional) ---
    public function getRubrikIdAttribute()
    {
        $arr = $this->komponen ?? [];
        foreach ($arr as $item) {
            if (isset($item['rubrik_id'])) return $item['rubrik_id'];
        }
        return null;
    }

    public function setRubrikIdAttribute($val)
    {
        $arr = $this->komponen ?? [];
        if (empty($arr)) {
            $arr = [['rubrik_id' => $val]];
        } else {
            $arr[0]['rubrik_id'] = $val;
        }

        // karena casts komponen = array, boleh set array langsung
        $this->attributes['komponen'] = json_encode($arr);
    }

    // --- nilai (virtual) -> map ke nilai_akhir ---
    public function getNilaiAttribute()
    {
        return $this->nilai_akhir;
    }

    public function setNilaiAttribute($val)
    {
        $this->attributes['nilai_akhir'] = $val;
    }
}
