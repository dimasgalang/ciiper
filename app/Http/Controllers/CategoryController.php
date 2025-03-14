<?php

namespace App\Http\Controllers;

use App\Imports\CategorysImport;
use App\Models\Category;
use App\Models\LogCiiper;
use App\Models\SetupIncrement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class CategoryController extends Controller
{
    public function index(Request $request) {
        $categories   = Category::select('*')
        ->where('void','=',$request->void)
        ->get();
        return view('category.index', compact('categories'));
    }

    public function create() {
        $setupincements = SetupIncrement::all()->where('models','=','Category')->last();
        return view('category.create', compact('setupincements'));
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Category ' . $request->category_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);
        SetupIncrement::updateOrCreate([
            'models' => 'Category'
        ],[
            'models' => 'Category',
            'last_number' => $request->category_no,
        ]);
        Category::create([
            'category_no' => $request->category_no,
            'category_name' => $request->category_name,
            'void' => 'false'
        ]);

        Alert::success('Create Successfully!', 'Category ' . $request->category_no . ' successfully created!');
        return redirect()
            ->route('category.create');
    }

    public function delete($id) {
        $categories = Category::find($id);    
        $categories->delete();

        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Category ' . $categories->category_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);
        Alert::success('Delete Successfully!', 'Category ' . $categories->category_no . ' successfully deleted!');
        return redirect('category/index');
    }

    public function find($id) {
        $categories = Category::find($id);
        return view('category.update', compact('categories'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated Category ' . $request->category_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);
        $categories = Category::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'category_no' => 'required|max:225|',
            'category_name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $categories->fill([
            'category_no' => $request->category_no,
            'category_name' => $request->category_name,
        ]);

        $categories->save();

        Alert::success('Update Successfully!', 'Category ' . $categories->category_no . ' successfully updated!');
        return redirect('category/index');
    }

    
    public function void(Request $request)
    {
        $categories = Category::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Void Category ' . $categories->category_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $categories->fill([
            'void' => 'true',
        ]);

        $categories->save();

        Alert::success('Void Successfully!', 'Category ' . $categories->category_no . ' successfully voided!');
        return redirect('category/index');
    }

    public function restore(Request $request)
    {
        $categories = Category::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Restore Category ' . $categories->category_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $categories->fill([
            'void' => 'false',
        ]);

        $categories->save();

        Alert::success('Restore Successfully!', 'Category ' . $categories->category_no . ' successfully restored!');
        return redirect('category/index');
    }
}
