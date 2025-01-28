<?php

namespace App\Http\Controllers;

use App\Imports\ProductionPlanningsImport;
use App\Models\OrderList;
use App\Models\OrderMaster;
use App\Models\ProductionPlanning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

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
            return redirect()->intended('productionplanning/index')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            return redirect()->intended('productionplanning/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create() {
        $ordermasters = OrderMaster::select('order_master.*','purchase_order.po_master')
        ->leftJoin('purchase_order','order_master.po_no','=','purchase_order.po_no')
        ->get();
        $orderlists = OrderList::all();
        return view('productionplanning.create', compact('ordermasters', 'orderlists'));
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
        ProductionPlanning::create([
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

        return redirect()
            ->route('productionplanning.create')
            ->with('success', 'Production Planning berhasil ditambahkan!');
    }

    public function delete($id) {
        $productionplannings = ProductionPlanning::find($id);    
        $productionplannings->delete();
        return redirect('productionplanning/index')->with(['error' => 'Record Berhasil Dihapus!']);
    }

    public function find($id) {
        $productionplannings = ProductionPlanning::find($id);
        $orderlists = OrderList::all();  
        $ordermasters = OrderMaster::all();  
        return view('productionplanning.update', compact('productionplannings','orderlists','ordermasters'));
    }

    public function update(Request $request)
    {
        $productionplannings = ProductionPlanning::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
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
            'remark' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $productionplannings->fill([
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

        return redirect('productionplanning/index')->with(['success' => 'Production Planning berhasil diupdate!']);
    }
}
