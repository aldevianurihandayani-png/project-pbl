<?php

namespace Database\Seeders;


use App\Models\Notification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class NotificationSeeder extends Seeder

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Notification::factory()->count(30)->create();
    }
        $user = User::first(); // Get the first user, or null if none exists

        // General Notifications (user_id = null)
        Notification::create([
            'type' => 'info',
            'title' => 'Selamat datang di sistem baru!',
            'course' => null,
            'link_url' => '#',
            'is_read' => false,
            'created_at' => Carbon::now()->subDays(2),
        ]);

        Notification::create([
            'type' => 'info',
            'title' => 'Sistem akan maintenance malam ini',
            'course' => null,
            'link_url' => '#',
            'is_read' => true,
            'created_at' => Carbon::now()->subDays(5),
        ]);

        // Materi Notifications
        Notification::create([
            'user_id' => $user ? $user->id : null,
            'type' => 'materi',
            'title' => 'Materi baru: Pengantar Laravel',
            'course' => 'Pemrograman Web Lanjut',
            'link_url' => '#',
            'is_read' => false,
            'created_at' => Carbon::now()->subHours(3),
        ]);

        Notification::create([
            'user_id' => $user ? $user->id : null,
            'type' => 'materi',
            'title' => 'Update materi: Database Relasional',
            'course' => 'Sistem Basis Data',
            'link_url' => '#',
            'is_read' => true,
            'created_at' => Carbon::now()->subDays(1),
        ]);

        // Tugas Notifications
        Notification::create([
            'user_id' => $user ? $user->id : null,
            'type' => 'tugas',
            'title' => 'Tugas baru: Proyek Akhir PBL',
            'course' => 'Proyek Berbasis Luaran',
            'link_url' => '#',
            'is_read' => false,
            'created_at' => Carbon::now()->subMinutes(30),
        ]);

        Notification::create([
            'user_id' => $user ? $user->id : null,
            'type' => 'tugas',
            'title' => 'Deadline tugas: Laporan Mingguan',
            'course' => 'Metodologi Penelitian',
            'link_url' => '#',
            'is_read' => false,
            'created_at' => Carbon::now()->subHours(12),
        ]);

        // More diverse notifications
        Notification::create([
            'user_id' => $user ? $user->id : null,
            'type' => 'info',
            'title' => 'Jadwal UAS telah dirilis',
            'course' => null,
            'link_url' => '#',
            'is_read' => false,
            'created_at' => Carbon::now()->subDays(7),
        ]);

        Notification::create([
            'user_id' => $user ? $user->id : null,
            'type' => 'materi',
            'title' => 'Video tutorial baru: ReactJS Dasar',
            'course' => 'Pengembangan Aplikasi Web',
            'link_url' => '#',
            'is_read' => true,
            'created_at' => Carbon::now()->subDays(10),
        ]);

        Notification::create([
            'user_id' => $user ? $user->id : null,
            'type' => 'tugas',
            'title' => 'Revisi tugas: Desain UI/UX',
            'course' => 'Desain Antarmuka Pengguna',
            'link_url' => '#',
            'is_read' => false,
            'created_at' => Carbon::now()->subHours(1),
        ]);

        Notification::create([
            'type' => 'info',
            'title' => 'Pengumuman libur nasional',
            'course' => null,
            'link_url' => '#',
            'is_read' => false,
            'created_at' => Carbon::now()->subDays(14),
        ]);

        Notification::create([
            'user_id' => $user ? $user->id : null,
            'type' => 'materi',
            'title' => 'Modul baru: Keamanan Jaringan',
            'course' => 'Jaringan Komputer',
            'link_url' => '#',
            'is_read' => false,
            'created_at' => Carbon::now()->subDays(3),
        ]);

        Notification::create([
            'user_id' => $user ? $user->id : null,
            'type' => 'tugas',
            'title' => 'Kuis 1: Algoritma dan Struktur Data',
            'course' => 'Algoritma dan Struktur Data',
            'link_url' => '#',
            'is_read' => true,
            'created_at' => Carbon::now()->subDays(6),
        ]);
    }
}
