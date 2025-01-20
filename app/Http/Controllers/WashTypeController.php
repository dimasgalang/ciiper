<?php

namespace App\Http\Controllers;

use App\Imports\WashTypesImport;
use App\Models\WashType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class WashTypeController extends Controller
{
    public function index() {
        $washtypes   = WashType::all();
        return view('washtype.index', compact('washtypes'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/',$nama_file);
        $import = Excel::import(new WashTypesImport(), storage_path('app/public/excel/'.$nama_file));
        Storage::delete($path);

        if($import) {
            return redirect()->intended('washtype/index')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            return redirect()->intended('washtype/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create() {
        $washtypes = WashType::all()->last();
        return view('washtype.create', compact('washtypes'));
    }

    public function store(Request $request)
    {
        WashType::create([
            'wash_no' => $request->wash_no,
            'wash_type' => $request->wash_type,
        ]);

        return redirect()
            ->route('washtype.create')
            ->with('success', 'Wash Type berhasil ditambahkan!');
    }

    public function delete($id) {
        $washtypes = WashType::find($id);    
        $washtypes->delete();
        return redirect('washtype/index')->with(['error' => 'Record Berhasil Dihapus!']);
    }

    public function find($id) {
        $washtypes = WashType::find($id);
        return view('washtype.update', compact('washtypes'));
    }

    public function update(Request $request)
    {
        $washtypes = WashType::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'wash_no' => 'required|max:225|',
            'wash_type' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $washtypes->fill([
            'wash_no' => $request->wash_no,
            'wash_type' => $request->wash_type,
        ]);

        $washtypes->save();

        return redirect('washtype/index')->with(['success' => 'Wash Type berhasil diupdate!']);
    }
}
