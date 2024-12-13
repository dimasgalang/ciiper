<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        date_default_timezone_set('Asia/Jakarta');
        $logs = DB::connection('sqlsrv')->table('LOG_CIIPER')->select('*')->orderBy('TIME', 'DESC')->paginate(5);
        View::share('logs', $logs);
    }
}
