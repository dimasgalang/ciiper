<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\TemplateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Auth
Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register');
    
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    //Telegram
    Route::get('/telegram/index', [TelegramController::class, 'index'])->name('telegram.index');
    Route::get('/telegram/indexblast', [TelegramController::class, 'indexblast'])->name('telegram.indexblast');
    Route::post('/telegram/send', [TelegramController::class, 'send'])->name('telegram.send');
    Route::post('/telegram/sendblast', [TelegramController::class, 'sendblast'])->name('telegram.sendblast');
    Route::get('/telegram/message', [TelegramController::class, 'message'])->name('telegram.message');
    Route::get('/telegram/delete/{id}', [TelegramController::class, 'delete'])->name('telegram.delete');

    //Template
    Route::get('/template/slipgaji', [TemplateController::class, 'slipgaji'])->name('template.slipgaji');
    Route::get('/template/generateslip', [TemplateController::class, 'generateslip'])->name('template.generateslip');

});

