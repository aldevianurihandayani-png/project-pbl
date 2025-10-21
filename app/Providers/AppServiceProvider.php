<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use App\Models\Notification;
use App\Http\Controllers\Admin\NotificationController;
use Illuminate\Support\Facades\Auth;

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

        View::composer(['layouts.app', 'admins.dashboard', 'admins.feedback.index'], function ($view) {
            if (Auth::check()) {
                $unreadCount = Notification::getUnreadCount();
                $notifications = Notification::getListForTopbar();
                $view->with(compact('unreadCount', 'notifications'));
            }
        });

    }
}
