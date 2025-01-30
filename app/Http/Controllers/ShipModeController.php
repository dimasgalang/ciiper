<?php

namespace App\Http\Controllers;

use App\Imports\ShipModesImport;
use App\Models\SetupIncrement;
use App\Models\ShipMode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

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
            return redirect()->intended('shipmode/index')->with(['success' => 'Data Berhasil Diimport!']);
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

        return redirect()
            ->route('shipmode.create')
            ->with('success', 'Ship Mode berhasil ditambahkan!');
    }

    public function delete($id) {
        $shipmodes = ShipMode::find($id);    
        $shipmodes->delete();
        return redirect('shipmode/index')->with(['error' => 'Record Berhasil Dihapus!']);
    }

    public function find($id) {
        $shipmodes = ShipMode::find($id);
        return view('shipmode.update', compact('shipmodes'));
    }

    public function update(Request $request)
    {
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

        return redirect('shipmode/index')->with(['success' => 'Ship Mode berhasil diupdate!']);
    }
}
