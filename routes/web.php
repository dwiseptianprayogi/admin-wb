<?php

use App\Http\Controllers\apps\ApprovalController;
use App\Http\Controllers\apps\Area;
use App\Http\Controllers\apps\AreaController;
use App\Http\Controllers\apps\DeviceController;
use App\Http\Controllers\apps\GroupController;
use App\Http\Controllers\apps\LogController;
use App\Http\Controllers\apps\PrintController;
use App\Http\Controllers\apps\RegionController;
use App\Http\Controllers\apps\TransporterController;
use App\Http\Controllers\apps\TransporterRateController;
use App\Http\Controllers\apps\UserController;
use App\Http\Controllers\apps\VehicleController;
use App\Http\Controllers\apps\VehicleTypeController;
use App\Http\Controllers\apps\WeightBridgeController;
use App\Http\Controllers\authentications\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\pages\AccountSettingsAccount;


// Main Page Route
Route::get('/', [Analytics::class, 'index'])->name('dashboard-analytics')->middleware('auth');
Route::get('/device', [DeviceController::class, 'detail'])->name('device.detail')->middleware('auth');

// Master Data Route
Route::prefix('master-data')->name('master-data.')->middleware('auth')->group(function () {
    Route::prefix('/vehicle')->name('vehicle.')->group(function () {
        Route::get('/', [VehicleController::class, 'index'])->middleware('can:view vehicle')->name('list');
        Route::get('/view', [VehicleController::class, 'index'])->middleware('can:view vehicle')->name('view');
        Route::get('/create', [VehicleController::class, 'create'])->middleware('can:create vehicle')->name('create');
        Route::post('/store', [VehicleController::class, 'store'])->middleware('can:create vehicle')->name('store');
        Route::post('/set/transporter/{uuid}', [VehicleController::class, 'setActiveTransporter'])->name('setTransporter');
        Route::delete('/delete/{uuid}', [VehicleController::class, 'delete'])->middleware('can:delete vehicle')->name('delete');
        Route::get('/edit/{uuid}', [VehicleController::class, 'edit'])->middleware('can:edit vehicle')->name('edit');
        Route::post('/update/{uuid}', [VehicleController::class, 'update'])->middleware('can:edit vehicle')->name('update');
        Route::get('/get-vehicle-details', [VehicleController::class, 'getVehicleDetails'])->name('details');
    });
    Route::prefix('/vehicle-type')->name('vehicle-type.')->group(function () {
        Route::get('/', [VehicleTypeController::class, 'index'])->middleware('can:view vehicle_type')->name('list');
        Route::get('/view', [VehicleTypeController::class, 'index'])->middleware('can:view vehicle_type')->name('view');
        Route::get('/create', [VehicleTypeController::class, 'create'])->middleware('can:create vehicle_type')->name('create');
        Route::post('/store', [VehicleTypeController::class, 'store'])->middleware('can:create vehicle_type')->name('store');
        Route::delete('/delete/{uuid}', [VehicleTypeController::class, 'delete'])->middleware('can:delete vehicle_type')->name('delete');
        Route::get('/edit/{uuid}', [VehicleTypeController::class, 'edit'])->middleware('can:edit vehicle_type')->name('edit');
        Route::post('/update/{uuid}', [VehicleTypeController::class, 'update'])->middleware('can:edit vehicle_type')->name('update');
    });
    Route::prefix('/transporter')->name('transporter.')->group(function () {
        Route::get('/', [TransporterController::class, 'index'])->middleware('can:view transporter')->name('list');
        Route::get('/view', [TransporterController::class, 'index'])->middleware('can:view transporter')->name('view');
        Route::get('/create', [TransporterController::class, 'create'])->middleware('can:create transporter')->name('create');
        Route::post('/store', [TransporterController::class, 'store'])->middleware('can:create transporter')->name('store');
        Route::delete('/delete/{uuid}', [TransporterController::class, 'delete'])->middleware('can:delete transporter')->name('delete');
        Route::get('/edit/{uuid}', [TransporterController::class, 'edit'])->middleware('can:edit transporter')->name('edit');
        Route::post('/update/{uuid}', [TransporterController::class, 'update'])->middleware('can:edit transporter')->name('update');
    });
    Route::prefix('/transporter-rate')->name('transporter-rate.')->group(function () {
        Route::get('/', [TransporterRateController::class, 'index'])->middleware('can:view transporter_rate')->name('list');
        Route::get('/view', [TransporterRateController::class, 'index'])->middleware('can:view transporter_rate')->name('view');
        Route::get('/create', [TransporterRateController::class, 'create'])->middleware('can:create transporter_rate')->name('create');
        Route::post('/store', [TransporterRateController::class, 'store'])->middleware('can:create transporter_rate')->name('store');
        Route::delete('/delete/{uuid}', [TransporterRateController::class, 'delete'])->middleware('can:delete transporter_rate')->name('delete');
        Route::get('/edit/{uuid}', [TransporterRateController::class, 'edit'])->middleware('can:edit transporter_rate')->name('edit');
        Route::post('/update/{uuid}', [TransporterRateController::class, 'update'])->middleware('can:edit transporter_rate')->name('update');
    });
    Route::prefix('/area')->name('area.')->group(function () {
        Route::get('/', [AreaController::class, 'index'])->middleware('can:view area')->name('list');
        Route::get('/view', [AreaController::class, 'index'])->middleware('can:view area')->name('view');
        Route::get('/create', [AreaController::class, 'create'])->middleware('can:create area')->name('create');
        Route::post('/store', [AreaController::class, 'store'])->middleware('can:create area')->name('store');
        Route::delete('/delete/{uuid}', [AreaController::class, 'delete'])->middleware('can:delete area')->name('delete');
        Route::get('/edit/{uuid}', [AreaController::class, 'edit'])->middleware('can:edit area')->name('edit');
        Route::post('/update/{uuid}', [AreaController::class, 'update'])->middleware('can:edit area')->name('update');
    });
    Route::prefix('/region')->name('region.')->group(function () {
        Route::get('/', [RegionController::class, 'index'])->middleware('can:view area')->name('list');
        Route::get('/view', [RegionController::class, 'index'])->middleware('can:view area')->name('view');
        Route::get('/create', [RegionController::class, 'create'])->middleware('can:create area')->name('create');
        Route::post('/store', [RegionController::class, 'store'])->middleware('can:create area')->name('store');
        Route::delete('/delete/{uuid}', [RegionController::class, 'delete'])->middleware('can:delete area')->name('delete');
        Route::get('/edit/{uuid}', [RegionController::class, 'edit'])->middleware('can:edit area')->name('edit');
        Route::post('/update/{uuid}', [RegionController::class, 'update'])->middleware('can:edit area')->name('update');
    });
});

