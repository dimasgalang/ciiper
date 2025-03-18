<?php

namespace App\Http\Controllers;

use App\Imports\FabricMillsImport;
use App\Models\FabricMill;
use App\Models\LogCiiper;
use App\Models\SetupIncrement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class FabricMillController extends Controller
{
    public function index(Request $request)
    {
        if ($request->void) {
            $fabricmills   = FabricMill::select('*')
                ->where('void', '=', $request->void)
                ->get();
        } else {
            $fabricmills   = FabricMill::select('*')
                ->where('void', '=', 'false')
                ->get();
        }
        return view('fabricmill.index', compact('fabricmills'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/', $nama_file);
        $import = Excel::import(new FabricMillsImport(), storage_path('app/public/excel/' . $nama_file));
        Storage::delete($path);

        if ($import) {
            Alert::success('Create Successfully!', 'Fabric Mill data successfully imported!');
            return redirect()->intended('fabricmill/index');
        } else {
            return redirect()->intended('fabricmill/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create()
    {
        $setupincements = SetupIncrement::all()->where('models', '=', 'FabricMill')->last();
        return view('fabricmill.create', compact('setupincements'));
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Fabric Mill ' . $request->fabmill_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);

        SetupIncrement::updateOrCreate([
            'models' => 'FabricMill'
        ], [
            'models' => 'FabricMill',
            'last_number' => $request->fabmill_no,
        ]);
        FabricMill::create([
            'fabmill_no' => $request->fabmill_no,
            'fabmill_name' => $request->fabmill_name,
            'void' => 'false'
        ]);

        Alert::success('Create Successfully!', 'Fabric Mill ' . $request->fabmill_no . ' successfully created!');
        return redirect()
            ->route('fabricmill.create');
    }

    public function delete($id)
    {
        $fabricmills = FabricMill::find($id);
        $fabricmills->delete();

        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Fabric Mill ' . $fabricmills->fabmill_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);
        Alert::success('Delete Successfully!', 'Fabric Mill ' . $fabricmills->fabmill_no . ' successfully deleted!');
        return redirect('fabricmill/index');
    }

    public function find($id)
    {
        $fabricmills = FabricMill::find($id);
        return view('fabricmill.update', compact('fabricmills'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated Fabric Mill ' . $request->fabmill_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $fabricmills = FabricMill::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'fabmill_no' => 'required|max:225|',
            'fabmill_name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $fabricmills->fill([
            'fabmill_no' => $request->fabmill_no,
            'fabmill_name' => $request->fabmill_name,
        ]);

        $fabricmills->save();

        Alert::success('Update Successfully!', 'Fabric Mill ' . $request->fabmill_no . ' successfully updated!');
        return redirect('fabricmill/index');
    }


    public function void(Request $request)
    {
        $fabricmills = FabricMill::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Void Fabric Mill ' . $fabricmills->fabmill_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $fabricmills->fill([
            'void' => 'true',
        ]);

        $fabricmills->save();

        Alert::success('Void Successfully!', 'Fabric Mill ' . $fabricmills->fabmill_no . ' successfully voided!');
        return redirect('fabricmill/index');
    }

    public function restore(Request $request)
    {
        $fabricmills = FabricMill::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Restore Fabric Mill ' . $fabricmills->fabmill_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $fabricmills->fill([
            'void' => 'false',
        ]);

        $fabricmills->save();

        Alert::success('Restore Successfully!', 'Fabric Mill ' . $fabricmills->fabmill_no . ' successfully restored!');
        return redirect('fabricmill/index');
    }
}
