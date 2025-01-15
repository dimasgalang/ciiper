<?php

namespace App\Http\Controllers;

use App\Imports\OrderMastersImport;
use App\Models\Brand;
use App\Models\Buyer;
use App\Models\Fabrication;
use App\Models\FollowUp;
use App\Models\OrderList;
use App\Models\OrderMaster;
use App\Models\RafProduction;
use App\Models\Season;
use App\Models\Shipment;
use App\Models\Style;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class OrderMasterController extends Controller
{
    public function index() {
        // $ordermasters = OrderMaster::all();
        $ordermasters = OrderMaster::select('order_master.*','season.season_cat','buyer.buyer_name', 'brand.brand_name', 'style_name', 'followup.fu_name', DB::raw('round(sum(raf_production.raf_qty),2) as sum_raf_qty'))
        ->leftJoin('season', 'order_master.season_no', '=', 'season.season_no')
        ->leftJoin('buyer', 'order_master.buyer_no', '=', 'buyer.buyer_no')
        ->leftJoin('brand', 'order_master.brand_no', '=', 'brand.brand_no')
        ->leftJoin('style', 'order_master.style_no', '=', 'style.style_no')
        ->leftJoin('followup', 'order_master.fu_no', '=', 'followup.fu_no')
        ->leftJoin('raf_production', 'order_master.order_trans', '=', 'raf_production.order_trans')
        ->groupBy('order_master.order_trans')
        ->get();
        // dd($ordermasters);
        return view('ordermaster.index', compact('ordermasters'));
    }

    public function create() {
        $ordermasters = OrderMaster::all()->last();
        $seasons = Season::all();
        $buyers = Buyer::all();
        $brands = Brand::all();
        $styles = Style::all();
        $followups = FollowUp::all();
        return view('ordermaster.create', compact('ordermasters','seasons','buyers','brands','styles','followups'));
    }

    public function showlist($order_trans) {
        
        $orderlists = OrderList::select('order_list.factory_no', 'order_list.lot_no','order_list.pobuyer_no','order_list.ex_factory_date','order_list.vsl_date','order_list.dcpo_qty', DB::raw('round(order_list.dcpo_qty/12,2) as dcpo_dzn'), DB::raw('(sum(raf_production.raf_qty)-order_list.dcpo_qty) as balance'), DB::raw('sum(raf_production.raf_qty) as raf_qty'))
        ->leftJoin('order_master', 'order_master.order_trans', '=', 'order_list.order_trans')
        ->leftJoin('raf_production','order_list.order_list', '=', 'raf_production.order_list')
        ->where('order_list.order_trans', '=', $order_trans)
        ->groupBy('order_list.order_trans', 'order_list.factory_no', 'order_list.lot_no','order_list.pobuyer_no', 'order_list.ex_factory_date', 'order_list.vsl_date','order_list.dcpo_qty')
        ->get();
        // return response()->json($orderlists);
        return DataTables::of($orderlists)->addIndexColumn()->make(true);
    }

    public function showraf($order_trans) {
        
        $rafproductions = RafProduction::select('*','order_list.*','order_master.*')
        ->leftJoin('order_list', 'order_list.order_list', '=', 'raf_production.order_list')
        ->leftJoin('order_master', 'order_master.order_trans', '=', 'order_list.order_trans')
        ->where('order_list.order_trans', '=', $order_trans)
        ->get();
        // return response()->json($orderlists);
        return DataTables::of($rafproductions)->addIndexColumn()->make(true);
    }

    public function showshipment($order_trans) {
        $shipments = Shipment::select('shipment.*','market.market_name','ship_mode.ship_name','order_list.pobuyer_no')
        ->leftJoin('market', 'shipment.market_no', '=', 'market.market_no')
        ->leftJoin('ship_mode', 'shipment.ship_no', '=', 'ship_mode.ship_no')
        ->leftJoin('order_list', 'shipment.order_list', '=', 'order_list.order_list')
        ->where('order_list.order_trans', '=', $order_trans)
        ->get();
        // return response()->json($orderlists);
        return DataTables::of($shipments)->addIndexColumn()->make(true);
    }
    
    public function showfab($order_trans) {
        
        $fabrication = Fabrication::select('*', 'fabric_mill.*')
        ->leftJoin('fabric_mill', 'fabric_mill.fabmill_no', '=', 'fabrication.fabmill_no')
        ->where('fabrication.order_trans', '=', $order_trans)
        ->get();
        return response()->json($fabrication);
    }

    public function showStyle($order_trans) {
        
        $style = Style::select('*', 'order_master.style_no')
        ->leftJoin('order_master', 'order_master.style_no', '=', 'style.style_no')
        ->where('order_master.order_trans', '=', $order_trans)
        ->get();
        return response()->json($style);
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

    public function store(Request $request)
    {
        $request->validate([
            'sketch_file' => 'required|mimes:jpg,png|max:10240'
        ]);

        $file = $request->file('sketch_file');
        $fileName = $file->getClientOriginalName();
        $file->storeAs('', $fileName, 'sketch_uploads');

        OrderMaster::create([
            'order_trans' => $request->order_trans,
            'season_no' => $request->season_no,
            'buyer_no' => $request->buyer_no,
            'brand_no' => $request->brand_no,
            'style_no' => $request->style_no,
            'po_no' => $request->po_no,
            'qty_order' => $request->qty_order,
            'qty_ocf' => $request->qty_ocf,
            'qty_gmt' => $request->qty_gmt,
            'qty_sbd' => $request->qty_sbd,
            'fu_no' => $request->fu_no,
            'wash_type' => $request->wash_type,
            'remark' => $request->remark,
            // 'sketch_file' => $request->sketch_file,
            'sketch_file' => $fileName,
        ]);

        return redirect()
            ->route('ordermaster.create')
            ->with('success', 'Order Master berhasil ditambahkan!');
    }
    
    public function delete($id) {
        $ordermasters = OrderMaster::find($id);    
        $ordermasters->delete();
        Storage::disk('sketch_uploads')->delete($ordermasters->id);
        return redirect('ordermaster/index')->with(['error' => 'Record Berhasil Dihapus!']);
    }

    public function find($id) {
        $ordermasters = OrderMaster::find($id);   
        $seasons = Season::all();
        $buyers = Buyer::all();
        $brands = Brand::all();
        $styles = Style::all();
        $followups = FollowUp::all();
        return view('ordermaster.update', compact('ordermasters','seasons','buyers','brands','styles','followups'));
    }

    public function update(Request $request)
    {
        if($request->hasFile('sketch_file')){
            $request->validate([
                'sketch_file' => 'required|mimes:jpg,png,jpeg|'
            ]);
    
            $file = $request->file('sketch_file');
            $fileName = $file->getClientOriginalName();
            $file->storeAs('', $fileName, 'sketch_uploads');
    
            $ordermasters = OrderMaster::findOrFail($request->id);
    
            $validator = Validator::make($request->all(), [
                'order_trans' => 'required|max:255',
                'season_no' => 'required|max:225|',
                'buyer_no' => 'required|max:225|',
                'brand_no' => 'required|max:225|',
                'style_no' => 'required|max:225|',
                'po_no' => 'required|max:225|',
                'qty_order' => 'required|max:225|',
                'qty_ocf' => 'required|max:225|',
                'qty_gmt' => 'required|max:225|',
                'qty_sbd' => 'required|max:225|',
                'fu_no' => 'required|max:225|',
                'wash_type' => 'required|max:225|',
                'sketch_file' => 'required',
                'remark' => 'required|max:225|',
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
    
            $ordermasters->fill([
                'order_trans' => $request->order_trans,
                'season_no' => $request->season_no,
                'buyer_no' => $request->buyer_no,
                'brand_no' => $request->brand_no,
                'style_no' => $request->style_no,
                'po_no' => $request->po_no,
                'qty_order' => $request->qty_order,
                'qty_ocf' => $request->qty_ocf,
                'qty_gmt' => $request->qty_gmt,
                'qty_sbd' => $request->qty_sbd,
                'fu_no' => $request->fu_no,
                'wash_type' => $request->wash_type,
                'sketch_file' => $fileName,
                'remark' => $request->remark,
            ]);
    
        } else{
            $ordermasters = OrderMaster::findOrFail($request->id);
    
            $validator = Validator::make($request->all(), [
                'order_trans' => 'required|max:255',
                'season_no' => 'required|max:225|',
                'buyer_no' => 'required|max:225|',
                'brand_no' => 'required|max:225|',
                'style_no' => 'required|max:225|',
                'po_no' => 'required|max:225|',
                'qty_order' => 'required|max:225|',
                'qty_ocf' => 'required|max:225|',
                'qty_gmt' => 'required|max:225|',
                'qty_sbd' => 'required|max:225|',
                'fu_no' => 'required|max:225|',
                'wash_type' => 'required|max:225|',
                'remark' => 'required|max:225|',
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
    
            $ordermasters->fill([
                'order_trans' => $request->order_trans,
                'season_no' => $request->season_no,
                'buyer_no' => $request->buyer_no,
                'brand_no' => $request->brand_no,
                'style_no' => $request->style_no,
                'po_no' => $request->po_no,
                'qty_order' => $request->qty_order,
                'qty_ocf' => $request->qty_ocf,
                'qty_gmt' => $request->qty_gmt,
                'qty_sbd' => $request->qty_sbd,
                'fu_no' => $request->fu_no,
                'wash_type' => $request->wash_type,
                'remark' => $request->remark,
            ]);    
        }

        
        $ordermasters->save();

        return redirect('ordermaster/index')->with(['success' => 'Order Master berhasil diupdate!']);
    }

    public function fetchbrand($buyer_no) {
        $brands   = Brand::select('*')
        ->where('buyer_no', '=', $buyer_no)
        ->get();
        return response()->json($brands);
    }

    public function fetchstyle($brand_no) {
        $styles   = Style::select('*')
        ->where('brand_no', '=', $brand_no)
        ->get();
        return response()->json($styles);
    }
}
