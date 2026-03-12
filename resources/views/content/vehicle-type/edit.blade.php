@extends('layouts/contentNavbarLayout')

@section('title', ' Vehicle Type - Forms')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms /</span> Vehicle Type / Edit</h4>

<div class="row">
  <div class="col-xl">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Vehicle Type Details</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('master-data.vehicle-type.update',$vehicle_type->uuid) }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label" for="name">Name</label>
            <input type="text" maxlength="255" class="form-control" id="name" name="name" placeholder="Name" value="{{ old('name', $vehicle_type->name) }}" />
            @error('name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="code">Code</label>
            <input type="text" maxlength="50" class="form-control" id="code" name="code" placeholder="Code" value="{{ old('code', $vehicle_type->code) }}" />
            @error('code')
            <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <label class="form-label" for="tolerance">Tolerance</label>
          <div class="input-group mb-3">
            <input type="number" min="1" class="form-control" id="tolerance" name="tolerance" placeholder="Tolerance" aria-describedby="tolerance-suffix" value="{{ ((int)old('tolerance', $vehicle_type->tolerance)) }}" />
            <span class="input-group-text" id="tolerance-suffix">KG</span>
            @error('tolerance')
            <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-none">
          <label class="form-label" for="weight_standart">Capacity</label>
          <div class="input-group mb-3">
            <input type="number" min="1" class="form-control" id="weight_standart" name="weight_standart" placeholder="Tolerance" aria-describedby="weight_standart-suffix" value="{{ ((int)old('weight_standart', $vehicle_type->weight_standart)) }}" />
            <span class="input-group-text" id="weight_standart-suffix">KG</span>
            @error('weight_standart')
            <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          </div>

          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
