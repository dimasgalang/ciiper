<?php

namespace App\Http\Controllers;

use App\Models\OrderList;
use App\Models\OrderMaster;
use App\Models\ProductionDept;
use App\Models\RafProduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class RafProductionController extends Controller
{
    public function index() {
        $rafproductions   = RafProduction::select('raf_production.*','production_dept.dept_name')
        ->leftJoin('production_dept', 'raf_production.raf_dept', '=', 'production_dept.dept_no')
        ->get();
        return view('rafproduction.index', compact('rafproductions'));
    }

    public function create() {
        $ordermasters = OrderMaster::select('order_master.*', 'purchase_order.po_master')
        ->leftJoin('purchase_order','order_master.po_no','=','purchase_order.po_no')
        ->get();
        $productiondepts = ProductionDept::all();
        $rafs = RafProduction::all()->last();
        return view('rafproduction.create', compact('rafs','ordermasters','productiondepts'));
    }

    public function fetchorderlist($order_trans) {
        $orderlists   = OrderList::select('*', 'order_master.*')
        ->leftJoin('order_master', 'order_master.order_trans', '=', 'order_list.order_trans')
        ->where('order_list.order_trans', '=', $order_trans)
        ->get();
        return response()->json($orderlists);
    }

    public function fetchrafleft($order_list, $raf_dept) {
        $raf_productions = RafProduction::select('raf_production.order_list','order_list.dcpo_qty', DB::raw('sum(raf_production.raf_qty) as sum_raf_qty'), DB::raw('(order_list.dcpo_qty - sum(raf_production.raf_qty)) as raf_left'))
        ->leftJoin('order_list', 'order_list.order_list', '=', 'raf_production.order_list')
        ->where('raf_production.order_list', '=', $order_list)
        ->where('raf_production.raf_dept', '=', $raf_dept)
        ->groupBy('raf_production.order_list','order_list.dcpo_qty')
        ->get();
        return response()->json($raf_productions);
    }


    public function store(Request $request)
    {
        RafProduction::create([
            'order_trans' => $request->order_trans,
            'order_list' => $request->order_list,
            'raf_dept' => $request->raf_dept,
            'raf_no' => $request->raf_no,
            'raf_date' => $request->raf_date,
            'raf_qty' => $request->raf_qty,
            'remark' => $request->remark,
        ]);

        return redirect()
            ->route('rafproduction.create')
            ->with('success', 'RAF Production berhasil ditambahkan!');
    }

    public function delete($id) {
        $rafproduction = RafProduction::find($id);    
        $rafproduction->delete();
        return redirect('rafproduction/index')->with(['error' => 'Record Berhasil Dihapus!']);
    }
}
