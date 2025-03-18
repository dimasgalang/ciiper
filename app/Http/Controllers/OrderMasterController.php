<?php

namespace App\Http\Controllers;

use App\Exports\OrderMasterExport;
use App\Imports\OrderMastersImport;
use App\Models\BordirType;
use App\Models\Brand;
use App\Models\Buyer;
use App\Models\Fabrication;
use App\Models\FollowUp;
use App\Models\LogCiiper;
use App\Models\OrderList;
use App\Models\OrderMaster;
use App\Models\OrderSize;
use App\Models\ProductionPlanning;
use App\Models\PurchaseOrder;
use App\Models\RafProduction;
use App\Models\Season;
use App\Models\SetupIncrement;
use App\Models\Shipment;
use App\Models\Style;
use App\Models\WashType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class OrderMasterController extends Controller
{
    public function index(Request $request)
    {
        // $ordermasters = OrderMaster::all();
        if ($request->void) {
            $ordermasters = OrderMaster::select('order_master.*', 'season.season_cat', 'season.season_year', 'buyer.buyer_name', 'brand.brand_name', 'style_name', 'followup.fu_name', 'purchase_order.po_master', DB::raw('round(sum(if(raf_production.raf_dept = "DEP000000004", raf_production.raf_qty, 0)),2) as sum_raf_qty'))
                ->leftJoin('season', 'order_master.season_no', '=', 'season.season_no')
                ->leftJoin('buyer', 'order_master.buyer_no', '=', 'buyer.buyer_no')
                ->leftJoin('brand', 'order_master.brand_no', '=', 'brand.brand_no')
                ->leftJoin('style', 'order_master.style_no', '=', 'style.style_no')
                ->leftJoin('followup', 'order_master.fu_no', '=', 'followup.fu_no')
                ->leftJoin('raf_production', 'order_master.order_trans', '=', 'raf_production.order_trans')
                ->leftJoin('purchase_order', 'order_master.po_no', '=', 'purchase_order.po_no')
                ->groupBy('order_master.order_trans')
                ->where('order_master.void', '=', $request->void)
                ->get();
        } else {
            $ordermasters = OrderMaster::select('order_master.*', 'season.season_cat', 'season.season_year', 'buyer.buyer_name', 'brand.brand_name', 'style_name', 'followup.fu_name', 'purchase_order.po_master', DB::raw('round(sum(if(raf_production.raf_dept = "DEP000000004", raf_production.raf_qty, 0)),2) as sum_raf_qty'))
                ->leftJoin('season', 'order_master.season_no', '=', 'season.season_no')
                ->leftJoin('buyer', 'order_master.buyer_no', '=', 'buyer.buyer_no')
                ->leftJoin('brand', 'order_master.brand_no', '=', 'brand.brand_no')
                ->leftJoin('style', 'order_master.style_no', '=', 'style.style_no')
                ->leftJoin('followup', 'order_master.fu_no', '=', 'followup.fu_no')
                ->leftJoin('raf_production', 'order_master.order_trans', '=', 'raf_production.order_trans')
                ->leftJoin('purchase_order', 'order_master.po_no', '=', 'purchase_order.po_no')
                ->groupBy('order_master.order_trans')
                ->where('order_master.void', '=', 'false')
                ->get();
        }
        // return response()->json($ordermasters);
        return view('ordermaster.index', compact('ordermasters'));
    }

    public function create()
    {
        $setupincements = SetupIncrement::all()->where('models', '=', 'OrderMaster')->last();
        $seasons = Season::all();
        $buyers = Buyer::all();
        $brands = Brand::all();
        $styles = Style::all();
        $followups = FollowUp::all();
        $pos = PurchaseOrder::all();
        return view('ordermaster.create', compact('setupincements', 'seasons', 'buyers', 'brands', 'styles', 'followups', 'pos'));
    }

    public function showlist($order_trans)
    {
        $orderlists = OrderList::select('factory.factory_name', 'order_list.status', 'order_list.factory_no', 'order_list.lot_no', 'order_list.pobuyer_no', 'order_list.ex_factory_date', 'order_list.vsl_date', 'order_list.dcpo_qty', 'order_list.carton_qty', DB::raw('round(order_list.dcpo_qty/12,2) as dcpo_dzn'), DB::raw('(sum(coalesce(if(raf_production.raf_dept = "DEP000000004", raf_production.raf_qty, 0),0))-order_list.dcpo_qty) as balance'), DB::raw('round(sum(if(raf_production.raf_dept = "DEP000000004", raf_production.raf_qty, 0)),2) as sum_raf_qty'))
            ->leftJoin('order_master', 'order_master.order_trans', '=', 'order_list.order_trans')
            ->leftJoin('raf_production', 'order_list.order_list', '=', 'raf_production.order_list')
            ->leftJoin('factory', 'factory.factory_no', '=', 'order_list.factory_no')
            ->where('order_list.order_trans', '=', $order_trans)
            ->where('order_list.void', '=', 'false')
            ->groupBy('order_list.order_trans', 'order_list.factory_no', 'order_list.lot_no', 'order_list.pobuyer_no', 'order_list.ex_factory_date', 'order_list.vsl_date', 'order_list.dcpo_qty', 'factory.factory_name', 'order_list.status', 'order_list.carton_qty')
            ->get();
        // return response()->json($orderlists);
        return DataTables::of($orderlists)
            ->addIndexColumn()
            ->addColumn('ex_factory_date_formated', function ($row) {
                return date('dmy', strtotime($row->ex_factory_date));
            })
            ->rawColumns(['ex_factory_date_formated'])
            ->addColumn('vsl_date_formated', function ($row) {
                return date('dmy', strtotime($row->vsl_date));
            })
            ->rawColumns(['vsl_date_formated'])
            ->addColumn('statusbadge', function ($row) {
                $statusBadge = '';
                if ($row->status == 'Finish') {
                    $statusBadge = '<center><a class="btn btn-success btn-circle btn-sm"><i class="fas fa-check"></i></a></center>';
                } else {
                    $statusBadge = '<center><a class="btn btn-danger btn-circle btn-sm"><i class="fas fa-times"></i></a></center>';
                }
                return $statusBadge;
            })
            ->rawColumns(['statusbadge'])
            ->make(true);
    }

    public function showordersize($order_trans)
    {
        $ordersizes = DB::select('select order_list.order_list,order_list.lot_no,order_list.pobuyer_no,order_list.dcpo_qty,ifnull(sum(case when size.size ="XS" then order_size.qty end),0) as "XS",ifnull(sum(case when size.size ="S" then order_size.qty end),0) as "S",ifnull(sum(case when size.size ="M" then order_size.qty end),0) as "M",ifnull(sum(case when size.size ="L" then order_size.qty end),0) as "L",ifnull(sum(case when size.size ="XL" then order_size.qty end),0) as "XL",ifnull(sum(case when size.size ="XXL" then order_size.qty end),0) as "XXL",(select sum(qty) from order_size t2 where t2.order_list = order_list.order_list) as total from order_size left join order_list on order_list.order_list = order_size.order_list left join size on size.size_no = order_size.size_no where order_list.order_trans = "' . $order_trans .  '" and order_size.void = "false" group by order_list.order_list,order_list.pobuyer_no,order_list.lot_no,order_list.dcpo_qty');
        // return response()->json($ordersizes);
        return DataTables::of($ordersizes)
            ->addIndexColumn()
            ->make(true);
    }

    public function showrafproduction($order_trans)
    {
        $rafproductions = RafProduction::select('raf_production.*', 'order_list.*', 'order_master.*')
            ->leftJoin('order_list', 'order_list.order_list', '=', 'raf_production.order_list')
            ->leftJoin('order_master', 'order_master.order_trans', '=', 'order_list.order_trans')
            ->where('order_list.order_trans', '=', $order_trans)
            ->where('raf_production.raf_dept', '=', 'DEP000000004')
            ->where('raf_production.void', '=', 'false')
            ->get();
        // return response()->json($orderlists);
        return DataTables::of($rafproductions)->addIndexColumn()->make(true);
    }

    public function showrafcutting($order_trans, Request $request)
    {
        if ($request->ajax()) {
            $rafcuttings = DB::select('select distinct(order_list.order_list),order_list.lot_no,order_list.pobuyer_no,order_list.dcpo_qty,size.size,raf_production.raf_date,ifnull((select sum(order_size.qty) from order_size where order_size.order_list = raf_production.order_list and order_size.size_no = size.size_no),0) as size_qty,raf_production.raf_qty,((select sum(raf_qty) from raf_production t2 where t2.raf_date <= raf_production.raf_date and t2.raf_dept = "DEP000000001" and t2.size_no = raf_production.size_no and t2.order_list = order_list.order_list order by order_list,size_no,raf_date)) as totalraf,((select sum(raf_qty) from raf_production t2 where t2.raf_date <= raf_production.raf_date and t2.raf_dept = "DEP000000001" and t2.size_no = raf_production.size_no and t2.order_list = order_list.order_list  order by order_list,size_no,raf_date)-ifnull((select sum(order_size.qty) from order_size where order_size.order_list = raf_production.order_list and order_size.size_no = size.size_no),0)) as balance from raf_production left join order_size on raf_production.size_no = order_size.size_no left join order_list on raf_production.order_list = order_list.order_list left join size on order_size.size_no = size.size_no where raf_production.raf_dept = "DEP000000001" and raf_production.order_trans = "' . $order_trans . '" and raf_production.void = "false" order by order_list.order_list,raf_date');
            // return response()->json($rafcuttings);
            return DataTables::of($rafcuttings)
                ->addIndexColumn()
                ->addColumn('raf_date_formated', function ($row) {
                    return date('dmy', strtotime($row->raf_date));
                })
                ->rawColumns(['raf_date_formated'])
                ->addColumn('balance_color', function ($row) {
                    if ($row->balance < 0) {
                        return '<font color="red"> ' . $row->balance . '</font>';
                    } else {
                        return '<font color="green"> ' . $row->balance . '</font>';
                    }
                })
                ->rawColumns(['balance_color'])
                ->filter(function ($instance) use ($request) {
                    if ($request->filled('fromdateCutting') && $request->filled('todateCutting') && $request->filled('searchCutting')) {
                        $searchCutting = $request->get('searchCutting');
                        $instance
                            ->where('raf_production.raf_date', '>=', $request->get('fromdateCutting'))
                            ->where('raf_production.raf_date', '<=', $request->get('todateCutting'))
                            ->where('raf_production.raf_dept', '=', 'DEP000000001')
                            ->where(function ($query) use ($searchCutting) {
                                $query->orWhere('order_list.lot_no', 'LIKE', "%$searchCutting%")
                                    ->orWhere('order_list.pobuyer_no', 'LIKE', "%$searchCutting%")
                                    ->orWhere('order_size.qty', 'LIKE', "%$searchCutting%")
                                    ->orWhere('size.size', 'LIKE', "%$searchCutting%")
                                    ->orWhere('raf_production.raf_qty', 'LIKE', "%$searchCutting%");
                            });
                    }
                })->make(true);
        }
    }

    public function showrafsewing($order_trans, Request $request)
    {
        if ($request->ajax()) {
            $rafcuttings = DB::select('select distinct(order_list.order_list),order_list.lot_no,order_list.pobuyer_no,order_list.dcpo_qty,size.size,raf_production.raf_date,ifnull((select sum(order_size.qty) from order_size where order_size.order_list = raf_production.order_list and order_size.size_no = size.size_no),0) as size_qty,raf_production.raf_qty,((select sum(raf_qty) from raf_production t2 where t2.raf_date <= raf_production.raf_date and t2.raf_dept = "DEP000000002" and t2.size_no = raf_production.size_no and t2.order_list = order_list.order_list order by order_list,size_no,raf_date)) as totalraf,((select sum(raf_qty) from raf_production t2 where t2.raf_date <= raf_production.raf_date and t2.raf_dept = "DEP000000002" and t2.size_no = raf_production.size_no and t2.order_list = order_list.order_list  order by order_list,size_no,raf_date)-ifnull((select sum(order_size.qty) from order_size where order_size.order_list = raf_production.order_list and order_size.size_no = size.size_no),0)) as balance from raf_production left join order_size on raf_production.size_no = order_size.size_no left join order_list on raf_production.order_list = order_list.order_list left join size on order_size.size_no = size.size_no where raf_production.raf_dept = "DEP000000002" and raf_production.order_trans = "' . $order_trans . '" and raf_production.void = "false" order by order_list.order_list,raf_date');
            // return response()->json($rafcuttings);
            return DataTables::of($rafcuttings)
                ->addIndexColumn()
                ->addColumn('raf_date_formated', function ($row) {
                    return date('dmy', strtotime($row->raf_date));
                })
                ->rawColumns(['raf_date_formated'])
                ->addColumn('balance_color', function ($row) {
                    if ($row->balance < 0) {
                        return '<font color="red"> ' . $row->balance . '</font>';
                    } else {
                        return '<font color="green"> ' . $row->balance . '</font>';
                    }
                })
                ->rawColumns(['balance_color'])
                ->filter(function ($instance) use ($request) {
                    if ($request->filled('fromdateCutting') && $request->filled('todateCutting') && $request->filled('searchCutting')) {
                        $searchCutting = $request->get('searchCutting');
                        $instance
                            ->where('raf_production.raf_date', '>=', $request->get('fromdateCutting'))
                            ->where('raf_production.raf_date', '<=', $request->get('todateCutting'))
                            ->where('raf_production.raf_dept', '=', 'DEP000000002')
                            ->where(function ($query) use ($searchCutting) {
                                $query->orWhere('order_list.lot_no', 'LIKE', "%$searchCutting%")
                                    ->orWhere('order_list.pobuyer_no', 'LIKE', "%$searchCutting%")
                                    ->orWhere('order_size.qty', 'LIKE', "%$searchCutting%")
                                    ->orWhere('size.size', 'LIKE', "%$searchCutting%")
                                    ->orWhere('raf_production.raf_qty', 'LIKE', "%$searchCutting%");
                            });
                    }
                })->make(true);
        }
    }
    public function showrafiron($order_trans, Request $request)
    {
        if ($request->ajax()) {
            $rafcuttings = DB::select('select distinct(order_list.order_list),order_list.lot_no,order_list.pobuyer_no,order_list.dcpo_qty,size.size,raf_production.raf_date,ifnull((select sum(order_size.qty) from order_size where order_size.order_list = raf_production.order_list and order_size.size_no = size.size_no),0) as size_qty,raf_production.raf_qty,((select sum(raf_qty) from raf_production t2 where t2.raf_date <= raf_production.raf_date and t2.raf_dept = "DEP000000003" and t2.size_no = raf_production.size_no and t2.order_list = order_list.order_list order by order_list,size_no,raf_date)) as totalraf,((select sum(raf_qty) from raf_production t2 where t2.raf_date <= raf_production.raf_date and t2.raf_dept = "DEP000000003" and t2.size_no = raf_production.size_no and t2.order_list = order_list.order_list  order by order_list,size_no,raf_date)-ifnull((select sum(order_size.qty) from order_size where order_size.order_list = raf_production.order_list and order_size.size_no = size.size_no),0)) as balance from raf_production left join order_size on raf_production.size_no = order_size.size_no left join order_list on raf_production.order_list = order_list.order_list left join size on order_size.size_no = size.size_no where raf_production.raf_dept = "DEP000000003" and raf_production.order_trans = "' . $order_trans . '" and raf_production.void = "false" order by order_list.order_list,raf_date');
            // return response()->json($rafcuttings);
            return DataTables::of($rafcuttings)
                ->addIndexColumn()
                ->addColumn('raf_date_formated', function ($row) {
                    return date('dmy', strtotime($row->raf_date));
                })
                ->rawColumns(['raf_date_formated'])
                ->addColumn('balance_color', function ($row) {
                    if ($row->balance < 0) {
                        return '<font color="red"> ' . $row->balance . '</font>';
                    } else {
                        return '<font color="green"> ' . $row->balance . '</font>';
                    }
                })
                ->rawColumns(['balance_color'])
                ->filter(function ($instance) use ($request) {
                    if ($request->filled('fromdateCutting') && $request->filled('todateCutting') && $request->filled('searchCutting')) {
                        $searchCutting = $request->get('searchCutting');
                        $instance
                            ->where('raf_production.raf_date', '>=', $request->get('fromdateCutting'))
                            ->where('raf_production.raf_date', '<=', $request->get('todateCutting'))
                            ->where('raf_production.raf_dept', '=', 'DEP000000003')
                            ->where(function ($query) use ($searchCutting) {
                                $query->orWhere('order_list.lot_no', 'LIKE', "%$searchCutting%")
                                    ->orWhere('order_list.pobuyer_no', 'LIKE', "%$searchCutting%")
                                    ->orWhere('order_size.qty', 'LIKE', "%$searchCutting%")
                                    ->orWhere('size.size', 'LIKE', "%$searchCutting%")
                                    ->orWhere('raf_production.raf_qty', 'LIKE', "%$searchCutting%");
                            });
                    }
                })->make(true);
        }
    }

    public function showrafpacking($order_trans, Request $request)
    {
        if ($request->ajax()) {
            $rafcuttings = DB::select('select distinct(order_list.order_list),order_list.lot_no,order_list.pobuyer_no,order_list.dcpo_qty,size.size,raf_production.raf_date,ifnull((select sum(order_size.qty) from order_size where order_size.order_list = raf_production.order_list and order_size.size_no = size.size_no),0) as size_qty,raf_production.raf_qty,((select sum(raf_qty) from raf_production t2 where t2.raf_date <= raf_production.raf_date and t2.raf_dept = "DEP000000004" and t2.size_no = raf_production.size_no and t2.order_list = order_list.order_list order by order_list,size_no,raf_date)) as totalraf,((select sum(raf_qty) from raf_production t2 where t2.raf_date <= raf_production.raf_date and t2.raf_dept = "DEP000000004" and t2.size_no = raf_production.size_no and t2.order_list = order_list.order_list  order by order_list,size_no,raf_date)-ifnull((select sum(order_size.qty) from order_size where order_size.order_list = raf_production.order_list and order_size.size_no = size.size_no),0)) as balance from raf_production left join order_size on raf_production.size_no = order_size.size_no left join order_list on raf_production.order_list = order_list.order_list left join size on order_size.size_no = size.size_no where raf_production.raf_dept = "DEP000000004" and raf_production.order_trans = "' . $order_trans . '" and raf_production.void = "false" order by order_list.order_list,raf_date');
            // return response()->json($rafcuttings);
            return DataTables::of($rafcuttings)
                ->addIndexColumn()
                ->addColumn('raf_date_formated', function ($row) {
                    return date('dmy', strtotime($row->raf_date));
                })
                ->rawColumns(['raf_date_formated'])
                ->addColumn('balance_color', function ($row) {
                    if ($row->balance < 0) {
                        return '<font color="red"> ' . $row->balance . '</font>';
                    } else {
                        return '<font color="green"> ' . $row->balance . '</font>';
                    }
                })
                ->rawColumns(['balance_color'])
                ->filter(function ($instance) use ($request) {
                    if ($request->filled('fromdateCutting') && $request->filled('todateCutting') && $request->filled('searchCutting')) {
                        $searchCutting = $request->get('searchCutting');
                        $instance
                            ->where('raf_production.raf_date', '>=', $request->get('fromdateCutting'))
                            ->where('raf_production.raf_date', '<=', $request->get('todateCutting'))
                            ->where('raf_production.raf_dept', '=', 'DEP000000004')
                            ->where(function ($query) use ($searchCutting) {
                                $query->orWhere('order_list.lot_no', 'LIKE', "%$searchCutting%")
                                    ->orWhere('order_list.pobuyer_no', 'LIKE', "%$searchCutting%")
                                    ->orWhere('order_size.qty', 'LIKE', "%$searchCutting%")
                                    ->orWhere('size.size', 'LIKE', "%$searchCutting%")
                                    ->orWhere('raf_production.raf_qty', 'LIKE', "%$searchCutting%");
                            });
                    }
                })->make(true);
        }
    }

    public function showshipment($order_trans)
    {
        $shipments = DB::select('select order_list.pobuyer_no,market.market_name,ship_mode.shipmode_name,shipment.ship_date,size.size,shipment.remark,shipment.carton_qty,ifnull((select sum(order_size.qty) from order_size where order_size.order_list = order_list.order_list and order_size.size_no = size.size_no),0) as size_qty,shipment.ship_qty,(ifnull((select sum(ship_qty) from shipment t2 where t2.ship_date <= shipment.ship_date and t2.size_no = shipment.size_no and t2.order_list = order_list.order_list order by order_list,size_no,ship_date),0)-ifnull((select sum(order_size.qty) from order_size where order_size.order_list = shipment.order_list and order_size.size_no = size.size_no),0)) as balance,(ifnull((select sum(carton_qty) from shipment t2 where t2.ship_date <= shipment.ship_date and t2.size_no = shipment.size_no and t2.order_list = order_list.order_list order by order_list,size_no,ship_date),0)-ifnull((select sum(order_list.carton_qty) from order_list where order_list.order_list = shipment.order_list),0)) as carton_balance from shipment left join order_list on order_list.order_list = shipment.order_list left join ship_mode on shipment.shipmode_no = ship_mode.shipmode_no left join market on shipment.market_no = market.market_no left join size on shipment.size_no = size.size_no where order_list.order_trans = "' . $order_trans . '"');
        // return response()->json($orderlists);
        return DataTables::of($shipments)
            ->addIndexColumn()
            ->addColumn('ship_date_formated', function ($row) {
                return date('dmy', strtotime($row->ship_date));
            })
            ->rawColumns(['ship_date_formated'])
            ->addColumn('balance_color', function ($row) {
                if ($row->balance < 0) {
                    return '<font color="red"> ' . $row->balance . '</font>';
                } else {
                    return '<font color="green"> ' . $row->balance . '</font>';
                }
            })
            ->addColumn('carton_balance_color', function ($row) {
                if ($row->carton_balance < 0) {
                    return '<font color="red"> ' . $row->carton_balance . '</font>';
                } else {
                    return '<font color="green"> ' . $row->carton_balance . '</font>';
                }
            })
            ->rawColumns(['balance_color', 'carton_balance_color'])
            ->make(true);
    }

    public function showfab($order_trans)
    {
        $fabrication = Fabrication::select('fabrication.*', 'fabric_mill.*')
            ->leftJoin('fabric_mill', 'fabric_mill.fabmill_no', '=', 'fabrication.fabmill_no')
            ->where('fabrication.order_trans', '=', $order_trans)
            ->where('fabrication.void', '=', 'false')
            ->get();
        return response()->json($fabrication);
    }

    public function showstyle($order_trans)
    {
        $style = Style::select('style.*', 'order_master.style_no')
            ->leftJoin('order_master', 'order_master.style_no', '=', 'style.style_no')
            ->where('order_master.order_trans', '=', $order_trans)
            ->where('style.void', '=', 'false')
            ->get();
        return response()->json($style);
    }

    public function showproductionplanning($order_trans)
    {
        $productionplannings = DB::select('select distinct production_planning.*,purchase_order.po_master,order_list.pobuyer_no,order_list.dcpo_qty,(select ifnull(round(avg(percentage),0),0) from proplan_detail t2 where t2.order_list = production_planning.order_list and category_no = "CAT000000001") as average_acc,(select ifnull(round(avg(percentage),0),0) from proplan_detail t2 where t2.order_list = production_planning.order_list and category_no = "CAT000000002") as average_sample,(select ifnull(round(avg(percentage),0),0) from proplan_detail t2 where t2.order_list = production_planning.order_list and category_no = "CAT000000003") as average_fabric,(select ifnull(round(avg(percentage),0),0) from proplan_detail t2 where t2.order_list = production_planning.order_list and category_no = "CAT000000004") as average_mi,(select ifnull(round(sum(raf_qty),0),0) from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000001") as sum_raf_cut,(select ifnull(round(sum(raf_qty),0),0)-order_list.dcpo_qty from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000001") as balance_cut, (select ifnull(round(sum(raf_qty),0),0) from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000002") as sum_raf_sew,(select ifnull(round(sum(raf_qty),0),0)-order_list.dcpo_qty from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000002") as balance_sew,(select ifnull(round(sum(raf_qty),0),0) from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000003") as sum_raf_iron,(select ifnull(round(sum(raf_qty),0),0)-order_list.dcpo_qty from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000003") as balance_iron,(select ifnull(round(sum(raf_qty),0),0) from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000004") as sum_raf_pack,(select ifnull(round(sum(raf_qty),0),0)-order_list.dcpo_qty from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000004") as balance_pack from production_planning left join proplan_detail on production_planning.order_list = proplan_detail.order_list left join order_master on production_planning.order_trans = order_master.order_trans left join order_list on production_planning.order_list = order_list.order_list left join purchase_order on order_master.po_no = purchase_order.po_no left join raf_production on raf_production.order_list = production_planning.order_list where production_planning.void = "false"');
        return DataTables::of($productionplannings)
            ->addIndexColumn()
            ->addColumn('samplebadge', function ($row) {
                $sampleBadge = '';
                if ($row->average_sample < 50) {
                    $sampleBadge = '<center><a class="btn btn-danger btn-icon-split btn-sm btn-show-detail-sample"><span class="text">' . $row->average_sample . '%</span></a></center>';
                } else if (($row->average_sample >= 50) && ($row->average_sample < 100)) {
                    $sampleBadge = '<center><a class="btn btn-warning btn-icon-split btn-sm btn-show-detail-sample"><span class="text">' . $row->average_sample . '%</span></a></center>';
                } else if ($row->average_sample == 100) {
                    $sampleBadge = '<center><a class="btn btn-success btn-icon-split btn-sm btn-show-detail-sample"><span class="text">' . $row->average_sample . '%</span></a></center>';
                }
                return $sampleBadge;
            })
            ->addColumn('mibadge', function ($row) {
                $miBadge = '';
                if ($row->average_mi < 50) {
                    $miBadge = '<center><a class="btn btn-danger btn-icon-split btn-sm btn-show-detail-mi"><span class="text">' . $row->average_mi . '%</span></a></center>';
                } else if (($row->average_mi >= 50) && ($row->average_mi < 100)) {
                    $miBadge = '<center><a class="btn btn-warning btn-icon-split btn-sm btn-show-detail-mi"><span class="text">' . $row->average_mi . '%</span></a></center>';
                } else if ($row->average_mi == 100) {
                    $miBadge = '<center><a class="btn btn-success btn-icon-split btn-sm btn-show-detail-mi"><span class="text">' . $row->average_mi . '%</span></a></center>';
                }
                return $miBadge;
            })
            ->addColumn('fabbadge', function ($row) {
                $fabBadge = '';
                if ($row->average_fabric < 50) {
                    $fabBadge = '<center><a class="btn btn-danger btn-icon-split btn-sm btn-show-detail-fab"><span class="text">' . $row->average_fabric . '%</span></a></center>';
                } else if (($row->average_fabric >= 50) && ($row->average_fabric < 100)) {
                    $fabBadge = '<center><a class="btn btn-warning btn-icon-split btn-sm btn-show-detail-fab"><span class="text">' . $row->average_fabric . '%</span></a></center>';
                } else if ($row->average_fabric == 100) {
                    $fabBadge = '<center><a class="btn btn-success btn-icon-split btn-sm btn-show-detail-sampfable"><span class="text">' . $row->average_fabric . '%</span></a></center>';
                }
                return $fabBadge;
            })
            ->addColumn('accbadge', function ($row) {
                $accBadge = '';
                if ($row->average_acc < 50) {
                    $accBadge = '<center><a class="btn btn-danger btn-icon-split btn-sm btn-show-detail-acc"><span class="text">' . $row->average_acc . '%</span></a></center>';
                } else if (($row->average_acc >= 50) && ($row->average_acc < 100)) {
                    $accBadge = '<center><a class="btn btn-warning btn-icon-split btn-sm btn-show-detail-acc"><span class="text">' . $row->average_acc . '%</span></a></center>';
                } else if ($row->average_acc == 100) {
                    $accBadge = '<center><a class="btn btn-success btn-icon-split btn-sm btn-show-detail-acc"><span class="text">' . $row->average_acc . '%</span></a></center>';
                }
                return $accBadge;
            })
            ->addColumn('balance_cut_colored', function ($row) {
                if ($row->balance_cut < 0) {
                    return '<font color="red"> ' . $row->balance_cut . '</font>';
                } else {
                    return '<font color="green"> ' . $row->balance_cut . '</font>';
                }
            })
            ->addColumn('balance_sew_colored', function ($row) {
                if ($row->balance_sew < 0) {
                    return '<font color="red"> ' . $row->balance_sew . '</font>';
                } else {
                    return '<font color="green"> ' . $row->balance_sew . '</font>';
                }
            })
            ->addColumn('balance_iron_colored', function ($row) {
                if ($row->balance_iron < 0) {
                    return '<font color="red"> ' . $row->balance_iron . '</font>';
                } else {
                    return '<font color="green"> ' . $row->balance_iron . '</font>';
                }
            })
            ->addColumn('balance_pack_colored', function ($row) {
                if ($row->balance_pack < 0) {
                    return '<font color="red"> ' . $row->balance_pack . '</font>';
                } else {
                    return '<font color="green"> ' . $row->balance_pack . '</font>';
                }
            })
            ->addColumn('fab_date_formated', function ($row) {
                return date('dmy', strtotime($row->fab_date));
            })
            ->addColumn('acc_date_formated', function ($row) {
                return date('dmy', strtotime($row->acc_date));
            })
            ->addColumn('bordir_approve_formated', function ($row) {
                return date('dmy', strtotime($row->bordir_approve));
            })
            ->addColumn('pattern_date_formated', function ($row) {
                return date('dmy', strtotime($row->pattern_date));
            })
            ->addColumn('sampletest_date_formated', function ($row) {
                return date('dmy', strtotime($row->sampletest_date));
            })
            ->addColumn('marker_date_formated', function ($row) {
                return date('dmy', strtotime($row->marker_date));
            })
            ->addColumn('pilotrun_date_formated', function ($row) {
                return date('dmy', strtotime($row->pilotrun_date));
            })
            ->addColumn('ppm_date_formated', function ($row) {
                return date('dmy', strtotime($row->ppm_date));
            })
            ->addColumn('startcut_date_formated', function ($row) {
                return date('dmy', strtotime($row->startcut_date));
            })
            ->addColumn('finishcut_date_formated', function ($row) {
                return date('dmy', strtotime($row->finishcut_date));
            })
            ->addColumn('startsew_date_formated', function ($row) {
                return date('dmy', strtotime($row->startsew_date));
            })
            ->addColumn('finishsew_date_formated', function ($row) {
                return date('dmy', strtotime($row->finishsew_date));
            })
            ->addColumn('finishpack_date_formated', function ($row) {
                return date('dmy', strtotime($row->finishpack_date));
            })
            ->rawColumns(['samplebadge', 'mibadge', 'fabbadge', 'accbadge', 'balance_cut_colored', 'balance_sew_colored', 'balance_iron_colored', 'balance_pack_colored', 'fab_date_formated', 'acc_date_formated', 'bordir_approve_formated', 'pattern_date_formated', 'sampletest_date_formated', 'marker_date_formated', 'pilotrun_date_formated', 'ppm_date_formated', 'startcut_date_formated', 'finishcut_date_formated', 'startsew_date_formated', 'finishsew_date_formated', 'finishpack_date_formated'])
            ->make(true);
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/', $nama_file);
        $import = Excel::import(new OrderMastersImport(), storage_path('app/public/excel/' . $nama_file));
        Storage::delete($path);

        if ($import) {
            Alert::success('Import Successfully!', 'Order Master data successfully imported!');
            return redirect()->intended('ordermaster/index');
        } else {
            return redirect()->intended('ordermaster/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Order Master ' . $request->order_trans;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);

        $request->validate([
            'sketch_file' => 'required|mimes:jpg,png|max:10240'
        ]);

        $file = $request->file('sketch_file');
        $fileName = $file->getClientOriginalName();
        $file->storeAs('', $fileName, 'sketch_uploads');

        SetupIncrement::updateOrCreate([
            'models' => 'OrderMaster'
        ], [
            'models' => 'OrderMaster',
            'last_number' => $request->order_trans,
        ]);
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
            'void' => 'false'
        ]);

        Alert::success('Create Successfully!', 'Order Master ' . $request->order_trans . ' successfully created!');
        return redirect()
            ->route('ordermaster.create');
    }

    public function delete($id)
    {
        $ordermasters = OrderMaster::find($id);
        $ordermasters->delete();

        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Order Master ' . $ordermasters->order_trans;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);

        Storage::disk('sketch_uploads')->delete($ordermasters->id);
        Alert::success('Delete Successfully!', 'Order Master ' . $ordermasters->order_trans . ' successfully deleted!');
        return redirect('ordermaster/index');
    }

    public function find($id)
    {
        $ordermasters = OrderMaster::find($id);
        $seasons = Season::all();
        $buyers = Buyer::all();
        $brands = Brand::all();
        $styles = Style::all();
        $followups = FollowUp::all();
        $pos = PurchaseOrder::all();
        return view('ordermaster.update', compact('ordermasters', 'seasons', 'buyers', 'brands', 'styles', 'followups', 'pos'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated Order Master ' . $request->order_trans;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $ordermasters = OrderMaster::findOrFail($request->id);

        if ($request->hasFile('sketch_file')) {
            $request->validate([
                'sketch_file' => 'required|mimes:jpg,png,jpeg|'
            ]);

            $file = $request->file('sketch_file');
            $fileName = $file->getClientOriginalName();
            $file->storeAs('', $fileName, 'sketch_uploads');

            $validator = Validator::make($request->all(), [
                'order_trans' => 'required|max:255',
                'season_no' => 'required|max:255|',
                'buyer_no' => 'required|max:255|',
                'brand_no' => 'required|max:255|',
                'style_no' => 'required|max:255|',
                'po_no' => 'required|max:255|',
                'qty_order' => 'required',
                'qty_ocf' => 'required',
                'fu_no' => 'required|max:255|',
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
        } else {
            $validator = Validator::make($request->all(), [
                'order_trans' => 'required|max:255',
                'season_no' => 'required|max:255|',
                'buyer_no' => 'required|max:255|',
                'brand_no' => 'required|max:255|',
                'style_no' => 'required|max:255|',
                'po_no' => 'required',
                'qty_order' => 'required',
                'qty_ocf' => 'required|max:255|',
                'fu_no' => 'required|max:255|',
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

        Alert::success('Update Successfully!', 'Order Master ' . $request->order_trans . ' successfully updated!');
        return redirect('ordermaster/index')->with(['success' => 'Order Master berhasil diupdate!']);
    }

    public function fetchbrand($buyer_no)
    {
        $brands   = Brand::select('*')
            ->where('buyer_no', '=', $buyer_no)
            ->get();
        return response()->json($brands);
    }

    public function fetchstyle($brand_no)
    {
        $styles   = Style::select('*')
            ->where('brand_no', '=', $brand_no)
            ->get();
        return response()->json($styles);
    }

    public function export_excel()
    {
        return Excel::download(new OrderMasterExport, 'All Order Master.xlsx');
    }


    public function void(Request $request)
    {
        $ordermasters = OrderMaster::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Void Order Master ' . $ordermasters->order_trans;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $ordermasters->fill([
            'void' => 'true',
        ]);

        $ordermasters->save();

        Alert::success('Void Successfully!', 'Order Master ' . $ordermasters->order_trans . ' successfully voided!');
        return redirect('ordermaster/index');
    }

    public function restore(Request $request)
    {
        $ordermasters = OrderMaster::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Restore Order Master ' . $ordermasters->order_trans;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $ordermasters->fill([
            'void' => 'false',
        ]);

        $ordermasters->save();

        Alert::success('Restore Successfully!', 'Order Master ' . $ordermasters->order_trans . ' successfully restored!');
        return redirect('ordermaster/index');
    }
}
