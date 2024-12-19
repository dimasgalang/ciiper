<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModulResource;
use App\Models\Modul;
use Illuminate\Http\Request;

class ModulController extends Controller
{
    public function index()
    {
        $moduls = Modul::get();
        return new ModulResource(true, 'List Data Modul', $moduls);
    }

    public function show(Modul $modul)
    {
        //return single post as a resource
        return new ModulResource(true, 'Data Modul Ditemukan!', $modul);
    }
}
