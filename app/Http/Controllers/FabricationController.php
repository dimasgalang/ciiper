<?php

namespace App\Http\Controllers;

use App\Imports\FabricationsImport;
use App\Models\Fabrication;
use App\Models\FabricMill;
use App\Models\LogCiiper;
use App\Models\OrderMaster;
use App\Models\SetupIncrement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class FabricationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->void) {
            $fabrications = Fabrication::select('fabrication.*', 'purchase_order.po_master', 'fabric_mill.fabmill_name')
                ->leftJoin('order_master', 'order_master.order_trans', '=', 'fabrication.order_trans')
                ->leftJoin('purchase_order', 'purchase_order.po_no', '=', 'order_master.po_no')
                ->leftJoin('fabric_mill', 'fabrication.fabmill_no', '=', 'fabric_mill.fabmill_no')
                ->where('fabrication.void', '=', $request->void)
                ->get();
        } else {
            $fabrications = Fabrication::select('fabrication.*', 'purchase_order.po_master', 'fabric_mill.fabmill_name')
                ->leftJoin('order_master', 'order_master.order_trans', '=', 'fabrication.order_trans')
                ->leftJoin('purchase_order', 'purchase_order.po_no', '=', 'order_master.po_no')
                ->leftJoin('fabric_mill', 'fabrication.fabmill_no', '=', 'fabric_mill.fabmill_no')
                ->where('fabrication.void', '=', 'false')
                ->get();
        }
        return view('fabrication.index', compact('fabrications'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/', $nama_file);
        $import = Excel::import(new FabricationsImport(), storage_path('app/public/excel/' . $nama_file));
        Storage::delete($path);

        if ($import) {
            Alert::success('Import Successfully!', 'Fabrication data successfully imported!');
            return redirect()->intended('fabrication/index');
        } else {
            return redirect()->intended('fabrication/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create()
    {
        $ordermasters = OrderMaster::select('order_master.*', 'purchase_order.po_master')
            ->leftJoin('purchase_order', 'order_master.po_no', '=', 'purchase_order.po_no')
            ->get();
        $setupincements = SetupIncrement::all()->where('models', '=', 'Fabrication')->last();
        $fabmills = FabricMill::all();
        return view('fabrication.create', compact('ordermasters', 'setupincements', 'fabmills'));
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Fabrication ' . $request->fab_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);
        SetupIncrement::updateOrCreate([
            'models' => 'Fabrication'
        ], [
            'models' => 'Fabrication',
            'last_number' => $request->fab_no,
        ]);
        Fabrication::create([
            'order_trans' => $request->order_trans,
            'fab_no' => $request->fab_no,
            'fabmill_no' => $request->fabmill_no,
            'fabrication' => $request->fabrication,
            'po_fab' => $request->po_fab,
            'etd' => $request->etd,
            'void' => 'false'
        ]);

        Alert::success('Create Successfully!', 'Fabrication ' . $request->fab_no . ' successfully created!');
        return redirect()
            ->route('fabrication.create');
    }

    public function delete($id)
    {
        $fabrication = Fabrication::find($id);
        $fabrication->delete();

        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Fabrication ' . $fabrication->fab_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);
        Alert::success('Delete Successfully!', 'Fabrication ' . $fabrication->fab_no . ' successfully deleted!');
        return redirect('fabrication/index');
    }

    public function find($id)
    {
        $fabrications = Fabrication::find($id);
        $ordermasters = OrderMaster::select('order_master.po_no', 'purchase_order.po_master', 'fabrication.*')
            ->leftJoin('purchase_order', 'order_master.po_no', '=', 'purchase_order.po_no')
            ->leftJoin('fabrication', 'order_master.order_trans', '=', 'fabrication.order_trans')
            ->where('fabrication.id', '=', $id)
            ->get();
        $fabmills = FabricMill::all();
        return view('fabrication.update', compact('fabrications', 'ordermasters', 'fabmills'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated Fabrication ' . $request->fab_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $fabrications = Fabrication::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'order_trans' => 'required|max:225|',
            'fab_no' => 'required|max:255',
            'fabmill_no' => 'required|max:255',
            'fabrication' => 'required',
            'po_fab' => 'required',
            'etd' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $fabrications->fill([
            'order_trans' => $request->order_trans,
            'fab_no' => $request->fab_no,
            'fabmill_no' => $request->fabmill_no,
            'fabrication' => $request->fabrication,
            'po_fab' => $request->po_fab,
            'etd' => $request->etd,
        ]);

        $fabrications->save();

        Alert::success('Update Successfully!', 'Fabrication ' . $request->fab_no . ' successfully updated!');
        return redirect('fabrication/index');
    }


    public function void(Request $request)
    {
        $fabrications = Fabrication::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Void Fabrication ' . $fabrications->fab_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $fabrications->fill([
            'void' => 'true',
        ]);

        $fabrications->save();

        Alert::success('Void Successfully!', 'Fabrication ' . $fabrications->fab_no . ' successfully voided!');
        return redirect('fabrication/index');
    }

    public function restore(Request $request)
    {
        $fabrications = Fabrication::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Restore Fabrication ' . $fabrications->fab_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $fabrications->fill([
            'void' => 'false',
        ]);

        $fabrications->save();

        Alert::success('Restore Successfully!', 'Fabrication ' . $fabrications->fab_no . ' successfully restored!');
        return redirect('fabrication/index');
    }
}
