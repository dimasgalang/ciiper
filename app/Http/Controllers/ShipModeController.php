<?php

namespace App\Http\Controllers;

use App\Imports\ShipModesImport;
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
        $shipmodes = ShipMode::all()->last();
        return view('shipmode.create', compact('shipmodes'));
    }

    public function store(Request $request)
    {
        ShipMode::create([
            'ship_no' => $request->ship_no,
            'ship_name' => $request->ship_name,
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
            'ship_no' => 'required|max:225|',
            'ship_name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $shipmodes->fill([
            'ship_no' => $request->ship_no,
            'ship_name' => $request->ship_name,
        ]);

        $shipmodes->save();

        return redirect('shipmode/index')->with(['success' => 'Ship Mode berhasil diupdate!']);
    }
}
