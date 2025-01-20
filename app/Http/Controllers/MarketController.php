<?php

namespace App\Http\Controllers;

use App\Imports\MarketsImport;
use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MarketController extends Controller
{
    public function index() {
        $markets   = Market::all();
        return view('market.index', compact('markets'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/',$nama_file);
        $import = Excel::import(new MarketsImport(), storage_path('app/public/excel/'.$nama_file));
        Storage::delete($path);

        if($import) {
            return redirect()->intended('market/index')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            return redirect()->intended('market/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create() {
        $markets = Market::all()->last();
        return view('market.create', compact('markets'));
    }

    public function store(Request $request)
    {
        Market::create([
            'market_no' => $request->market_no,
            'market_name' => $request->market_name,
        ]);

        return redirect()
            ->route('market.create')
            ->with('success', 'Market berhasil ditambahkan!');
    }

    public function delete($id) {
        $markets = Market::find($id);    
        $markets->delete();
        return redirect('market/index')->with(['error' => 'Record Berhasil Dihapus!']);
    }

    public function find($id) {
        $markets = Market::find($id);
        return view('market.update', compact('markets'));
    }

    public function update(Request $request)
    {
        $markets = Market::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'market_no' => 'required|max:225|',
            'market_name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $markets->fill([
            'market_no' => $request->market_no,
            'market_name' => $request->market_name,
        ]);

        $markets->save();

        return redirect('market/index')->with(['success' => 'Market berhasil diupdate!']);
    }
}
