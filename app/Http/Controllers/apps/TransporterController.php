<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransporterStoreRequest;
use App\Http\Requests\TransporterUpdateRequest;
use App\Models\Area;
use App\Models\Transporter;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class TransporterController extends Controller
{
  public function index()
  {
    return view(
      'content.transporter.list',
      [
        'transporters' => Transporter::get()
      ]
    );
  }

  public function view()
  {
    return view(
      'content.transporter.create',
      [
        'areas' => Area::get()
      ]
    );
  }

  public function create()
  {
    return view(
      'content.transporter.create',
      [
        'areas' => Area::get()
      ]
    );
  }

  public function edit($uuid)
  {
    return view(
      'content.transporter.edit',
      [
        "transporter" => Transporter::findOrFail($uuid),
        'areas' => Area::get()
      ]
    );
  }

  public function update(TransporterUpdateRequest $request, $uuid)
  {
    $transporter = Transporter::findOrFail($uuid);
    $validated = $request->validated();
    $validated['address'] = $validated['address'] ?? '';
    $validated['contact_name'] = $validated['contact_name'] ?? '';
    $validated['phone'] = $validated['phone'] ?? '';
    $transporter->update($validated);
    return redirect()->route(
      'master-data.transporter.edit',
      ['uuid' => $uuid]
    )->with('success', 'Transporter updated successfully');
  }

  public function delete($uuid)
  {
    $transporter = Transporter::findOrFail($uuid);

    $vehicle = Vehicle::where('Key3', $uuid)->exists();
    if ($vehicle) {
      return redirect()->route('master-data.transporter.list')->with('error', 'Failed to delete Transporter. Reason: The Transporter is already associated with a Vehicle`s.');
    }

    $transporter->delete();

    return redirect()->route('master-data.transporter.list')->with('success', 'Transporter deleted successfully.');
  }

  public function store(TransporterStoreRequest $request)
  {
    $validated = $request->validated();
    $validated['address'] = $validated['address'] ?? '';
    $validated['contact_name'] = $validated['contact_name'] ?? '';
    $validated['phone'] = $validated['phone'] ?? '';
    Transporter::create($validated);
    return redirect()->route('master-data.transporter.list')->with('success', 'Transporter created successfully');
  }
}
