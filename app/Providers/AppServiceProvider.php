<?php

namespace App\Providers;

use App\Channel;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale('pt_BR');
        \View::composer('*', function ($view) {
            $channels = Cache::rememberForever('channels', function() {
                 return Channel::all();
            });
            $view->with('channels', $channels);
        });

        Route::model('user', User::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            $this->app->alias(\Barryvdh\Debugbar\Facade::class, 'Debugbar');
        }

    }
}
