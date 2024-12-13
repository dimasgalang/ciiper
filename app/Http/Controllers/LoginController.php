<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $username = Auth::user()->name;
            $storeTime = Carbon::now();
            DB::connection('sqlsrv')->table('LOG_CIIPER')->insert([
                ['username' => $username, 'activity' => 'Signed In', 'time' => $storeTime->toDateTimeString(), 'icon' => 'link', 'color' => 'bg-success'],
            ]);
            return redirect()->intended('/home')->with(['success' => 'Berhasil Login!']);
        };

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    public function logout()
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        Auth::logout();
        DB::connection('sqlsrv')->table('LOG_CIIPER')->insert([
            ['username' => $username, 'activity' => 'Signed Out', 'time' => $storeTime->toDateTimeString(), 'icon' => 'unlink', 'color' => 'bg-danger'],
        ]);
        return redirect('/login')->with(['success' => 'Berhasil Logout!']);
    }
}
