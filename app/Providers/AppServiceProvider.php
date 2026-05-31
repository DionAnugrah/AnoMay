<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Session;
use App\Services\CustomDatabaseSessionHandler;
use Illuminate\Contracts\Foundation\Application;

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
        // Daftarkan custom driver untuk session database
        Session::extend('custom_database', function (Application $app) {
            $connection = $app['db']->connection(config('session.connection'));
            $table = config('session.table');
            $lifetime = config('session.lifetime');

            return new CustomDatabaseSessionHandler($connection, $table, $lifetime, $app);
        });
    }
}