Route::prefix('transaction')->name('transaction.')->middleware('auth')->group(function () {
    Route::prefix('/weight-bridge')->name('weight-bridge.')->group(function () {
        Route::get('/data', [WeightBridgeController::class, 'index'])->middleware('can:view data_wb')->name('data');
        Route::get('/view/{weightBridgeUuid}', [WeightBridgeController::class, 'view'])->middleware('can:view data_wb')->name('view');
        Route::get('/receiving-material', [WeightBridgeController::class, 'receivingMaterial'])->name('receiving-material')->middleware('can:view receiving_material');
        Route::get('/finish-good', [WeightBridgeController::class, 'finishGood'])->name('finish-good')->middleware('can:view finish_good');
        Route::get('/approval', [ApprovalController::class, 'index'])->middleware('can:view approval')->name('approval.list');
        Route::post('/approval/approve/{approvalUuid}', [ApprovalController::class, 'approve'])
            ->middleware('permission:approve|approve 2')
            ->name('approval.approve');
        Route::post('/approval/reject/{approvalUuid}', [ApprovalController::class, 'reject'])
            ->middleware('permission:reject|reject 2')
            ->name('approval.reject');
        Route::post('/weight-in', [WeightBridgeController::class, 'weightIn'])->middleware('can:weight_in')->name('weightIn');
        Route::get('/report', [WeightBridgeController::class, 'transporterReport'])->middleware('can:view report')->name('report');
        Route::post('/weight-out', [WeightBridgeController::class, 'weightOut'])->middleware('can:weight_out')->name('weightOut');
        route::get('/print/{uuid}/slip', [PrintController::class, 'generateSlipPDF'])->middleware('can:print_rw')->name('printSlip');
    });
});

Route::prefix('data')->name('data.')->middleware('auth')->group(function () {
    Route::get('/export', [\App\Http\Controllers\apps\ExportImportController::class, 'export'])
        ->middleware(
            [
                'can:export user',
                'can:export vehicle',
                'can:export vehicle_type',
                'can:export area',
                'can:export region',
                'can:export transporter',
                'can:export transporter_rate',
            ]
        )
        ->name('export');
    Route::post('/import', [\App\Http\Controllers\apps\ExportImportController::class, 'import'])
        ->middleware([
            'can:import user',
            'can:import vehicle',
            'can:import vehicle_type',
            'can:import area',
            'can:import region',
            'can:import transporter',
            'can:import transporter_rate',
        ])
        ->name('import');
    Route::get('/download-template', [\App\Http\Controllers\apps\ExportImportController::class, 'download'])
        ->middleware([
            'can:import user',
            'can:import vehicle',
            'can:import vehicle_type',
            'can:import area',
            'can:import region',
            'can:import transporter',
            'can:import transporter_rate',
        ])
        ->name('download');
});

Route::prefix('account')->name('account.')->middleware('auth')->group(function () {
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->middleware('can:view user')->name('list');
        Route::get('/create', [UserController::class, 'create'])->middleware('can:create user')->name('create');
        Route::post('/store', [UserController::class, 'store'])->middleware('can:create user')->name('store');
        Route::get('/{uuid}/edit', [UserController::class, 'edit'])->middleware('can:edit user')->name('edit');
        Route::put('/{uuid}/update', [UserController::class, 'update'])->middleware('can:edit user')->name('update');
        Route::delete('/{uuid}/delete', [UserController::class, 'delete'])->middleware('can:delete user')->name('delete');
    });
    Route::prefix('group')->name('group.')->group(function () {
        Route::get('/', [GroupController::class, 'index'])->middleware('can:view group')->name('list');
        Route::get('/create', [GroupController::class, 'create'])->middleware('can:create group')->name('create');
        Route::post('/store', [GroupController::class, 'store'])->middleware('can:create group')->name('store');
        Route::get('/{uuid}/edit', [GroupController::class, 'edit'])->middleware('can:edit group')->name('edit');
        Route::put('/{uuid}/update', [GroupController::class, 'update'])->middleware('can:edit group')->name('update');
    });
    Route::get('/profile', [AccountSettingsAccount::class, 'index'])->name('profile')->middleware('auth');
    Route::put('/profile/update', [AccountSettingsAccount::class, 'update'])->name('profile.update')->middleware('auth');
});

Route::prefix('setting')->name('setting.')->middleware('auth')->group(function () {
    Route::get('/log', [LogController::class, 'index'])->name('log.list')->middleware('can:view log');
    Route::get('/log/{uuid}', [LogController::class, 'view'])->name('log.view')->middleware('can:view log');
});

// authentication
Route::middleware('guest')->group(function () {
    Route::get('/auth/login', [AuthController::class, 'index'])->name('login');
});
route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth');
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
