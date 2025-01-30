<?php

namespace App\Http\Controllers;

use App\Imports\ProductionDeptsImport;
use App\Models\ProductionDept;
use App\Models\SetupIncrement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ProductionDeptController extends Controller
{
    public function index() {
        $productiondepts   = ProductionDept::all();
        return view('productiondept.index', compact('productiondepts'));
    }

    public function create() {
        $setupincements = SetupIncrement::all()->where('models','=','ProductionDept')->last();
        return view('productiondept.create', compact('setupincements'));
    }

    public function store(Request $request)
    {
        SetupIncrement::updateOrCreate([
            'models' => 'ProductionDept'
        ],[
            'models' => 'ProductionDept',
            'last_number' => $request->dept_no,
        ]);
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

    public function find($id) {
        $productiondepts = ProductionDept::find($id);
        return view('productiondept.update', compact('productiondepts'));
    }

    public function update(Request $request)
    {
        $productiondepts = ProductionDept::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'dept_no' => 'required|max:225|',
            'dept_name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $productiondepts->fill([
            'dept_no' => $request->dept_no,
            'dept_name' => $request->dept_name,
        ]);

        $productiondepts->save();

        return redirect('productiondept/index')->with(['success' => 'Market berhasil diupdate!']);
    }
}
