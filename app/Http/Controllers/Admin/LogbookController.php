<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use Illuminate\Http\Request;

class LogbookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $logbooks = Logbook::with(['user:id,name'])->latest()->paginate(15);

        return view('admins.logbook.index', [
            'logbooks' => $logbooks,
        ]);
    }
}
