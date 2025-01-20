<?php

namespace App\Http\Controllers;

use App\Imports\FabricMillsImport;
use App\Models\FabricMill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
        $fabricmills = FabricMill::find($id);    
        $fabricmills->delete();
        return redirect('fabricmill/index')->with(['error' => 'Record Berhasil Dihapus!']);
    }

    public function find($id) {
        $fabricmills = FabricMill::find($id);
        return view('fabricmill.update', compact('fabricmills'));
    }

    public function update(Request $request)
    {
        $fabricmills = FabricMill::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'fabmill_no' => 'required|max:225|',
            'fabmill_name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $fabricmills->fill([
            'fabmill_no' => $request->fabmill_no,
            'fabmill_name' => $request->fabmill_name,
        ]);

        $fabricmills->save();

        return redirect('fabricmill/index')->with(['success' => 'Fabric Mill berhasil diupdate!']);
    }
}
