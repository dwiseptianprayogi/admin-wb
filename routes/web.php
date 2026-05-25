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
use Barryvdh\DomPDF\Facade\Pdf;


// Main Page Route
Route::get('/', [Analytics::class, 'index'])->name('dashboard-analytics')->middleware('auth');
Route::get('/device', [DeviceController::class, 'detail'])->name('device.detail')->middleware('auth');

// Debug route: contoh report dengan data dummy (untuk testing repeat header PDF & CSV)
Route::get('/debug/report-dummy', function (\Illuminate\Http\Request $request) {
    $fmtRound = fn($v) => number_format(round((float)($v ?? 0), 0, PHP_ROUND_HALF_UP), 0);

    // Helper buat baris dummy
    $makeRow = fn($i, $transCode, $transName) => (object)[
        'TransporterCode' => $transCode,
        'TransporterName' => $transName,
        'DoNo'            => 'PK.' . str_pad($i, 5, '0', STR_PAD_LEFT) . '/04/26',
        'date'            => '2026-04-' . str_pad(($i % 20) + 1, 2, '0', STR_PAD_LEFT),
        'PlateNo'         => 'B ' . (1000 + $i) . ' ZA',
        'VehicleGroup'    => $i % 2 === 0 ? 'COLT' : 'FUSO',
        'Area'            => $i % 3 === 0 ? 'JAKARTA' : ($i % 3 === 1 ? 'BANDUNG' : 'SURABAYA'),
        'Quantity'        => 3 + ($i % 5),
        'WbDoc'           => 'FG26040' . str_pad($i, 2, '0', STR_PAD_LEFT),
        'StdWeight'       => 6500 + ($i * 100),
        'Weight'          => 6450 + ($i * 98),
        'VarKg'           => 50 + ($i * 2),
        'Rate'            => 500000,
        'Amount'          => (6450 + ($i * 98)) * 500000,
        'Kwitansi_NO'     => 'KWT-' . str_pad($i, 4, '0', STR_PAD_LEFT),
    ];

    // TR-001: 50 baris (cukup untuk melewati 3+ halaman, untuk test repeat header)
    $data = [];
    for ($i = 1; $i <= 50; $i++) {
        $data['AMBIL SENDIRI'][] = $makeRow($i, 'TR-001', 'AMBIL SENDIRI');
    }
    for ($i = 51; $i <= 70; $i++) {
        $data['SAUDARA DWI TRANSPORTINDO PT'][] = $makeRow($i, 'TR-002', 'SAUDARA DWI TRANSPORTINDO PT');
    }
    // Urutkan setiap grup berdasarkan Area A-Z, lalu date
    foreach ($data as &$group) {
        usort($group, function ($a, $b) {
            $areaCompare = strcasecmp($a->Area ?? '', $b->Area ?? '');
            if ($areaCompare !== 0) return $areaCompare;
            return strcmp($a->date ?? '', $b->date ?? '');
        });
    }
    unset($group);
    $isMultipleTransporter = true;

    // --- Export PDF ---
    if ($request->get('export') === 'PDF') {
        $pdf = Pdf::loadView('content.weight-bridge.print.report', [
            'reports'              => $data,
            'is_multi_transporter' => $isMultipleTransporter,
            'current_date_time'    => now()->format('d-m-Y H:i:s'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('report-dummy.pdf');
    }

    // --- Export CSV ---
    $fileName = 'report-dummy.csv';
    $headers  = [
        'Content-type'        => 'text/csv',
        'Content-Disposition' => "attachment; filename=$fileName",
        'Pragma'              => 'no-cache',
        'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
        'Expires'             => '0',
    ];

    $callback = function () use ($data, $isMultipleTransporter, $fmtRound) {
        $file = fopen('php://output', 'w');

        $grandTotalQty = $grandTotalStd = $grandTotalWt = $grandTotalVar = $grandTotalAmt = 0;

        foreach ($data as $key => $report) {
            $subQty = $subStd = $subWt = $subVar = $subAmt = 0;

            fputcsv($file, ['Kode Suplier:', $report[0]->TransporterCode ?? 'N/A']);
            fputcsv($file, ['Nama Suplier:', $key ?? 'N/A']);
            fputcsv($file, ['D/O NO','Date','Plate NO','Vehicle Group','Area','Quantity','WB.Doc','STD Weight (Kg)','Weight (Kg)','Var (Kg)','Rate','Amount (Rp)','Kwitansi NO']);

            foreach ($report as $row) {
                $subQty  += $row->Quantity  ?? 0;
                $subStd  += $row->StdWeight ?? 0;
                $subWt   += $row->Weight    ?? 0;
                $subVar  += $row->VarKg     ?? 0;
                $subAmt  += $row->Amount    ?? 0;
                fputcsv($file, [
                    $row->DoNo, $row->date, $row->PlateNo, $row->VehicleGroup, $row->Area,
                    $fmtRound($row->Quantity), $row->WbDoc, $fmtRound($row->StdWeight),
                    $fmtRound($row->Weight), $fmtRound($row->VarKg), $fmtRound($row->Rate),
                    $fmtRound($row->Amount), $row->Kwitansi_NO,
                ]);
            }

            fputcsv($file, ['','','','', ($isMultipleTransporter ? 'Sub ' : '') . 'Total',
                $fmtRound($subQty),'', $fmtRound($subStd), $fmtRound($subWt),
                $fmtRound($subVar),'', $fmtRound($subAmt),'']);
            fputcsv($file, []);

            $grandTotalQty += $subQty; $grandTotalStd += $subStd;
            $grandTotalWt  += $subWt;  $grandTotalVar += $subVar; $grandTotalAmt += $subAmt;
        }

        if ($isMultipleTransporter) {
            fputcsv($file, ['','','','','Total',
                $fmtRound($grandTotalQty),'', $fmtRound($grandTotalStd), $fmtRound($grandTotalWt),
                $fmtRound($grandTotalVar),'', $fmtRound($grandTotalAmt),'']);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
})->name('debug.reportDummy');

// Debug route: contoh slip timbang tanpa akses database (untuk testing print lokal)
Route::get('/debug/print-sample', function () {
    $spbDetails = [
        (object) [
            'LegalNumber' => 'SPK.001/11/25',
            'TotalNetWeight' => 1500.0,
            'OrderLine' => 1,
            'PartNum' => 'CD1 TILES',
            'beratStandarPergenteng' => 3.675,
        ],
        (object) [
            'LegalNumber' => 'SPK.001/11/25',
            'TotalNetWeight' => 507.0,
            'OrderLine' => 2,
            'PartNum' => 'CD1 TILES',
            'beratStandarPergenteng' => 3.675,
        ],
    ];

    $data = [
        'slip_no' => '23389',
        'vehicle_no' => 'B 9127 ZA',
        'transporter_name' => 'SAUDARA DWI TRANSPORTINDO PT',
        'vehicle_type' => 'COLT DIESEL',
        'weight_type' => 'fg',
        'remark' => 'Muatan: Finish Good',
        'weight_in' => 4370.0,
        'weight_in_time' => '09:29:00',
        'weight_in_date' => '2025-11-05',
        'weight_out' => 14507.0,
        'weight_netto' => 10087.0,
        'weight_out_time' => '17:01:03',
        'weight_out_date' => '2025-11-05',
        'weight_in_by' => 'admin',
        'driver_name' => 'Sopir A',
        'po_do' => 'DO-12345',
        'actual_weight' => null,
        'status' => 'FG-OUT',
        'spb_details' => $spbDetails,
        'total_berat_standart' => 1.0,
        'total_qty' => 3675.0,
    ];

    // Generate PDF contoh slip tanpa akses SQL Server
    $pdf = Pdf::loadView('content.weight-bridge.print.slip', $data);
    $pdf->setPaper('80mm', 'portrait');
    return $pdf->stream('Slip_SAMPLE.pdf');
})->name('debug.printSample');

// Debug route: contoh slip RM (Raw Material) tanpa akses database
Route::get('/debug/print-rm-sample', function () {
    $data = [
        'slip_no'           => 'RM2604210001',
        'vehicle_no'        => 'B 5678 CD',
        'transporter_name'  => 'LUMBUNG BERLIAN UTAMA CV', // contoh nama transporter RM
        'vehicle_type'      => null,
        'weight_type'       => 'rm',
        'remark'            => 'Batu Kapur / Limestone',
        'weight_in'         => 18500.0,
        'weight_in_time'    => '08:15:00',
        'weight_in_date'    => '2026-04-21',
        'weight_out'        => 7200.0,
        'weight_netto'      => 11300.0,
        'weight_out_time'   => '09:45:00',
        'weight_out_date'   => '2026-04-21',
        'weight_in_by'      => 'admin',
        'driver_name'       => 'Budi Santoso',
        'po_do'             => 'PO-RM-2026-0042',
        'actual_weight'     => null,
        'status'            => 'RM-OUT',
        'spb_details'       => [],
        'total_berat_standart' => 0,
        'total_qty'         => 0,
    ];

    $pdf = Pdf::loadView('content.weight-bridge.print.slip', $data);
    $pdf->setPaper('80mm', 'portrait');
    return $pdf->stream('Slip_RM_SAMPLE.pdf');
})->name('debug.printRmSample');


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