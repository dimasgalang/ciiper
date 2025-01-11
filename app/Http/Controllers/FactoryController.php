<?php

namespace App\Http\Controllers;

use App\Imports\FactorysImport;
use App\Models\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        return view('factory.create');
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
        $factory = Factory::find($id);    
        $factory->delete();
        return redirect('factory/index')->with(['error' => 'Record Berhasil Dihapus!']);
    }
}
