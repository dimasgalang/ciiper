<?php

namespace App\Http\Controllers;

use App\Imports\WashTypesImport;
use App\Models\LogCiiper;
use App\Models\SetupIncrement;
use App\Models\WashType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

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
            Alert::success('Import Successfully!', 'Wash Type data successfully imported!');
            return redirect()->intended('washtype/index');
        } else {
            return redirect()->intended('washtype/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create() {
        $setupincements = SetupIncrement::all()->where('models','=','WashType')->last();
        return view('washtype.create', compact('setupincements'));
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Wash Type ' . $request->wash_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);
        SetupIncrement::updateOrCreate([
            'models' => 'WashType'
        ],[
            'models' => 'WashType',
            'last_number' => $request->wash_no,
        ]);
        WashType::create([
            'wash_no' => $request->wash_no,
            'wash_type' => $request->wash_type,
        ]);

        Alert::success('Create Successfully!', 'Wash Type ' . $request->wash_no . ' successfully created!');
        return redirect()
            ->route('washtype.create');
    }

    public function delete($id) {
        $washtypes = WashType::find($id);    
        $washtypes->delete();
        
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Wash Type ' . $washtypes->wash_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);
        Alert::success('Delete Successfully!', 'Wash Type ' . $washtypes->wash_no . ' successfully deleted!');
        return redirect('washtype/index');
    }

    public function find($id) {
        $washtypes = WashType::find($id);
        return view('washtype.update', compact('washtypes'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated Wash Type ' . $request->wash_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);
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

        Alert::success('Update Successfully!', 'Wash Type ' . $request->wash_no . ' successfully updated!');
        return redirect('washtype/index');
    }
}
