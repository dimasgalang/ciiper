<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use App\Models\ModelHasRoles;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function delete($id) {
        $users = User::find($id);    
        $users->delete();
        return redirect()->intended('listuser')->with(['error' => 'Record Berhasil Dihapus!']);
    }

    public function detail($id) {
        $users = User::find($id); 
        return view('auth.detail', ['users' => $users]);
    }

    public function assign($id) {
        $modelhasroles = User::select('users.name','users.email','users.id','model_has_roles.*')
        ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
        ->where('users.id', '=', $id)
        ->get();
        $roles = Role::all();
        return view('auth.assign', compact('modelhasroles','roles'));
    }

    public function assignrole(Request $request)
    {
        $modelhasroles = ModelHasRoles::where('model_id', '=', $request->id)
        ->where('model_type', '=', 'App\Models\User')
        ->delete();
        // dd($modelhasroles);
        // $modelhasroles->delete();
        ModelHasRoles::updateOrCreate(
        [
            'role_id' => $request->role_id,
            'model_type' => 'App\Models\User',
            'model_id' => $request->id
            ]
        );

        return redirect()->intended('listuser')->with(['success' => 'Assign Role User Berhasil!']);
    }

    public function update(Request $request)
    {
        $user = User::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:225|',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->fill([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $user->save();

        return redirect()->intended('listuser')->with(['success' => 'Update User Berhasil!']);
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/',$nama_file);
        $import = Excel::import(new UsersImport(), storage_path('app/public/excel/'.$nama_file));
        Storage::delete($path);

        if($import) {
            return redirect()->intended('listuser')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            return redirect()->intended('listuser')->with(['error' => 'Data Gagal Diimport!']);
        }
    }
}
