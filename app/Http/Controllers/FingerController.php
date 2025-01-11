<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FingerController extends Controller
{
    public function tarikdata() {
        return view('fingerprint.tarik-data');
    }
}
