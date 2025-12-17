<?php

namespace App\Http\Controllers\JaminanMutu;

use App\Http\Controllers\Controller;
use App\Models\Rubrik;
use Illuminate\Http\Request;

class JaminanMutuRubrikController extends Controller
{
    public function index(Request $request)
    {
        $rubrik = Rubrik::query()
            ->latest()
            ->paginate(10);

        return view('jaminanmutu.rubrik.index', compact('rubrik'));
    }

    public function show(Rubrik $rubrik)
    {
        return view('jaminanmutu.rubrik.show', compact('rubrik'));
    }
}
