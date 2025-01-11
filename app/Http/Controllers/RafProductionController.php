<?php

namespace App\Http\Controllers;

use App\Models\OrderList;
use App\Models\RafProduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class RafProductionController extends Controller
{
    public function index() {
        $rafproductions   = RafProduction::all();
        return view('rafproduction.index', compact('rafproductions'));
    }

    public function create() {
        $orderlists   = OrderList::all();
        return view('rafproduction.create', compact('orderlists'));
    }

    public function store(Request $request)
    {
        RafProduction::create([
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
