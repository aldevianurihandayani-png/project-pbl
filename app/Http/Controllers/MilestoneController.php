<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    public function index()
    {
        // Dummy data milestone (belum dari database)
        $milestones = [
            ['minggu' => 1, 'kegiatan' => 'Pembentukan Kelompok', 'deadline' => '1 Sept 2025', 'status' => 'Selesai'],
            ['minggu' => 4, 'kegiatan' => 'Proposal', 'deadline' => '25 Sept 2025', 'status' => 'Pending'],
            ['minggu' => 10, 'kegiatan' => 'Presentasi Final', 'deadline' => '15 Nov 2025', 'status' => 'Belum'],
        ];

        return view('milestone.index', compact('milestones'));
    }
}
