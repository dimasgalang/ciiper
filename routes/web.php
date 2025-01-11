<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\FabricationController;
use App\Http\Controllers\FabricMillController;
use App\Http\Controllers\FactoryController;
use App\Http\Controllers\FingerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ModulController;
use App\Http\Controllers\OrderListController;
use App\Http\Controllers\OrderMasterController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\RafProductionController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\StyleController;
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
    Route::get('/', function () {
        return view('welcome');
    });

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
    Route::get('/karyawan/detail/{id}', [KaryawanController::class, 'show'])->name('karyawan.detail');
    
    //Fingerprint
    Route::get('/fingerprint/tarik-data', [FingerController::class, 'tarikdata'])->name('fingerprint.tarik-data');
    
    //Buyer
    Route::get('/buyer/index', [BuyerController::class, 'index'])->name('buyer.index');
    Route::get('/buyer/delete/{id}', [BuyerController::class, 'delete'])->name('buyer.delete');
    Route::get('/buyer/create', [BuyerController::class, 'create'])->name('buyer.create');
    Route::post('/buyer/store', [BuyerController::class, 'store'])->name('buyer.store');
    
    //Brand
    Route::get('/brand/index', [BrandController::class, 'index'])->name('brand.index');
    Route::get('/brand/delete/{id}', [BrandController::class, 'delete'])->name('brand.delete');
    Route::get('/brand/create', [BrandController::class, 'create'])->name('brand.create');
    Route::post('/brand/store', [BrandController::class, 'store'])->name('brand.store');
    
    //Style
    Route::get('/style/index', [StyleController::class, 'index'])->name('style.index');
    Route::get('/style/delete/{id}', [StyleController::class, 'delete'])->name('style.delete');
    Route::get('/style/create', [StyleController::class, 'create'])->name('style.create');
    Route::post('/style/store', [StyleController::class, 'store'])->name('style.store');

    //Season
    Route::get('/season/index', [SeasonController::class, 'index'])->name('season.index');
    Route::get('/season/delete/{id}', [SeasonController::class, 'delete'])->name('season.delete');
    Route::get('/season/create', [SeasonController::class, 'create'])->name('season.create');
    Route::post('/season/store', [SeasonController::class, 'store'])->name('season.store');

    //Master PO
    Route::get('/po/index', [PurchaseOrderController::class, 'index'])->name('po.index');
    Route::get('/po/delete/{id}', [PurchaseOrderController::class, 'delete'])->name('po.delete');
    Route::get('/po/create', [PurchaseOrderController::class, 'create'])->name('po.create');
    Route::post('/po/store', [PurchaseOrderController::class, 'store'])->name('po.store');

    //Order Master
    Route::get('/ordermaster/index', [OrderMasterController::class, 'index'])->name('ordermaster.index');
    Route::get('/ordermaster/delete/{id}', [OrderMasterController::class, 'delete'])->name('ordermaster.delete');
    Route::get('/ordermaster/create', [OrderMasterController::class, 'create'])->name('ordermaster.create');
    Route::post('/ordermaster/store', [OrderMasterController::class, 'store'])->name('ordermaster.store');
    Route::get('/ordermaster/detail/{order_trans}', [OrderMasterController::class, 'show'])->name('ordermaster.detail');

    //Order List
    Route::get('/orderlist/index', [OrderListController::class, 'index'])->name('orderlist.index');
    Route::get('/orderlist/delete/{id}', [OrderListController::class, 'delete'])->name('orderlist.delete');
    Route::get('/orderlist/create', [OrderListController::class, 'create'])->name('orderlist.create');
    Route::post('/orderlist/store', [OrderListController::class, 'store'])->name('orderlist.store');
    Route::get('/orderlist/detail/{order_trans}', [OrderListController::class, 'show'])->name('orderlist.detail');
    
    //Fabrication
    Route::get('/fabrication/index', [FabricationController::class, 'index'])->name('fabrication.index');
    Route::get('/fabrication/delete/{id}', [FabricationController::class, 'delete'])->name('fabrication.delete');
    Route::get('/fabrication/create', [FabricationController::class, 'create'])->name('fabrication.create');
    Route::post('/fabrication/store', [FabricationController::class, 'store'])->name('fabrication.store');
    
    //Fabrication Mill
    Route::get('/fabricmill/index', [FabricMillController::class, 'index'])->name('fabricmill.index');
    Route::get('/fabricmill/delete/{id}', [FabricMillController::class, 'delete'])->name('fabricmill.delete');
    Route::get('/fabricmill/create', [FabricMillController::class, 'create'])->name('fabricmill.create');
    Route::post('/fabricmill/store', [FabricMillController::class, 'store'])->name('fabricmill.store');
    
    //Factory
    Route::get('/factory/index', [FactoryController::class, 'index'])->name('factory.index');
    Route::get('/factory/delete/{id}', [FactoryController::class, 'delete'])->name('factory.delete');
    Route::get('/factory/create', [FactoryController::class, 'create'])->name('factory.create');
    Route::post('/factory/store', [FactoryController::class, 'store'])->name('factory.store');
    
    //RAF Production
    Route::get('/rafproduction/index', [RafProductionController::class, 'index'])->name('rafproduction.index');
    Route::get('/rafproduction/delete/{id}', [RafProductionController::class, 'delete'])->name('rafproduction.delete');
    Route::get('/rafproduction/create', [RafProductionController::class, 'create'])->name('rafproduction.create');
    Route::post('/rafproduction/store', [RafProductionController::class, 'store'])->name('rafproduction.store');

    //Import
    Route::post('/user/import', [UserController::class, 'import'])->name('user.import');
    Route::post('/buyer/import', [BuyerController::class, 'import'])->name('buyer.import');
    Route::post('/brand/import', [BrandController::class, 'import'])->name('brand.import');
    Route::post('/style/import', [StyleController::class, 'import'])->name('style.import');
    Route::post('/season/import', [SeasonController::class, 'import'])->name('season.import');
    Route::post('/po/import', [PurchaseOrderController::class, 'import'])->name('po.import');
    Route::post('/ordermaster/import', [OrderMasterController::class, 'import'])->name('ordermaster.import');
    Route::post('/orderlist/import', [OrderListController::class, 'import'])->name('orderlist.import');
    Route::post('/fabrication/import', [FabricationController::class, 'import'])->name('fabrication.import');
    Route::post('/fabricmill/import', [FabricMillController::class, 'import'])->name('fabricmill.import');
    Route::post('/factory/import', [FactoryController::class, 'import'])->name('factory.import');
    Route::post('/rafproduction/import', [RafProductionController::class, 'import'])->name('rafproduction.import');
});

