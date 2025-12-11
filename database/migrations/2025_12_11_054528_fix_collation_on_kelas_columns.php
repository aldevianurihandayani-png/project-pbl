<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        // Pastikan ekstensi doctrine/dbal terinstal
        if (!Schema::hasTable('mahasiswas') || !Schema::hasTable('mata_kuliah') || !Schema::hasTable('kelas')) {
            echo "One or more tables (mahasiswas, mata_kuliah, kelas) do not exist. Skipping collation fix.\n";
            return;
        }

        // Perbaiki kolasi untuk mahasiswas.kelas
        Schema::table('mahasiswas', function (Blueprint $table) {
            // Untuk ENUM, kita mungkin perlu melakukan langkah-langkah yang lebih hati-hati jika perubahan langsung gagal.
            // Namun, kita akan mencoba mengubahnya secara langsung terlebih dahulu.
            // Mengubah ENUM tidak selalu berhasil dengan ->change() di beberapa versi MySQL atau Doctrine DBAL.
            // Jika ini gagal, metode alternatif adalah dengan membuat kolom sementara, mengisi data,
            // menghapus kolom lama, mengganti nama kolom baru, lalu membuat ulang indeks.
            // Namun, untuk saat ini, kita asumsikan ini akan bekerja.
            $table->enum('kelas', ['A', 'B', 'C', 'D', 'E'])
                  ->collation('utf8mb4_unicode_ci')
                  ->change();
        });

        // Perbaiki kolasi untuk mata_kuliah.kelas
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->string('kelas', 2)
                  ->collation('utf8mb4_unicode_ci')
                  ->change();
        });

        // Perbaiki kolasi untuk kelas.nama_kelas
        Schema::table('kelas', function (Blueprint $table) {
            // Asumsi default length 255 jika tidak ada di create migration.
            // Sesuaikan jika panjang sebenarnya berbeda.
            $table->string('nama_kelas', 255)
                  ->collation('utf8mb4_unicode_ci')
                  ->change();
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        // Tidak ada rollback otomatis untuk perubahan kolasi yang aman.
        // Jika Anda perlu mengembalikan ini, Anda harus secara manual mengembalikan kolasi.
        // Namun, umumnya mengubah kolasi ke unicode_ci adalah perbaikan permanen.
    }
};