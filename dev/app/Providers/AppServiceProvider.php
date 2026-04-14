<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\NotificacionController;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        if (str_contains(request()->getHost(), 'ngrok')) {
            URL::forceScheme('https');
        }
    }
}