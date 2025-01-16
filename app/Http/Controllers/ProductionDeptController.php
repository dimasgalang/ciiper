<?php

namespace App\Http\Controllers;

use App\Imports\ProductionDeptsImport;
use App\Models\ProductionDept;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductionDeptController extends Controller
{
    public function index() {
        $productiondepts   = ProductionDept::all();
        return view('productiondept.index', compact('productiondepts'));
    }

    public function create() {
        $productiondepts = ProductionDept::all()->last();
        return view('productiondept.create', compact('productiondepts'));
    }

    public function store(Request $request)
    {
        ProductionDept::create([
            'dept_no' => $request->dept_no,
            'dept_name' => $request->dept_name,
        ]);

        return redirect()
            ->route('productiondept.create')
            ->with('success', 'Production Dept berhasil ditambahkan!');
    }

    public function delete($id) {
        $productiondepts = ProductionDept::find($id);    
        $productiondepts->delete();
        return redirect('productiondept/index')->with(['error' => 'Record Berhasil Dihapus!']);
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/',$nama_file);
        $import = Excel::import(new ProductionDeptsImport(), storage_path('app/public/excel/'.$nama_file));

        if($import) {
            return redirect()->intended('productiondept/index')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            return redirect()->intended('productiondept/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }
}
