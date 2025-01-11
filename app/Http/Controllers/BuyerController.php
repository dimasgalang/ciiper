<?php

namespace App\Http\Controllers;

use App\Imports\BuyersImport;
use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class BuyerController extends Controller
{
    public function index() {
        $buyers   = Buyer::all();
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
            return redirect()->intended('buyer/index')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            return redirect()->intended('buyer/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create() {
        return view('buyer.create');
    }

    public function store(Request $request)
    {
        Buyer::create([
            'buyer_no' => $request->buyer_no,
            'buyer_name' => $request->buyer_name,
            'buyer_address' => $request->buyer_address,
            'buyer_contact' => $request->buyer_contact,
        ]);

        return redirect()
            ->route('buyer.create')
            ->with('success', 'Buyer berhasil ditambahkan!');
    }

    public function delete($id) {
        $buyers = Buyer::find($id);    
        $buyers->delete();
        return redirect('buyer/index')->with(['error' => 'Record Berhasil Dihapus!']);
    }
}
