<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use App\Models\Cpmk;

class KoordinatorCpmkController extends Controller
{
    public function index()
    {
        $cpmk = Cpmk::latest()->paginate(10);
        return view('koordinator.cpmk.index', compact('cpmk'));
    }

    public function show(Cpmk $cpmk)
    {
        return view('koordinator.cpmk.show', compact('cpmk'));
    }
}
