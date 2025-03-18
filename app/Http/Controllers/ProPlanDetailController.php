<?php

namespace App\Http\Controllers;

use App\Imports\ProPlanDetailsImport;
use App\Models\Category;
use App\Models\ProPlanDetail;
use Illuminate\Http\Request;
use App\Models\LogCiiper;
use App\Models\OrderList;
use App\Models\OrderMaster;
use App\Models\SetupIncrement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class ProPlanDetailController extends Controller
{
    public function index(Request $request)
    {
        if ($request->void) {
            $proplandetails   = ProPlanDetail::select('proplan_detail.*', 'order_list.pobuyer_no')->leftJoin('order_list', 'proplan_detail.order_list', '=', 'order_list.order_list')
                ->where('proplan_detail.void', '=', $request->void)
                ->get();
        } else {
            $proplandetails   = ProPlanDetail::select('proplan_detail.*', 'order_list.pobuyer_no')->leftJoin('order_list', 'proplan_detail.order_list', '=', 'order_list.order_list')
                ->where('proplan_detail.void', '=', 'false')
                ->get();
        }
        return view('proplandetail.index', compact('proplandetails'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/', $nama_file);
        $import = Excel::import(new ProPlanDetailsImport(), storage_path('app/public/excel/' . $nama_file));
        Storage::delete($path);

        if ($import) {
            Alert::success('Import Successfully!', 'Production Planning Detail data successfully imported!');
            return redirect()->intended('proplandetail/index');
        } else {
            return redirect()->intended('proplandetail/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create()
    {
        $setupincements = SetupIncrement::all()->where('models', '=', 'ProPlanDetail')->last();
        $ordermasters = OrderMaster::select('order_master.*', 'purchase_order.po_master')
            ->leftJoin('purchase_order', 'order_master.po_no', '=', 'purchase_order.po_no')
            ->get();
        $orderlists = OrderList::all();
        $categories = Category::all();
        return view('proplandetail.create', compact('ordermasters', 'orderlists', 'setupincements', 'categories'));
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Production Planning Detail ' . $request->proplan_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);
        SetupIncrement::updateOrCreate([
            'models' => 'ProPlanDetail'
        ], [
            'models' => 'ProPlanDetail',
            'last_number' => $request->proplan_no,
        ]);
        ProPlanDetail::create([
            'proplan_no' => $request->proplan_no,
            'order_trans' => $request->order_trans,
            'order_list' => $request->order_list,
            'item' => $request->item,
            'category_no' => $request->category_no,
            'percentage' => $request->percentage,
            'remark' => $request->remark,
            'status' => 'Unfinished',
            'void' => 'false'
        ]);

        Alert::success('Create Successfully!', 'Production Planning Detail ' . $request->proplan_no . ' successfully created!');
        return redirect()
            ->route('proplandetail.create');
    }

    public function delete($id)
    {
        $proplandetails = ProPlanDetail::find($id);
        $proplandetails->delete();

        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Production Planning Detail ' . $proplandetails->proplan_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);
        Alert::success('Delete Successfully!', 'Production Planning Detail ' . $proplandetails->proplan_no . ' successfully deleted!');
        return redirect('proplandetail/index');
    }

    public function find($id)
    {
        $proplandetails = ProPlanDetail::find($id);
        $ordermasters = Ordermaster::select('*')->leftJoin('purchase_order', 'order_master.po_no', '=', 'purchase_order.po_no')->get();
        $orderlists = OrderList::all();
        $categories = Category::all();
        return view('proplandetail.update', compact('proplandetails', 'ordermasters', 'orderlists', 'categories'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated ProPlanDetail ' . $request->proplan_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);
        $proplandetails = ProPlanDetail::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'proplan_no' => 'required|max:225|',
            'order_trans' => 'required|max:255',
            'order_list' => 'required|max:255',
            'item' => 'required|max:255',
            'category_no' => 'required|max:255',
            'percentage' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $proplandetails->fill([
            'proplan_no' => $request->proplan_no,
            'order_trans' => $request->order_trans,
            'order_list' => $request->order_list,
            'item' => $request->item,
            'category_no' => $request->category_no,
            'percentage' => $request->percentage,
            'remark' => $request->remark,
        ]);

        $proplandetails->save();

        Alert::success('Update Successfully!', 'Production Planning Detail ' . $request->proplan_no . ' successfully updated!');
        return redirect('proplandetail/index');
    }

    public function showdetailsample($order_list)
    {
        $proplandetails = ProPlanDetail::select('proplan_detail.item', 'proplan_detail.remark', 'proplan_detail.status', 'category.category_name', DB::raw('concat(proplan_detail.percentage,"%") as percentage'))
            ->leftJoin('category', 'proplan_detail.category_no', '=', 'category.category_no')
            ->where('proplan_detail.order_list', '=', $order_list)
            ->where('proplan_detail.category_no', '=', 'CAT000000002')
            ->get();
        return DataTables::of($proplandetails)
            ->addIndexColumn()
            ->make(true);
    }
    public function showdetailfabric($order_list)
    {
        $proplandetails = ProPlanDetail::select('proplan_detail.item', 'proplan_detail.remark', 'proplan_detail.status', 'category.category_name', DB::raw('concat(proplan_detail.percentage,"%") as percentage'))
            ->leftJoin('category', 'proplan_detail.category_no', '=', 'category.category_no')
            ->where('proplan_detail.order_list', '=', $order_list)
            ->where('proplan_detail.category_no', '=', 'CAT000000003')
            ->get();
        return DataTables::of($proplandetails)
            ->addIndexColumn()
            ->make(true);
    }
    public function showdetailmi($order_list)
    {
        $proplandetails = ProPlanDetail::select('proplan_detail.item', 'proplan_detail.remark', 'proplan_detail.status', 'category.category_name', DB::raw('concat(proplan_detail.percentage,"%") as percentage'))
            ->leftJoin('category', 'proplan_detail.category_no', '=', 'category.category_no')
            ->where('proplan_detail.order_list', '=', $order_list)
            ->where('proplan_detail.category_no', '=', 'CAT000000004')
            ->get();
        return DataTables::of($proplandetails)
            ->addIndexColumn()
            ->make(true);
    }
    public function showdetailacc($order_list)
    {
        $proplandetails = ProPlanDetail::select('proplan_detail.item', 'proplan_detail.remark', 'proplan_detail.status', 'category.category_name', DB::raw('concat(proplan_detail.percentage,"%") as percentage'))
            ->leftJoin('category', 'proplan_detail.category_no', '=', 'category.category_no')
            ->where('proplan_detail.order_list', '=', $order_list)
            ->where('proplan_detail.category_no', '=', 'CAT000000001')
            ->get();
        return DataTables::of($proplandetails)
            ->addIndexColumn()
            ->make(true);
    }


    public function void(Request $request)
    {
        $proplandetails = ProPlanDetail::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Void Production Planning Detail ' . $proplandetails->proplandetail_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $proplandetails->fill([
            'void' => 'true',
        ]);

        $proplandetails->save();

        Alert::success('Void Successfully!', 'Production Planning Detail ' . $proplandetails->proplandetail_no . ' successfully voided!');
        return redirect('proplandetail/index');
    }

    public function restore(Request $request)
    {
        $proplandetails = ProPlanDetail::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Restore Production Planning Detail ' . $proplandetails->proplandetail_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $proplandetails->fill([
            'void' => 'false',
        ]);

        $proplandetails->save();

        Alert::success('Restore Successfully!', 'Production Planning Detail ' . $proplandetails->proplandetail_no . ' successfully restored!');
        return redirect('proplandetail/index');
    }
}
