<?php

namespace App\Providers;

use App\Models\LogCiiper;
use App\Models\User;
use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;
use Illuminate\Routing\Route;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
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

        view()->composer('*', function ($view) {
            if (Auth::check()) {
                $roleusers = User::select('users.name', 'users.email', 'users.id', 'model_has_roles.*', 'roles.name as rolename')
                    ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                    ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
                    ->where('users.id', '=', Auth::user()->id)
                    ->get();

                date_default_timezone_set('Asia/Jakarta');
                $logs = LogCiiper::orderBy('created_at', 'desc')->get()->take(5);


                $dataReminder = array();

                $now = Carbon::now();
                $countAlert = 0;
                // $now = "2025-06-20";
                $formatedFrom = Carbon::parse($now);
                $fabdate = DB::select('select production_planning.*,order_list.pobuyer_no from production_planning left join order_list on production_planning.order_list = order_list.order_list where "' . $now . '" >= DATE_SUB(fab_date, INTERVAL 7 DAY) and "' . $now . '" <= fab_date');
                $accdate = DB::select('select production_planning.*,order_list.pobuyer_no from production_planning left join order_list on production_planning.order_list = order_list.order_list where "' . $now . '" >= DATE_SUB(acc_date, INTERVAL 7 DAY) and "' . $now . '" <= acc_date');
                $patterndate = DB::select('select production_planning.*,order_list.pobuyer_no from production_planning left join order_list on production_planning.order_list = order_list.order_list where "' . $now . '" >= DATE_SUB(pattern_date, INTERVAL 7 DAY) and "' . $now . '" <= pattern_date');
                $markerdate = DB::select('select production_planning.*,order_list.pobuyer_no from production_planning left join order_list on production_planning.order_list = order_list.order_list where "' . $now . '" >= DATE_SUB(marker_date, INTERVAL 7 DAY) and "' . $now . '" <= marker_date');
                $pilotrundate = DB::select('select production_planning.*,order_list.pobuyer_no from production_planning left join order_list on production_planning.order_list = order_list.order_list where "' . $now . '" >= DATE_SUB(pilotrun_date, INTERVAL 7 DAY) and "' . $now . '" <= pilotrun_date');
                $ppmdate = DB::select('select production_planning.*,order_list.pobuyer_no from production_planning left join order_list on production_planning.order_list = order_list.order_list where "' . $now . '" >= DATE_SUB(ppm_date, INTERVAL 7 DAY) and "' . $now . '" <= ppm_date');
                $startcutdate = DB::select('select production_planning.*,order_list.pobuyer_no from production_planning left join order_list on production_planning.order_list = order_list.order_list where "' . $now . '" >= DATE_SUB(startcut_date, INTERVAL 7 DAY) and "' . $now . '" <= startcut_date');
                $finishcutdate = DB::select('select production_planning.*,order_list.pobuyer_no from production_planning left join order_list on production_planning.order_list = order_list.order_list where "' . $now . '" >= DATE_SUB(finishcut_date, INTERVAL 7 DAY) and "' . $now . '" <= finishcut_date');
                $startsewdate = DB::select('select production_planning.*,order_list.pobuyer_no from production_planning left join order_list on production_planning.order_list = order_list.order_list where "' . $now . '" >= DATE_SUB(startsew_date, INTERVAL 7 DAY) and "' . $now . '" <= startsew_date');
                $finishsewdate = DB::select('select production_planning.*,order_list.pobuyer_no from production_planning left join order_list on production_planning.order_list = order_list.order_list where "' . $now . '" >= DATE_SUB(finishsew_date, INTERVAL 7 DAY) and "' . $now . '" <= finishsew_date');
                $finishpackdate = DB::select('select production_planning.*,order_list.pobuyer_no from production_planning left join order_list on production_planning.order_list = order_list.order_list where "' . $now . '" >= DATE_SUB(finishpack_date, INTERVAL 7 DAY) and "' . $now . '" <= finishpack_date');

                // dd($markerdate);
                // dd(count($patterndate));

                for ($i = 0; $i < count($fabdate); $i++) {
                    if ($fabdate) {
                        if (Carbon::parse($fabdate[$i]->fab_date)->diffInDays($formatedFrom) == 0) {
                            $messageFab = 'Reminder Fabrication Date (' . $fabdate[$i]->pobuyer_no . ') = ' . $fabdate[$i]->fab_date . ' (' . Carbon::parse($fabdate[$i]->fab_date)->diffInHours($formatedFrom) . ' Hours Left)';
                        } else {
                            $messageFab = 'Reminder Fabrication Date (' . $fabdate[$i]->pobuyer_no . ') = ' . $fabdate[$i]->fab_date . ' (' . Carbon::parse($fabdate[$i]->fab_date)->diffInDays($formatedFrom) . ' Days Left)';
                        }
                        $dataReminder[0][$countAlert] = $messageFab;
                        $countAlert++;
                    }
                }
                for ($i = 0; $i < count($accdate); $i++) {
                    if ($accdate) {
                        if (Carbon::parse($accdate[$i]->acc_date)->diffInDays($formatedFrom) == 0) {
                            $messageAcc = 'Reminder Accesories Date (' . $accdate[$i]->pobuyer_no . ') = ' . $accdate[$i]->acc_date . ' (' . Carbon::parse($accdate[$i]->acc_date)->diffInHours($formatedFrom) . ' Hours Left)';
                        } else {
                            $messageAcc = 'Reminder Accesories Date (' . $accdate[$i]->pobuyer_no . ') = ' . $accdate[$i]->acc_date . ' (' . Carbon::parse($accdate[$i]->acc_date)->diffInDays($formatedFrom) . ' Days Left)';
                        }
                        $dataReminder[0][$countAlert] = $messageAcc;
                        $countAlert++;
                    }
                }
                for ($i = 0; $i < count($patterndate); $i++) {
                    if ($patterndate) {
                        if (Carbon::parse($patterndate[$i]->pattern_date)->diffInDays($formatedFrom) == 0) {
                            $messagePattern = 'Reminder Pattern Date (' . $patterndate[$i]->pobuyer_no . ') = ' . $patterndate[$i]->pattern_date . ' (' . Carbon::parse($patterndate[$i]->pattern_date)->diffInHours($formatedFrom) . ' Hours Left)';
                        } else {
                            $messagePattern = 'Reminder Pattern Date (' . $patterndate[$i]->pobuyer_no . ') = ' . $patterndate[$i]->pattern_date . ' (' . Carbon::parse($patterndate[$i]->pattern_date)->diffInDays($formatedFrom) . ' Days Left)';
                        }
                        $dataReminder[0][$countAlert] = $messagePattern;
                        $countAlert++;
                    }
                }
                for ($i = 0; $i < count($markerdate); $i++) {
                    if ($markerdate) {
                        if (Carbon::parse($markerdate[$i]->marker_date)->diffInDays($formatedFrom) == 0) {
                            $messageMarker = 'Reminder Marker Date (' . $markerdate[$i]->pobuyer_no . ') = ' . $markerdate[$i]->marker_date . ' (' . Carbon::parse($markerdate[$i]->marker_date)->diffInHours($formatedFrom) . ' Hours Left)';
                        } else {
                            $messageMarker = 'Reminder Marker Date (' . $markerdate[$i]->pobuyer_no . ') = ' . $markerdate[$i]->marker_date . ' (' . Carbon::parse($markerdate[$i]->marker_date)->diffInDays($formatedFrom) . ' Days Left)';
                        }
                        $dataReminder[0][$countAlert] = $messageMarker;
                        $countAlert++;
                    }
                }
                for ($i = 0; $i < count($pilotrundate); $i++) {
                    if ($pilotrundate) {
                        if (Carbon::parse($pilotrundate[$i]->pilotrun_date)->diffInDays($formatedFrom) == 0) {
                            $messagePilotRun = 'Reminder Pilot Run Date (' . $pilotrundate[$i]->pobuyer_no . ') = ' . $pilotrundate[$i]->pilotrun_date . ' (' . Carbon::parse($pilotrundate[$i]->pilotrun_date)->diffInHours($formatedFrom) . ' Hours Left)';
                        } else {
                            $messagePilotRun = 'Reminder Pilot Run Date (' . $pilotrundate[$i]->pobuyer_no . ') = ' . $pilotrundate[$i]->pilotrun_date . ' (' . Carbon::parse($pilotrundate[$i]->pilotrun_date)->diffInDays($formatedFrom) . ' Days Left)';
                        }
                        $dataReminder[0][$countAlert] = $messagePilotRun;
                        $countAlert++;
                    }
                }
                for ($i = 0; $i < count($ppmdate); $i++) {
                    if ($ppmdate) {
                        if (Carbon::parse($ppmdate[$i]->ppm_date)->diffInDays($formatedFrom) == 0) {
                            $messagePPM = 'Reminder PPM Date (' . $ppmdate[$i]->pobuyer_no . ') = ' . $ppmdate[$i]->ppm_date . ' (' . Carbon::parse($ppmdate[$i]->ppm_date)->diffInHours($formatedFrom) . ' Hours Left)';
                        } else {
                            $messagePPM = 'Reminder PPM Date (' . $ppmdate[$i]->pobuyer_no . ') = ' . $ppmdate[$i]->ppm_date . ' (' . Carbon::parse($ppmdate[$i]->ppm_date)->diffInDays($formatedFrom) . ' Days Left)';
                        }
                        $dataReminder[0][$countAlert] = $messagePPM;
                        $countAlert++;
                    }
                }
                for ($i = 0; $i < count($startcutdate); $i++) {
                    if ($startcutdate) {
                        if (Carbon::parse($startcutdate[$i]->startcut_date)->diffInDays($formatedFrom) == 0) {
                            $messageStartCut = 'Reminder Start Cutting Date (' . $startcutdate[$i]->pobuyer_no . ') = ' . $startcutdate[$i]->startcut_date . ' (' . Carbon::parse($startcutdate[$i]->startcut_date)->diffInHours($formatedFrom) . ' Hours Left)';
                        } else {
                            $messageStartCut = 'Reminder Start Cutting Date (' . $startcutdate[$i]->pobuyer_no . ') = ' . $startcutdate[$i]->startcut_date . ' (' . Carbon::parse($startcutdate[$i]->startcut_date)->diffInDays($formatedFrom) . ' Days Left)';
                        }
                        $dataReminder[0][$countAlert] = $messageStartCut;
                        $countAlert++;
                    }
                }
                for ($i = 0; $i < count($finishcutdate); $i++) {
                    if ($finishcutdate) {
                        if (Carbon::parse($finishcutdate[$i]->finishcut_date)->diffInDays($formatedFrom) == 0) {
                            $messageFinnishCut = 'Reminder Finish Cutting Date (' . $finishcutdate[$i]->pobuyer_no . ') = ' . $finishcutdate[$i]->finishcut_date . ' (' . Carbon::parse($finishcutdate[$i]->finishcut_date)->diffInHours($formatedFrom) . ' Hours Left)';
                        } else {
                            $messageFinnishCut = 'Reminder Finish Cutting Date (' . $finishcutdate[$i]->pobuyer_no . ') = ' . $finishcutdate[$i]->finishcut_date . ' (' . Carbon::parse($finishcutdate[$i]->finishcut_date)->diffInDays($formatedFrom) . ' Days Left)';
                        }
                        $dataReminder[0][$countAlert] = $messageFinnishCut;
                        $countAlert++;
                    }
                }
                for ($i = 0; $i < count($startsewdate); $i++) {
                    if ($startsewdate) {
                        if (Carbon::parse($startsewdate[$i]->startsew_date)->diffInDays($formatedFrom) == 0) {
                            $messageStartSew = 'Reminder Start Sewing Date (' . $startsewdate[$i]->pobuyer_no . ') = ' . $startsewdate[$i]->startsew_date . ' (' . Carbon::parse($startsewdate[$i]->startsew_date)->diffInHours($formatedFrom) . ' Hours Left)';
                        } else {
                            $messageStartSew = 'Reminder Start Sewing Date (' . $startsewdate[$i]->pobuyer_no . ') = ' . $startsewdate[$i]->startsew_date . ' (' . Carbon::parse($startsewdate[$i]->startsew_date)->diffInDays($formatedFrom) . ' Days Left)';
                        }
                        $dataReminder[0][$countAlert] = $messageStartSew;
                        $countAlert++;
                    }
                }
                for ($i = 0; $i < count($finishsewdate); $i++) {
                    if ($finishsewdate) {
                        if (Carbon::parse($finishsewdate[$i]->finishsew_date)->diffInDays($formatedFrom) == 0) {
                            $messageFinishSew = 'Reminder Finish Sewing Date (' . $finishsewdate[$i]->pobuyer_no . ') = ' . $finishsewdate[$i]->finishsew_date . ' (' . Carbon::parse($finishsewdate[$i]->finishsew_date)->diffInHours($formatedFrom) . ' Hours Left)';
                        } else {
                            $messageFinishSew = 'Reminder Finish Sewing Date (' . $finishsewdate[$i]->pobuyer_no . ') = ' . $finishsewdate[$i]->finishsew_date . ' (' . Carbon::parse($finishsewdate[$i]->finishsew_date)->diffInDays($formatedFrom) . ' Days Left)';
                        }
                        $dataReminder[0][$countAlert] = $messageFinishSew;
                        $countAlert++;
                    }
                }
                for ($i = 0; $i < count($finishpackdate); $i++) {
                    if ($finishpackdate) {
                        if (Carbon::parse($finishpackdate[$i]->finishpack_date)->diffInDays($formatedFrom) == 0) {
                            $messageFinishSew = 'Reminder Finish Packing Date (' . $finishpackdate[$i]->pobuyer_no . ') = ' . $finishpackdate[$i]->finishpack_date . ' (' . Carbon::parse($finishpackdate[$i]->finishpack_date)->diffInHours($formatedFrom) . ' Hours Left)';
                        } else {
                            $messageFinishSew = 'Reminder Finish Packing Date (' . $finishpackdate[$i]->pobuyer_no . ') = ' . $finishpackdate[$i]->finishpack_date . ' (' . Carbon::parse($finishpackdate[$i]->finishpack_date)->diffInDays($formatedFrom) . ' Days Left)';
                        }
                        $dataReminder[0][$countAlert] = $messageFinishSew;
                        $countAlert++;
                    }
                }

                View::share(['roleusers' => $roleusers, 'logs' => $logs, 'datareminders' => $dataReminder]);
            }
        });

        // $client = new Client();
        // $apiUrl = "https://zenquotes.io/api/quotes/";
        // $response = $client->get($apiUrl);
        // $quote = json_decode($response->getBody(), true);



        // View::share(['logs' => $logs, 'quotes' => $quote]);
        // dd($dataReminder);
    }
}
