<?php

namespace App\Http\Controllers;

use App\Imports\MarketsImport;
use App\Models\LogCiiper;
use App\Models\Market;
use App\Models\SetupIncrement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class MarketController extends Controller
{
    public function index() {
        $markets   = Market::all();
        return view('market.index', compact('markets'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/',$nama_file);
        $import = Excel::import(new MarketsImport(), storage_path('app/public/excel/'.$nama_file));
        Storage::delete($path);

        if($import) {
            Alert::success('Import Successfully!', 'Market data successfully imported!');
            return redirect()->intended('market/index');
        } else {
            return redirect()->intended('market/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create() {
        $setupincements = SetupIncrement::all()->where('models','=','Market')->last();
        return view('market.create', compact('setupincements'));
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Market ' . $request->market_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);
        SetupIncrement::updateOrCreate([
            'models' => 'Market'
        ],[
            'models' => 'Market',
            'last_number' => $request->market_no,
        ]);
        Market::create([
            'market_no' => $request->market_no,
            'market_name' => $request->market_name,
        ]);

        Alert::success('Create Successfully!', 'Market ' . $request->market_no . ' successfully created!');
        return redirect()
            ->route('market.create');
    }

    public function delete($id) {
        $markets = Market::find($id);    
        $markets->delete();
        
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Market ' . $markets->market_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);
        Alert::success('Delete Successfully!', 'Market ' . $markets->market_no . ' successfully deleted!');
        return redirect('market/index');
    }

    public function find($id) {
        $markets = Market::find($id);
        return view('market.update', compact('markets'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated Market ' . $request->market_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $markets = Market::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'market_no' => 'required|max:225|',
            'market_name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $markets->fill([
            'market_no' => $request->market_no,
            'market_name' => $request->market_name,
        ]);

        $markets->save();

        Alert::success('Update Successfully!', 'Market ' . $request->market_no . ' successfully updated!');
        return redirect('market/index');
    }
}
