<?php

namespace App\Http\Controllers;

use App\Imports\BordirTypesImport;
use App\Models\BordirType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class BordirTypeController extends Controller
{
    public function index() {
        $bordirtypes   = BordirType::all();
        return view('bordirtype.index', compact('bordirtypes'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/',$nama_file);
        $import = Excel::import(new BordirTypesImport(), storage_path('app/public/excel/'.$nama_file));
        Storage::delete($path);

        if($import) {
            return redirect()->intended('bordirtype/index')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            return redirect()->intended('bordirtype/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create() {
        $bordirtypes = BordirType::all()->last();
        return view('bordirtype.create', compact('bordirtypes'));
    }

    public function store(Request $request)
    {
        BordirType::create([
            'bordir_no' => $request->bordir_no,
            'bordir_type' => $request->bordir_type,
        ]);

        return redirect()
            ->route('bordirtype.create')
            ->with('success', 'Bordir Type berhasil ditambahkan!');
    }

    public function delete($id) {
        $bordirtypes = BordirType::find($id);    
        $bordirtypes->delete();
        return redirect('bordirtype/index')->with(['error' => 'Record Berhasil Dihapus!']);
    }

    public function find($id) {
        $bordirtypes = BordirType::find($id);
        return view('bordirtype.update', compact('bordirtypes'));
    }

    public function update(Request $request)
    {
        $bordirtypes = BordirType::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'bordir_no' => 'required|max:225|',
            'bordir_type' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $bordirtypes->fill([
            'bordir_no' => $request->bordir_no,
            'bordir_type' => $request->bordir_type,
        ]);

        $bordirtypes->save();

        return redirect('bordirtype/index')->with(['success' => 'Bordir Type berhasil diupdate!']);
    }
}
