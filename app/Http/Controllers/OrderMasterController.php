<?php

namespace App\Http\Controllers;

use App\Exports\OrderMasterExport;
use App\Imports\OrderMastersImport;
use App\Models\BordirType;
use App\Models\Brand;
use App\Models\Buyer;
use App\Models\Fabrication;
use App\Models\FollowUp;
use App\Models\OrderList;
use App\Models\OrderMaster;
use App\Models\ProductionPlanning;
use App\Models\PurchaseOrder;
use App\Models\RafProduction;
use App\Models\Season;
use App\Models\Shipment;
use App\Models\Style;
use App\Models\WashType;
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
        $ordermasters = OrderMaster::select('order_master.*','season.season_cat','season.season_year','buyer.buyer_name', 'brand.brand_name', 'style_name', 'followup.fu_name','purchase_order.po_master', DB::raw('round(sum(if(raf_production.raf_dept = "DEP000000004", raf_production.raf_qty, 0)),2) as sum_raf_qty'))
        ->leftJoin('season', 'order_master.season_no', '=', 'season.season_no')
        ->leftJoin('buyer', 'order_master.buyer_no', '=', 'buyer.buyer_no')
        ->leftJoin('brand', 'order_master.brand_no', '=', 'brand.brand_no')
        ->leftJoin('style', 'order_master.style_no', '=', 'style.style_no')
        ->leftJoin('followup', 'order_master.fu_no', '=', 'followup.fu_no')
        ->leftJoin('raf_production', 'order_master.order_trans', '=', 'raf_production.order_trans')
        ->leftJoin('purchase_order', 'order_master.po_no', '=', 'purchase_order.po_no')
        ->groupBy('order_master.order_trans')
        ->get();
        // return response()->json($ordermasters);
        return view('ordermaster.index', compact('ordermasters'));
    }

    public function create() {
        $ordermasters = OrderMaster::all()->last();
        $seasons = Season::all();
        $buyers = Buyer::all();
        $brands = Brand::all();
        $styles = Style::all();
        $followups = FollowUp::all();
        $pos = PurchaseOrder::all();
        return view('ordermaster.create', compact('ordermasters','seasons','buyers','brands','styles','followups','pos'));
    }

    public function showlist($order_trans) {
        $orderlists = OrderList::select('order_list.factory_no', 'order_list.lot_no','order_list.pobuyer_no','order_list.ex_factory_date','order_list.vsl_date','order_list.dcpo_qty', DB::raw('round(order_list.dcpo_qty/12,2) as dcpo_dzn'), DB::raw('(sum(coalesce(if(raf_production.raf_dept = "DEP000000004", raf_production.raf_qty, 0),0))-order_list.dcpo_qty) as balance'), DB::raw('round(sum(if(raf_production.raf_dept = "DEP000000004", raf_production.raf_qty, 0)),2) as sum_raf_qty'))
        ->leftJoin('order_master', 'order_master.order_trans', '=', 'order_list.order_trans')
        ->leftJoin('raf_production','order_list.order_list', '=', 'raf_production.order_list')
        ->where('order_list.order_trans', '=', $order_trans)
        ->groupBy('order_list.order_trans', 'order_list.factory_no', 'order_list.lot_no','order_list.pobuyer_no', 'order_list.ex_factory_date', 'order_list.vsl_date','order_list.dcpo_qty')
        ->get();
        // return response()->json($orderlists);
        return DataTables::of($orderlists)->addIndexColumn()->make(true);
    }

    public function showrafproduction($order_trans) {
        $rafproductions = RafProduction::select('*','order_list.*','order_master.*')
        ->leftJoin('order_list', 'order_list.order_list', '=', 'raf_production.order_list')
        ->leftJoin('order_master', 'order_master.order_trans', '=', 'order_list.order_trans')
        ->where('order_list.order_trans', '=', $order_trans)
        ->where('raf_production.raf_dept', '=', 'DEP000000004')
        ->get();
        // return response()->json($orderlists);
        return DataTables::of($rafproductions)->addIndexColumn()->make(true);
    }

    public function showrafcutting($order_trans, Request $request) {
        if($request->ajax()) {
            $rafcuttings = RafProduction::select('raf_production.order_list','order_list.lot_no','order_list.pobuyer_no','order_master.order_trans','raf_production.raf_date','order_list.dcpo_qty','raf_production.raf_qty', DB::raw('((select sum(raf_qty) from raf_production t2 where t2.raf_date <= raf_production.raf_date and t2.raf_dept = "DEP000000001" and t2.order_list = raf_production.order_list order by order_list, raf_date)-dcpo_qty) as balance'), DB::raw('((select sum(raf_qty) from raf_production t2 where t2.raf_date <= raf_production.raf_date and t2.raf_dept = "DEP000000001" and t2.order_list = raf_production.order_list order by order_list, raf_date)) as totalraf'))
            ->leftJoin('order_list', 'order_list.order_list', '=', 'raf_production.order_list')
            ->leftJoin('order_master', 'order_master.order_trans', '=', 'order_list.order_trans')
            ->where('order_list.order_trans', '=', $order_trans)
            ->where('raf_production.raf_dept', '=', 'DEP000000001')
            ->orderBy('raf_production.order_list')
            ->orderBy('raf_production.raf_date');
            // return response()->json($orderlists);
            return DataTables::of($rafcuttings)
            ->addIndexColumn()
            ->filter(function ($instance) use ($request) {
                if ($request->filled('fromdateCutting') && $request->filled('todateCutting') && $request->filled('searchCutting')) {
                    $searchCutting = $request->get('searchCutting');
                    $instance
                    ->where('raf_production.raf_date', '>=', $request->get('fromdateCutting'))
                    ->where('raf_production.raf_date', '<=', $request->get('todateCutting'))
                    ->where('raf_production.raf_dept', '=', 'DEP000000001')
                    ->where(function($query) use ($searchCutting) {
                        $query->orWhere('order_list.lot_no', 'LIKE', "%$searchCutting%")
                        ->orWhere('order_list.pobuyer_no', 'LIKE', "%$searchCutting%")
                        ->orWhere('order_list.dcpo_qty', 'LIKE', "%$searchCutting%")
                        ->orWhere('raf_production.raf_qty', 'LIKE', "%$searchCutting%");
                    });
                }
            })->make(true);
        }
    }

    public function showrafsewing($order_trans, Request $request) {
        if($request->ajax()) {
            $rafsewings = RafProduction::select('raf_production.order_list','order_list.lot_no','order_list.pobuyer_no','order_master.order_trans','raf_production.raf_date','order_list.dcpo_qty','raf_production.raf_qty', DB::raw('((select sum(raf_qty) from raf_production t2 where t2.raf_date <= raf_production.raf_date and t2.raf_dept = "DEP000000002" and t2.order_list = raf_production.order_list order by order_list, raf_date)-dcpo_qty) as balance'), DB::raw('((select sum(raf_qty) from raf_production t2 where t2.raf_date <= raf_production.raf_date and t2.raf_dept = "DEP000000002" and t2.order_list = raf_production.order_list order by order_list, raf_date)) as totalraf'))
            ->leftJoin('order_list', 'order_list.order_list', '=', 'raf_production.order_list')
            ->leftJoin('order_master', 'order_master.order_trans', '=', 'order_list.order_trans')
            ->where('order_list.order_trans', '=', $order_trans)
            ->where('raf_production.raf_dept', '=', 'DEP000000002')
            ->orderBy('raf_production.order_list')
            ->orderBy('raf_production.raf_date');
            // return response()->json($orderlists);
            return DataTables::of($rafsewings)
            ->addIndexColumn()
            ->filter(function ($instance) use ($request) {
                if ($request->filled('fromdateSewing') && $request->filled('todateSewing') && $request->filled('searchSewing')) {
                    $searchSewing = $request->get('searchSewing');
                    $instance
                    ->where('raf_production.raf_date', '>=', $request->get('fromdateSewing'))
                    ->where('raf_production.raf_date', '<=', $request->get('todateSewing'))
                    ->where('raf_production.raf_dept', '=', 'DEP000000002')
                    ->where(function($query) use ($searchSewing) {
                        $query->orWhere('order_list.lot_no', 'LIKE', "%$searchSewing%")
                        ->orWhere('order_list.pobuyer_no', 'LIKE', "%$searchSewing%")
                        ->orWhere('order_list.dcpo_qty', 'LIKE', "%$searchSewing%")
                        ->orWhere('raf_production.raf_qty', 'LIKE', "%$searchSewing%");
                    });
                }
            })->make(true);
        }
    }
    public function showrafiron($order_trans, Request $request) {
        if($request->ajax()) {
            $rafirons = RafProduction::select('raf_production.order_list','order_list.lot_no','order_list.pobuyer_no','order_master.order_trans','raf_production.raf_date','order_list.dcpo_qty','raf_production.raf_qty', DB::raw('((select sum(raf_qty) from raf_production t2 where t2.raf_date <= raf_production.raf_date and t2.raf_dept = "DEP000000003" and t2.order_list = raf_production.order_list order by order_list, raf_date)-dcpo_qty) as balance'), DB::raw('((select sum(raf_qty) from raf_production t2 where t2.raf_date <= raf_production.raf_date and t2.raf_dept = "DEP000000003" and t2.order_list = raf_production.order_list order by order_list, raf_date)) as totalraf'))
            ->leftJoin('order_list', 'order_list.order_list', '=', 'raf_production.order_list')
            ->leftJoin('order_master', 'order_master.order_trans', '=', 'order_list.order_trans')
            ->where('order_list.order_trans', '=', $order_trans)
            ->where('raf_production.raf_dept', '=', 'DEP000000003')
            ->orderBy('raf_production.order_list')
            ->orderBy('raf_production.raf_date');
            // return response()->json($orderlists);
            return DataTables::of($rafirons)
            ->addIndexColumn()
            ->filter(function ($instance) use ($request) {
                if ($request->filled('fromdateIron') && $request->filled('todateIron') && $request->filled('searchIron')) {
                    $searchIron = $request->get('searchIron');
                    $instance
                    ->where('raf_production.raf_date', '>=', $request->get('fromdateIron'))
                    ->where('raf_production.raf_date', '<=', $request->get('todateIron'))
                    ->where('raf_production.raf_dept', '=', 'DEP000000003')
                    ->where(function($query) use ($searchIron) {
                        $query->orWhere('order_list.lot_no', 'LIKE', "%$searchIron%")
                        ->orWhere('order_list.pobuyer_no', 'LIKE', "%$searchIron%")
                        ->orWhere('order_list.dcpo_qty', 'LIKE', "%$searchIron%")
                        ->orWhere('raf_production.raf_qty', 'LIKE', "%$searchIron%");
                    });
                }
            })->make(true);
        }
    }
    
    public function showrafpacking($order_trans, Request $request) {
        if($request->ajax()) {
            $rafpackings = RafProduction::select('raf_production.order_list','order_list.lot_no','order_list.pobuyer_no','order_master.order_trans','raf_production.raf_date','order_list.dcpo_qty','raf_production.raf_qty', DB::raw('((select sum(raf_qty) from raf_production t2 where t2.raf_date <= raf_production.raf_date and t2.raf_dept = "DEP000000004" and t2.order_list = raf_production.order_list order by order_list, raf_date)-dcpo_qty) as balance'), DB::raw('((select sum(raf_qty) from raf_production t2 where t2.raf_date <= raf_production.raf_date and t2.raf_dept = "DEP000000004" and t2.order_list = raf_production.order_list order by order_list, raf_date)) as totalraf'))
            ->leftJoin('order_list', 'order_list.order_list', '=', 'raf_production.order_list')
            ->leftJoin('order_master', 'order_master.order_trans', '=', 'order_list.order_trans')
            ->where('order_list.order_trans', '=', $order_trans)
            ->where('raf_production.raf_dept', '=', 'DEP000000004')
            ->orderBy('raf_production.order_list')
            ->orderBy('raf_production.raf_date');
            // return response()->json($rafpackings);
            return DataTables::of($rafpackings)
            ->addIndexColumn()->filter(function ($instance) use ($request) {
                if ($request->filled('fromdatePacking') && $request->filled('todatePacking') && $request->filled('searchPacking')) {
                    $searchPacking = $request->get('searchPacking');
                    $instance
                    ->where('raf_production.raf_date', '>=', $request->get('fromdatePacking'))
                    ->where('raf_production.raf_date', '<=', $request->get('todatePacking'))
                    ->where('raf_production.raf_dept', '=', 'DEP000000004')
                    ->where(function($query) use ($searchPacking) {
                        $query->orWhere('order_list.lot_no', 'LIKE', "%$searchPacking%")
                        ->orWhere('order_list.pobuyer_no', 'LIKE', "%$searchPacking%")
                        ->orWhere('order_list.dcpo_qty', 'LIKE', "%$searchPacking%")
                        ->orWhere('raf_production.raf_qty', 'LIKE', "%$searchPacking%");
                    });
                }
            })->make(true);
        }
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

    public function showstyle($order_trans) {
        $style = Style::select('*', 'order_master.style_no')
        ->leftJoin('order_master', 'order_master.style_no', '=', 'style.style_no')
        ->where('order_master.order_trans', '=', $order_trans)
        ->get();
        return response()->json($style);
    }

    public function showproductionplanning($order_trans) {
        $productionplannings = ProductionPlanning::select('production_planning.*', 'purchase_order.po_master', 'order_list.pobuyer_no')
        ->leftJoin('order_list', 'production_planning.order_list', '=', 'order_list.order_list')
        ->leftJoin('order_master', 'order_master.order_trans', '=', 'production_planning.order_trans')
        ->leftJoin('purchase_order', 'order_master.po_no', '=', 'purchase_order.po_no')
        ->where('order_master.order_trans', '=', $order_trans)
        ->get();
        return DataTables::of($productionplannings)->addIndexColumn()->make(true);
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
            'fu_no' => $request->fu_no,
            'remark' => $request->remark,
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
        $pos = PurchaseOrder::all();
        return view('ordermaster.update', compact('ordermasters','seasons','buyers','brands','styles','followups','pos'));
    }

    public function update(Request $request)
    {
        $ordermasters = OrderMaster::findOrFail($request->id);

        if($request->hasFile('sketch_file')){
            $request->validate([
                'sketch_file' => 'required|mimes:jpg,png,jpeg|'
            ]);
    
            $file = $request->file('sketch_file');
            $fileName = $file->getClientOriginalName();
            $file->storeAs('', $fileName, 'sketch_uploads');
    
            $validator = Validator::make($request->all(), [
                'order_trans' => 'required|max:255',
                'season_no' => 'required|max:225|',
                'buyer_no' => 'required|max:225|',
                'brand_no' => 'required|max:225|',
                'style_no' => 'required|max:225|',
                'po_no' => 'required|max:225|',
                'qty_order' => 'required|max:225|',
                'qty_ocf' => 'required|max:225|',
                'fu_no' => 'required|max:225|',
                'sketch_file' => 'required',
                // 'remark' => 'required|max:225|',
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
                'fu_no' => $request->fu_no,
                'sketch_file' => $fileName,
                'remark' => $request->remark,
            ]);
    
        } else{
            $validator = Validator::make($request->all(), [
                'order_trans' => 'required|max:255',
                'season_no' => 'required|max:225|',
                'buyer_no' => 'required|max:225|',
                'brand_no' => 'required|max:225|',
                'style_no' => 'required|max:225|',
                'po_no' => 'required|max:225|',
                'qty_order' => 'required|max:225|',
                'qty_ocf' => 'required|max:225|',
                'fu_no' => 'required|max:225|',
                // 'remark' => 'required|max:225|',
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
                'fu_no' => $request->fu_no,
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

    public function export_excel()
	{
		return Excel::download(new OrderMasterExport, 'All Order Master.xlsx');
	}
}
