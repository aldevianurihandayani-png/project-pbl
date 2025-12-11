<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
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
