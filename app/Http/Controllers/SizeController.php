<?php

namespace App\Http\Controllers;

use App\Imports\SizesImport;
use App\Models\Size;
use Illuminate\Http\Request;
use App\Models\LogCiiper;
use App\Models\SetupIncrement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class SizeController extends Controller
{
    public function index(Request $request) {
        $sizes   = Size::select('*')
        ->where('void','=',$request->void)
        ->get();
        return view('size.index', compact('sizes'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/',$nama_file);
        $import = Excel::import(new SizesImport(), storage_path('app/public/excel/'.$nama_file));
        Storage::delete($path);

        if($import) {
            Alert::success('Import Successfully!', 'Size data successfully imported!');
            return redirect()->intended('size/index');
        } else {
            return redirect()->intended('size/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create() {
        $setupincements = SetupIncrement::all()->where('models','=','Size')->last();
        return view('size.create', compact('setupincements'));
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Size ' . $request->size_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);
        SetupIncrement::updateOrCreate([
            'models' => 'Size'
        ],[
            'models' => 'Size',
            'last_number' => $request->size_no,
        ]);
        Size::create([
            'size_no' => $request->size_no,
            'size' => $request->size,
            'void' => 'false'
        ]);

        Alert::success('Create Successfully!', 'Size ' . $request->size_no . ' successfully created!');
        return redirect()
            ->route('size.create');
    }

    public function delete($id) {
        $sizes = Size::find($id);    
        $sizes->delete();
        
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Size ' . $sizes->size_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);
        Alert::success('Delete Successfully!', 'Size ' . $sizes->size_no . ' successfully deleted!');
        return redirect('size/index');
    }

    public function find($id) {
        $sizes = Size::find($id);
        return view('size.update', compact('sizes'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated Size ' . $request->size_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);
        $sizes = Size::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'size_no' => 'required|max:225|',
            'size' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $sizes->fill([
            'size_no' => $request->size_no,
            'size' => $request->size,
        ]);

        $sizes->save();

        Alert::success('Update Successfully!', 'Size ' . $request->size_no . ' successfully updated!');
        return redirect('size/index');
    }

    
    public function void(Request $request)
    {
        $sizes = Size::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Void Size ' . $sizes->size_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $sizes->fill([
            'void' => 'true',
        ]);

        $sizes->save();

        Alert::success('Void Successfully!', 'Size ' . $sizes->size_no . ' successfully voided!');
        return redirect('size/index');
    }

    public function restore(Request $request)
    {
        $sizes = Size::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Restore Size ' . $sizes->size_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $sizes->fill([
            'void' => 'false',
        ]);

        $sizes->save();

        Alert::success('Restore Successfully!', 'Size ' . $sizes->size_no . ' successfully restored!');
        return redirect('size/index');
    }
}
