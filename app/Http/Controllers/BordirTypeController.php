<?php

namespace App\Http\Controllers;

use App\Imports\BordirTypesImport;
use App\Models\BordirType;
use App\Models\LogCiiper;
use App\Models\SetupIncrement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class BordirTypeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->void) {
            $bordirtypes = BordirType::select('*')->where('void', '=', $request->void)->get();
        } else {
            $bordirtypes = BordirType::select('*')->where('void', '=', 'false')->get();
        }
        return view('bordirtype.index', compact('bordirtypes'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/', $nama_file);
        $import = Excel::import(new BordirTypesImport(), storage_path('app/public/excel/' . $nama_file));
        Storage::delete($path);

        if ($import) {
            Alert::success('Import Successfully!', 'Bordir type data successfully imported!');
            return redirect()->intended('bordirtype/index')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            return redirect()->intended('bordirtype/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create()
    {
        $setupincements = SetupIncrement::all()->where('models', '=', 'BordirType')->last();
        return view('bordirtype.create', compact('setupincements'));
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Bordir Type ' . $request->bordir_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);
        SetupIncrement::updateOrCreate([
            'models' => 'BordirType'
        ], [
            'models' => 'BordirType',
            'last_number' => $request->bordir_no,
        ]);
        BordirType::create([
            'bordir_no' => $request->bordir_no,
            'bordir_type' => $request->bordir_type,
            'void' => 'false',
        ]);

        Alert::success('Create Successfully!', 'Bordir Type ' . $request->bordir_no . ' successfully created!');
        return redirect()
            ->route('bordirtype.create');
    }

    public function delete($id)
    {
        $bordirtypes = BordirType::find($id);
        $bordirtypes->delete();

        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Bordir Type ' . $bordirtypes->bordir_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);
        Alert::success('Delete Successfully!', 'Bordir type ' . $bordirtypes->bordir_no . ' successfully deleted!');
        return redirect('bordirtype/index');
    }

    public function find($id)
    {
        $bordirtypes = BordirType::find($id);
        return view('bordirtype.update', compact('bordirtypes'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated Bordir Type ' . $request->bordir_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);
        $bordirtypes = BordirType::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'bordir_no' => 'required|max:225|',
            'bordir_type' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $bordirtypes->fill([
            'bordir_no' => $request->bordir_no,
            'bordir_type' => $request->bordir_type,
        ]);

        $bordirtypes->save();

        Alert::success('Update Successfully!', 'Bordir type ' . $bordirtypes->bordir_no . ' successfully updated!');
        return redirect('bordirtype/index');
    }

    public function void(Request $request)
    {
        $bordirtypes = BordirType::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Void Bordir Type ' . $bordirtypes->bordir_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $bordirtypes->fill([
            'void' => 'true',
        ]);

        $bordirtypes->save();

        Alert::success('Void Successfully!', 'Bordir type ' . $bordirtypes->bordir_no . ' successfully voided!');
        return redirect('bordirtype/index');
    }

    public function restore(Request $request)
    {
        $bordirtypes = BordirType::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Restore Bordir Type ' . $bordirtypes->bordir_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $bordirtypes->fill([
            'void' => 'false',
        ]);

        $bordirtypes->save();

        Alert::success('Restore Successfully!', 'Bordir type ' . $bordirtypes->bordir_no . ' successfully restored!');
        return redirect('bordirtype/index');
    }
}
