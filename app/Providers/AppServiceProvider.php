<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;

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
        $client = new Client();
        $apiUrl = "https://zenquotes.io/api/quotes/";
        $response = $client->get($apiUrl);
        $quote = json_decode($response->getBody(), true);

        date_default_timezone_set('Asia/Jakarta');
        $logs = DB::connection('sqlsrv')->table('LOG_CIIPER')->select('*')->orderBy('TIME', 'DESC')->paginate(5);
        View::share(['logs' => $logs, 'quotes' => $quote]);
    }
}
