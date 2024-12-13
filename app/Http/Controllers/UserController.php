<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function delete($id) {
        $users = User::find($id);    
        $users->delete();
        return redirect()->intended('listuser')->with(['error' => 'Record Berhasil Dihapus!']);
    }

    public function detail($id) {
        $users = User::find($id);   
        $roles = ['Admin', 'HRD', 'Payroll'];
        return view('auth.detail', ['user' => $users, 'roles' => $roles]);
    }

    public function update(Request $request)
    {
        $user = User::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:225|',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->fill([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        $user->save();

        return redirect()->intended('listuser')->with(['success' => 'Update User Berhasil!']);
    }
}
