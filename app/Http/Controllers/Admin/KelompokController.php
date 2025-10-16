<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelompoks = Kelompok::latest()->paginate(15);

        return view('admins.kelompok.index', [
            'kelompoks' => $kelompoks,
        ]);
    }

    public function show(Kelompok $kelompok)
    {
        $kelompok->load('anggotas');
        return view('admins.kelompok.show', compact('kelompok'));
    }
}
