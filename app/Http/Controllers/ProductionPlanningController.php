<?php

namespace App\Http\Controllers;

use App\Imports\ProductionPlanningsImport;
use App\Models\LogCiiper;
use App\Models\OrderList;
use App\Models\OrderMaster;
use App\Models\ProductionPlanning;
use App\Models\SetupIncrement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class ProductionPlanningController extends Controller
{
    public function index() {
        $productionplannings = ProductionPlanning::select('production_planning.*', 'purchase_order.po_master', 'order_list.pobuyer_no')
        ->leftJoin('order_list', 'production_planning.order_list', '=', 'order_list.order_list')
        ->leftJoin('order_master', 'order_master.order_trans', '=', 'production_planning.order_trans')
        ->leftJoin('purchase_order', 'order_master.po_no', '=', 'purchase_order.po_no')
        ->get();
        return view('productionplanning.index', compact('productionplannings'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/',$nama_file);
        $import = Excel::import(new ProductionPlanningsImport(), storage_path('app/public/excel/'.$nama_file));
        Storage::delete($path);

        if($import) {
            Alert::success('Import Successfully!', 'Production Planning data successfully imported!');
            return redirect()->intended('productionplanning/index');
        } else {
            return redirect()->intended('productionplanning/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create() {
        $ordermasters = OrderMaster::select('order_master.*','purchase_order.po_master')
        ->leftJoin('purchase_order','order_master.po_no','=','purchase_order.po_no')
        ->get();
        $orderlists = OrderList::all();
        $setupincements = SetupIncrement::all()->where('models','=','ProductionPlanning')->last();
        return view('productionplanning.create', compact('ordermasters', 'orderlists','setupincements'));
    }

    public function fetchorderlist($order_trans) {
        $orderlists   = OrderList::select('*', 'order_master.*')
        ->leftJoin('order_master', 'order_master.order_trans', '=', 'order_list.order_trans')
        ->where('order_list.order_trans', '=', $order_trans)
        ->get();
        return response()->json($orderlists);
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Production Planning ' . $request->plan_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);
        SetupIncrement::updateOrCreate([
            'models' => 'ProductionPlanning'
        ],[
            'models' => 'ProductionPlanning',
            'last_number' => $request->plan_no,
        ]);
        ProductionPlanning::create([
            'plan_no' => $request->plan_no,
            'order_trans' => $request->order_trans,
            'order_list' => $request->order_list,
            'has_sample' => $request->has_sample,
            'has_mi' => $request->has_mi,
            'has_cart' => $request->has_cart,
            'fab_date' => $request->fab_date,
            'acc_date' => $request->acc_date,
            'bordir_approve' => $request->bordir_approve,
            'pattern_date' => $request->pattern_date,
            'sampletest_date' => $request->sampletest_date,
            'marker_date' => $request->marker_date,
            'pilotrun_date' => $request->pilotrun_date,
            'ppm_date' => $request->ppm_date,
            'startcut_date' => $request->startcut_date,
            'finishcut_date' => $request->finishcut_date,
            'startsew_date' => $request->startsew_date,
            'finishsew_date' => $request->finishsew_date,
            'finishpack_date' => $request->finishpack_date,
            'remark' => $request->remark,
        ]);

        Alert::success('Create Successfully!', 'Production Planning ' . $request->plan_no . ' successfully created!');
        return redirect()
            ->route('productionplanning.create');
    }

    public function delete($id) {
        $productionplannings = ProductionPlanning::find($id);    
        $productionplannings->delete();
        
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Production Planning ' . $productionplannings->plan_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);
        Alert::success('Delete Successfully!', 'Production Planning ' . $productionplannings->plan_no . ' successfully deleted!');
        return redirect('productionplanning/index');
    }

    public function find($id) {
        $productionplannings = ProductionPlanning::find($id);
        $orderlists = OrderList::select('order_list.order_list','order_list.pobuyer_no','production_planning.*')
        ->leftJoin('production_planning','production_planning.order_list','=','order_list.order_list')
        ->where('production_planning.id','=',$id)
        ->get();
        $ordermasters = OrderMaster::select('order_master.order_trans','purchase_order.po_master','production_planning.*')
        ->leftJoin('purchase_order','order_master.po_no','=','purchase_order.po_no')
        ->leftJoin('production_planning','order_master.order_trans','=','production_planning.order_trans')
        ->where('production_planning.id','=',$id)
        ->get(); 
        return view('productionplanning.update', compact('productionplannings','orderlists','ordermasters'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated Production Planning ' . $request->plan_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $productionplannings = ProductionPlanning::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'plan_no' => 'required|max:225|',
            'order_trans' => 'required|max:225|',
            'order_list' => 'required|max:255',
            'has_sample' => 'required|max:255',
            'has_mi' => 'required|max:255',
            'has_cart' => 'required|max:255',
            'fab_date' => 'required|max:255',
            'acc_date' => 'required|max:255',
            'bordir_approve' => 'required|max:255',
            'pattern_date' => 'required|max:255',
            'sampletest_date' => 'required|max:255',
            'marker_date' => 'required|max:255',
            'pilotrun_date' => 'required|max:255',
            'ppm_date' => 'required|max:255',
            'startcut_date' => 'required|max:255',
            'finishcut_date' => 'required|max:255',
            'startsew_date' => 'required|max:255',
            'finishsew_date' => 'required|max:255',
            'finishpack_date' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $productionplannings->fill([
            'plan_no' => $request->plan_no,
            'order_trans' => $request->order_trans,
            'order_list' => $request->order_list,
            'has_sample' => $request->has_sample,
            'has_mi' => $request->has_mi,
            'has_cart' => $request->has_cart,
            'fab_date' => $request->fab_date,
            'acc_date' => $request->acc_date,
            'bordir_approve' => $request->bordir_approve,
            'pattern_date' => $request->pattern_date,
            'sampletest_date' => $request->sampletest_date,
            'marker_date' => $request->marker_date,
            'pilotrun_date' => $request->pilotrun_date,
            'ppm_date' => $request->ppm_date,
            'startcut_date' => $request->startcut_date,
            'finishcut_date' => $request->finishcut_date,
            'startsew_date' => $request->startsew_date,
            'finishsew_date' => $request->finishsew_date,
            'finishpack_date' => $request->finishpack_date,
            'remark' => $request->remark,
        ]);

        $productionplannings->save();

        Alert::success('Update Successfully!', 'Production Planning ' . $request->plan_no . ' successfully updated!');
        return redirect('productionplanning/index');
    }
}
