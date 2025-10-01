<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // data dummy
        $jumlahKelompok = 4;
        $jumlahLogbook  = 5;
        $jumlahMhs      = 100;

        return view('admin.dashboard.index', compact(
            'jumlahKelompok','jumlahLogbook','jumlahMhs'
        ));
    }
}
