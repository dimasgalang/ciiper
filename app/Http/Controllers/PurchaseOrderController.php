<?php

namespace App\Http\Controllers;

use App\Imports\PurchaseOrdersImport;
use App\Models\LogCiiper;
use App\Models\PurchaseOrder;
use App\Models\SetupIncrement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->void) {
            $pos   = PurchaseOrder::select('*')
                ->where('void', '=', $request->void)
                ->get();
        } else {
            $pos   = PurchaseOrder::select('*')
                ->where('void', '=', 'false')
                ->get();
        }
        return view('po.index', compact('pos'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/', $nama_file);
        $import = Excel::import(new PurchaseOrdersImport(), storage_path('app/public/excel/' . $nama_file));
        Storage::delete($path);

        if ($import) {
            Alert::success('Import Successfully!', 'Purchase Order data successfully imported!');
            return redirect()->intended('po/index');
        } else {
            return redirect()->intended('po/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create()
    {
        $pos = PurchaseOrder::all()->last();
        $setupincements = SetupIncrement::all()->where('models', '=', 'PurchaseOrder')->last();
        $potypes = ['E', 'C', 'K'];
        // dd($pos);
        return view('po.create', compact('pos', 'potypes', 'setupincements'));
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Purchase Order ' . $request->po_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);

        SetupIncrement::updateOrCreate([
            'models' => 'PurchaseOrder'
        ], [
            'models' => 'PurchaseOrder',
            'last_number' => $request->po_no,
        ]);
        PurchaseOrder::create([
            'po_no' => $request->po_no,
            'po_master' => $request->po_master,
            'po_desc' => $request->po_desc,
            'void' => 'false'
        ]);

        Alert::success('Create Successfully!', 'Purchase Order ' . $request->po_no . ' successfully created!');
        return redirect()
            ->route('po.create');
    }

    public function delete($id)
    {
        $pos = PurchaseOrder::find($id);
        $pos->delete();

        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Purchase Order ' . $pos->po_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);

        Alert::success('Delete Successfully!', 'Purchase Order ' . $pos->po_no . ' successfully deleted!');
        return redirect('po/index');
    }

    public function find($id)
    {
        $pos = PurchaseOrder::find($id);
        return view('po.update', compact('pos'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated Purchase Order ' . $request->po_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $pos = PurchaseOrder::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'po_no' => 'required|max:225|',
            'po_master' => 'required|max:255',
            'po_desc' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $pos->fill([
            'po_no' => $request->po_no,
            'po_master' => $request->po_master,
            'po_desc' => $request->po_desc,
        ]);

        $pos->save();

        Alert::success('Update Successfully!', 'Purchase Order ' . $request->po_no . ' successfully update!');
        return redirect('po/index');
    }


    public function void(Request $request)
    {
        $pos = PurchaseOrder::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Void Purchase Order ' . $pos->po_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $pos->fill([
            'void' => 'true',
        ]);

        $pos->save();

        Alert::success('Void Successfully!', 'Purchase Order ' . $pos->po_no . ' successfully voided!');
        return redirect('po/index');
    }

    public function restore(Request $request)
    {
        $pos = PurchaseOrder::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Restore Purchase Order ' . $pos->po_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $pos->fill([
            'void' => 'false',
        ]);

        $pos->save();

        Alert::success('Restore Successfully!', 'Purchase Order ' . $pos->po_no . ' successfully restored!');
        return redirect('po/index');
    }
}
