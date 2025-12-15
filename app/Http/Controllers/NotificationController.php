<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Tandai SEMUA notifikasi user sebagai dibaca
     */
    public function readAll()
    {
        if (!Auth::check()) {
            return back();
        }

        DB::table('notification_user')
            ->where('user_id', Auth::id())
            ->where('is_read', 0)
            ->update([
                'is_read'    => 1,
                'read_at'    => now(),
                'updated_at' => now(),
            ]);

        return back();
    }
}
