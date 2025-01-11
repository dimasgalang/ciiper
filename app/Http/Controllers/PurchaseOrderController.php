<?php

namespace App\Http\Controllers;

use App\Imports\PurchaseOrdersImport;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseOrderController extends Controller
{
    public function index() {
        $pos   = PurchaseOrder::all();
        return view('po.index', compact('pos'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/',$nama_file);
        $import = Excel::import(new PurchaseOrdersImport(), storage_path('app/public/excel/'.$nama_file));
        Storage::delete($path);

        if($import) {
            return redirect()->intended('po/index')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            return redirect()->intended('po/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }
}
