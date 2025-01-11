<?php

namespace App\Http\Controllers;

use App\Imports\FabricMillsImport;
use App\Models\FabricMill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class FabricMillController extends Controller
{
    public function index() {
        $fabricmills   = FabricMill::all();
        return view('fabricmill.index', compact('fabricmills'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/',$nama_file);
        $import = Excel::import(new FabricMillsImport(), storage_path('app/public/excel/'.$nama_file));
        Storage::delete($path);

        if($import) {
            return redirect()->intended('fabricmill/index')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            return redirect()->intended('fabricmill/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create() {
        return view('fabricmill.create');
    }

    public function store(Request $request)
    {
        FabricMill::create([
            'fabmill_no' => $request->fabmill_no,
            'fabmill_name' => $request->fabmill_name,
        ]);

        return redirect()
            ->route('fabricmill.create')
            ->with('success', 'Fabric Mill berhasil ditambahkan!');
    }

    public function delete($id) {
        $fabricmill = FabricMill::find($id);    
        $fabricmill->delete();
        return redirect('fabricmill/index')->with(['error' => 'Record Berhasil Dihapus!']);
    }
}
