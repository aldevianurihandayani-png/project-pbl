<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // data dummy
        $jumlahKelompok = 4;
        $jumlahLogbook  = 5;
        $jumlahMhs      = 100;

        $unreadCount = Notification::getUnreadCount();
        $notifications = Notification::getListForTopbar();

        return view('admins.dashboard', compact(
            'jumlahKelompok', 'jumlahLogbook', 'jumlahMhs', 'unreadCount', 'notifications'
        ));
    }
}