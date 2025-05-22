<?php

namespace App\Http\Controllers;

use App\Models\LogCiiper;
use App\Models\OrderList;
use App\Models\OrderMaster;
use App\Models\OrderSize;
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
    public function index(Request $request)
    {
        if ($request->void) {
            $rafproductions = RafProduction::select('raf_production.*', 'production_dept.dept_name', 'order_list.pobuyer_no', 'size.size')
                ->leftJoin('production_dept', 'raf_production.raf_dept', '=', 'production_dept.dept_no')
                ->leftJoin('order_list', 'raf_production.order_list', '=', 'order_list.order_list')
                ->leftJoin('size', 'raf_production.size_no', '=', 'size.size_no')
                ->where('raf_production.void', '=', $request->void)
                ->get();
        } else {
            $rafproductions = RafProduction::select('raf_production.*', 'production_dept.dept_name', 'order_list.pobuyer_no', 'size.size')
                ->leftJoin('production_dept', 'raf_production.raf_dept', '=', 'production_dept.dept_no')
                ->leftJoin('order_list', 'raf_production.order_list', '=', 'order_list.order_list')
                ->leftJoin('size', 'raf_production.size_no', '=', 'size.size_no')
                ->where('raf_production.void', '=', 'false')
                ->get();
        }
        return view('rafproduction.index', compact('rafproductions'));
    }

    public function create()
    {
        $ordermasters = OrderMaster::select('order_master.*', 'purchase_order.po_master')
            ->leftJoin('purchase_order', 'order_master.po_no', '=', 'purchase_order.po_no')
            ->get();
        $ordersizes = OrderSize::select('order_size.*', 'size.size')->leftJoin('size', 'order_size.size_no', '=', 'size.size_no')->get();
        $productiondepts = ProductionDept::all();
        $setupincements = SetupIncrement::all()->where('models', '=', 'RafProduction')->last();
        return view('rafproduction.create', compact('setupincements', 'ordermasters', 'productiondepts', 'ordersizes'));
    }

    public function find($id)
    {
        $rafproductions = RafProduction::find($id);
        $orderlists = OrderMaster::select('order_master.po_no', 'purchase_order.po_master', 'raf_production.*', 'order_list.pobuyer_no')
            ->leftJoin('purchase_order', 'order_master.po_no', '=', 'purchase_order.po_no')
            ->leftJoin('raf_production', 'order_master.order_trans', '=', 'raf_production.order_trans')
            ->leftJoin('order_list', 'order_list.order_list', '=', 'raf_production.order_list')
            ->where('raf_production.id', '=', $id)
            ->get();
        $productiondepts = ProductionDept::all();
        $ordersizes = DB::select('select distinct order_size.size_no,size.size from order_size left join size on order_size.size_no = size.size_no');
        return view('rafproduction.update', compact('rafproductions', 'orderlists', 'productiondepts', 'ordersizes'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated RAF Production ' . $request->raf_no;

        $rafproductions = RafProduction::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'order_trans' => 'required|max:255',
            'order_list' => 'required|max:255|',
            'order_size_no' => 'required|max:255|',
            'raf_no' => 'required|max:255|',
            'raf_date' => 'required|max:255|',
            'raf_qty' => 'required',
            'raf_dept' => 'required|max:255|',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $rafproductions->fill([
            'order_trans' => $request->order_trans,
            'order_list' => $request->order_list,
            'size_no' => $request->order_size_no,
            'raf_no' => $request->raf_no,
            'raf_date' => $request->raf_date,
            'raf_qty' => $request->raf_qty,
            'raf_dept' => $request->raf_dept,
            'remark' => $request->remark,
        ]);

        $rafproductions->save();
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        Alert::success('Update Successfully!', 'RAF Production ' . $request->raf_no . ' successfully updated!');
        return redirect('rafproduction/index');
    }

    public function fetchorderlist($order_trans)
    {
        $orderlists   = OrderList::select('order_list.*', 'production_planning.*')
            ->join('production_planning', 'order_list.order_list', '=', 'production_planning.order_list')
            ->where('order_list.order_trans', '=', $order_trans)
            ->where('order_list.void', '=', 'false')
            ->get();
        return response()->json($orderlists);
    }

    public function fetchordersize($order_list)
    {
        $ordersizes = OrderSize::select(DB::raw('sum(order_size.qty) as qty_total'), 'size.size', 'size.size_no')
            ->leftJoin('size', 'order_size.size_no', '=', 'size.size_no')
            ->where('order_size.order_list', '=', $order_list)
            ->where('order_size.void', '=', 'false')
            ->groupBy('order_size.order_list', 'size.size', 'size.size_no')
            ->get();
        return response()->json($ordersizes);
    }

    public function fetchrafleft($order_list, $raf_dept, $size_no)
    {
        $deptPicking = "";
        $deptFrom =  "";
        if ($raf_dept == 'DEP000000001') {
            $deptPicking = 'DEP000000001';
            $raf_productions = DB::select('select distinct order_size.order_list,size.size,order_list.dcpo_qty,ifnull((select sum(order_size.qty) from order_size where order_size.size_no = size.size_no and order_list.order_list = order_size.order_list),0) as size_qty,ifnull((select sum(raf_qty) from raf_production where raf_production.size_no = order_size.size_no and raf_production.order_list = order_list.order_list and raf_production.raf_dept = "' . $deptPicking . '"),0) as total_raf,ifnull((select sum(raf_qty) from raf_production t2 where t2.size_no = order_size.size_no and t2.order_list = order_list.order_list and t2.raf_dept = "' . $deptFrom . '"),0) as dept_from,ifnull((select sum(order_size.qty) from order_size where order_size.size_no = size.size_no and order_list.order_list = order_size.order_list),0)-ifnull((select sum(raf_qty) from raf_production where raf_production.size_no = order_size.size_no and raf_production.order_list = order_list.order_list and raf_production.raf_dept = "' . $deptPicking . '"),0) as raf_left from order_size left join order_list on order_size.order_list = order_list.order_list left join size on size.size_no = order_size.size_no where order_list.order_list = "' . $order_list . '" and size.size_no = "' . $size_no . '"');
            return response()->json($raf_productions);
        } else if ($raf_dept == 'DEP000000002') {
            $deptPicking = 'DEP000000002';
            $deptFrom = 'DEP000000001';
            $raf_productions = DB::select('select distinct order_size.order_list,size.size,order_list.dcpo_qty,ifnull((select sum(order_size.qty) from order_size where order_size.size_no = size.size_no and order_list.order_list = order_size.order_list),0) as size_qty,ifnull((select sum(raf_qty) from raf_production where raf_production.size_no = order_size.size_no and raf_production.order_list = order_list.order_list and raf_production.raf_dept = "' . $deptPicking . '"),0) as total_raf,ifnull((select sum(raf_qty) from raf_production t2 where t2.size_no = order_size.size_no and t2.order_list = order_list.order_list and t2.raf_dept = "' . $deptFrom . '"),0) as dept_from,ifnull((select sum(raf_qty) from raf_production t2 where t2.size_no = order_size.size_no and t2.order_list = order_list.order_list and t2.raf_dept = "' . $deptFrom . '"),0)-ifnull((select sum(raf_qty) from raf_production where raf_production.size_no = order_size.size_no and raf_production.order_list = order_list.order_list and raf_production.raf_dept = "' . $deptPicking . '"),0) as raf_left from order_size left join order_list on order_size.order_list = order_list.order_list left join size on size.size_no = order_size.size_no where order_list.order_list = "' . $order_list . '" and size.size_no = "' . $size_no . '"');
            return response()->json($raf_productions);
        } else if ($raf_dept == 'DEP000000003') {
            $deptPicking = 'DEP000000003';
            $deptFrom = 'DEP000000002';
            $raf_productions = DB::select('select distinct order_size.order_list,size.size,order_list.dcpo_qty,ifnull((select sum(order_size.qty) from order_size where order_size.size_no = size.size_no and order_list.order_list = order_size.order_list),0) as size_qty,ifnull((select sum(raf_qty) from raf_production where raf_production.size_no = order_size.size_no and raf_production.order_list = order_list.order_list and raf_production.raf_dept = "' . $deptPicking . '"),0) as total_raf,ifnull((select sum(raf_qty) from raf_production t2 where t2.size_no = order_size.size_no and t2.order_list = order_list.order_list and t2.raf_dept = "' . $deptFrom . '"),0) as dept_from,ifnull((select sum(raf_qty) from raf_production t2 where t2.size_no = order_size.size_no and t2.order_list = order_list.order_list and t2.raf_dept = "' . $deptFrom . '"),0)-ifnull((select sum(raf_qty) from raf_production where raf_production.size_no = order_size.size_no and raf_production.order_list = order_list.order_list and raf_production.raf_dept = "' . $deptPicking . '"),0) as raf_left from order_size left join order_list on order_size.order_list = order_list.order_list left join size on size.size_no = order_size.size_no where order_list.order_list = "' . $order_list . '" and size.size_no = "' . $size_no . '"');
            return response()->json($raf_productions);
        } else if ($raf_dept == 'DEP000000004') {
            $deptPicking = 'DEP000000004';
            $deptFrom = 'DEP000000003';
            $raf_productions = DB::select('select distinct order_size.order_list,size.size,order_list.dcpo_qty,ifnull((select sum(order_size.qty) from order_size where order_size.size_no = size.size_no and order_list.order_list = order_size.order_list),0) as size_qty,ifnull((select sum(raf_qty) from raf_production where raf_production.size_no = order_size.size_no and raf_production.order_list = order_list.order_list and raf_production.raf_dept = "' . $deptPicking . '"),0) as total_raf,ifnull((select sum(raf_qty) from raf_production t2 where t2.size_no = order_size.size_no and t2.order_list = order_list.order_list and t2.raf_dept = "' . $deptFrom . '"),0) as dept_from,ifnull((select sum(raf_qty) from raf_production t2 where t2.size_no = order_size.size_no and t2.order_list = order_list.order_list and t2.raf_dept = "' . $deptFrom . '"),0)-ifnull((select sum(raf_qty) from raf_production where raf_production.size_no = order_size.size_no and raf_production.order_list = order_list.order_list and raf_production.raf_dept = "' . $deptPicking . '"),0) as raf_left from order_size left join order_list on order_size.order_list = order_list.order_list left join size on size.size_no = order_size.size_no where order_list.order_list = "' . $order_list . '" and size.size_no = "' . $size_no . '"');
            return response()->json($raf_productions);
        }
    }

    public function fetchcartonleft($order_list)
    {
        $orderlists = OrderList::select('order_list.carton_qty', DB::raw('ifnull((select sum(raf_production.carton_qty) from raf_production where order_list = "' . $order_list . '" and raf_production.void = "false"),0) as sum_carton'), DB::raw('ifnull(((order_list.carton_qty) - ifnull((select sum(raf_production.carton_qty) from raf_production where order_list = "' . $order_list . '" and raf_production.void = "false"),0)),0) as carton_balance'))
            ->where('order_list.order_list', '=', $order_list)
            ->get();
        return response()->json($orderlists);
    }

    public function fetchplanningdate($order_list, $raf_dept)
    {
        if ($raf_dept == 'DEP000000001') {
            $productionplannings = ProductionPlanning::select('production_planning.order_list', 'production_planning.startcut_date as startdate', 'production_planning.finishcut_date as finishdate')
                ->where('production_planning.order_list', '=', $order_list)
                ->where('production_planning.void', '=', 'false')
                ->get();
            return response()->json($productionplannings);
        } else if ($raf_dept == 'DEP000000002') {
            $productionplannings = ProductionPlanning::select('production_planning.order_list', 'production_planning.startsew_date as startdate', 'production_planning.finishsew_date as finishdate')
                ->where('production_planning.order_list', '=', $order_list)
                ->where('production_planning.void', '=', 'false')
                ->get();
            return response()->json($productionplannings);
        } else if ($raf_dept == 'DEP000000003') {
            $productionplannings = ProductionPlanning::select('production_planning.order_list', 'production_planning.startsew_date as startdate', 'production_planning.finishsew_date as finishdate')
                ->where('production_planning.order_list', '=', $order_list)
                ->where('production_planning.void', '=', 'false')
                ->get();
            return response()->json($productionplannings);
        } else if ($raf_dept == 'DEP000000004') {
            $productionplannings = ProductionPlanning::select('production_planning.order_list', 'production_planning.startsew_date as startdate', 'production_planning.finishpack_date as finishdate')
                ->where('production_planning.order_list', '=', $order_list)
                ->where('production_planning.void', '=', 'false')
                ->get();
            return response()->json($productionplannings);
        }
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created RAF Production ' . $request->raf_no;

        SetupIncrement::updateOrCreate([
            'models' => 'RafProduction'
        ], [
            'models' => 'RafProduction',
            'last_number' => $request->raf_no,
        ]);
        RafProduction::create([
            'order_trans' => $request->order_trans,
            'order_list' => $request->order_list,
            'size_no' => $request->size_no,
            'raf_dept' => $request->raf_dept,
            'raf_no' => $request->raf_no,
            'raf_date' => $request->raf_date,
            'raf_qty' => $request->raf_qty,
            'carton_qty' => $request->carton_qty,
            'remark' => $request->remark,
            'void' => 'false'
        ]);

        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);

        Alert::success('Create Successfully!', 'RAF Production ' . $request->raf_no . ' successfully created!');
        return redirect()
            ->route('rafproduction.create');
    }

    public function delete($id)
    {
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


    public function void(Request $request)
    {
        $rafproductions = RafProduction::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Void RAF Production ' . $rafproductions->raf_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $rafproductions->fill([
            'void' => 'true',
        ]);

        $rafproductions->save();

        Alert::success('Void Successfully!', 'RAF Production ' . $rafproductions->raf_no . ' successfully voided!');
        return redirect('rafproduction/index');
    }

    public function restore(Request $request)
    {
        $rafproductions = RafProduction::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Restore RAF Production ' . $rafproductions->raf_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $rafproductions->fill([
            'void' => 'false',
        ]);

        $rafproductions->save();

        Alert::success('Restore Successfully!', 'RAF Production ' . $rafproductions->raf_no . ' successfully restored!');
        return redirect('rafproduction/index');
    }
}
