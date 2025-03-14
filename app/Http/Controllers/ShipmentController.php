<?php

namespace App\Http\Controllers;

use App\Models\LogCiiper;
use App\Models\Market;
use App\Models\OrderList;
use App\Models\OrderMaster;
use App\Models\OrderSize;
use App\Models\RafProduction;
use App\Models\SetupIncrement;
use App\Models\Shipment;
use App\Models\ShipMode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ShipmentController extends Controller
{
    public function index(Request $request) {
        $shipments = Shipment::select('shipment.*','market.market_name','ship_mode.shipmode_name','order_list.pobuyer_no')
        ->leftJoin('market', 'shipment.market_no', '=', 'market.market_no')
        ->leftJoin('ship_mode', 'shipment.shipmode_no', '=', 'ship_mode.shipmode_no')
        ->leftJoin('order_list', 'shipment.order_list', '=', 'order_list.order_list')
        ->where('shipment.void','=',$request->void)
        ->get();
        return view('shipment.index', compact('shipments'));
    }

    public function create() {
        $setupincements = SetupIncrement::all()->where('models','=','Shipment')->last();
        $ordermasters = OrderMaster::select('order_master.*', 'purchase_order.po_master')
        ->leftJoin('purchase_order','order_master.po_no','=','purchase_order.po_no')
        ->get();
        $orderlists = OrderList::all();
        $shipmodes = ShipMode::all();
        $markets = Market::all();
        $ordersizes = OrderSize::select('order_size.*','size.size')->leftJoin('size','order_size.size_no','=','size.size_no')->get();
        return view('shipment.create', compact('shipmodes','markets','orderlists','ordermasters','setupincements','ordersizes'));
    }

    public function find($id) {
        $shipments = Shipment::find($id);
        $orderlists = OrderMaster::select('order_master.po_no','purchase_order.po_master','shipment.*','order_list.pobuyer_no','order_master.order_trans')
        ->leftJoin('purchase_order','order_master.po_no','=','purchase_order.po_no')
        ->leftJoin('order_list','order_list.order_trans','=','order_master.order_trans')
        ->leftJoin('shipment','order_list.order_list','=','shipment.order_list')
        ->where('shipment.id','=',$id)
        ->get();
        $markets = Market::all();
        $shipmodes= ShipMode::all();
        return view('shipment.update', compact('shipments', 'orderlists','markets','shipmodes'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated Shipment ' . $request->ship_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $shipments = RafProduction::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'ship_no' => 'required|max:255|',
            'order_list' => 'required|max:255',
            'market_no' => 'required|max:255|',
            'shipmode_no' => 'required|max:255|',
            'size_no' => 'required|max:255|',
            'ship_qty' => 'required',
            'ship_date' => 'required|max:255|',
            'carton_qty' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $shipments->fill([
            'ship_no' => $request->ship_no,
            'order_list' => $request->order_list,
            'market_no' => $request->market_no,
            'shipmode_no' => $request->shipmode_no,
            'size_no' => $request->size_no,
            'ship_qty' => $request->ship_qty,
            'carton_qty' => $request->carton_qty,
            'ship_date' => $request->ship_date,
            'remark' => $request->remark,
        ]);

        $shipments->save();

        Alert::success('Update Successfully!', 'Shipment ' . $request->ship_no . ' successfully updated!');
        return redirect('shipment/index');
    }
    
    public function fetchorderlist($order_trans) {
        $orderlists   = OrderList::select('*', 'order_master.*')
        ->leftJoin('order_master', 'order_master.order_trans', '=', 'order_list.order_trans')
        ->where('order_list.order_trans', '=', $order_trans)
        ->where('order_list.void','=','false')
        ->get();
        return response()->json($orderlists);
    }

    public function fetchreadyship($order_list, $size_no) {
        $deptPicking = "DEP000000004";
        $raf_productions = DB::select('select distinct(order_size.order_list),size.size,order_list.dcpo_qty,ifnull((select sum(order_size.qty) from order_size where order_size.size_no = size.size_no and order_list.order_list = order_size.order_list),0) as size_qty,ifnull((select sum(raf_qty) from raf_production where raf_production.size_no = order_size.size_no and raf_production.order_list = order_list.order_list and raf_production.raf_dept = "' . $deptPicking . '" and void = "false"),0) as total_raf,ifnull((select sum(shipment.ship_qty) from shipment where size_no = order_size.size_no and order_list = order_list.order_list),0) as sum_ship,ifnull((select sum(raf_qty) from raf_production where raf_production.size_no = order_size.size_no and raf_production.order_list = order_list.order_list and raf_production.raf_dept = "' . $deptPicking . '"),0)-ifnull((select sum(shipment.ship_qty) from shipment where size_no = order_size.size_no and order_list = order_list.order_list),0) as ready_ship from order_size left join order_list on order_size.order_list = order_list.order_list left join size on size.size_no = order_size.size_no where order_list.order_list = "' . $order_list . '" and size.size_no = "' . $size_no . '"');
        return response()->json($raf_productions);
    }

    public function fetchcartonleft($order_list) {
        $orderlists = OrderList::select('order_list.carton_qty', DB::raw('ifnull((select sum(shipment.carton_qty) from shipment where order_list = "' . $order_list . '" and shipment.void = "false"),0) as sum_carton'),DB::raw('ifnull(((order_list.carton_qty) - ifnull((select sum(shipment.carton_qty) from shipment where order_list = "' . $order_list . '" and shipment.void = "false"),0)),0) as carton_balance'))
            ->where('order_list.order_list', '=', $order_list)
            ->get();
        return response()->json($orderlists);
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Shipment ' . $request->ship_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);

        SetupIncrement::updateOrCreate([
            'models' => 'Shipment'
        ],[
            'models' => 'Shipment',
            'last_number' => $request->ship_no,
        ]);
        Shipment::create([
            'ship_no' => $request->ship_no,
            'order_list' => $request->order_list,
            'market_no' => $request->market_no,
            'shipmode_no' => $request->shipmode_no,
            'size_no' => $request->size_no,
            'ship_qty' => $request->ship_qty,
            'carton_qty' => $request->carton_qty,
            'ship_date' => $request->ship_date,
            'remark' => $request->remark,
            'void' => 'false'
        ]);

        Alert::success('Create Successfully!', 'Shipment ' . $request->ship_no . ' successfully created!');
        return redirect()
            ->route('shipment.create');
    }
    

    public function fetchordersize($order_list) {
        $ordersizes = OrderSize::select(DB::raw('sum(order_size.qty) as qty_total'),'size.size','size.size_no')
        ->leftJoin('size', 'order_size.size_no', '=', 'size.size_no')
        ->where('order_size.order_list', '=', $order_list)
        ->where('order_size.void','=','false')
        ->groupBy('order_size.order_list','size.size','size.size_no')
        ->get();
        return response()->json($ordersizes);
    }

    public function delete($id) {
        $shipments = Shipment::find($id);    
        $shipments->delete();

        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Shipment ' . $shipments->bordir_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);
        Alert::success('Delete Successfully!', 'Shipment ' . $shipments->ship_no . ' successfully deleted!');
        return redirect('shipment/index');
    }

    
    public function void(Request $request)
    {
        $shipments = Shipment::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Void Shipment ' . $shipments->ship_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $shipments->fill([
            'void' => 'true',
        ]);

        $shipments->save();

        Alert::success('Void Successfully!', 'Shipment ' . $shipments->ship_no . ' successfully voided!');
        return redirect('shipment/index');
    }

    public function restore(Request $request)
    {
        $shipments = Shipment::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Restore Shipment ' . $shipments->ship_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $shipments->fill([
            'void' => 'false',
        ]);

        $shipments->save();

        Alert::success('Restore Successfully!', 'Shipment ' . $shipments->ship_no . ' successfully restored!');
        return redirect('shipment/index');
    }
}
