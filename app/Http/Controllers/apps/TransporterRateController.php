<?php

namespace App\Http\Controllers\apps;

use DateTime;
use Carbon\Carbon;
use App\Models\Area;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use App\Models\TransporterRate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransporterRateStoreRequest;
use App\Http\Requests\TransporterRateUpdateRequest;

class TransporterRateController extends Controller
{
  public function index()
  {
    return view(
      'content.transporter-rate.list',
      [
        'transporterRates' => TransporterRate::get()
      ]
    );
  }

  public function view()
  {
    return view('content.transporter-rate.create');
  }

  public function create()
  {
    return view(
      'content.transporter-rate.create',
      [
        'areas' => Area::get(),
        "vehicleTypes" => VehicleType::orderBy('ShortChar01', 'ASC')->get(),
      ]
    );
  }

  public function edit($uuid)
  {
    $transporterRate = TransporterRate::findOrFail($uuid);
    $startDate = str_replace(':AM', ' AM', $transporterRate->start_date);
    $startDate = str_replace(':PM', ' PM', $startDate);

    $startDate = Carbon::parse($startDate);
    $formatedStartDate = $startDate->format('Y-m-d');

    $endDate = str_replace(':AM', ' AM', $transporterRate->end_date);
    $endDate = str_replace(':PM', ' PM', $endDate);

    $endDate = Carbon::parse($endDate);
    $formatedEndDate = $endDate->format('Y-m-d');
    return view(
      'content.transporter-rate.edit',
      [
        "transporterRate" => TransporterRate::findOrFail($uuid),
        "vehicleTypes" => VehicleType::orderBy('ShortChar01', 'ASC')->get(),
        'formatedStartDate' => $formatedStartDate,
        'formatedEndDate' => $formatedEndDate,
        'areas' => Area::get(),
      ]
    );
  }

  public function update(TransporterRateUpdateRequest $request, $uuid)
  {
    $transporterRate = TransporterRate::findOrFail($uuid);
    $validated = $request->validated();

    // $checkExisting = TransporterRate::where('Key2', $validated['area_uuid'])
    //     ->where('ChildKey1', $validated['vehicle_type_uuid'])
    //     ->where('Key1', '!=', $uuid)
    //     ->exists();

    $checkExisting = TransporterRate::where('Key2', $validated['area_uuid'])->where('ChildKey1', $validated['vehicle_type_uuid'])->exists();

    if($transporterRate->uuid === $uuid) {
      $checkExisting = false;
    }

    if ($checkExisting) {
        return redirect()->route('master-data.transporter-rate.edit', ['uuid' => $uuid])->with('error', 'Transporter Rate already exists.');
    }

    $transporterRate->update($validated);
    return redirect()->route(
      'master-data.transporter-rate.edit',
      ['uuid' => $uuid]
    )->with('success', 'TransporterRate updated successfully');
  }

  public function delete($uuid)
  {
    $transporterRate = TransporterRate::findOrFail($uuid);

    $query = "SELECT WBArea_c, VehicleType_c FROM ShipHead
    WHERE WBArea_c <> '' AND VehicleType_c <> ''
    GROUP BY WBArea_c, VehicleType_c
    ORDER BY WBArea_c";

    $items = DB::select($query);

    foreach ($items as $item) {
      if ($item->WBArea_c === $transporterRate->area->name && $item->VehicleType_c === $transporterRate->vehicle_type->name) {
        return redirect()->route('master-data.transporter-rate.list')->with('error', 'Transporter Rate cannot be deleted because it is being used in a shipment.');
      }
    }

    $transporterRate->delete();

    return redirect()->route('master-data.transporter-rate.list')->with('success', 'TransporterRate deleted successfully.');
  }

  public function store(TransporterRateStoreRequest $request)
  {
    $validated = $request->validated();
    $checkExisting = TransporterRate::where('Key2', $validated['area_uuid'])->where('ChildKey1', $validated['vehicle_type_uuid'])->exists();
    if ($checkExisting) {
      return redirect()->route('master-data.transporter-rate.create')->with('error', 'Transporter Rate already exists.');
    }
    TransporterRate::create($validated);
    return redirect()->route('master-data.transporter-rate.list')->with('success', 'TransporterRate created successfully');
  }
}
