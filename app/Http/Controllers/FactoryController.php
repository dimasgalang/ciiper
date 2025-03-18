<?php

namespace App\Http\Controllers;

use App\Imports\FactorysImport;
use App\Models\Factory;
use App\Models\LogCiiper;
use App\Models\SetupIncrement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class FactoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->void) {
            $factorys   = Factory::select('*')
                ->where('void', '=', $request->void)
                ->get();
        } else {
            $factorys   = Factory::select('*')
                ->where('void', '=', 'false')
                ->get();
        }
        return view('factory.index', compact('factorys'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/', $nama_file);
        $import = Excel::import(new FactorysImport(), storage_path('app/public/excel/' . $nama_file));
        Storage::delete($path);

        if ($import) {
            Alert::success('Import Successfully!', 'Factory data successfully imported!');
            return redirect()->intended('factory/index');
        } else {
            return redirect()->intended('factory/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create()
    {
        $setupincements = SetupIncrement::all()->where('models', '=', 'Factory')->last();
        return view('factory.create', compact('setupincements'));
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Factory ' . $request->factory_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);
        SetupIncrement::updateOrCreate([
            'models' => 'Factory'
        ], [
            'models' => 'Factory',
            'last_number' => $request->factory_no,
        ]);
        Factory::create([
            'factory_no' => $request->factory_no,
            'factory_name' => $request->factory_name,
            'void' => 'false'
        ]);

        Alert::success('Create Successfully!', 'Factory ' . $request->factory_no . ' successfully created!');
        return redirect()
            ->route('factory.create');
    }

    public function delete($id)
    {
        $factorys = Factory::find($id);
        $factorys->delete();

        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Factory ' . $factorys->factory_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);
        Alert::success('Delete Successfully!', 'Factory ' . $factorys->factory_no . ' successfully deleted!');
        return redirect('factory/index');
    }

    public function find($id)
    {
        $factorys = Factory::find($id);
        return view('factory.update', compact('factorys'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated Factory ' . $request->factory_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Factory ' . $request->factory_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);

        $factorys = Factory::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'factory_no' => 'required|max:255|',
            'factory_name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $factorys->fill([
            'factory_no' => $request->factory_no,
            'factory_name' => $request->factory_name,
        ]);

        $factorys->save();

        Alert::success('Update Successfully!', 'Factory ' . $request->factory_no . ' successfully updated!');
        return redirect('factory/index');
    }


    public function void(Request $request)
    {
        $factorys = Factory::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Void Factory ' . $factorys->factory_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $factorys->fill([
            'void' => 'true',
        ]);

        $factorys->save();

        Alert::success('Void Successfully!', 'Factory ' . $factorys->factory_no . ' successfully voided!');
        return redirect('factory/index');
    }

    public function restore(Request $request)
    {
        $factorys = Factory::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Restore Factory ' . $factorys->factory_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $factorys->fill([
            'void' => 'false',
        ]);

        $factorys->save();

        Alert::success('Restore Successfully!', 'Factory ' . $factorys->factory_no . ' successfully restored!');
        return redirect('factory/index');
    }
}
