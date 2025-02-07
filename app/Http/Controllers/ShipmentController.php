<?php

namespace App\Http\Controllers;

use App\Models\LogCiiper;
use App\Models\Market;
use App\Models\OrderList;
use App\Models\OrderMaster;
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
    public function index() {
        $shipments = Shipment::select('shipment.*','market.market_name','ship_mode.shipmode_name','order_list.pobuyer_no')
        ->leftJoin('market', 'shipment.market_no', '=', 'market.market_no')
        ->leftJoin('ship_mode', 'shipment.shipmode_no', '=', 'ship_mode.shipmode_no')
        ->leftJoin('order_list', 'shipment.order_list', '=', 'order_list.order_list')
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
        return view('shipment.create', compact('shipmodes','markets','orderlists','ordermasters','setupincements'));
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
            'ship_no' => 'required|max:225|',
            'order_list' => 'required|max:255',
            'market_no' => 'required|max:225|',
            'shipmode_no' => 'required|max:225|',
            'ship_qty' => 'required|max:225|',
            'ship_date' => 'required|max:225|',
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
            'ship_qty' => $request->ship_qty,
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
        ->get();
        return response()->json($orderlists);
    }

    public function fetchreadyship($order_list) {
        $raf_productions = RafProduction::select('raf_production.order_list','order_list.dcpo_qty', DB::raw('ifnull((select sum(raf_production.raf_qty) from raf_production where order_list = "' . $order_list . '" and raf_dept = "DEP000000004"),0) as raf_packing'), DB::raw('ifnull((select sum(shipment.ship_qty) from shipment where order_list = "' . $order_list . '"),0) as sum_ship_qty'), DB::raw('ifnull(((select sum(raf_production.raf_qty) from raf_production where order_list = "' . $order_list . '" and raf_dept = "DEP000000004") - ifnull((select sum(shipment.ship_qty) from shipment where order_list = "' . $order_list . '"),0)),0) as ship_left'))
            ->leftJoin('order_list', 'order_list.order_list', '=', 'raf_production.order_list')
            ->where('raf_production.order_list', '=', $order_list)
            ->groupBy('raf_production.order_list','order_list.dcpo_qty')
            ->get();
        return response()->json($raf_productions);
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
            'ship_qty' => $request->ship_qty,
            'ship_date' => $request->ship_date,
            'remark' => $request->remark,
        ]);

        Alert::success('Create Successfully!', 'Shipment ' . $request->ship_no . ' successfully created!');
        return redirect()
            ->route('shipment.create');
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
}
