<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Kelas;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ================== KOTAK ATAS ==================
        $jumlahAkun       = User::count();
        $jumlahMahasiswa  = Mahasiswa::count();
        $jumlahMataKuliah = MataKuliah::count();
        $jumlahKelas      = Kelas::count();

        // ========= RINGKASAN UPDATE TERAKHIR (REAL DATA) =========
        $lastUpdateMahasiswa  = Mahasiswa::max('updated_at');
        $lastUpdateMataKuliah = MataKuliah::max('updated_at');
        $lastUpdateKelas      = Kelas::max('updated_at');

        $lastUpdatedAt = collect([
            $lastUpdateMahasiswa,
            $lastUpdateMataKuliah,
            $lastUpdateKelas,
        ])->filter()->max(); // bisa null kalau semua kosong

        // ========= RINGKASAN PER KELAS (TANPA JOIN, ANTI ERROR COLLATION) =========
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        // statistik mahasiswa per kelas
        $mhsStats = Mahasiswa::select('kelas', DB::raw('COUNT(*) as total_mhs'))
            ->groupBy('kelas')
            ->get()
            ->keyBy('kelas');

        // statistik mata kuliah per kelas + rentang semester
        $mkStats = MataKuliah::select(
                'kelas',
                DB::raw('COUNT(*) as total_mk'),
                DB::raw('MIN(semester) as min_semester'),
                DB::raw('MAX(semester) as max_semester')
            )
            ->groupBy('kelas')
            ->get()
            ->keyBy('kelas');

        $ringkasanKelas = $kelasList->map(function ($k) use ($mhsStats, $mkStats) {
            $nama = $k->nama_kelas;

            $mhs = $mhsStats[$nama]->total_mhs ?? 0;
            $mk  = $mkStats[$nama]->total_mk ?? 0;

            $minSem = $mkStats[$nama]->min_semester ?? null;
            $maxSem = $mkStats[$nama]->max_semester ?? null;

            return (object) [
                'nama_kelas'   => $nama,
                'total_mhs'    => $mhs,
                'total_mk'     => $mk,
                'min_semester' => $minSem,
                'max_semester' => $maxSem,
            ];
        });

        // kelas dengan mahasiswa terbanyak & MK terbanyak (kalau ada)
        $kelasTerbanyakMhs = $ringkasanKelas->sortByDesc('total_mhs')->first();
        $kelasTerbanyakMk  = $ringkasanKelas->sortByDesc('total_mk')->first();

        // ========= INFO PERIODE / SEMESTER AKTIF =========
        $periodeAktif  = Kelas::max('periode');
        $semesterAktif = Kelas::max('semester');

        // ========= NOTIFIKASI =========
        $unreadCount   = Notification::getUnreadCount();
        $notifications = Notification::getListForTopbar();

        return view('admins.dashboard', [
            'jumlahAkun'        => $jumlahAkun,
            'jumlahMahasiswa'   => $jumlahMahasiswa,
            'jumlahMataKuliah'  => $jumlahMataKuliah,
            'jumlahKelas'       => $jumlahKelas,

            'lastUpdatedAt'     => $lastUpdatedAt,

            'ringkasanKelas'    => $ringkasanKelas,
            'kelasTerbanyakMhs' => $kelasTerbanyakMhs,
            'kelasTerbanyakMk'  => $kelasTerbanyakMk,

            'periodeAktif'      => $periodeAktif,
            'semesterAktif'     => $semesterAktif,

            'unreadCount'       => $unreadCount,
            'notifications'     => $notifications,
        ]);
    }
}
