<?php

namespace App\Http\Controllers;

use App\Imports\OrderListsImport;
use App\Models\BordirType;
use App\Models\Brand;
use App\Models\Buyer;
use App\Models\Fabrication;
use App\Models\Factory;
use App\Models\FollowUp;
use App\Models\LogCiiper;
use App\Models\OrderList;
use App\Models\OrderMaster;
use App\Models\OrderSize;
use App\Models\RafProduction;
use App\Models\Season;
use App\Models\SetupIncrement;
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

class OrderListController extends Controller
{
    public function index(Request $request) {
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
        ->where('order_list.void','=',$request->void)
        ->get();
        // dd($orderlists);
        return view('orderlist.index', compact('orderlists'));
    }
    
    public function showfab($order_trans) {
        
        $fabrication = Fabrication::select('*', 'fabric_mill.*')
        ->leftJoin('fabric_mill', 'fabric_mill.fabmill_no', '=', 'fabrication.fabmill_no')
        ->where('fabrication.order_trans', '=', $order_trans)
        ->where('fabrication.void','=','false')
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
            Alert::success('Import Successfully!', 'Order List data successfully imported!');
            return redirect()->intended('orderlist/index');
        } else {
            return redirect()->intended('orderlist/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create() {
        $setupincements = SetupIncrement::all()->where('models','=','OrderList')->last();
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
        return view('orderlist.create', compact('setupincements','ordermasters','seasons','buyers','brands','styles','followups','factorys','washtypes','bordirtypes'));
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Order List ' . $request->order_list;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);
        SetupIncrement::updateOrCreate([
            'models' => 'OrderList'
        ],[
            'models' => 'OrderList',
            'last_number' => $request->order_list,
        ]);
        OrderList::create([
            'order_trans' => $request->order_trans,
            'order_list' => $request->order_list,
            'factory_no' => $request->factory_no,
            'lot_no' => $request->lot_no,
            'pobuyer_no' => $request->pobuyer_no,
            'dcpo_qty' => $request->dcpo_qty,
            'carton_qty' => $request->carton_qty,
            'ex_factory_date' => $request->ex_factory_date,
            'vsl_date' => $request->vsl_date,
            'wash_no' => $request->wash_no,
            'bordir_no' => $request->bordir_no,
            'line' => $request->line,
            'target_qty' => $request->target_qty,
            'production_day' => $request->production_day,
            'smv' => $request->smv,
            'void' => 'false'
        ]);

        Alert::success('Create Successfully!', 'Order List ' . $request->order_list . ' successfully created!');
        return redirect()
            ->route('orderlist.create');
    }

    public function delete($id) {
        $orderlists = OrderList::find($id);    
        $orderlists->delete();
        
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Order List ' . $orderlists->order_list;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);
        Alert::success('Delete Successfully!', 'Order List ' . $orderlists->order_list . ' successfully deleted!');
        return redirect('orderlist/index');
    }
    
    public function finish(Request $request) {
        $orderlists = OrderList::findOrFail($request->id);
        $orderlists->fill([
            'status' => 'Finish',
        ]);

        $orderlists->save();

        Alert::success('Finish Successfully!', 'Order List ' . $request->order_list . ' successfully finish!');
        return redirect('orderlist/index');
    }

    public function find($id) {
        $orderlists = OrderList::find($id);
        $ordermasters = OrderMaster::select('order_master.po_no','purchase_order.po_master','order_list.*')
        ->leftJoin('purchase_order','order_master.po_no','=','purchase_order.po_no')
        ->leftJoin('order_list','order_master.order_trans','=','order_list.order_trans')
        ->where('order_list.id','=',$id)
        ->get();
        $seasons = Season::all();
        $buyers = Buyer::all();
        $brands = Brand::all();
        $styles = Style::all();
        $followups = FollowUp::all();
        $factorys = Factory::all();
        $washtypes = WashType::all();
        $bordirtypes = BordirType::all();
        return view('orderlist.update', compact('orderlists','ordermasters','seasons','buyers','brands','styles','followups','factorys','washtypes','bordirtypes'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated Order List ' . $request->order_list;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $orderlists = OrderList::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'order_trans' => 'required|max:255',
            'order_list' => 'required|max:255|',
            'factory_no' => 'required|max:255|',
            'lot_no' => 'required|max:255|',
            'pobuyer_no' => 'required|max:255|',
            'dcpo_qty' => 'required',
            'carton_qty' => 'required',
            'ex_factory_date' => 'required|max:255|',
            'vsl_date' => 'required|max:255|',
            'wash_no' => 'required|max:255|',
            'bordir_no' => 'required|max:255|',
            'line' => 'required',
            'target_qty' => 'required',
            'production_day' => 'required',
            'smv' => 'required',
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
            'carton_qty' => $request->carton_qty,
            'ex_factory_date' => $request->ex_factory_date,
            'vsl_date' => $request->vsl_date,
            'wash_no' => $request->wash_no,
            'bordir_no' => $request->bordir_no,
            'line' => $request->line,
            'target_qty' => $request->target_qty,
            'production_day' => $request->production_day,
            'smv' => $request->smv,
        ]);

        $orderlists->save();

        Alert::success('Update Successfully!', 'Order List ' . $request->order_list . ' successfully updated!');
        return redirect('orderlist/index');
    }
    

    public function fetchorderleft($order_trans) {
        $order_lists = OrderMaster::select('order_master.order_trans','order_master.qty_order', DB::raw('ifnull((select sum(dcpo_qty) from order_list where order_trans = order_master.order_trans and order_list.void = "false"),0) as sum_dcpo_qty'), DB::raw('order_master.qty_order-ifnull((select sum(dcpo_qty) from order_list where order_trans = order_master.order_trans and order_list.void = "false"),0) as qty_left'))
        ->leftJoin('order_list', 'order_list.order_trans', '=', 'order_master.order_trans')
        ->where('order_master.order_trans', '=', $order_trans)
        ->get();
        return response()->json($order_lists);
    }

    // public function showordersize($order_list) {
    //     $ordersizes = OrderSize::select('size.size','order_list.pobuyer_no','order_list.lot_no','order_list.dcpo_qty','order_size.qty',DB::raw('(select sum(qty) from order_size t2 where t2.order_list = "'. $order_list .'") as sum_qty'))
    //         ->leftJoin('order_list','order_size.order_list','=','order_list.order_list')
    //         ->leftJoin('size','order_size.size_no','=','size.size_no')
    //         ->where('order_size.order_list','=',$order_list)
    //         ->get();
    //         return DataTables::of($ordersizes)->addIndexColumn()->make(true);
    // }

    // Pivot
    public function showordersize($order_list) {
        $ordersizes = OrderSize::select('order_list.pobuyer_no','order_list.lot_no','order_list.dcpo_qty',DB::raw('ifnull(sum(case when size.size ="XS" then order_size.qty end),0) as "XS"'),DB::raw('ifnull(sum(case when size.size ="S" then order_size.qty end),0) as "S"'),DB::raw('ifnull(sum(case when size.size ="M" then order_size.qty end),0) as "M"'),DB::raw('ifnull(sum(case when size.size ="L" then order_size.qty end),0) as "L"'),DB::raw('ifnull(sum(case when size.size ="XL" then order_size.qty end),0) as "XL"'),DB::raw('ifnull(sum(case when size.size ="XXL" then order_size.qty end),0) as "XXL"'))
            ->leftJoin('order_list','order_size.order_list','=','order_list.order_list')
            ->leftJoin('size','order_size.size_no','=','size.size_no')
            ->where('order_size.order_list','=',$order_list)
            ->where('order_size.void','=','false')
            ->groupBy('order_list.pobuyer_no','order_list.lot_no','order_list.dcpo_qty')
            ->get();
            // return response()->json($ordersizes);
            return DataTables::of($ordersizes)->addIndexColumn()->make(true);
    }

    
    public function void(Request $request)
    {
        $orderlists = OrderList::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Void Order List ' . $orderlists->order_list;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $orderlists->fill([
            'void' => 'true',
        ]);

        $orderlists->save();

        Alert::success('Void Successfully!', 'Order List ' . $orderlists->order_list . ' successfully voided!');
        return redirect('orderlist/index');
    }

    public function restore(Request $request)
    {
        $orderlists = OrderList::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Restore Order List ' . $orderlists->order_list;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $orderlists->fill([
            'void' => 'false',
        ]);

        $orderlists->save();

        Alert::success('Restore Successfully!', 'Order List ' . $orderlists->order_list . ' successfully restored!');
        return redirect('orderlist/index');
    }
}
