<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ModulController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UserController;
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
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register/guest', [RegisterController::class, 'store'])->name('register.guest');
    
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login');
});

Route::group(['middleware' => 'auth'], function () {
    //Auth
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/listuser', [HomeController::class, 'listuser'])->name('listuser');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/register/create', [RegisterController::class, 'create'])->name('register.create');
    Route::post('/register', [RegisterController::class, 'storeAuth'])->name('register');

    //User
    Route::get('/user/delete/{id}', [UserController::class, 'delete'])->name('user.delete');
    Route::get('/user/detail/{id}', [UserController::class, 'detail'])->name('user.detail');
    Route::post('/user/update', [UserController::class, 'update'])->name('user.update');
    
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

    //Modul
    Route::get('/modul/daftar', [ModulController::class, 'index'])->name('modul.daftar');
    Route::get('/modul/delete/{id}', [ModulController::class, 'delete'])->name('modul.delete');
    Route::get('/modul/create', [ModulController::class, 'create'])->name('modul.create');
    Route::post('/modul/store', [ModulController::class, 'store'])->name('modul.store');

    //Karyawan
    Route::get('/karyawan/daftar', [KaryawanController::class, 'index'])->name('karyawan.daftar');
});

