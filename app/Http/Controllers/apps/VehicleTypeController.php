<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleTypeStoreRequest;
use App\Models\TransporterRate;
use App\Models\Vehicle;
use App\Models\VehicleType;

class VehicleTypeController extends Controller
{
  public function index()
  {
    return view(
      'content.vehicle-type.list',
      [
        "vehicle_types" => VehicleType::get()
      ]
    );
  }

  public function view($uuid)
  {
    return view('content.vehicle-type.view');
  }

  public function create()
  {
    return view('content.vehicle-type.create');
  }

  public function edit($uuid)
  {
    return view(
      'content.vehicle-type.edit',
      [
        "vehicle_type" => VehicleType::findOrFail($uuid)
      ]
    );
  }

  public function update(VehicleTypeStoreRequest $request, $uuid)
  {
    $vehicleType = VehicleType::findOrFail($uuid);
    $validated = $request->validated();
    $vehicleType->update($validated);
    return redirect()->route(
      'master-data.vehicle-type.edit',
      ['uuid' => $uuid]
    )->with('success', 'Vehicle Type updated successfully');
  }

  public function delete($uuid)
  {
    $vehicleType = VehicleType::findOrFail($uuid);

    $vehicle = Vehicle::where('Key2', $uuid)->exists();

    if ($vehicle) {
      return redirect()->route('master-data.vehicle-type.list')->with('error', 'Failed to delete Vehicle Type. Reason: The Vehicle Type is already associated with a Vehicle`s.');
    }

    $transporterRate = TransporterRate::where('ChildKey1', $uuid)->exists();

    if ($transporterRate) {
      return redirect()->route('master-data.vehicle-type.list')->with('error', 'Failed to delete Vehicle Type. Reason: The Vehicle Type is already associated with a Transporer Rates`s.');
    }

    $vehicleType->delete();

    return redirect()->route('master-data.vehicle-type.list')->with('success', 'Vehicle Type deleted successfully.');
  }

  public function store(VehicleTypeStoreRequest $request)
  {
    $validated = $request->validated();
    VehicleType::create($validated);
    return redirect()->route('master-data.vehicle-type.list')->with('success', 'Vehicle Type created successfully');
  }
}
