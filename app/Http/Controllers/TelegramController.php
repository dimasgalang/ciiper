<?php

namespace App\Http\Controllers;

use App\Notifications\TelegramNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use App\Models\TelegramModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Storage;
use Telegram\Bot\FileUpload\InputFile;
use Illuminate\Support\Facades\Auth;

class TelegramController extends Controller
{
    public function index() {
        $telegrams = DB::connection('sqlsrv')->table('TELEGRAM')->select('*')->get();
        return view('telegram.index', compact('telegrams'));
    }

    public function message() {
        $messages = DB::connection('sqlsrv')->table('TELEGRAM_MESSAGE')->select('TELEGRAM_MESSAGE.*', 'TELEGRAM.NAME')
        ->leftJoin('TELEGRAM', 'TELEGRAM.CHATID', '=', 'TELEGRAM_MESSAGE.CHATID')->get();
        return view('telegram.message', compact('messages'));
    }

    public function indexblast() {
        return view('telegram.index-blast');
    }

    public function send(Request $request) {
        $message = $request->message;
        $chatid = $request->chatid;
        $storeTime = Carbon::now();
        $username = Auth::user()->name;

        Telegram::sendMessage([
                    'chat_id' => $chatid,
                    'text' => $message,
                    ]);

        $this->validate($request, [
            'chatid' => ['required'],
            'message' => ['required'],
        ]);

        DB::connection('sqlsrv')->table('TELEGRAM_MESSAGE')->insert([
            ['chatid' => $chatid, 'message' => $message, 'type' => 'Text', 'created_at' => $storeTime->toDateTimeString(), 'send_by' => $username],
        ]);

        return redirect('telegram/index')->with(['success' => 'Pesan Telegram Berhasil Terkirim!']);
    }

    public function sendblast(Request $request) {
        $telegrams = DB::connection('sqlsrv')->table('TELEGRAM')->select('*')->where('CHATID', '=', '1062965337')->get();
        $message = $request->message;
        $storeTime = Carbon::now();
        $username = Auth::user()->name;
        foreach($telegrams as $telegram) {
            Telegram::sendDocument([
                'chat_id' => $telegram->CHATID,
                'document' => new InputFile(public_path('Slip Gaji/Slip Gaji - '.$telegram->CHATID.'.pdf')),
                'caption' => $message . ' ' . $telegram->NAME,
            ]);
            DB::connection('sqlsrv')->table('TELEGRAM_MESSAGE')->insert([
                ['chatid' => $telegram->CHATID, 'message' => $message, 'type' => 'Document', 'file_url' => public_path('Slip Gaji/Slip Gaji - '.$telegram->CHATID.'.pdf'), 'created_at' => $storeTime->toDateTimeString(), 'send_by' => $username],
            ]);
        }
        return redirect('telegram/indexblast')->with(['success' => 'Telegram Blast Berhasil Terkirim!']);
    }

    public function delete($id) {
        DB::connection('sqlsrv')->delete('DELETE FROM TELEGRAM_MESSAGE WHERE id = ?',[$id]);
        return redirect('telegram/message')->with(['error' => 'Record Berhasil Dihapus!']);
    }
}
