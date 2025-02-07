<?php

namespace App\Http\Controllers;

use App\Imports\ShipModesImport;
use App\Models\LogCiiper;
use App\Models\SetupIncrement;
use App\Models\ShipMode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class ShipModeController extends Controller
{
    public function index() {
        $shipmodes   = ShipMode::all();
        return view('shipmode.index', compact('shipmodes'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/',$nama_file);
        $import = Excel::import(new ShipModesImport(), storage_path('app/public/excel/'.$nama_file));
        Storage::delete($path);

        if($import) {
            Alert::success('Import Successfully!', 'Ship Mode data successfully imported!');
            return redirect()->intended('shipmode/index');
        } else {
            return redirect()->intended('shipmode/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create() {
        $setupincements = SetupIncrement::all()->where('models','=','ShipMode')->last();
        return view('shipmode.create', compact('setupincements'));
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Ship Mode ' . $request->shipmode_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);

        SetupIncrement::updateOrCreate([
            'models' => 'ShipMode'
        ],[
            'models' => 'ShipMode',
            'last_number' => $request->shipmode_no,
        ]);
        ShipMode::create([
            'shipmode_no' => $request->shipmode_no,
            'shipmode_name' => $request->shipmode_name,
        ]);

        Alert::success('Create Successfully!', 'Ship Mode ' . $request->shipmode_no . ' successfully created!');
        return redirect()
            ->route('shipmode.create');
    }

    public function delete($id) {
        $shipmodes = ShipMode::find($id);    
        $shipmodes->delete();
        
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Ship Mode ' . $shipmodes->shipmode_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);

        Alert::success('Delete Successfully!', 'Ship Mode ' . $shipmodes->shipmode_no . ' successfully deleted!');
        return redirect('shipmode/index');
    }

    public function find($id) {
        $shipmodes = ShipMode::find($id);
        return view('shipmode.update', compact('shipmodes'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated Ship Mode ' . $request->shipmode_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $shipmodes = ShipMode::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'shipmode_no' => 'required|max:225|',
            'shipmode_name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $shipmodes->fill([
            'shipmode_no' => $request->shipmode_no,
            'shipmode_name' => $request->shipmode_name,
        ]);

        $shipmodes->save();

        Alert::success('Update Successfully!', 'Ship Mode ' . $request->shipmode_no . ' successfully updated!');
        return redirect('shipmode/index');
    }
}
