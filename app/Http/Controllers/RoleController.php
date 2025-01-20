<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index() {
        $roles = Role::all();
         return view('role.index', compact('roles'));
    }

    public function create() {
        return view('role.create');
    }

    public function store(Request $request)
    {
        Role::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);

        return redirect()
            ->route('role.create')
            ->with('success', 'Role berhasil ditambahkan!');
    }

    public function delete($id) {
        $roles = Role::find($id);    
        $roles->delete();
        return redirect('role/index')->with(['error' => 'Role Berhasil Dihapus!']);
    }
}
