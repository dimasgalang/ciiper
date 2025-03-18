<?php

namespace App\Http\Controllers;

use App\Imports\SeasonsImport;
use App\Models\LogCiiper;
use App\Models\Season;
use App\Models\SetupIncrement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class SeasonController extends Controller
{
    public function index(Request $request)
    {
        if ($request->void) {
            $seasons   = Season::select('*')
                ->where('void', '=', $request->void)
                ->get();
        } else {
            $seasons   = Season::select('*')
                ->where('void', '=', 'false')
                ->get();
        }
        return view('season.index', compact('seasons'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/', $nama_file);
        $import = Excel::import(new SeasonsImport(), storage_path('app/public/excel/' . $nama_file));
        Storage::delete($path);

        if ($import) {
            Alert::success('Import Successfully!', 'Season data successfully imported!');
            return redirect()->intended('season/index');
        } else {
            return redirect()->intended('season/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create()
    {
        $setupincements = SetupIncrement::all()->where('models', '=', 'Season')->last();
        $seasonscats = ['SUMMER', 'SPRING', 'FALL', 'WINTER'];
        return view('season.create', compact('setupincements', 'seasonscats'));
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Season ' . $request->season_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);

        SetupIncrement::updateOrCreate([
            'models' => 'Season'
        ], [
            'models' => 'Season',
            'last_number' => $request->season_no,
        ]);
        Season::create([
            'season_no' => $request->season_no,
            'season_cat' => $request->season_cat,
            'season_year' => $request->season_year,
            'void' => 'false'
        ]);

        Alert::success('Create Successfully!', 'Season ' . $request->season_no . ' successfully created!');
        return redirect()
            ->route('season.create');
    }

    public function delete($id)
    {
        $seasons = Season::find($id);
        $seasons->delete();

        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Season ' . $seasons->season_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);

        Alert::success('Delete Successfully!', 'Season ' . $seasons->season_no . ' successfully deleted!');
        return redirect('season/index');
    }

    public function find($id)
    {
        $seasons = Season::find($id);
        $seasonscats = ['SUMMER', 'SPRING', 'FALL', 'WINTER'];
        return view('season.update', compact('seasons', 'seasonscats'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated Season ' . $request->season_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $seasons = Season::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'season_no' => 'required|max:225|',
            'season_cat' => 'required|max:255',
            'season_year' => 'required|max:225|',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $seasons->fill([
            'season_no' => $request->season_no,
            'season_cat' => $request->season_cat,
            'season_year' => $request->season_year,
        ]);

        $seasons->save();

        Alert::success('Update Successfully!', 'Season ' . $request->season_no . ' successfully updated!');
        return redirect('season/index');
    }


    public function void(Request $request)
    {
        $seasons = Season::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Void Season ' . $seasons->season_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $seasons->fill([
            'void' => 'true',
        ]);

        $seasons->save();

        Alert::success('Void Successfully!', 'Season ' . $seasons->season_no . ' successfully voided!');
        return redirect('season/index');
    }

    public function restore(Request $request)
    {
        $seasons = Season::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Restore Season ' . $seasons->season_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $seasons->fill([
            'void' => 'false',
        ]);

        $seasons->save();

        Alert::success('Restore Successfully!', 'Season ' . $seasons->season_no . ' successfully restored!');
        return redirect('season/index');
    }
}
