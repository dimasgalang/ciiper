<?php

namespace App\Http\Controllers;

use App\Models\OrderList;
use App\Models\OrderMaster;
use App\Models\RafProduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class RafProductionController extends Controller
{
    public function index() {
        $rafproductions   = RafProduction::all();
        return view('rafproduction.index', compact('rafproductions'));
    }

    public function create() {
        $ordermasters = OrderMaster::all();
        $rafs = RafProduction::all()->last();
        return view('rafproduction.create', compact('rafs','ordermasters'));
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
        RafProduction::create([
            'order_trans' => $request->order_trans,
            'order_list' => $request->order_list,
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
