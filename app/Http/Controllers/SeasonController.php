<?php

namespace App\Http\Controllers;

use App\Imports\SeasonsImport;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SeasonController extends Controller
{
    public function index() {
        $seasons   = Season::all();
        return view('season.index', compact('seasons'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/',$nama_file);
        $import = Excel::import(new SeasonsImport(), storage_path('app/public/excel/'.$nama_file));
        Storage::delete($path);

        if($import) {
            return redirect()->intended('season/index')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            return redirect()->intended('season/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create() {
        return view('season.create');
    }

    public function store(Request $request)
    {
        Season::create([
            'season_no' => $request->season_no,
            'season_cat' => $request->season_cat,
            'season_year' => $request->season_year,
        ]);

        return redirect()
            ->route('season.create')
            ->with('success', 'Season berhasil ditambahkan!');
    }

    public function delete($id) {
        $seasons = Season::find($id);    
        $seasons->delete();
        return redirect('season/index')->with(['error' => 'Record Berhasil Dihapus!']);
    }
}
