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
        'mahasiswa_id',
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
     |  RELASI (tetap seperti skema baru)
     * ============================================================ */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id'); // mahasiswas.id
    }

    public function matakuliah()
    {
        // Ganti MataKuliah::class jika model kamu bernama Matakuliah
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
     |  - mahasiswa_nim  -> mapping ke mahasiswa_id
     |  - rubrik_id      -> disimpan/diambil dari komponen JSON (opsional)
     |  - nilai          -> mapping ke nilai_akhir
     * ============================================================ */

    // --- mahasiswa_nim (virtual) ---
    public function getMahasiswaNimAttribute()
    {
        // jika relasi ter-load, ambil nim; aman jika null
        return optional($this->mahasiswa)->nim;
    }

    public function setMahasiswaNimAttribute($nim)
    {
        // cari Mahasiswa by nim lalu set mahasiswa_id (tanpa menyimpan kolom baru)
        if (! empty($nim)) {
            $mhs = Mahasiswa::where('nim', $nim)->first();
            if ($mhs) {
                $this->attributes['mahasiswa_id'] = $mhs->id;
            }
        }
    }

    // --- rubrik_id (virtual, opsional) ---
    public function getRubrikIdAttribute()
    {
        // kalau di komponen kamu menaruh rubrik_id, ambil dari sana
        // contoh asumsi: komponen = [{ "rubrik_id": 5, "nama": "...", "bobot": 30, "skor": 80}, ...]
        $arr = $this->komponen ?? [];
        foreach ($arr as $item) {
            if (isset($item['rubrik_id'])) {
                return $item['rubrik_id'];
            }
        }
        return null;
    }

    public function setRubrikIdAttribute($val)
    {
        // sisipkan/replace rubrik_id ke elemen pertama komponen (tanpa menghapus data lain)
        $arr = $this->komponen ?? [];
        if (empty($arr)) {
            $arr = [['rubrik_id' => $val]];
        } else {
            $arr[0]['rubrik_id'] = $val;
        }
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
