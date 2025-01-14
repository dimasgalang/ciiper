<?php

namespace App\Http\Controllers;

use App\Imports\OrderListsImport;
use App\Models\Fabrication;
use App\Models\OrderList;
use App\Models\OrderMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class OrderListController extends Controller
{
    public function index() {
        // $orderlists = OrderList::all();
        $orderlists = OrderList::select('order_list.*','season.season_cat','buyer.buyer_name', 'brand.brand_name', 'style.style_name', 'fabrication.*', 'fabric_mill.*', 'factory.*')
        ->leftJoin('order_master', 'order_master.order_trans', '=', 'order_list.order_trans')
        ->leftJoin('season', 'order_master.season_no', '=', 'season.season_no')
        ->leftJoin('buyer', 'order_master.buyer_no', '=', 'buyer.buyer_no')
        ->leftJoin('brand', 'order_master.brand_no', '=', 'brand.brand_no')
        ->leftJoin('style', 'order_master.style_no', '=', 'style.style_no')
        ->leftJoin('fabrication', 'order_master.order_trans', '=', 'fabrication.order_trans')
        ->leftJoin('fabric_mill', 'fabric_mill.fabmill_no', '=', 'fabrication.fabmill_no')
        ->leftJoin('factory', 'factory.factory_no', '=', 'order_list.factory_no')
        ->get();
        // dd($orderlists);
        return view('orderlist.index', compact('orderlists'));
    }
    
    public function showfab($order_trans) {
        
        $fabrication = Fabrication::select('*', 'fabric_mill.*')
        ->leftJoin('fabric_mill', 'fabric_mill.fabmill_no', '=', 'fabrication.fabmill_no')
        ->where('fabrication.order_trans', '=', $order_trans)
        ->get();
        return response()->json($fabrication);
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/',$nama_file);
        $import = Excel::import(new OrderListsImport(), storage_path('app/public/excel/'.$nama_file));
        Storage::delete($path);

        if($import) {
            return redirect()->intended('orderlist/index')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            return redirect()->intended('orderlist/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }
}
