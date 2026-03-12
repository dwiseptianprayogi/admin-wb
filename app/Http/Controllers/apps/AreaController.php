<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Http\Requests\AreaStoreRequest;
use App\Http\Requests\AreaUpdateRequest;
use App\Models\Area;
use App\Models\Region;
use App\Models\TransporterRate;
use Illuminate\Http\Request;

class AreaController extends Controller
{
  public function index()
  {
    return view(
      'content.area.list',
      [
        'areas' => Area::get()
      ]
    );
  }

  public function view()
  {
    return view('content.area.create');
  }

  public function create()
  {
    return view(
      'content.area.create',
      [
        'regions' => Region::get(),
      ]
    );
  }

  public function edit($uuid)
  {
    return view(
      'content.area.edit',
      [
        "area" => Area::findOrFail($uuid),
        "regions" => Region::get(),
      ]
    );
  }

  public function update(AreaUpdateRequest $request, $uuid)
  {
    $validated = $request->validated();
    $findArea = Area::where('ShortChar01', $validated['code'])->first();
    $area = Area::findOrFail($uuid);

    if ($findArea and $area->uuid != $findArea->uuid) {
      return redirect()->route(
        'master-data.area.edit',
        ['uuid' => $uuid]
      )->with('error', 'Code already exist!');
    }

    $area->update($validated);
    return redirect()->route(
      'master-data.area.edit',
      ['uuid' => $uuid]
    )->with('success', 'Area updated successfully');
  }

  public function delete($uuid)
  {
    $area = Area::findOrFail($uuid);

    $transporterRate = TransporterRate::where('Key2', $uuid)->exists();
    if ($transporterRate) {
      return redirect()->route('master-data.area.list')->with('error', 'Failed to delete Area. Reason: The Area is already associated with a Transporter Rate`s.');
    }
    $area->delete();

    return redirect()->route('master-data.area.list')->with('success', 'Area deleted successfully.');
  }

  public function store(AreaStoreRequest $request)
  {
    $validated = $request->validated();
    Area::create($validated);
    return redirect()->route('master-data.area.list')->with('success', 'Area created successfully');
  }
}
