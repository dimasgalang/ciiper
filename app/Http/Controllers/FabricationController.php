<?php

namespace App\Http\Controllers;

use App\Imports\FabricationsImport;
use App\Models\Fabrication;
use App\Models\FabricMill;
use App\Models\OrderMaster;
use App\Models\SetupIncrement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class FabricationController extends Controller
{
    public function index() {
        $fabrications = Fabrication::select('fabrication.*', 'purchase_order.po_master','fabric_mill.fabmill_name')
        ->leftJoin('order_master', 'order_master.order_trans', '=', 'fabrication.order_trans')
        ->leftJoin('purchase_order', 'purchase_order.po_no', '=', 'order_master.po_no')
        ->leftJoin('fabric_mill', 'fabrication.fabmill_no', '=', 'fabric_mill.fabmill_no')
        ->get();
        return view('fabrication.index', compact('fabrications'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/',$nama_file);
        $import = Excel::import(new FabricationsImport(), storage_path('app/public/excel/'.$nama_file));
        Storage::delete($path);

        if($import) {
            return redirect()->intended('fabrication/index')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            return redirect()->intended('fabrication/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create() {
        $ordermasters = OrderMaster::select('order_master.*', 'purchase_order.po_master')
        ->leftJoin('purchase_order','order_master.po_no','=','purchase_order.po_no')
        ->get();
        $setupincements = SetupIncrement::all()->where('models','=','Fabrication')->last();
        $fabmills = FabricMill::all();
        return view('fabrication.create', compact('ordermasters', 'setupincements','fabmills'));
    }

    public function store(Request $request)
    {
        SetupIncrement::updateOrCreate([
            'models' => 'Fabrication'
        ],[
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
        ]);

        return redirect()
            ->route('fabrication.create')
            ->with('success', 'Fabrication berhasil ditambahkan!');
    }

    public function delete($id) {
        $fabrication = Fabrication::find($id);    
        $fabrication->delete();
        return redirect('fabrication/index')->with(['error' => 'Record Berhasil Dihapus!']);
    }

    public function find($id) {
        $fabrications = Fabrication::find($id);
        $ordermasters = OrderMaster::select('order_master.po_no','purchase_order.po_master','fabrication.*')
        ->leftJoin('purchase_order','order_master.po_no','=','purchase_order.po_no')
        ->leftJoin('fabrication','order_master.order_trans','=','fabrication.order_trans')
        ->where('fabrication.id', '=', $id)
        ->get();
        $fabmills = FabricMill::all();
        return view('fabrication.update', compact('fabrications','ordermasters','fabmills'));
    }

    public function update(Request $request)
    {
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

        return redirect('fabrication/index')->with(['success' => 'Fabrication berhasil diupdate!']);
    }
}
