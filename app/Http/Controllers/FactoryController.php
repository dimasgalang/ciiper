<?php

namespace App\Http\Controllers;

use App\Imports\FactorysImport;
use App\Models\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class FactoryController extends Controller
{
    public function index() {
        $factorys   = Factory::all();
        return view('factory.index', compact('factorys'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/',$nama_file);
        $import = Excel::import(new FactorysImport(), storage_path('app/public/excel/'.$nama_file));
        Storage::delete($path);

        if($import) {
            return redirect()->intended('factory/index')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            return redirect()->intended('factory/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create() {
        $factorys = Factory::all()->last();
        return view('factory.create', compact('factorys'));
    }

    public function store(Request $request)
    {
        Factory::create([
            'factory_no' => $request->factory_no,
            'factory_name' => $request->factory_name,
        ]);

        return redirect()
            ->route('factory.create')
            ->with('success', 'Factory berhasil ditambahkan!');
    }

    public function delete($id) {
        $factorys = Factory::find($id);    
        $factorys->delete();
        return redirect('factory/index')->with(['error' => 'Record Berhasil Dihapus!']);
    }

    public function find($id) {
        $factorys = Factory::find($id);
        return view('factory.update', compact('factorys'));
    }

    public function update(Request $request)
    {
        $factorys = Factory::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'factory_no' => 'required|max:225|',
            'factory_name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $factorys->fill([
            'factory_no' => $request->factory_no,
            'factory_name' => $request->factory_name,
        ]);

        $factorys->save();

        return redirect('factory/index')->with(['success' => 'Factory berhasil diupdate!']);
    }
}
