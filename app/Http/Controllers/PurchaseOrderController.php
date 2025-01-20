<?php

namespace App\Http\Controllers;

use App\Imports\PurchaseOrdersImport;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
    
    public function create() {
        $pos = PurchaseOrder::all()->last();
        $potypes = ['E', 'C', 'K'];
        return view('po.create', compact('pos', 'potypes'));
    }

    public function store(Request $request)
    {
        PurchaseOrder::create([
            'po_no' => $request->po_no,
            'po_master' => $request->po_master,
            'po_desc' => $request->po_desc,
        ]);

        return redirect()
            ->route('po.create')
            ->with('success', 'Master PO berhasil ditambahkan!');
    }

    public function delete($id) {
        $pos = PurchaseOrder::find($id);    
        $pos->delete();
        return redirect('po/index')->with(['error' => 'Record Berhasil Dihapus!']);
    }

    public function find($id) {
        $pos = PurchaseOrder::find($id);
        return view('po.update', compact('pos'));
    }

    public function update(Request $request)
    {
        $pos = PurchaseOrder::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'po_no' => 'required|max:225|',
            'po_master' => 'required|max:255',
            'po_desc' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $pos->fill([
            'po_no' => $request->po_no,
            'po_master' => $request->po_master,
            'po_desc' => $request->po_desc,
        ]);

        $pos->save();

        return redirect('po/index')->with(['success' => 'Master PO berhasil diupdate!']);
    }
}
