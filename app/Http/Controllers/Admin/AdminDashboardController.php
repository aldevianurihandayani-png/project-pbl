<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{// app/Http/Controllers/Admin/AdminDashboardController.php
public function index()
{
    // data dummy
    $jumlahKelompok = 4;
    $jumlahLogbook  = 5;
    $jumlahMhs      = 100;

    return view('admins.dashboard', compact(
        'jumlahKelompok', 'jumlahLogbook', 'jumlahMhs'
    ));
}

}
