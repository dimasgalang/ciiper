<?php

namespace App\Http\Controllers;

use App\Imports\OrderMastersImport;
use App\Models\OrderList;
use App\Models\OrderMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class OrderMasterController extends Controller
{
    public function index() {
        // $ordermasters = OrderMaster::all();
        $ordermasters = OrderMaster::select('order_master.*','season.season_cat','buyer.buyer_name', 'brand.brand_name', 'style_name')
        ->leftJoin('season', 'order_master.season_no', '=', 'season.season_no')
        ->leftJoin('buyer', 'order_master.buyer_no', '=', 'buyer.buyer_no')
        ->leftJoin('brand', 'order_master.brand_no', '=', 'brand.brand_no')
        ->leftJoin('style', 'order_master.style_no', '=', 'style.style_no')
        ->get();
        // dd($ordermasters);
        return view('ordermaster.index', compact('ordermasters'));
    }

    public function show($order_trans) {
        
        $orderlists = OrderList::select('*', DB::raw('round(order_list.dcpo_qty/12,2) as dcpo_dzn'))
        ->leftJoin('order_master', 'order_master.order_trans', '=', 'order_list.order_trans')
        ->where('order_list.order_trans', '=', $order_trans)
        ->get();
        // return response()->json($orderlists);
        return DataTables::of($orderlists)->make(true);
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/',$nama_file);
        $import = Excel::import(new OrderMastersImport(), storage_path('app/public/excel/'.$nama_file));
        Storage::delete($path);

        if($import) {
            return redirect()->intended('ordermaster/index')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            return redirect()->intended('ordermaster/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }
}
