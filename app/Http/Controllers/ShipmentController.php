<?php

namespace App\Http\Controllers;

use App\Models\Market;
use App\Models\OrderList;
use App\Models\OrderMaster;
use App\Models\Shipment;
use App\Models\ShipMode;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index() {
        $shipments = Shipment::select('shipment.*','market.market_name','ship_mode.ship_name','order_list.pobuyer_no')
        ->leftJoin('market', 'shipment.market_no', '=', 'market.market_no')
        ->leftJoin('ship_mode', 'shipment.ship_no', '=', 'ship_mode.ship_no')
        ->leftJoin('order_list', 'shipment.order_list', '=', 'order_list.order_list')
        ->get();
        return view('shipment.index', compact('shipments'));
    }

    public function create() {
        $ordermasters = OrderMaster::select('order_master.*', 'purchase_order.po_master')
        ->leftJoin('purchase_order','order_master.po_no','=','purchase_order.po_no')
        ->get();
        $orderlists = OrderList::all();
        $shipmodes = ShipMode::all();
        $markets = Market::all();
        return view('shipment.create', compact('shipmodes','markets','orderlists','ordermasters'));
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
        Shipment::create([
            'order_list' => $request->order_list,
            'market_no' => $request->market_no,
            'ship_no' => $request->ship_no,
            'ship_qty' => $request->ship_qty,
            'ship_date' => $request->ship_date,
            'remark' => $request->remark,
        ]);

        return redirect()
            ->route('shipment.create')
            ->with('success', 'Shipment berhasil ditambahkan!');
    }
}
