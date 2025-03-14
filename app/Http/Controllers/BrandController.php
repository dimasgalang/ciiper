<?php

namespace App\Http\Controllers;

use App\Imports\BrandsImport;
use App\Models\Brand;
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

class BrandController extends Controller
{
    public function index(Request $request) {
        $brands   = Brand::select('brand.*', 'buyer.buyer_name')
        ->leftJoin('buyer', 'brand.buyer_no', '=', 'buyer.buyer_no')
        ->where('void','=',$request->void)
        ->get();
        return view('brand.index', compact('brands'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/',$nama_file);
        $import = Excel::import(new BrandsImport(), storage_path('app/public/excel/'.$nama_file));
        Storage::delete($path);

        if($import) {
            Alert::success('Import Successfully!', 'Brand data successfully imported!');
            return redirect()->intended('brand/index');
        } else {
            return redirect()->intended('brand/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create() {
        $buyers   = Buyer::all();
        $setupincements = SetupIncrement::all()->where('models','=','Brand')->last();
        $genders = ['Mens', 'Ladies'];
        return view('brand.create', compact('buyers', 'setupincements', 'genders'));
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Brand ' . $request->brand_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);
        SetupIncrement::updateOrCreate([
            'models' => 'Brand'
        ],[
            'models' => 'Brand',
            'last_number' => $request->brand_no,
        ]);
        Brand::create([
            'buyer_no' => $request->buyer_no,
            'brand_no' => $request->brand_no,
            'brand_name' => $request->brand_name,
            'brand_gender' => $request->brand_gender,
            'void' => 'false'
        ]);

        Alert::success('Create Successfully!', 'Brand ' . $request->brand_no . ' successfully created!');
        return redirect()
            ->route('brand.create');
    }

    public function delete($id) {
        $brands = Brand::find($id);    
        $brands->delete();
        
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Brand ' . $brands->brand_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);
        Alert::success('Delete Successfully!', 'Brand ' . $brands->brand_no . ' successfully deleted!');
        return redirect('brand/index');
    }

    public function find($id) {
        $brands = Brand::find($id);
        $genders = ['Mens', 'Ladies'];
        return view('brand.update', compact('brands', 'genders'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated Brand ' . $request->brand_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $brands = Brand::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'buyer_no' => 'required|max:255',
            'brand_no' => 'required|max:225|',
            'brand_name' => 'required|max:225|',
            'brand_gender' => 'required|max:225|',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $brands->fill([
            'buyer_no' => $request->buyer_no,
            'brand_no' => $request->brand_no,
            'brand_name' => $request->brand_name,
            'brand_gender' => $request->brand_gender,
        ]);

        $brands->save();

        Alert::success('Update Successfully!', 'Brand ' . $request->brand_no . ' successfully updated!');
        return redirect()->intended('brand/index');
    }

    public function void(Request $request)
    {
        $brands = Brand::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Void Brand ' . $brands->brand_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $brands->fill([
            'void' => 'true',
        ]);

        $brands->save();

        Alert::success('Void Successfully!', 'Brand ' . $brands->brand_no . ' successfully voided!');
        return redirect('brand/index');
    }

    public function restore(Request $request)
    {
        $brands = Brand::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Restore Brand ' . $brands->brand_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $brands->fill([
            'void' => 'false',
        ]);

        $brands->save();

        Alert::success('Restore Successfully!', 'Brand ' . $brands->brand_no . ' successfully restored!');
        return redirect('brand/index');
    }
}
