<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BiodataResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BiodataController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get posts
        $biodatas = DB::connection('sqlsrv')->table('BIODATA')->select('BIODATA.*', 'DEPT.DEPARTEMENT')->leftJoin('DEPT', 'DEPT.ID_DEPT', '=', 'BIODATA.ID_DEPT')->get();

        //return collection of posts as a resource
        return new BiodataResource(true, 'List Data Biodata', $biodatas);
    }
}
