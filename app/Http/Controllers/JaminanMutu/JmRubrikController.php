<?php

namespace App\Http\Controllers\JaminanMutu;

use App\Http\Controllers\Controller;
use App\Models\Rubrik;
use Illuminate\Http\Request;

class JmRubrikController extends Controller
{
    // READ ONLY: list rubrik
    public function index(Request $request)
    {
        // kalau tabel rubrik kamu pakai kolom lain, tinggal sesuaikan
        $rubrik = Rubrik::latest()->paginate(10);

        return view('jaminanmutu.rubrik.index', compact('rubrik'));
    }

    // READ ONLY: detail rubrik
    public function show(Rubrik $rubrik)
    {
        return view('jaminanmutu.rubrik.show', compact('rubrik'));
    }
}
