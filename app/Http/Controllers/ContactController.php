<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;   

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'message' => 'required|string|max:2000',
        ]);

        // Simpan / kirim email sesuai kebutuhan
        Log::info('CONTACT FORM', $data);

        return back()->with('success', 'Pesan berhasil dikirim.');
    }
}
