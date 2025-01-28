<?php

namespace App\Http\Controllers;

use App\Imports\OrderListsImport;
use App\Models\BordirType;
use App\Models\Brand;
use App\Models\Buyer;
use App\Models\Fabrication;
use App\Models\Factory;
use App\Models\FollowUp;
use App\Models\OrderList;
use App\Models\OrderMaster;
use App\Models\RafProduction;
use App\Models\Season;
use App\Models\Style;
use App\Models\WashType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class OrderListController extends Controller
{
    public function index() {
        // $orderlists = OrderList::all();
        $orderlists = OrderList::select('order_list.*','purchase_order.po_master','season.season_cat','buyer.buyer_name', 'brand.brand_name', 'style.style_name', 'fabrication.fabrication', 'fabrication.po_fab', 'fabrication.etd', 'fabric_mill.fabmill_name', 'factory.factory_name','wash_type','bordir_type')
        ->leftJoin('order_master', 'order_master.order_trans', '=', 'order_list.order_trans')
        ->leftJoin('season', 'order_master.season_no', '=', 'season.season_no')
        ->leftJoin('buyer', 'order_master.buyer_no', '=', 'buyer.buyer_no')
        ->leftJoin('brand', 'order_master.brand_no', '=', 'brand.brand_no')
        ->leftJoin('style', 'order_master.style_no', '=', 'style.style_no')
        ->leftJoin('fabrication', 'order_master.order_trans', '=', 'fabrication.order_trans')
        ->leftJoin('fabric_mill', 'fabric_mill.fabmill_no', '=', 'fabrication.fabmill_no')
        ->leftJoin('factory', 'factory.factory_no', '=', 'order_list.factory_no')
        ->leftJoin('purchase_order', 'order_master.po_no', '=', 'purchase_order.po_no')
        ->leftJoin('wash_type', 'order_list.wash_no', '=', 'wash_type.wash_no')
        ->leftJoin('bordir_type', 'order_list.bordir_no', '=', 'bordir_type.bordir_no')
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

    public function create() {
        $orderlists = OrderList::all()->last();
        $ordermasters = OrderMaster::select('order_master.*','purchase_order.po_master')
        ->leftJoin('purchase_order','order_master.po_no','=','purchase_order.po_no')
        ->get();
        $seasons = Season::all();
        $buyers = Buyer::all();
        $brands = Brand::all();
        $styles = Style::all();
        $followups = FollowUp::all();
        $factorys = Factory::all();
        $washtypes = WashType::all();
        $bordirtypes = BordirType::all();
        return view('orderlist.create', compact('orderlists','ordermasters','seasons','buyers','brands','styles','followups','factorys','washtypes','bordirtypes'));
    }

    public function store(Request $request)
    {
        OrderList::create([
            'order_trans' => $request->order_trans,
            'order_list' => $request->order_list,
            'factory_no' => $request->factory_no,
            'lot_no' => $request->lot_no,
            'pobuyer_no' => $request->pobuyer_no,
            'dcpo_qty' => $request->dcpo_qty,
            'ex_factory_date' => $request->ex_factory_date,
            'vsl_date' => $request->vsl_date,
            'wash_no' => $request->wash_no,
            'bordir_no' => $request->bordir_no,
            'line' => $request->line,
            'target_qty' => $request->target_qty,
            'production_day' => $request->production_day,
        ]);

        return redirect()
            ->route('orderlist.create')
            ->with('success', 'Order List berhasil ditambahkan!');
    }

    public function delete($id) {
        $orderlists = OrderList::find($id);    
        $orderlists->delete();
        return redirect('orderlist/index')->with(['error' => 'Record Berhasil Dihapus!']);
    }
    
    public function finish(Request $request) {
        $orderlists = OrderList::findOrFail($request->id);
        $orderlists->fill([
            'status' => 'Finish',
        ]);

        $orderlists->save();

        return redirect('orderlist/index')->with(['success' => 'Status Order List berhasil diupdate menjadi Finish!']);
    }

    public function find($id) {
        $orderlists = OrderList::find($id);  
        $ordermasters = OrderMaster::all();  
        $seasons = Season::all();
        $buyers = Buyer::all();
        $brands = Brand::all();
        $styles = Style::all();
        $followups = FollowUp::all();
        $factorys = Factory::all();
        return view('orderlist.update', compact('orderlists','ordermasters','seasons','buyers','brands','styles','followups','factorys'));
    }

    public function update(Request $request)
    {
        $orderlists = OrderList::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'order_trans' => 'required|max:255',
            'order_list' => 'required|max:225|',
            'factory_no' => 'required|max:225|',
            'lot_no' => 'required|max:225|',
            'pobuyer_no' => 'required|max:225|',
            'dcpo_qty' => 'required|max:225|',
            'ex_factory_date' => 'required|max:225|',
            'vsl_date' => 'required|max:225|',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $orderlists->fill([
            'order_trans' => $request->order_trans,
            'order_list' => $request->order_list,
            'factory_no' => $request->factory_no,
            'lot_no' => $request->lot_no,
            'pobuyer_no' => $request->pobuyer_no,
            'dcpo_qty' => $request->dcpo_qty,
            'ex_factory_date' => $request->ex_factory_date,
            'vsl_date' => $request->vsl_date,
        ]);

        $orderlists->save();

        return redirect('orderlist/index')->with(['success' => 'Order List berhasil diupdate!']);
    }
    

    public function fetchorderleft($order_trans) {
        $order_lists = OrderMaster::select('order_master.order_trans','order_master.qty_order', DB::raw('ifnull(sum(order_list.dcpo_qty),0) as sum_dcpo_qty'), DB::raw('ifnull((order_master.qty_order - ifnull(sum(order_list.dcpo_qty),0)),0) as qty_left'))
        ->leftJoin('order_list', 'order_list.order_trans', '=', 'order_master.order_trans')
        ->where('order_master.order_trans', '=', $order_trans)
        ->groupBy('order_master.order_trans','order_master.qty_order')
        ->get();
        return response()->json($order_lists);
    }
}
