<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{
    public function index() {
        $employees = DB::connection('sqlsrv')->table('BIODATA')->select('BIODATA.*', 'DEPT.DEPARTEMENT')->leftJoin('DEPT', 'DEPT.ID_DEPT', '=', 'BIODATA.ID_DEPT')->get();
        return view('karyawan.index', compact('employees'));
    }
}
