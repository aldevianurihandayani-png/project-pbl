<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User; // Import model User
use App\Models\Matakuliah; // Import model Matakuliah
use App\Models\Mahasiswa; // Import model Mahasiswa

class AdminDashboardController extends Controller
{
    public function index()
    {
        // data dummy
        $jumlahKelompok = 4;
        $jumlahUsers    = User::count(); // Hitung jumlah pengguna
        $jumlahMataKuliah = Matakuliah::count(); // Hitung jumlah mata kuliah
        $jumlahMahasiswa = Mahasiswa::count(); // Hitung jumlah mahasiswa

        $unreadCount = Notification::getUnreadCount();
        $notifications = Notification::getListForTopbar();

        return view('admins.dashboard', compact(
            'jumlahKelompok', 'jumlahUsers', 'jumlahMataKuliah', 'jumlahMahasiswa', 'unreadCount', 'notifications'
        ));
    }
}