<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\StylesImport;
use App\Models\Brand;
use App\Models\Style;
use App\Models\Buyer;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class StyleController extends Controller
{
    public function index() {
        $styles   = Style::all();
        return view('style.index', compact('styles'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/',$nama_file);
        $import = Excel::import(new StylesImport(), storage_path('app/public/excel/'.$nama_file));
        Storage::delete($path);

        if($import) {
            return redirect()->intended('style/index')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            return redirect()->intended('style/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create() {
        $brands   = Brand::all();
        return view('style.create', compact('brands'));
    }

    public function store(Request $request)
    {
        Style::create([
            'brand_no' => $request->brand_no,
            'style_no' => $request->style_no,
            'style_name' => $request->style_name,
        ]);

        return redirect()
            ->route('style.create')
            ->with('success', 'Style berhasil ditambahkan!');
    }

    public function delete($id) {
        $buyers = Style::find($id);    
        $buyers->delete();
        return redirect('style/index')->with(['error' => 'Record Berhasil Dihapus!']);
    }
}
