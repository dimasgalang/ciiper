<?php

namespace App\Providers;

use App\Models\LogCiiper;
use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;

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

        Scramble::routes(function (Route $route) {
            return Str::startsWith($route->uri, 'api/');
        });

        $client = new Client();
        $apiUrl = "https://zenquotes.io/api/quotes/";
        $response = $client->get($apiUrl);
        $quote = json_decode($response->getBody(), true);

        date_default_timezone_set('Asia/Jakarta');
        $logs = LogCiiper::all()->take(5);
        View::share(['logs' => $logs, 'quotes' => $quote]);
    }
}
