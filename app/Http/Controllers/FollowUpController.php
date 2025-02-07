<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\FollowUpsImport;
use App\Models\FollowUp;
use App\Models\LogCiiper;
use App\Models\SetupIncrement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

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
            Alert::success('Import Successfully!', 'Follow Up data successfully imported!');
            return redirect()->intended('followup/index');
        } else {
            return redirect()->intended('followup/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create() {
        $setupincements = SetupIncrement::all()->where('models','=','FollowUp')->last();
        return view('followup.create', compact('setupincements'));
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Follow Up ' . $request->fu_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);
        SetupIncrement::updateOrCreate([
            'models' => 'FollowUp'
        ],[
            'models' => 'FollowUp',
            'last_number' => $request->fu_no,
        ]);

        FollowUp::create([
            'fu_no' => $request->fu_no,
            'fu_name' => $request->fu_name,
        ]);

        Alert::success('Create Successfully!', 'Follow Up ' . $request->fu_no . ' successfully created!');
        return redirect()
            ->route('followup.create');
    }

    public function delete($id) {
        $followups = FollowUp::find($id);    
        $followups->delete();
        
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Follow Up ' . $followups->fu_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);
        Alert::success('Delete Successfully!', 'Follow Up ' . $followups->fu_no . ' successfully deleted!');
        return redirect('followup/index');
    }

    public function find($id) {
        $followups = FollowUp::find($id);
        return view('followup.update', compact('followups'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated Follow Up ' . $request->fu_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

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

        Alert::success('Update Successfully!', 'Follow Up ' . $request->fu_no . ' successfully updated!');
        return redirect('followup/index');
    }
}
