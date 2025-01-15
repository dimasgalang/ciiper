<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\StylesImport;
use App\Models\Brand;
use App\Models\Style;
use App\Models\Buyer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
            'style_desc' => $request->style_desc,
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

    public function find($id) {
        $styles = Style::find($id);
        return view('style.update', compact('styles'));
    }

    public function update(Request $request)
    {
        $styles = Style::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'brand_no' => 'required|max:225|',
            'style_no' => 'required|max:255',
            'style_name' => 'required|max:225|',
            'style_desc' => 'required|max:225|',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $styles->fill([
            'brand_no' => $request->brand_no,
            'style_no' => $request->style_no,
            'style_name' => $request->style_name,
            'style_desc' => $request->style_desc,
        ]);

        $styles->save();

        return redirect('style/index')->with(['success' => 'Style berhasil diupdate!']);
    }
}
