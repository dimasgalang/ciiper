<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\FollowUpsImport;
use App\Models\FollowUp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class FollowUpController extends Controller
{
    public function index() {
        $followups   = FollowUp::all();
        return view('followup.index', compact('followups'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/',$nama_file);
        $import = Excel::import(new FollowUpsImport(), storage_path('app/public/excel/'.$nama_file));
        Storage::delete($path);

        if($import) {
            return redirect()->intended('followup/index')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            return redirect()->intended('followup/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create() {
        $followups = FollowUp::all()->last();
        return view('followup.create', compact('followups'));
    }

    public function store(Request $request)
    {
        FollowUp::create([
            'fu_no' => $request->fu_no,
            'fu_name' => $request->fu_name,
        ]);

        return redirect()
            ->route('followup.create')
            ->with('success', 'Follow Up berhasil ditambahkan!');
    }

    public function delete($id) {
        $followups = FollowUp::find($id);    
        $followups->delete();
        return redirect('followup/index')->with(['error' => 'Record Berhasil Dihapus!']);
    }

    public function find($id) {
        $followups = FollowUp::find($id);
        return view('followup.update', compact('followups'));
    }

    public function update(Request $request)
    {
        $followups = FollowUp::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'fu_no' => 'required|max:225|',
            'fu_name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $followups->fill([
            'fu_no' => $request->fu_no,
            'fu_name' => $request->fu_name,
        ]);

        $followups->save();

        return redirect('followup/index')->with(['success' => 'Follow Up berhasil diupdate!']);
    }
}
