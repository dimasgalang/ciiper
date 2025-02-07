<?php

namespace App\Http\Controllers;

use App\Models\LogCiiper;
use App\Models\OrderList;
use App\Models\OrderMaster;
use App\Models\ProductionDept;
use App\Models\ProductionPlanning;
use App\Models\RafProduction;
use App\Models\SetupIncrement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class RafProductionController extends Controller
{
    public function index() {
        $rafproductions   = RafProduction::select('raf_production.*','production_dept.dept_name','order_list.pobuyer_no')
        ->leftJoin('production_dept', 'raf_production.raf_dept', '=', 'production_dept.dept_no')
        ->leftJoin('order_list', 'raf_production.order_list', '=', 'order_list.order_list')
        ->get();
        return view('rafproduction.index', compact('rafproductions'));
    }

    public function create() {
        $ordermasters = OrderMaster::select('order_master.*', 'purchase_order.po_master')
        ->leftJoin('purchase_order','order_master.po_no','=','purchase_order.po_no')
        ->get();
        $productiondepts = ProductionDept::all();
        $setupincements = SetupIncrement::all()->where('models','=','RafProduction')->last();
        return view('rafproduction.create', compact('setupincements','ordermasters','productiondepts'));
    }

    public function find($id) {
        $rafproductions = RafProduction::find($id);
        $orderlists = OrderMaster::select('order_master.po_no','purchase_order.po_master','raf_production.*','order_list.pobuyer_no')
        ->leftJoin('purchase_order','order_master.po_no','=','purchase_order.po_no')
        ->leftJoin('raf_production','order_master.order_trans','=','raf_production.order_trans')
        ->leftJoin('order_list','order_list.order_list','=','raf_production.order_list')
        ->where('raf_production.id','=',$id)
        ->get();
        $productiondepts = ProductionDept::all();
        return view('rafproduction.update', compact('rafproductions', 'orderlists','productiondepts'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated RAF Production ' . $request->raf_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);


        $rafproductions = RafProduction::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'order_trans' => 'required|max:255',
            'order_list' => 'required|max:225|',
            'raf_no' => 'required|max:225|',
            'raf_date' => 'required|max:225|',
            'raf_qty' => 'required|max:225|',
            'raf_dept' => 'required|max:225|',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $rafproductions->fill([
            'order_trans' => $request->order_trans,
            'order_list' => $request->order_list,
            'raf_no' => $request->raf_no,
            'raf_date' => $request->raf_date,
            'raf_qty' => $request->raf_qty,
            'raf_dept' => $request->raf_dept,
            'remark' => $request->remark,
        ]);

        $rafproductions->save();

        Alert::success('Update Successfully!', 'RAF Production ' . $request->raf_no . ' successfully updated!');
        return redirect('rafproduction/index');
    }

    public function fetchorderlist($order_trans) {
        $orderlists   = OrderList::select('order_list.*','production_planning.*')
        ->join('production_planning', 'order_list.order_list', '=', 'production_planning.order_list')
        ->where('order_list.order_trans', '=', $order_trans)
        ->get();
        return response()->json($orderlists);
    }

    public function fetchrafleft($order_list, $raf_dept) {
        $deptPicking = "";
        $deptFrom =  "";
        if ($raf_dept == 'DEP000000001') {
            $deptPicking = 'DEP000000001';
            $raf_productions = RafProduction::select('raf_production.order_list','order_list.dcpo_qty', DB::raw('ifnull(sum(raf_production.raf_qty),0) as sum_raf_qty'), DB::raw('ifnull((order_list.dcpo_qty - sum(raf_production.raf_qty)),0) as raf_left'))
            ->leftJoin('order_list', 'order_list.order_list', '=', 'raf_production.order_list')
            ->where('raf_production.order_list', '=', $order_list)
            ->where('raf_production.raf_dept', '=', $deptPicking)
            ->groupBy('raf_production.order_list','order_list.dcpo_qty')
            ->get();
            return response()->json($raf_productions);
        } else if ($raf_dept == 'DEP000000002') {
            $deptPicking = 'DEP000000002';
            $deptFrom = 'DEP000000001';
            $raf_productions = RafProduction::select('raf_production.order_list','order_list.dcpo_qty', DB::raw('ifnull((select sum(raf_production.raf_qty) from raf_production where order_list = "' . $order_list . '" and raf_dept = "' . $deptFrom . '"),0) as raf_cutting'), DB::raw('ifnull((select sum(raf_production.raf_qty) from raf_production where order_list = "' . $order_list . '" and raf_dept = "' . $deptPicking . '"),0) as sum_raf_qty'), DB::raw('ifnull(((select sum(raf_production.raf_qty) from raf_production where order_list = "' . $order_list . '" and raf_dept = "' . $deptFrom . '") - ifnull((select sum(raf_production.raf_qty) from raf_production where order_list = "' . $order_list . '" and raf_dept = "' . $deptPicking . '"),0)),0) as raf_left'))
            ->leftJoin('order_list', 'order_list.order_list', '=', 'raf_production.order_list')
            ->where('raf_production.order_list', '=', $order_list)
            ->groupBy('raf_production.order_list','order_list.dcpo_qty')
            ->get();
            return response()->json($raf_productions);
        }else if ($raf_dept == 'DEP000000003') {
            $deptPicking = 'DEP000000003';
            $deptFrom = 'DEP000000002';
            $raf_productions = RafProduction::select('raf_production.order_list','order_list.dcpo_qty', DB::raw('ifnull((select sum(raf_production.raf_qty) from raf_production where order_list = "' . $order_list . '" and raf_dept = "' . $deptFrom . '"),0) as raf_sewing'), DB::raw('ifnull((select sum(raf_production.raf_qty) from raf_production where order_list = "' . $order_list . '" and raf_dept = "' . $deptPicking . '"),0) as sum_raf_qty'), DB::raw('ifnull(((select sum(raf_production.raf_qty) from raf_production where order_list = "' . $order_list . '" and raf_dept = "' . $deptFrom . '") - ifnull((select sum(raf_production.raf_qty) from raf_production where order_list = "' . $order_list . '" and raf_dept = "' . $deptPicking . '"),0)),0) as raf_left'))
            ->leftJoin('order_list', 'order_list.order_list', '=', 'raf_production.order_list')
            ->where('raf_production.order_list', '=', $order_list)
            ->groupBy('raf_production.order_list','order_list.dcpo_qty')
            ->get();
            return response()->json($raf_productions);
        }else if ($raf_dept == 'DEP000000004') {
            $deptPicking = 'DEP000000004';
            $deptFrom = 'DEP000000003';
            $raf_productions = RafProduction::select('raf_production.order_list','order_list.dcpo_qty', DB::raw('ifnull((select sum(raf_production.raf_qty) from raf_production where order_list = "' . $order_list . '" and raf_dept = "' . $deptFrom . '"),0) as raf_iron'), DB::raw('ifnull((select sum(raf_production.raf_qty) from raf_production where order_list = "' . $order_list . '" and raf_dept = "' . $deptPicking . '"),0) as sum_raf_qty'), DB::raw('ifnull(((select sum(raf_production.raf_qty) from raf_production where order_list = "' . $order_list . '" and raf_dept = "' . $deptFrom . '") - ifnull((select sum(raf_production.raf_qty) from raf_production where order_list = "' . $order_list . '" and raf_dept = "' . $deptPicking . '"),0)),0) as raf_left'))
            ->leftJoin('order_list', 'order_list.order_list', '=', 'raf_production.order_list')
            ->where('raf_production.order_list', '=', $order_list)
            ->groupBy('raf_production.order_list','order_list.dcpo_qty')
            ->get();
            return response()->json($raf_productions);
        }
    }

    public function fetchplanningdate($order_list, $raf_dept) {
        if ($raf_dept == 'DEP000000001') {
            $productionplannings = ProductionPlanning::select('production_planning.order_list','production_planning.startcut_date as startdate', 'production_planning.finishcut_date as finishdate')
            ->where('production_planning.order_list', '=', $order_list)
            ->get();
            return response()->json($productionplannings);
        } else if ($raf_dept == 'DEP000000002') {
            $productionplannings = ProductionPlanning::select('production_planning.order_list','production_planning.startsew_date as startdate', 'production_planning.finishsew_date as finishdate')
            ->where('production_planning.order_list', '=', $order_list)
            ->get();
            return response()->json($productionplannings);
        } else if ($raf_dept == 'DEP000000003') {
            $productionplannings = ProductionPlanning::select('production_planning.order_list','production_planning.startsew_date as startdate', 'production_planning.finishsew_date as finishdate')
            ->where('production_planning.order_list', '=', $order_list)
            ->get();
            return response()->json($productionplannings);
        } else if ($raf_dept == 'DEP000000004') {
            $productionplannings = ProductionPlanning::select('production_planning.order_list','production_planning.startsew_date as startdate', 'production_planning.finishpack_date as finishdate')
            ->where('production_planning.order_list', '=', $order_list)
            ->get();
            return response()->json($productionplannings);
        }
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created RAF Production ' . $request->raf_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);

        SetupIncrement::updateOrCreate([
            'models' => 'RafProduction'
        ],[
            'models' => 'RafProduction',
            'last_number' => $request->raf_no,
        ]);
        RafProduction::create([
            'order_trans' => $request->order_trans,
            'order_list' => $request->order_list,
            'raf_dept' => $request->raf_dept,
            'raf_no' => $request->raf_no,
            'raf_date' => $request->raf_date,
            'raf_qty' => $request->raf_qty,
            'remark' => $request->remark,
        ]);

        Alert::success('Create Successfully!', 'RAF Production ' . $request->raf_no . ' successfully created!');
        return redirect()
            ->route('rafproduction.create');
    }

    public function delete($id) {
        $rafproduction = RafProduction::find($id);    
        $rafproduction->delete();
        
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted RAF Production ' . $rafproduction->raf_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);

        Alert::success('Delete Successfully!', 'RAF Production ' . $rafproduction->raf_no . ' successfully deleted!');
        return redirect('rafproduction/index');
    }
}
