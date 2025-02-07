<?php

namespace App\Http\Controllers;

use App\Imports\ProductionDeptsImport;
use App\Models\LogCiiper;
use App\Models\ProductionDept;
use App\Models\SetupIncrement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

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
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Production Dept ' . $request->dept_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);
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

        Alert::success('Create Successfully!', 'Production Dept ' . $request->dept_no . ' successfully created!');
        return redirect()
            ->route('productiondept.create');
    }

    public function delete($id) {
        $productiondepts = ProductionDept::find($id);    
        $productiondepts->delete();
        
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Production Dept ' . $productiondepts->dept_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);

        Alert::success('Delete Successfully!', 'Production Dept ' . $productiondepts->dept_no . ' successfully deleted!');
        return redirect('productiondept/index');
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
            Alert::success('Import Successfully!', 'Production Dept data successfully imported!');
            return redirect()->intended('productiondept/index');
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
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated Production Dept ' . $request->dept_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

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

        Alert::success('Update Successfully!', 'Production Dept ' . $request->dept_no . ' successfully updated!');
        return redirect('productiondept/index');
    }
}
