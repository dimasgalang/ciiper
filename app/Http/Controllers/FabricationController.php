<?php

namespace App\Http\Controllers;

use App\Imports\FabricationsImport;
use App\Models\Fabrication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class FabricationController extends Controller
{
    public function index() {
        $fabrications   = Fabrication::all();
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
        return view('fabrication.create');
    }

    public function store(Request $request)
    {
        Fabrication::create([
            'order_trans' => $request->order_trans,
            'fab_no' => $request->fab_no,
            'fabmil_no' => $request->fabmil_no,
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
}
