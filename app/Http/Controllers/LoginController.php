<?php

namespace App\Http\Controllers;

use App\Models\LogCiiper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class LoginController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $username = Auth::user()->name;
            $storeTime = Carbon::now();
            // DB::connection('sqlsrv')->table('LOG_CIIPER')->insert([
            //     ['username' => $username, 'activity' => 'Signed In', 'time' => $storeTime->toDateTimeString(), 'icon' => 'link', 'color' => 'bg-success'],
            // ]);
            LogCiiper::create([
                'username' => $username,
                'activity' => 'Signed In',
                'time' => $storeTime->toDateTimeString(),
                'icon' => 'link',
                'color' => 'bg-success',
            ]);

        //     $dataReminder = array();
            
        //     $now = Carbon::now();
        //     // $now = "2025-06-19";
        //     $formatedFrom = Carbon::parse($now);
        //     $fabdate = DB::select('select production_planning.*,order_list.pobuyer_no from production_planning left join order_list on production_planning.order_list = order_list.order_list where "' . $now . '" >= DATE_SUB(fab_date, INTERVAL 7 DAY) and "' . $now . '" <= fab_date');
        //     $accdate = DB::select('select production_planning.*,order_list.pobuyer_no from production_planning left join order_list on production_planning.order_list = order_list.order_list where "' . $now . '" >= DATE_SUB(acc_date, INTERVAL 7 DAY) and "' . $now . '" <= acc_date');
        //     $patterndate = DB::select('select production_planning.*,order_list.pobuyer_no from production_planning left join order_list on production_planning.order_list = order_list.order_list where "' . $now . '" >= DATE_SUB(pattern_date, INTERVAL 7 DAY) and "' . $now . '" <= pattern_date');
        //     $markerdate = DB::select('select production_planning.*,order_list.pobuyer_no from production_planning left join order_list on production_planning.order_list = order_list.order_list where "' . $now . '" >= DATE_SUB(marker_date, INTERVAL 7 DAY) and "' . $now . '" <= marker_date');
        //     $pilotrundate = DB::select('select production_planning.*,order_list.pobuyer_no from production_planning left join order_list on production_planning.order_list = order_list.order_list where "' . $now . '" >= DATE_SUB(pilotrun_date, INTERVAL 7 DAY) and "' . $now . '" <= pilotrun_date');
        //     $ppmdate = DB::select('select production_planning.*,order_list.pobuyer_no from production_planning left join order_list on production_planning.order_list = order_list.order_list where "' . $now . '" >= DATE_SUB(ppm_date, INTERVAL 7 DAY) and "' . $now . '" <= ppm_date');
        //     $startcutdate = DB::select('select production_planning.*,order_list.pobuyer_no from production_planning left join order_list on production_planning.order_list = order_list.order_list where "' . $now . '" >= DATE_SUB(startcut_date, INTERVAL 7 DAY) and "' . $now . '" <= startcut_date');
        //     $finishcutdate = DB::select('select production_planning.*,order_list.pobuyer_no from production_planning left join order_list on production_planning.order_list = order_list.order_list where "' . $now . '" >= DATE_SUB(finishcut_date, INTERVAL 7 DAY) and "' . $now . '" <= finishcut_date');
        //     $startsewdate = DB::select('select production_planning.*,order_list.pobuyer_no from production_planning left join order_list on production_planning.order_list = order_list.order_list where "' . $now . '" >= DATE_SUB(startsew_date, INTERVAL 7 DAY) and "' . $now . '" <= startsew_date');
        //     $finishsewdate = DB::select('select production_planning.*,order_list.pobuyer_no from production_planning left join order_list on production_planning.order_list = order_list.order_list where "' . $now . '" >= DATE_SUB(finishsew_date, INTERVAL 7 DAY) and "' . $now . '" <= finishsew_date');
        //     $finishpackdate = DB::select('select production_planning.*,order_list.pobuyer_no from production_planning left join order_list on production_planning.order_list = order_list.order_list where "' . $now . '" >= DATE_SUB(finishpack_date, INTERVAL 7 DAY) and "' . $now . '" <= finishpack_date');
    
        //     Alert::success('Login Successfully!', 'Welcome To Chutex Sistem');
        //     if ($fabdate) {
        //         $messageFab = 'Reminder Fabrication Date (' . $fabdate[0]->pobuyer_no . ') = ' . $fabdate[0]->fab_date . ' (' . Carbon::parse($fabdate[0]->fab_date)->diffInDays($formatedFrom) . ' Days Left)';
        //         $dataReminder["Fabrication Date"] = $messageFab;
        //         flash($messageFab)->error()->important();
        //     }
        //     if ($accdate) {
        //         $messageAcc = 'Reminder Accesories Date (' . $accdate[0]->pobuyer_no . ') = ' . $accdate[0]->acc_date . ' (' . Carbon::parse($accdate[0]->acc_date)->diffInDays($formatedFrom) . ' Days Left)';
        //         $dataReminder["Accessories Date"] = $messageAcc;
        //         flash($messageAcc)->error()->important();
        //     }
        //     if ($patterndate) {
        //         $messagePattern = 'Reminder Pattern Date (' . $patterndate[0]->pobuyer_no . ') = ' . $patterndate[0]->pattern_date . ' (' . Carbon::parse($patterndate[0]->pattern_date)->diffInDays($formatedFrom) . ' Days Left)';
        //         $dataReminder["Pattern Date"] = $messagePattern;
        //         flash($messagePattern)->error()->important();
        //     }
        //     if ($markerdate) {
        //         $messageMarker = 'Reminder Accesories Date (' . $markerdate[0]->pobuyer_no . ') = ' . $markerdate[0]->marker_date . ' (' . Carbon::parse($markerdate[0]->marker_date)->diffInDays($formatedFrom) . ' Days Left)';
        //         $dataReminder["Marker Date"] = $messageMarker;
        //         flash($messageMarker)->error()->important();
        //     }
        //     if ($pilotrundate) {
        //         $messagePilotRun = 'Reminder Accesories Date (' . $pilotrundate[0]->pobuyer_no . ') = ' . $pilotrundate[0]->pilotrun_date . ' (' . Carbon::parse($pilotrundate[0]->pilotrun_date)->diffInDays($formatedFrom) . ' Days Left)';
        //         $dataReminder["Pilot Run Date"] = $messagePilotRun;
        //         flash($messagePilotRun)->error()->important();
        //     }
        //     if ($ppmdate) {
        //         $messagePPM = 'Reminder PPM Date (' . $ppmdate[0]->pobuyer_no . ') = ' . $ppmdate[0]->ppm_date . ' (' . Carbon::parse($ppmdate[0]->ppm_date)->diffInDays($formatedFrom) . ' Days Left)';
        //         $dataReminder["PPM Date"] = $messagePPM;
        //         flash($messagePPM)->error()->important();
        //     }
        //     if ($startcutdate) {
        //         $messageStartCut = 'Reminder Start Cutting Date (' . $startcutdate[0]->pobuyer_no . ') = ' . $startcutdate[0]->startcut_date . ' (' . Carbon::parse($startcutdate[0]->startcut_date)->diffInDays($formatedFrom) . ' Days Left)';
        //         $dataReminder["Start Cutting Date"] = $messageStartCut;
        //         flash($messageStartCut)->error()->important();
        //     }
        //     if ($finishcutdate) {
        //         $messageFinnishCut = 'Reminder Finish Cutting Date (' . $finishcutdate[0]->pobuyer_no . ') = ' . $finishcutdate[0]->finishcut_date . ' (' . Carbon::parse($finishcutdate[0]->finishcut_date)->diffInDays($formatedFrom) . ' Days Left)';
        //         $dataReminder["Finish Cutting Date"] = $messageFinnishCut;
        //         flash($messageFinnishCut)->error()->important();
        //     }
        //     if ($startsewdate) {
        //         $messageStartSew = 'Reminder Start Sewing Date (' . $startsewdate[0]->pobuyer_no . ') = ' . $startsewdate[0]->startsew_date . ' (' . Carbon::parse($startsewdate[0]->startsew_date)->diffInDays($formatedFrom) . ' Days Left)';
        //         $dataReminder["Start Sewing Date"] = $messageStartSew;
        //         flash($messageStartSew)->error()->important();
        //     }
        //     if ($finishsewdate) {
        //         $messageFinishSew = 'Reminder Finish Sewing Date (' . $finishsewdate[0]->pobuyer_no . ') = ' . $finishsewdate[0]->finishsew_date . ' (' . Carbon::parse($finishsewdate[0]->finishsew_date)->diffInDays($formatedFrom) . ' Days Left)';
        //         $dataReminder["Finish Sewing Date"] = $messageFinishSew;
        //         flash($messageFinishSew)->error()->important();
        //     }
        //     if ($finishpackdate) {
        //         $messageFinishPack = 'Reminder Finish Packing Date (' . $finishpackdate[0]->pobuyer_no . ') = ' . $finishpackdate[0]->finishpack_date . ' (' . Carbon::parse($finishpackdate[0]->finishpack_date)->diffInDays($formatedFrom) . ' Days Left)';
        //         $dataReminder["Finish Packing Date"] = $messageFinishPack;
        //         flash($messageFinishPack)->error()->important();
        //     }
            return redirect()->intended('/home');
        };

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout()
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        Auth::logout();
        // DB::connection('sqlsrv')->table('LOG_CIIPER')->insert([
        //     ['username' => $username, 'activity' => 'Signed Out', 'time' => $storeTime->toDateTimeString(), 'icon' => 'unlink', 'color' => 'bg-danger'],
        // ]);
        LogCiiper::create([
            'username' => $username,
            'activity' => 'Signed Out',
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'unlink',
            'color' => 'bg-danger',
        ]);
        Alert::success('Logout Successfully!', 'See You Next Time');
        return redirect('/login');
    }
}
