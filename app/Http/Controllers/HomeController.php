<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\LogCiiper;
use App\Models\OrderMaster;
use App\Models\ProductionPlanning;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function landing() {
        return view('menu.landing');
    }

    public function index()
    {
        $latestlogs = LogCiiper::orderBy('id', 'DESC')->get();
        $users = User::orderBy('id', 'DESC')->take(4)->get();

        $ordermasters = OrderMaster::select(DB::raw('COUNT(*) AS JUMLAH'))->get();
        $buyers = Buyer::select(DB::raw('COUNT(*) AS JUMLAH'))->get();

        $now = Carbon::now();
        $fabdate = DB::select('select *,DATE_SUB(fab_date, INTERVAL 7 DAY) as tanggal from production_planning where "2025-06-19" >= DATE_SUB(fab_date, INTERVAL 7 DAY) and "2025-06-19" <= fab_date');
        // dd($fabdate);

        // $data = DB::connection('sqlsrv')->table('REKAP')->select('*')
        // ->where('TAHUN', '>=', date('Y')-1)
        // ->orderBy('TAHUN', 'ASC')
        // ->orderBy('BULAN', 'ASC')->get()->toArray();
        // $pkwtChart = [ 0 => 'Others' ];
        // for($i = 0; $i < count($data); $i++) {
        //     $pkwtChart[$i] = $data[$i]->PKWT;
        // }

        // $magangChart = [ 0 => 'Others' ];
        // for($i = 0; $i < count($data); $i++) {
        //     $magangChart[$i] = $data[$i]->MAGANG;
        // }
        
        // $labels = DB::connection('sqlsrv')->table('REKAP')->select('*')
        // ->where('TAHUN', '>=', date('Y'))
        // ->orderBy('TAHUN', 'ASC')
        // ->orderBy('BULAN', 'ASC')->get()->toArray();

        // $labelChart = [ 0 => 'Others' ];
        // for($i = 0; $i < count($labels); $i++) {
        //     $monthName = DateTime::createFromFormat('!m', $data[$i]->BULAN)->format('F');
        //     $labelChart[$i] = $monthName . ' ' . $data[$i]->TAHUN;
        // }

        // $chart = Chartjs::build()
        //     ->name("UserRegistrationsChart")
        //     ->type("line")
        //     ->size(["width" => 400, "height" => 200])
        //     ->labels($labelChart)
        //     ->datasets([
        //         [
        //             "label" => "PKWT",
        //             "backgroundColor" => "rgba(124, 201, 252, 0.1)",
        //             "borderColor" => "rgba(54, 129, 179, 0.7)",
        //             "data" => $pkwtChart
        //         ],
        //         [
        //             "label" => "Intern",
        //             "backgroundColor" => "rgba(112, 250, 161, 0.1)",
        //             "borderColor" => "rgba(54, 179, 97, 0.7)",
        //             "data" => $magangChart
        //         ]
        //     ])
        //     ->options([
        //         'scales' => [
        //             'x' => [
        //                 'type' => 'time',
        //                 'time' => [
        //                     'unit' => 'month'
        //                 ],
        //             ]
        //         ],
        //         'plugins' => [
        //             'title' => [
        //                 'display' => true,
        //                 'text' => 'Monthly User Registrations'
        //             ]
        //         ]
        //     ]);

        // $employee = DB::connection('sqlsrv')->table('BIODATA')->select(DB::raw('COUNT(*) AS JUMLAH'))
        // ->leftJoin('DEPT', 'BIODATA.ID_DEPT', '=', 'DEPT.ID_DEPT')
        // ->where('DEPARTEMENT', 'NOT LIKE', '%MAGANG%')->get();

        // $intern = DB::connection('sqlsrv')->table('BIODATA')->select(DB::raw('COUNT(*) AS JUMLAH'))
        // ->leftJoin('DEPT', 'BIODATA.ID_DEPT', '=', 'DEPT.ID_DEPT')
        // ->where('DEPARTEMENT', 'LIKE', '%MAGANG%')->get();
        
        // return view('menu.home', ['employee' => $employee, 'intern' => $intern, 'users' => $users, 'quotes' => $quote], compact('chart'));
        // return redirect()->intended('/home')->with(compact('users','latestlogs','ordermasters','buyers'))->with(['error' => 'Data Gagal Diimport!']);
        return view('menu.homeprod', compact('users','latestlogs','ordermasters','buyers'));
    }

    public function listuser() {
        $users   = User::select('users.*','roles.name as role')->leftJoin('model_has_roles','users.id','=','model_has_roles.model_id')->leftJoin('roles','roles.id','=','model_has_roles.role_id')->get();
        return view('auth.list-user', compact('users'));
    }
}
