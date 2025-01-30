<?php

namespace App\Http\Controllers;

use App\Imports\BrandsImport;
use App\Models\Brand;
use App\Models\Buyer;
use App\Models\SetupIncrement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class BrandController extends Controller
{
    public function index() {
        $brands   = Brand::select('brand.*', 'buyer.buyer_name')
        ->leftJoin('buyer', 'brand.buyer_no', '=', 'buyer.buyer_no')
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
            return redirect()->intended('brand/index')->with(['success' => 'Data Berhasil Diimport!']);
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
        ]);

        return redirect()
            ->route('brand.create')
            ->with('success', 'Brand berhasil ditambahkan!');
    }

    public function delete($id) {
        $brands = Brand::find($id);    
        $brands->delete();
        return redirect('brand/index')->with(['error' => 'Record Berhasil Dihapus!']);
    }

    public function find($id) {
        $brands = Brand::find($id);
        $genders = ['Mens', 'Ladies'];
        return view('brand.update', compact('brands', 'genders'));
    }

    public function update(Request $request)
    {
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

        return redirect()->intended('brand/index')->with(['success' => 'Update Brand Berhasil!']);
    }
}
