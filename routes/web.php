<?php

use App\Http\Controllers\AccesoriesController;
use App\Http\Controllers\AlertReminderController;
use App\Http\Controllers\BordirTypeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FabricationController;
use App\Http\Controllers\FabricMillController;
use App\Http\Controllers\FactoryController;
use App\Http\Controllers\FingerController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\ModulController;
use App\Http\Controllers\OrderListController;
use App\Http\Controllers\OrderMasterController;
use App\Http\Controllers\OrderSizeController;
use App\Http\Controllers\ProductionDeptController;
use App\Http\Controllers\ProductionPlanningController;
use App\Http\Controllers\ProPlanAccController;
use App\Http\Controllers\ProPlanDetailController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\RafProductionController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\ShipModeController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\StyleController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WashTypeController;
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

Route::get('/', [LoginController::class, 'login'])->name('/');

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
    Route::get('/listuser', [HomeController::class, 'listuser'])->name('listuser')->middleware(['auth', 'role:Admin|User']);
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/register/create', [RegisterController::class, 'create'])->name('register.create')->middleware(['auth', 'role:Admin']);
    Route::post('/register', [RegisterController::class, 'storeAuth'])->name('register')->middleware(['auth', 'role:Admin']);

    //Role
    Route::get('/role/index', [RoleController::class, 'index'])->name('role.index')->middleware(['auth', 'role:Admin']);
    Route::get('/role/delete/{id}', [RoleController::class, 'delete'])->name('role.delete')->middleware(['auth', 'role:Admin']);
    Route::get('/role/create', [RoleController::class, 'create'])->name('role.create')->middleware(['auth', 'role:Admin']);
    Route::post('/role/store', [RoleController::class, 'store'])->name('role.store')->middleware(['auth', 'role:Admin']);
    Route::get('/role/find/{id}', [RoleController::class, 'find'])->name('role.find')->middleware(['auth', 'role:Admin']);
    Route::post('/role/update', [RoleController::class, 'update'])->name('role.update')->middleware(['auth', 'role:Admin']);

    //User
    Route::get('/user/delete/{id}', [UserController::class, 'delete'])->name('user.delete')->middleware(['auth', 'role:Admin']);
    Route::get('/user/detail/{id}', [UserController::class, 'detail'])->name('user.detail')->middleware(['auth', 'role:Admin']);
    Route::get('/user/assign/{id}', [UserController::class, 'assign'])->name('user.assign')->middleware(['auth', 'role:Admin']);
    Route::post('/user/update', [UserController::class, 'update'])->name('user.update')->middleware(['auth', 'role:Admin']);
    Route::post('/user/assignrole', [UserController::class, 'assignrole'])->name('user.assignrole')->middleware(['auth', 'role:Admin']);

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
    Route::get('/buyer/void/{id}', [BuyerController::class, 'void'])->name('buyer.void');
    Route::get('/buyer/restore/{id}', [BuyerController::class, 'restore'])->name('buyer.restore');
    Route::get('/buyer/create', [BuyerController::class, 'create'])->name('buyer.create');
    Route::post('/buyer/store', [BuyerController::class, 'store'])->name('buyer.store');
    Route::get('/buyer/find/{id}', [BuyerController::class, 'find'])->name('buyer.find');
    Route::post('/buyer/update', [BuyerController::class, 'update'])->name('buyer.update');

    //Brand
    Route::get('/brand/index', [BrandController::class, 'index'])->name('brand.index');
    Route::get('/brand/delete/{id}', [BrandController::class, 'delete'])->name('brand.delete');
    Route::get('/brand/void/{id}', [BrandController::class, 'void'])->name('brand.void');
    Route::get('/brand/restore/{id}', [BrandController::class, 'restore'])->name('brand.restore');
    Route::get('/brand/create', [BrandController::class, 'create'])->name('brand.create');
    Route::post('/brand/store', [BrandController::class, 'store'])->name('brand.store');
    Route::get('/brand/find/{id}', [BrandController::class, 'find'])->name('brand.find');
    Route::post('/brand/update', [BrandController::class, 'update'])->name('brand.update');

    //Style
    Route::get('/style/index', [StyleController::class, 'index'])->name('style.index');
    Route::get('/style/delete/{id}', [StyleController::class, 'delete'])->name('style.delete');
    Route::get('/style/void/{id}', [StyleController::class, 'void'])->name('style.void');
    Route::get('/style/restore/{id}', [StyleController::class, 'restore'])->name('style.restore');
    Route::get('/style/create', [StyleController::class, 'create'])->name('style.create');
    Route::post('/style/store', [StyleController::class, 'store'])->name('style.store');
    Route::get('/style/find/{id}', [StyleController::class, 'find'])->name('style.find');
    Route::post('/style/update', [StyleController::class, 'update'])->name('style.update');

    //Season
    Route::get('/season/index', [SeasonController::class, 'index'])->name('season.index');
    Route::get('/season/delete/{id}', [SeasonController::class, 'delete'])->name('season.delete');
    Route::get('/season/void/{id}', [SeasonController::class, 'void'])->name('season.void');
    Route::get('/season/restore/{id}', [SeasonController::class, 'restore'])->name('season.restore');
    Route::get('/season/create', [SeasonController::class, 'create'])->name('season.create');
    Route::post('/season/store', [SeasonController::class, 'store'])->name('season.store');
    Route::get('/season/find/{id}', [SeasonController::class, 'find'])->name('season.find');
    Route::post('/season/update', [SeasonController::class, 'update'])->name('season.update');

    //Purchase Order
    Route::get('/po/index', [PurchaseOrderController::class, 'index'])->name('po.index');
    Route::get('/po/delete/{id}', [PurchaseOrderController::class, 'delete'])->name('po.delete');
    Route::get('/po/void/{id}', [PurchaseOrderController::class, 'void'])->name('po.void');
    Route::get('/po/restore/{id}', [PurchaseOrderController::class, 'restore'])->name('po.restore');
    Route::get('/po/create', [PurchaseOrderController::class, 'create'])->name('po.create');
    Route::post('/po/store', [PurchaseOrderController::class, 'store'])->name('po.store');
    Route::get('/po/find/{id}', [PurchaseOrderController::class, 'find'])->name('po.find');
    Route::post('/po/update', [PurchaseOrderController::class, 'update'])->name('po.update');

    //Order Master
    Route::get('/ordermaster/index', [OrderMasterController::class, 'index'])->name('ordermaster.index');
    Route::get('/ordermaster/create', [OrderMasterController::class, 'create'])->name('ordermaster.create');
    Route::get('/ordermaster/delete/{id}', [OrderMasterController::class, 'delete'])->name('ordermaster.delete');
    Route::get('/ordermaster/void/{id}', [OrderMasterController::class, 'void'])->name('ordermaster.void');
    Route::get('/ordermaster/restore/{id}', [OrderMasterController::class, 'restore'])->name('ordermaster.restore');
    Route::get('/ordermaster/find/{id}', [OrderMasterController::class, 'find'])->name('ordermaster.find');
    Route::post('/ordermaster/update', [OrderMasterController::class, 'update'])->name('ordermaster.update');
    Route::post('/ordermaster/store', [OrderMasterController::class, 'store'])->name('ordermaster.store');
    Route::get('/ordermaster/orderlist/{order_trans}', [OrderMasterController::class, 'showlist'])->name('ordermaster.orderlist');
    Route::get('/ordermaster/ordersize/{order_trans}', [OrderMasterController::class, 'showordersize'])->name('ordermaster.ordersize');
    Route::get('/ordermaster/shipment/{order_trans}', [OrderMasterController::class, 'showshipment'])->name('ordermaster.shipment');
    Route::get('/ordermaster/rafproduction/{order_trans}', [OrderMasterController::class, 'showrafproduction'])->name('ordermaster.rafproduction');
    Route::get('/ordermaster/rafcutting/{order_trans}', [OrderMasterController::class, 'showrafcutting'])->name('ordermaster.rafcutting');
    Route::post('/ordermaster/rafcutting/{order_trans}', [OrderMasterController::class, 'showrafcutting'])->name('ordermaster.rafcutting');
    Route::get('/ordermaster/rafsewing/{order_trans}', [OrderMasterController::class, 'showrafsewing'])->name('ordermaster.rafsewing');
    Route::post('/ordermaster/rafsewing/{order_trans}', [OrderMasterController::class, 'showrafsewing'])->name('ordermaster.rafsewing');
    Route::get('/ordermaster/rafiron/{order_trans}', [OrderMasterController::class, 'showrafiron'])->name('ordermaster.rafiron');
    Route::post('/ordermaster/rafiron/{order_trans}', [OrderMasterController::class, 'showrafiron'])->name('ordermaster.rafiron');
    Route::get('/ordermaster/rafpacking/{order_trans}', [OrderMasterController::class, 'showrafpacking'])->name('ordermaster.rafpacking');
    Route::post('/ordermaster/rafpacking/{order_trans}', [OrderMasterController::class, 'showrafpacking'])->name('ordermaster.rafpacking');
    Route::get('/ordermaster/fab/{order_trans}', [OrderMasterController::class, 'showfab'])->name('ordermaster.fab');
    Route::get('/ordermaster/style/{order_trans}', [OrderMasterController::class, 'showstyle'])->name('ordermaster.style');
    Route::get('/ordermaster/productionplanning/{order_trans}', [OrderMasterController::class, 'showproductionplanning'])->name('ordermaster.productionplanning');
    Route::get('/ordermaster/fetchbrand/{buyer_no}', [OrderMasterController::class, 'fetchbrand'])->name('ordermaster.fetchbrand');
    Route::get('/ordermaster/fetchstyle/{brand_no}', [OrderMasterController::class, 'fetchstyle'])->name('ordermaster.fetchstyle');

    //Order List
    Route::get('/orderlist/index', [OrderListController::class, 'index'])->name('orderlist.index');
    Route::get('/orderlist/delete/{id}', [OrderListController::class, 'delete'])->name('orderlist.delete');
    Route::get('/orderlist/void/{id}', [OrderListController::class, 'void'])->name('orderlist.void');
    Route::get('/orderlist/restore/{id}', [OrderListController::class, 'restore'])->name('orderlist.restore');
    Route::get('/orderlist/change/{id}', [OrderListController::class, 'finish'])->name('orderlist.change');
    Route::get('/orderlist/create', [OrderListController::class, 'create'])->name('orderlist.create');
    Route::post('/orderlist/store', [OrderListController::class, 'store'])->name('orderlist.store');
    Route::get('/orderlist/fab/{order_trans}', [OrderListController::class, 'showfab'])->name('orderlist.fab');
    Route::get('/orderlist/showordersize/{order_list}', [OrderListController::class, 'showordersize'])->name('orderlist.showordersize');
    Route::get('/orderlist/fetchorderleft/{order_trans}', [OrderListController::class, 'fetchorderleft'])->name('orderlist.fetchorderleft');
    Route::get('/orderlist/find/{id}', [OrderListController::class, 'find'])->name('orderlist.find');
    Route::post('/orderlist/update', [OrderListController::class, 'update'])->name('orderlist.update');

    //Fabrication
    Route::get('/fabrication/index', [FabricationController::class, 'index'])->name('fabrication.index');
    Route::get('/fabrication/delete/{id}', [FabricationController::class, 'delete'])->name('fabrication.delete');
    Route::get('/fabrication/void/{id}', [FabricationController::class, 'void'])->name('fabrication.void');
    Route::get('/fabrication/restore/{id}', [FabricationController::class, 'restore'])->name('fabrication.restore');
    Route::get('/fabrication/create', [FabricationController::class, 'create'])->name('fabrication.create');
    Route::post('/fabrication/store', [FabricationController::class, 'store'])->name('fabrication.store');
    Route::get('/fabrication/find/{id}', [FabricationController::class, 'find'])->name('fabrication.find');
    Route::post('/fabrication/update', [FabricationController::class, 'update'])->name('fabrication.update');

    //Fabrication Mill
    Route::get('/fabricmill/index', [FabricMillController::class, 'index'])->name('fabricmill.index');
    Route::get('/fabricmill/delete/{id}', [FabricMillController::class, 'delete'])->name('fabricmill.delete');
    Route::get('/fabricmill/void/{id}', [FabricMillController::class, 'void'])->name('fabricmill.void');
    Route::get('/fabricmill/restore/{id}', [FabricMillController::class, 'restore'])->name('fabricmill.restore');
    Route::get('/fabricmill/create', [FabricMillController::class, 'create'])->name('fabricmill.create');
    Route::post('/fabricmill/store', [FabricMillController::class, 'store'])->name('fabricmill.store');
    Route::get('/fabricmill/find/{id}', [FabricMillController::class, 'find'])->name('fabricmill.find');
    Route::post('/fabricmill/update', [FabricMillController::class, 'update'])->name('fabricmill.update');

    //Factory
    Route::get('/factory/index', [FactoryController::class, 'index'])->name('factory.index');
    Route::get('/factory/delete/{id}', [FactoryController::class, 'delete'])->name('factory.delete');
    Route::get('/factory/void/{id}', [FactoryController::class, 'void'])->name('factory.void');
    Route::get('/factory/restore/{id}', [FactoryController::class, 'restore'])->name('factory.restore');
    Route::get('/factory/create', [FactoryController::class, 'create'])->name('factory.create');
    Route::post('/factory/store', [FactoryController::class, 'store'])->name('factory.store');
    Route::get('/factory/find/{id}', [FactoryController::class, 'find'])->name('factory.find');
    Route::post('/factory/update', [FactoryController::class, 'update'])->name('factory.update');

    //RAF Production
    Route::get('/rafproduction/index', [RafProductionController::class, 'index'])->name('rafproduction.index');
    Route::get('/rafproduction/delete/{id}', [RafProductionController::class, 'delete'])->name('rafproduction.delete');
    Route::get('/rafproduction/void/{id}', [RafProductionController::class, 'void'])->name('rafproduction.void');
    Route::get('/rafproduction/restore/{id}', [RafProductionController::class, 'restore'])->name('rafproduction.restore');
    Route::get('/rafproduction/create', [RafProductionController::class, 'create'])->name('rafproduction.create');
    Route::post('/rafproduction/store', [RafProductionController::class, 'store'])->name('rafproduction.store');
    Route::get('/rafproduction/find/{id}', [RafProductionController::class, 'find'])->name('rafproduction.find');
    Route::post('/rafproduction/update', [RafProductionController::class, 'update'])->name('rafproduction.update');
    Route::get('/rafproduction/fetchorderlist/{order_trans}', [RafProductionController::class, 'fetchorderlist'])->name('rafproduction.fetchorderlist');
    Route::get('/rafproduction/fetchordersize/{order_list}', [RafProductionController::class, 'fetchordersize'])->name('rafproduction.fetchordersize');
    Route::get('/rafproduction/fetchrafleft/{order_list}/{raf_dept}/{size_no}', [RafProductionController::class, 'fetchrafleft'])->name('rafproduction.fetchrafleft');
    Route::get('/rafproduction/fetchplanningdate/{order_list}/{raf_dept}', [RafProductionController::class, 'fetchplanningdate'])->name('rafproduction.fetchplanningdate');

    //Ship Mode
    Route::get('/shipmode/index', [ShipModeController::class, 'index'])->name('shipmode.index');
    Route::get('/shipmode/delete/{id}', [ShipModeController::class, 'delete'])->name('shipmode.delete');
    Route::get('/shipmode/void/{id}', [ShipModeController::class, 'void'])->name('shipmode.void');
    Route::get('/shipmode/restore/{id}', [ShipModeController::class, 'restore'])->name('shipmode.restore');
    Route::get('/shipmode/create', [ShipModeController::class, 'create'])->name('shipmode.create');
    Route::post('/shipmode/store', [ShipModeController::class, 'store'])->name('shipmode.store');
    Route::get('/shipmode/find/{id}', [ShipModeController::class, 'find'])->name('shipmode.find');
    Route::post('/shipmode/update', [ShipModeController::class, 'update'])->name('shipmode.update');

    //Market
    Route::get('/market/index', [MarketController::class, 'index'])->name('market.index');
    Route::get('/market/delete/{id}', [MarketController::class, 'delete'])->name('market.delete');
    Route::get('/market/void/{id}', [MarketController::class, 'void'])->name('market.void');
    Route::get('/market/restore/{id}', [MarketController::class, 'restore'])->name('market.restore');
    Route::get('/market/create', [MarketController::class, 'create'])->name('market.create');
    Route::post('/market/store', [MarketController::class, 'store'])->name('market.store');
    Route::get('/market/find/{id}', [MarketController::class, 'find'])->name('market.find');
    Route::post('/market/update', [MarketController::class, 'update'])->name('market.update');

    //Shipment
    Route::get('/shipment/index', [ShipmentController::class, 'index'])->name('shipment.index');
    Route::get('/shipment/delete/{id}', [ShipmentController::class, 'delete'])->name('shipment.delete');
    Route::get('/shipment/void/{id}', [ShipmentController::class, 'void'])->name('shipment.void');
    Route::get('/shipment/restore/{id}', [ShipmentController::class, 'restore'])->name('shipment.restore');
    Route::get('/shipment/create', [ShipmentController::class, 'create'])->name('shipment.create');
    Route::post('/shipment/store', [ShipmentController::class, 'store'])->name('shipment.store');
    Route::get('/shipment/find/{id}', [ShipmentController::class, 'find'])->name('shipment.find');
    Route::post('/shipment/update', [ShipmentController::class, 'update'])->name('shipment.update');
    Route::get('/shipment/fetchorderlist/{order_trans}', [ShipmentController::class, 'fetchorderlist'])->name('shipment.fetchorderlist');
    Route::get('/shipment/fetchreadyship/{order_list}/{size_no}', [ShipmentController::class, 'fetchreadyship'])->name('shipment.fetchreadyship');
    Route::get('/shipment/fetchcartonleft/{order_list}', [ShipmentController::class, 'fetchcartonleft'])->name('shipment.fetchcartonleft');
    Route::get('/shipment/fetchordersize/{order_list}', [ShipmentController::class, 'fetchordersize'])->name('shipment.fetchordersize');

    //Production Dept
    Route::get('/productiondept/index', [ProductionDeptController::class, 'index'])->name('productiondept.index');
    Route::get('/productiondept/delete/{id}', [ProductionDeptController::class, 'delete'])->name('productiondept.delete');
    Route::get('/productiondept/void/{id}', [ProductionDeptController::class, 'void'])->name('productiondept.void');
    Route::get('/productiondept/restore/{id}', [ProductionDeptController::class, 'restore'])->name('productiondept.restore');
    Route::get('/productiondept/create', [ProductionDeptController::class, 'create'])->name('productiondept.create');
    Route::post('/productiondept/store', [ProductionDeptController::class, 'store'])->name('productiondept.store');
    Route::get('/productiondept/find/{id}', [ProductionDeptController::class, 'find'])->name('productiondept.find');
    Route::post('/productiondept/update', [ProductionDeptController::class, 'update'])->name('productiondept.update');

    //Follow Up
    Route::get('/followup/index', [FollowUpController::class, 'index'])->name('followup.index');
    Route::get('/followup/delete/{id}', [FollowUpController::class, 'delete'])->name('followup.delete');
    Route::get('/followup/void/{id}', [FollowUpController::class, 'void'])->name('followup.void');
    Route::get('/followup/restore/{id}', [FollowUpController::class, 'restore'])->name('followup.restore');
    Route::get('/followup/create', [FollowUpController::class, 'create'])->name('followup.create');
    Route::post('/followup/store', [FollowUpController::class, 'store'])->name('followup.store');
    Route::get('/followup/find/{id}', [FollowUpController::class, 'find'])->name('followup.find');
    Route::post('/followup/update', [FollowUpController::class, 'update'])->name('followup.update');

    //Wash Type
    Route::get('/washtype/index', [WashTypeController::class, 'index'])->name('washtype.index');
    Route::get('/washtype/delete/{id}', [WashTypeController::class, 'delete'])->name('washtype.delete');
    Route::get('/washtype/void/{id}', [WashTypeController::class, 'void'])->name('washtype.void');
    Route::get('/washtype/restore/{id}', [WashTypeController::class, 'restore'])->name('washtype.restore');
    Route::get('/washtype/create', [WashTypeController::class, 'create'])->name('washtype.create');
    Route::post('/washtype/store', [WashTypeController::class, 'store'])->name('washtype.store');
    Route::get('/washtype/find/{id}', [WashTypeController::class, 'find'])->name('washtype.find');
    Route::post('/washtype/update', [WashTypeController::class, 'update'])->name('washtype.update');

    //Size
    Route::get('/size/index', [SizeController::class, 'index'])->name('size.index');
    Route::get('/size/delete/{id}', [SizeController::class, 'delete'])->name('size.delete');
    Route::get('/size/void/{id}', [SizeController::class, 'void'])->name('size.void');
    Route::get('/size/restore/{id}', [SizeController::class, 'restore'])->name('size.restore');
    Route::get('/size/create', [SizeController::class, 'create'])->name('size.create');
    Route::post('/size/store', [SizeController::class, 'store'])->name('size.store');
    Route::get('/size/find/{id}', [SizeController::class, 'find'])->name('size.find');
    Route::post('/size/update', [SizeController::class, 'update'])->name('size.update');

    //Category
    Route::get('/category/index', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/category/delete/{id}', [CategoryController::class, 'delete'])->name('category.delete');
    Route::get('/category/void/{id}', [CategoryController::class, 'void'])->name('category.void');
    Route::get('/category/restore/{id}', [CategoryController::class, 'restore'])->name('category.restore');
    Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/category/find/{id}', [CategoryController::class, 'find'])->name('category.find');
    Route::post('/category/update', [CategoryController::class, 'update'])->name('category.update');

    //Accesories
    Route::get('/accesories/index', [AccesoriesController::class, 'index'])->name('accesories.index');
    Route::get('/accesories/delete/{id}', [AccesoriesController::class, 'delete'])->name('accesories.delete');
    Route::get('/accesories/void/{id}', [AccesoriesController::class, 'void'])->name('accesories.void');
    Route::get('/accesories/restore/{id}', [AccesoriesController::class, 'restore'])->name('accesories.restore');
    Route::get('/accesories/create', [AccesoriesController::class, 'create'])->name('accesories.create');
    Route::post('/accesories/store', [AccesoriesController::class, 'store'])->name('accesories.store');
    Route::get('/accesories/find/{id}', [AccesoriesController::class, 'find'])->name('accesories.find');
    Route::post('/accesories/update', [AccesoriesController::class, 'update'])->name('accesories.update');

    //Order Size
    Route::get('/ordersize/index', [OrderSizeController::class, 'index'])->name('ordersize.index');
    Route::get('/ordersize/delete/{id}', [OrderSizeController::class, 'delete'])->name('ordersize.delete');
    Route::get('/ordersize/void/{id}', [OrderSizeController::class, 'void'])->name('ordersize.void');
    Route::get('/ordersize/restore/{id}', [OrderSizeController::class, 'restore'])->name('ordersize.restore');
    Route::get('/ordersize/create', [OrderSizeController::class, 'create'])->name('ordersize.create');
    Route::post('/ordersize/store', [OrderSizeController::class, 'store'])->name('ordersize.store');
    Route::get('/ordersize/find/{id}', [OrderSizeController::class, 'find'])->name('ordersize.find');
    Route::post('/ordersize/update', [OrderSizeController::class, 'update'])->name('ordersize.update');
    Route::get('/ordersize/dcpoleft/{order_list}', [OrderSizeController::class, 'fetchdcpoleft'])->name('ordersize.dcpoleft');
    Route::get('/ordersize/fetchorderlist/{order_trans}', [OrderSizeController::class, 'fetchorderlist'])->name('ordersize.fetchorderlist');

    //Bordir Type
    Route::get('/bordirtype/index', [BordirTypeController::class, 'index'])->name('bordirtype.index');
    Route::post('/bordirtype/index', [BordirTypeController::class, 'index'])->name('bordirtype.index');
    Route::get('/bordirtype/delete/{id}', [BordirTypeController::class, 'delete'])->name('bordirtype.delete');
    Route::get('/bordirtype/void/{id}', [BordirTypeController::class, 'void'])->name('bordirtype.void');
    Route::get('/bordirtype/restore/{id}', [BordirTypeController::class, 'restore'])->name('bordirtype.restore');
    Route::get('/bordirtype/create', [BordirTypeController::class, 'create'])->name('bordirtype.create');
    Route::post('/bordirtype/store', [BordirTypeController::class, 'store'])->name('bordirtype.store');
    Route::get('/bordirtype/find/{id}', [BordirTypeController::class, 'find'])->name('bordirtype.find');
    Route::post('/bordirtype/update', [BordirTypeController::class, 'update'])->name('bordirtype.update');

    //Production Planning
    Route::get('/productionplanning/index', [ProductionPlanningController::class, 'index'])->name('productionplanning.index');
    Route::get('/productionplanning/delete/{id}', [ProductionPlanningController::class, 'delete'])->name('productionplanning.delete');
    Route::get('/productionplanning/void/{id}', [ProductionPlanningController::class, 'void'])->name('productionplanning.void');
    Route::get('/productionplanning/restore/{id}', [ProductionPlanningController::class, 'restore'])->name('productionplanning.restore');
    Route::get('/productionplanning/create', [ProductionPlanningController::class, 'create'])->name('productionplanning.create');
    Route::post('/productionplanning/store', [ProductionPlanningController::class, 'store'])->name('productionplanning.store');
    Route::get('/productionplanning/find/{id}', [ProductionPlanningController::class, 'find'])->name('productionplanning.find');
    Route::post('/productionplanning/update', [ProductionPlanningController::class, 'update'])->name('productionplanning.update');
    Route::post('/productionplanning/updatesample', [ProductionPlanningController::class, 'updatesample'])->name('productionplanning.updatesample');
    Route::post('/productionplanning/updatemi', [ProductionPlanningController::class, 'updatemi'])->name('productionplanning.updatemi');
    Route::post('/productionplanning/updatefab', [ProductionPlanningController::class, 'updatefab'])->name('productionplanning.updatefab');
    Route::post('/productionplanning/updateacc', [ProductionPlanningController::class, 'updateacc'])->name('productionplanning.updateacc');
    Route::get('/productionplanning/masterqty/{order_trans}', [ProductionPlanningController::class, 'masterqty'])->name('productionplanning.masterqty');
    Route::get('/productionplanning/fetchorderlist/{order_trans}', [ProductionPlanningController::class, 'fetchorderlist'])->name('productionplanning.fetchorderlist');

    //Production Planning Detail
    Route::get('/proplandetail/index', [ProPlanDetailController::class, 'index'])->name('proplandetail.index');
    Route::get('/proplandetail/delete/{id}', [ProPlanDetailController::class, 'delete'])->name('proplandetail.delete');
    Route::get('/proplandetail/void/{id}', [ProPlanDetailController::class, 'void'])->name('proplandetail.void');
    Route::get('/proplandetail/restore/{id}', [ProPlanDetailController::class, 'restore'])->name('proplandetail.restore');
    Route::get('/proplandetail/create', [ProPlanDetailController::class, 'create'])->name('proplandetail.create');
    Route::post('/proplandetail/store', [ProPlanDetailController::class, 'store'])->name('proplandetail.store');
    Route::get('/proplandetail/find/{id}', [ProPlanDetailController::class, 'find'])->name('proplandetail.find');
    Route::post('/proplandetail/update', [ProPlanDetailController::class, 'update'])->name('proplandetail.update');
    Route::get('/proplandetail/fetchdetailsample/{order_list}', [ProPlanDetailController::class, 'showdetailsample'])->name('proplandetail.fetchdetailsample');
    Route::get('/proplandetail/fetchdetailfabric/{order_list}', [ProPlanDetailController::class, 'showdetailfabric'])->name('proplandetail.fetchdetailfabric');
    Route::get('/proplandetail/fetchdetailmi/{order_list}', [ProPlanDetailController::class, 'showdetailmi'])->name('proplandetail.fetchdetailmi');
    Route::get('/proplandetail/fetchdetailacc/{order_list}', [ProPlanDetailController::class, 'showdetailacc'])->name('proplandetail.fetchdetailacc');

    //Production Planning Accesories
    Route::get('/proplanacc/index', [ProPlanAccController::class, 'index'])->name('proplanacc.index');
    Route::get('/proplanacc/delete/{id}', [ProPlanAccController::class, 'delete'])->name('proplanacc.delete');
    Route::get('/proplanacc/void/{id}', [ProPlanAccController::class, 'void'])->name('proplanacc.void');
    Route::get('/proplanacc/restore/{id}', [ProPlanAccController::class, 'restore'])->name('proplanacc.restore');
    Route::get('/proplanacc/create', [ProPlanAccController::class, 'create'])->name('proplanacc.create');
    Route::get('/proplanacc/add/{order_trans}/{order_list}', [ProPlanAccController::class, 'add'])->name('proplanacc.add');
    Route::post('/proplanacc/store', [ProPlanAccController::class, 'store'])->name('proplanacc.store');
    Route::get('/proplanacc/find/{id}', [ProPlanAccController::class, 'find'])->name('proplanacc.find');
    Route::post('/proplanacc/update', [ProPlanAccController::class, 'update'])->name('proplanacc.update');
    Route::get('/proplanacc/fetchaccesories/{category_no}', [ProPlanAccController::class, 'fetchaccesories'])->name('proplandetail.fetchaccesories');
    Route::get('/proplanacc/fetchaccsew/{order_list}', [ProPlanAccController::class, 'fetchaccsew'])->name('proplanacc.fetchaccsew');
    Route::get('/proplanacc/fetchaccpack/{order_list}', [ProPlanAccController::class, 'fetchaccpack'])->name('proplanacc.fetchaccpack');

    //Alert
    Route::get('/alert/index', [AlertReminderController::class, 'index'])->name('alert.index');

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
    Route::post('/shipmode/import', [ShipModeController::class, 'import'])->name('shipmode.import');
    Route::post('/market/import', [MarketController::class, 'import'])->name('market.import');
    Route::post('/shipment/import', [ShipmentController::class, 'import'])->name('shipment.import');
    Route::post('/productiondept/import', [ProductionDeptController::class, 'import'])->name('productiondept.import');
    Route::post('/washtype/import', [WashTypeController::class, 'import'])->name('washtype.import');
    Route::post('/followup/import', [FollowUpController::class, 'import'])->name('followup.import');
    Route::post('/bordirtype/import', [BordirTypeController::class, 'import'])->name('bordirtype.import');
    Route::post('/productionplanning/import', [ProductionPlanningController::class, 'import'])->name('productionplanning.import');
    Route::post('/size/import', [SizeController::class, 'import'])->name('size.import');

    //Export
    Route::get('/ordermaster/export', [OrderMasterController::class, 'export_excel'])->name('ordermaster.export');
});
