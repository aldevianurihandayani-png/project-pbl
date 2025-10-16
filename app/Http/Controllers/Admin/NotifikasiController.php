<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifikasis = Notification::with('user')->latest()->paginate(10);

        return view('admins.notifikasi.index', [
            'notifikasis' => $notifikasis,
        ]);
    }
}
