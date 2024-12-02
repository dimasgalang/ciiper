<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemplateController extends Controller
{
    public function slipgaji() {
        $pegawais = DB::connection('sqlsrv')->table('TELEGRAM')->select('*')->get();
        return view('template.slipgaji', ['index' => '0', 'pegawai' => $pegawais]);
    }

    public function generateslip(){
        $pegawais = DB::connection('sqlsrv')->table('TELEGRAM')->select('*')->get();

        for($i = 0; $i < count($pegawais); $i++) {
            $pdf = PDF::loadview('template.slipgaji',['index' => $i, 'pegawai' => $pegawais])->save(public_path('Slip Gaji/Slip Gaji - '.$pegawais[$i]->CHATID.'.pdf'));
        }
        
        return redirect('home')->with(['success' => 'Generate Slip Gaji Berhasil!']);
    }
}
