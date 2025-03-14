<?php

namespace App\Http\Controllers;

use App\Models\Accesories;
use App\Models\Category;
use App\Models\LogCiiper;
use App\Models\OrderList;
use App\Models\OrderMaster;
use App\Models\ProPlanAcc;
use App\Models\SetupIncrement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class ProPlanAccController extends Controller
{
    public function index(Request $request)
    {
        $proplanaccs = ProPlanAcc::select('proplan_acc.*', 'order_list.pobuyer_no', 'category.category_name', 'accesories.accesories_name')
            ->leftJoin('order_list', 'proplan_acc.order_list', '=', 'order_list.order_list')
            ->leftJoin('category', 'category.category_no', '=', 'proplan_acc.category_no')
            ->leftJoin('accesories', 'proplan_acc.accesories_no', '=', 'accesories.accesories_no')
            ->where('proplan_acc.void', '=', $request->void)
            ->get();
        return view('proplanacc.index', compact('proplanaccs'));
    }

    public function create()
    {
        $setupincements = SetupIncrement::all()->where('models', '=', 'ProPlanAcc')->last();
        $ordermasters = OrderMaster::select('order_master.*', 'purchase_order.po_master')
            ->leftJoin('purchase_order', 'order_master.po_no', '=', 'purchase_order.po_no')
            ->get();
        $orderlists = OrderList::all();
        $categories = Category::all();
        $accesories = Accesories::all();
        return view('proplanacc.create', compact('ordermasters', 'orderlists', 'setupincements', 'categories', 'accesories'));
    }

    public function fetchaccesories($category_no)
    {
        $accesories = Accesories::select('accesories.*')->where('category_no', '=', $category_no)->get();
        return response()->json($accesories);
    }

    public function fetchaccsew($order_list)
    {
        $proplanacc = ProPlanAcc::select('proplan_acc.*', 'accesories.accesories_name')
            ->leftJoin('accesories', 'accesories.accesories_no', '=', 'proplan_acc.accesories_no')
            ->where('order_list', '=', $order_list)
            ->where('proplan_acc.category_no', '=', 'CAT000000006')
            ->get();
        return DataTables::of($proplanacc)
            ->addIndexColumn()
            ->addColumn('item_date_formated', function ($row) {
                return date('dmy', strtotime($row->item_date));
            })
            ->rawColumns(['item_date_formated'])
            ->make(true);
    }

    public function fetchaccpack($order_list)
    {
        $proplanacc = ProPlanAcc::select('proplan_acc.*', 'accesories.accesories_name')
            ->leftJoin('accesories', 'accesories.accesories_no', '=', 'proplan_acc.accesories_no')
            ->where('order_list', '=', $order_list)
            ->where('proplan_acc.category_no', '=', 'CAT000000005')
            ->get();
        return DataTables::of($proplanacc)
            ->addIndexColumn()
            ->addColumn('item_date_formated', function ($row) {
                return date('dmy', strtotime($row->item_date));
            })
            ->rawColumns(['item_date_formated'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Production Planning Accesories ' . $request->proplan_acc_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);
        SetupIncrement::updateOrCreate([
            'models' => 'ProPlanAcc'
        ], [
            'models' => 'ProPlanAcc',
            'last_number' => $request->proplan_acc_no,
        ]);
        ProPlanAcc::create([
            'proplan_acc_no' => $request->proplan_acc_no,
            'order_trans' => $request->order_trans,
            'order_list' => $request->order_list,
            'accesories_no' => $request->accesories_no,
            'category_no' => $request->category_no,
            'item_date' => $request->item_date,
            'void' => 'false'
        ]);

        Alert::success('Create Successfully!', 'Production Planning Accesories ' . $request->proplan_acc_no . ' successfully created!');
        return redirect()
            ->route('proplanacc.create');
    }

    public function find($id)
    {
        $proplanacc = ProPlanAcc::find($id);
        $ordermasters = Ordermaster::select('*')->leftJoin('purchase_order', 'order_master.po_no', '=', 'purchase_order.po_no')->get();
        $orderlists = OrderList::all();
        $categories = Category::all();
        $accesories = Accesories::all();
        return view('proplanacc.update', compact('proplanacc', 'ordermasters', 'orderlists', 'categories', 'accesories'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated ProPlanAcc ' . $request->proplan_acc_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);
        $proplanaccs = ProPlanAcc::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'order_trans' => 'required|max:255',
            'order_list' => 'required|max:255',
            'accesories_no' => 'required|max:255',
            'category_no' => 'required|max:255',
            'item_date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $proplanaccs->fill([
            'order_trans' => $request->order_trans,
            'order_list' => $request->order_list,
            'accesories_no' => $request->accesories_no,
            'category_no' => $request->category_no,
            'item_date' => $request->item_date,
        ]);

        $proplanaccs->save();

        Alert::success('Update Successfully!', 'Production Planning Detail ' . $request->proplan_acc_no . ' successfully updated!');
        return redirect('proplanacc/index');
    }

    public function void(Request $request)
    {
        $proplanaccs = ProPlanAcc::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Void Production Planning Accesories ' . $proplanaccs->proplan_acc_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $proplanaccs->fill([
            'void' => 'true',
        ]);

        $proplanaccs->save();

        Alert::success('Void Successfully!', 'Production Planning Accesories ' . $proplanaccs->proplan_acc_no . ' successfully voided!');
        return redirect('proplanacc/index');
    }

    public function restore(Request $request)
    {
        $proplanaccs = ProPlanAcc::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Restore Production Planning Accesories ' . $proplanaccs->proplan_acc_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $proplanaccs->fill([
            'void' => 'false',
        ]);

        $proplanaccs->save();

        Alert::success('Restore Successfully!', 'Production Planning Accesories ' . $proplanaccs->proplandetail_no . ' successfully restored!');
        return redirect('proplanacc/index');
    }
}
