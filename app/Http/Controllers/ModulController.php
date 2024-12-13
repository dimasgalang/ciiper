<?php

namespace App\Http\Controllers;

use App\Models\Modul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ModulController extends Controller
{
    public function index() {
        $moduls   = Modul::all();
        return view('modul.list-modul', compact('moduls'));
    }

    public function delete($id) {
        $moduls = Modul::find($id);    
        $moduls->delete();
        Storage::disk('modul_uploads')->delete($moduls->generated_name);
        return redirect('modul/daftar')->with(['error' => 'Record Berhasil Dihapus!']);
    }

    public function create() {
        return view('modul.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:docx,pdf|max:10240'
        ]);

        $file = $request->file('file');
        $fileName = $file->hashName();
        $file->storeAs('', $fileName, 'modul_uploads');

        Modul::create([
            'judul' => $request->judul,
            'hit' => $request->hit,
            'original_name' => $file->getClientOriginalName(),
            'generated_name' => $fileName
        ]);

        return redirect()
            ->route('modul.daftar')
            ->with('success', 'File berhasil diupload');
    }
}
