<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\StylesImport;
use App\Models\Brand;
use App\Models\Style;
use App\Models\Buyer;
use App\Models\LogCiiper;
use App\Models\SetupIncrement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class StyleController extends Controller
{
    public function index() {
        $styles   = Style::select('style.*', 'brand.brand_name')
        ->leftJoin('brand', 'style.brand_no', '=', 'brand.brand_no')
        ->get();
        return view('style.index', compact('styles'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/',$nama_file);
        $import = Excel::import(new StylesImport(), storage_path('app/public/excel/'.$nama_file));
        Storage::delete($path);

        if($import) {
            Alert::success('Import Successfully!', 'Style data successfully imported!');
            return redirect()->intended('style/index');
        } else {
            return redirect()->intended('style/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create() {
        $brands   = Brand::all();
        $setupincements = SetupIncrement::all()->where('models','=','Style')->last();
        return view('style.create', compact('brands', 'setupincements'));
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Style ' . $request->style_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);

        SetupIncrement::updateOrCreate([
            'models' => 'Style'
        ],[
            'models' => 'Style',
            'last_number' => $request->style_no,
        ]);
        Style::create([
            'brand_no' => $request->brand_no,
            'style_no' => $request->style_no,
            'style_name' => $request->style_name,
            'style_desc' => $request->style_desc,
        ]);

        Alert::success('Create Successfully!', 'Style ' . $request->style_no . ' successfully created!');
        return redirect()
            ->route('style.create');
    }

    public function delete($id) {
        $styles = Style::find($id);    
        $styles->delete();
        
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Style ' . $styles->style_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);

        Alert::success('Delete Successfully!', 'Style ' . $styles->style_no . ' successfully deleted!');
        return redirect('style/index');
    }

    public function find($id) {
        $styles = Style::find($id);
        return view('style.update', compact('styles'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated Style ' . $request->style_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);


        $styles = Style::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'brand_no' => 'required|max:225|',
            'style_no' => 'required|max:255',
            'style_name' => 'required|max:225|',
            'style_desc' => 'required|max:225|',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $styles->fill([
            'brand_no' => $request->brand_no,
            'style_no' => $request->style_no,
            'style_name' => $request->style_name,
            'style_desc' => $request->style_desc,
        ]);

        $styles->save();

        Alert::success('Update Successfully!', 'Style ' . $request->style_no . ' successfully updated!');
        return redirect('style/index');
    }
}
