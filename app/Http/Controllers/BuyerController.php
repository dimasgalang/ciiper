<?php

namespace App\Http\Controllers;

use App\Imports\BuyersImport;
use App\Models\Buyer;
use App\Models\LogCiiper;
use App\Models\SetupIncrement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class BuyerController extends Controller
{
    public function index(Request $request) {
        $buyers   = Buyer::select('*')
        ->where('void','=',$request->void)
        ->get();
        return view('buyer.index', compact('buyers'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/',$nama_file);
        $import = Excel::import(new BuyersImport(), storage_path('app/public/excel/'.$nama_file));
        Storage::delete($path);

        if($import) {
            Alert::success('Import Successfully!', 'Buyer data successfully imported!');
            return redirect()->intended('buyer/index');
        } else {
            return redirect()->intended('buyer/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create() {
        $setupincements = SetupIncrement::all()->where('models','=','Buyer')->last();
        return view('buyer.create', compact('setupincements'));
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Buyer ' . $request->buyer_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);

        SetupIncrement::updateOrCreate([
            'models' => 'Buyer'
        ],[
            'models' => 'Buyer',
            'last_number' => $request->buyer_no,
        ]);
        Buyer::create([
            'buyer_no' => $request->buyer_no,
            'buyer_name' => $request->buyer_name,
            'buyer_address' => $request->buyer_address,
            'buyer_contact' => $request->buyer_contact,
            'void' => 'false'
        ]);

        Alert::success('Create Successfully!', 'Buyer ' . $request->buyer_no . ' successfully created!');
        return redirect()
            ->route('buyer.create');
    }

    public function delete($id) {
        $buyers = Buyer::find($id);    
        $buyers->delete();
        
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Buyer ' . $buyers->buyer_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);
        
        Alert::success('Delete Successfully!', 'Buyer ' . $buyers->buyer_no . ' successfully deleted!');
        return redirect('buyer/index');
    }

    public function find($id) {
        $buyers = Buyer::find($id);
        return view('buyer.update', compact('buyers'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated Buyer ' . $request->buyer_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);
        
        $buyers = Buyer::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'buyer_no' => 'required|max:255',
            'buyer_name' => 'required|max:225|',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $buyers->fill([
            'buyer_no' => $request->buyer_no,
            'buyer_name' => $request->buyer_name,
            'buyer_address' => $request->buyer_address,
            'buyer_contact' => $request->buyer_contact,
        ]);

        $buyers->save();

        Alert::success('Update Successfully!', 'Buyer ' . $request->buyer_no . ' successfully updated!');
        return redirect()->intended('buyer/index');
    }

    
    public function void(Request $request)
    {
        $buyers = Buyer::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Void Buyer ' . $buyers->buyer_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $buyers->fill([
            'void' => 'true',
        ]);

        $buyers->save();

        Alert::success('Void Successfully!', 'Buyer ' . $buyers->buyer_no . ' successfully voided!');
        return redirect('buyer/index');
    }

    public function restore(Request $request)
    {
        $buyers = Buyer::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Restore Buyer ' . $buyers->buyer_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $buyers->fill([
            'void' => 'false',
        ]);

        $buyers->save();

        Alert::success('Restore Successfully!', 'Buyer ' . $buyers->buyer_no . ' successfully restored!');
        return redirect('buyer/index');
    }
}
