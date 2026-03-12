<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleStoreRequest;
use App\Http\Requests\VehicleUpdateRequest;
use App\Models\Transporter;
use App\Models\TransporterRate;
use App\Models\Vehicle;
use App\Models\VehicleTransporter;
use App\Models\VehicleType;
use App\Models\WeightBridge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleController extends Controller
{
  public function index()
  {
    return view(
      'content.vehicle.list',
      [
        'vehicles' => Vehicle::get()
      ]
    );
  }

  public function view()
  {
    return view('content.vehicle.create');
  }

  public function create()
  {
    return view(
      'content.vehicle.create',
      [
        "vehicleTypes" => VehicleType::orderBy('ShortChar01', 'ASC')->get(),
        // 'transporterRates' => TransporterRate::get(),
        'transporters' => Transporter::get(),
      ]
    );
  }

  public function update(VehicleUpdateRequest $request, $uuid)
  {
    $vehicle = Vehicle::findOrFail($uuid);
    $validated = $request->validated();

    $transporters = $validated['multi_transporter_uuid'];
    unset($validated['multi_transporter_uuid']);

    $transporterVehicles = [];
    foreach ($transporters as $transporter) {
      $transporterVehicles[] = [
        'vehicle_uuid' => $vehicle->uuid,
        'transporter_uuid' => $transporter,
      ];
    }

    $vehicle->vehicle_transporters()->delete();

    foreach ($transporterVehicles as $transporterVehicle) {
      VehicleTransporter::create($transporterVehicle);
    }

    $vehicle->update($validated);
    return redirect()->route(
      'master-data.vehicle.edit',
      ['uuid' => $uuid]
    )->with('success', 'Vehicle updated successfully');
  }

  public function edit($uuid)
  {
    return view(
      'content.vehicle.edit',
      [
        "vehicle" => Vehicle::findOrFail($uuid),
        "vehicleTransporters" => VehicleTransporter::where('Key3', $uuid)->get(),
        "vehicleTypes" => VehicleType::orderBy('ShortChar01', 'ASC')->get(),
        'transporters' => Transporter::get(),
      ]
    );
  }

  public function delete($uuid)
  {
    $vehicle = Vehicle::findOrFail($uuid);

    $weightBridge = WeightBridge::where('Key2', $uuid)->exists();
    if ($weightBridge) {
      return redirect()->route('master-data.vehicle.list')->with('error', 'Failed to delete vehicle. Reason: The vehicle is already associated with a transaction.');
    }

    $vehicle->delete();

    return redirect()->route('master-data.vehicle.list')->with('success', 'Vehicle deleted successfully.');
  }

  public function store(VehicleStoreRequest $request)
  {
    $validated = $request->validated();
    if (Vehicle::where('Character01', $validated['register_number'])->exists()) {
      return redirect()->route('master-data.vehicle.create')->with('failed', 'Register Number already registered.');
    }

    $transporters = $validated['multi_transporter_uuid'];
    unset($validated['multi_transporter_uuid']);

    $vehicle = Vehicle::create($validated);

    $transporterVehicles = [];
    foreach ($transporters as $transporter) {
      $transporterVehicles[] = [
        'vehicle_uuid' => $vehicle->uuid,
        'transporter_uuid' => $transporter,
      ];
    }

    foreach ($transporterVehicles as $transporterVehicle) {
      VehicleTransporter::create($transporterVehicle);
    }

    return redirect()->route('master-data.vehicle.list')->with('success', 'Vehicle created successfully');
  }

  public function getVehicleDetails(Request $request)
  {
    $vehicleNo = $request->input('vehicle_no');
    $weightType = $request->input('weight_type');

    $vehicle = null;
    switch ($weightType) {
      case 'FG':
        $vehicle = Vehicle::where('Character01', $vehicleNo)->first();
        if (!$vehicle) {
          return response()->json([
            "status" => "failed",
            "message" => "Data Not Found!",
            "data" => []
          ]);
        }
        $weightBridge = WeightBridge::where('Key2', $vehicle->uuid)->where('ShortChar02', 'FG-IN')->first();
        break;

      default:
        $weightBridge = WeightBridge::where('Character08', $vehicleNo)->where('ShortChar02', 'RM-IN')->first();
        break;
    }

    $device = new DeviceController();
    $deviceDetail = $device->detail();

    $deviceData = $deviceDetail->getData() ?? [];

    $routePrint = '#';
    $totalWeightValue = 0;
    if ($weightBridge != null) {
      $slipNo = $weightBridge->slip_no;

      $totalWeight = DB::select("
        SELECT SUM(T2.TotalNetWeight) AS TotalWeight
        FROM ShipHead AS T1
        LEFT JOIN ShipDtl AS T2 ON T1.PackNum = T2.PackNum AND T1.Company = T2.Company
        WHERE T1.NoDokumen_c = :slipNo
    ", ['slipNo' => $slipNo]);

      $totalWeightValue = $totalWeight[0]->TotalWeight ?? 0;
      $routePrint = route('transaction.weight-bridge.printSlip', $weightBridge->uuid);
    }
    $vehicleDetails = [
      "status" => "success",
      "message" => "Data Found!",
      "data" => [
        'vehicle_type' => $vehicle?->vehicle_type->name,
        'tolerance' => number_format($vehicle?->vehicle_type->tolerance, 2),
        'weight_standart' => number_format($vehicle?->vehicle_type->weight_standart, 2),
        'transporter_name' => $vehicle?->transporter?->name,
        'slip_no' => $weightBridge?->slip_no,
        'weight_in' => $weightBridge?->weight_in,
        'weight_in_date' => $weightBridge?->weight_in_date,
        'remark' => $weightBridge?->remark,
        'status' => $weightBridge?->status,
        'device_status' => $deviceData->status ?? false,
        'total_weight_value' => ($totalWeightValue),
        'route_print' => $routePrint,
      ]
    ];

    return response()->json($vehicleDetails);
  }
}
