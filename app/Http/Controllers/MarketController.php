<?php

namespace App\Http\Controllers;

use App\Imports\MarketsImport;
use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $market = Market::find($id);    
        $market->delete();
        return redirect('market/index')->with(['error' => 'Record Berhasil Dihapus!']);
    }
}
