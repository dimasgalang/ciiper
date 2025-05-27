<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderSize;
use App\Models\LogCiiper;
use App\Models\OrderList;
use App\Models\OrderMaster;
use App\Models\SetupIncrement;
use App\Models\Size;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class OrderSizeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->void) {
            $ordersizes = OrderSize::select('order_size.*', 'size.size_no', 'size.size')
                ->leftJoin('size', 'order_size.size_no', '=', 'size.size_no')
                ->where('order_size.void', '=', $request->void)
                ->get();
        } else {
            $ordersizes = OrderSize::select('order_size.*', 'size.size_no', 'size.size')
                ->leftJoin('size', 'order_size.size_no', '=', 'size.size_no')
                ->where('order_size.void', '=', 'false')
                ->get();
        }
        return view('ordersize.index', compact('ordersizes'));
    }

    public function create()
    {
        $setupincements = SetupIncrement::all()->where('models', '=', 'OrderSize')->last();
        $orderlists = OrderList::all();
        $ordermasters = OrderMaster::select('order_master.*', 'purchase_order.po_master')
            ->leftJoin('purchase_order', 'order_master.po_no', '=', 'purchase_order.po_no')
            ->get();
        $sizes = Size::all();
        return view('ordersize.create', compact('setupincements', 'orderlists', 'sizes', 'ordermasters'));
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Order Size ' . $request->order_size_no;
        OrderSize::create([
            'order_size_no' => $request->order_size_no,
            'order_list' => $request->order_list,
            'order_trans' => $request->order_trans,
            'size_no' => $request->size_no,
            'qty' => $request->qty,
            'void' => 'false'
        ]);
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);
        SetupIncrement::updateOrCreate([
            'models' => 'OrderSize'
        ], [
            'models' => 'OrderSize',
            'last_number' => $request->order_size_no,
        ]);

        Alert::success('Create Successfully!', 'Order Size ' . $request->order_size_no . ' successfully created!');
        return redirect()
            ->route('ordersize.create');
    }

    public function delete($id)
    {
        $ordersizes = OrderSize::find($id);
        $ordersizes->delete();

        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Order Size ' . $ordersizes->order_size_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);
        Alert::success('Delete Successfully!', 'Order Size ' . $ordersizes->order_size_no . ' successfully deleted!');
        return redirect('ordersize/index');
    }

    public function find($id)
    {
        $ordersizes = OrderSize::find($id);
        $sizes = Size::all();
        return view('ordersize.update', compact('ordersizes'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated Order Size ' . $request->order_size_no;
        $ordersizes = OrderSize::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'order_size_no' => 'required|max:255|',
            // 'order_list' => 'required|max:255',
            // 'order_trans' => 'required|max:255',
            // 'size_no' => 'required|max:255',
            'qty' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $ordersizes->fill([
            'order_size_no' => $request->order_size_no,
            // 'order_list' => $request->order_list,
            // 'order_trans' => $request->order_trans,
            // 'size_no' => $request->size_no,
            'qty' => $request->qty,
        ]);

        $ordersizes->save();
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        Alert::success('Update Successfully!', 'Order Size ' . $request->order_size_no . ' successfully updated!');
        return redirect('ordersize/index');
    }

    public function fetchdcpoleft($order_list)
    {
        // $ordersizes = OrderList::select('order_list.dcpo_qty', DB::raw('ifnull(sum(order_size.qty),0) as sum_qty'), DB::raw('ifnull((order_list.dcpo_qty-ifnull(sum(order_size.qty),0)),0) as dcpo_left'))
        //     ->leftJoin('order_size', 'order_size.order_list', '=', 'order_list.order_list')
        //     ->where('order_list.order_list', '=', $order_list)
        //     ->where('order_size.void', '=', 'false')
        //     ->groupBy('order_list.dcpo_qty')
        //     ->get();

        $ordersizes = OrderList::select(
            'order_list.dcpo_qty',
            DB::raw('ifnull(sum(CASE WHEN order_size.void = "false" THEN order_size.qty ELSE 0 END), 0) as sum_qty'),
            DB::raw('ifnull((order_list.dcpo_qty - ifnull(sum(CASE WHEN order_size.void = "false" THEN order_size.qty ELSE 0 END), 0)), 0) as dcpo_left')
        )
            ->leftJoin('order_size', 'order_size.order_list', '=', 'order_list.order_list')
            ->where('order_list.order_list', '=', $order_list)
            ->groupBy('order_list.dcpo_qty')
            ->get();
        return response()->json($ordersizes);
    }

    public function fetchorderlist($order_trans)
    {
        $orderlists   = OrderList::select('order_list.*', 'production_planning.*')
            ->join('production_planning', 'order_list.order_list', '=', 'production_planning.order_list')
            ->where('order_list.order_trans', '=', $order_trans)
            ->get();
        return response()->json($orderlists);
    }


    public function void(Request $request)
    {
        $ordersizes = OrderSize::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Void Order Size ' . $ordersizes->order_size_no;

        $ordersizes->fill([
            'void' => 'true',
        ]);

        $ordersizes->save();
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        Alert::success('Void Successfully!', 'Order Size ' . $ordersizes->order_size_no . ' successfully voided!');
        return redirect('ordersize/index');
    }

    public function restore(Request $request)
    {
        $ordersizes = OrderSize::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Restore Order Size ' . $ordersizes->order_size_no;

        $ordersizes->fill([
            'void' => 'false',
        ]);

        $ordersizes->save();
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        Alert::success('Restore Successfully!', 'Order Size ' . $ordersizes->order_size_no . ' successfully restored!');
        return redirect('ordersize/index');
    }
}
