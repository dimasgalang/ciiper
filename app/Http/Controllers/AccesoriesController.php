<?php

namespace App\Http\Controllers;

use App\Models\Accesories;
use App\Models\Category;
use App\Models\LogCiiper;
use App\Models\SetupIncrement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class AccesoriesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->void) {
            $accesories   = Accesories::select('accesories.*', 'category.category_name')
                ->where('accesories.void', '=', $request->void)
                ->leftJoin('category', 'category.category_no', '=', 'accesories.category_no')
                ->get();
        } else {
            $accesories   = Accesories::select('accesories.*', 'category.category_name')
                ->where('accesories.void', '=', 'false')
                ->leftJoin('category', 'category.category_no', '=', 'accesories.category_no')
                ->get();
        }
        return view('accesories.index', compact('accesories'));
    }

    public function create()
    {
        $setupincements = SetupIncrement::all()->where('models', '=', 'Accesories')->last();
        $categories = Category::all();
        return view('accesories.create', compact('setupincements', 'categories'));
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Accesories ' . $request->accesories_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);
        SetupIncrement::updateOrCreate([
            'models' => 'Accesories'
        ], [
            'models' => 'Accesories',
            'last_number' => $request->accesories_no,
        ]);
        Accesories::create([
            'accesories_no' => $request->accesories_no,
            'category_no' => $request->category_no,
            'accesories_name' => $request->accesories_name,
            'void' => 'false'
        ]);

        Alert::success('Create Successfully!', 'Accesories ' . $request->accesories_no . ' successfully created!');
        return redirect()
            ->route('accesories.create');
    }

    public function delete($id)
    {
        $accesories = Accesories::find($id);
        $accesories->delete();

        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Accesories ' . $accesories->accesories_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);
        Alert::success('Delete Successfully!', 'Accesories ' . $accesories->accesories_no . ' successfully deleted!');
        return redirect('accesories/index');
    }

    public function find($id)
    {
        $accesories = Accesories::find($id);
        $categories = Category::all();
        return view('accesories.update', compact('accesories', 'categories'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated Accesories ' . $request->accesories_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);
        $accesories = Accesories::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'accesories_no' => 'required|max:255|',
            'category_no' => 'required|max:255|',
            'accesories_name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $accesories->fill([
            'accesories_no' => $request->accesories_no,
            'category_no' => $request->category_no,
            'accesories_name' => $request->accesories_name,
        ]);

        $accesories->save();

        Alert::success('Update Successfully!', 'Accesories ' . $accesories->accesories_no . ' successfully updated!');
        return redirect('accesories/index');
    }


    public function void(Request $request)
    {
        $accesories = Accesories::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Void Accesories ' . $accesories->accesories_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $accesories->fill([
            'void' => 'true',
        ]);

        $accesories->save();

        Alert::success('Void Successfully!', 'Accesories ' . $accesories->accesories_no . ' successfully voided!');
        return redirect('accesories/index');
    }

    public function restore(Request $request)
    {
        $accesories = Accesories::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Restore Accesories ' . $accesories->accesories_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $accesories->fill([
            'void' => 'false',
        ]);

        $accesories->save();

        Alert::success('Restore Successfully!', 'Accesories ' . $accesories->accesories_no . ' successfully restored!');
        return redirect('accesories/index');
    }
}
