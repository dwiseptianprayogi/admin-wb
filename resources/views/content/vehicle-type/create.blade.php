@extends('layouts/contentNavbarLayout')

@section('title', ' Vehicle Type - Forms')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms /</span> Vehicle Type / Create</h4>

<div class="row">
  <div class="col-xl">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Vehicle Type Details</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('master-data.vehicle-type.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label" for="code">Code</label>
            <input type="text" maxlength="50" class="form-control" id="code" name="code" placeholder="Code" required value="{{ old('code') }}" />
            @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="name">Name</label>
            <input type="text" maxlength="255" class="form-control" id="name" name="name" placeholder="Name" required value="{{ old('name') }}" />
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <label class="form-label" for="tolerance">Tolerance</label>
          <div class="input-group mb-3">
            <input type="number" min="1" class="form-control" id="tolerance" name="tolerance" placeholder="Tolerance" aria-describedby="tolerance-suffix" required value="{{ old('tolerance') }}" />
            <span class="input-group-text" id="tolerance-suffix">KG</span>
            @error('tolerance')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- <label class="form-label" for="weight_standart">Capacity</label> --}}
          <div class="input-group mb-3 d-none">
            <input type="number" min="0" class="form-control" id="weight_standart" name="weight_standart" placeholder="Tolerance" aria-describedby="weight_standart-suffix" value="0" />
            <span class="input-group-text" id="weight_standart-suffix">KG</span>
            @error('weight_standart')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
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
