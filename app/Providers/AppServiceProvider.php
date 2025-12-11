<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share data notifikasi ke semua view saat user login
        View::composer('*', function ($view) {
            if (Auth::check()) {

                $userId = Auth::id();

                // aman walaupun dipanggil tanpa parameter di model
                $unreadCount   = Notification::getUnreadCount($userId);
                $notifications = Notification::getListForTopbar($userId, 5);

                $view->with(compact('unreadCount', 'notifications'));
            }
        });
    }
}
