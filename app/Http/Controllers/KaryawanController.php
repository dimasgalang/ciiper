<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{
    public function index() {
        $employees = DB::connection('sqlsrv')->table('BIODATA')->select('BIODATA.*', 'DEPT.DEPARTEMENT')->leftJoin('DEPT', 'DEPT.ID_DEPT', '=', 'BIODATA.ID_DEPT')->orderBy('DEPARTEMENT', 'ASC')->get();
        return view('karyawan.index', compact('employees'));
    }

    public function show($id) {
        $employees = DB::connection('sqlsrv')->table('BIODATA')->select('PKWT.*', 'DEPT.DEPARTEMENT', 'BIODATA.ID_DEPT')->leftJoin('DEPT', 'DEPT.ID_DEPT', '=', 'BIODATA.ID_DEPT')->leftJoin('PKWT', 'BIODATA.NPK', '=', 'PKWT.NPK')->where('BIODATA.NPK', '=', $id)->get();
        return response()->json($employees);
    }
}
