<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification as NotificationModel;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (!Auth::check()) {
                return;
            }

            try {
                $unreadCount   = NotificationModel::getUnreadCount();
                $notifications = NotificationModel::getListForTopbar(5);
            } catch (\Throwable $e) {
                // Biar nggak nge-crash semua halaman kalau ada masalah notif
                $unreadCount   = 0;
                $notifications = collect();
            }

            $view->with(compact('unreadCount', 'notifications'));
        });
    }
}
