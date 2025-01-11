<?php

namespace App\Http\Controllers;

use App\Imports\BrandsImport;
use App\Models\Brand;
use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class BrandController extends Controller
{
    public function index() {
        $brands   = Brand::all();
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
        return view('brand.create', compact('buyers'));
    }

    public function store(Request $request)
    {
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
        $buyers = Brand::find($id);    
        $buyers->delete();
        return redirect('brand/index')->with(['error' => 'Record Berhasil Dihapus!']);
    }
}
