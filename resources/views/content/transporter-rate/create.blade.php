@extends('layouts/contentNavbarLayout')

@section('title', ' Transporter Rate - Forms')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms /</span> Transporter Rate</h4>

<div class="row">
  <div class="col-xl">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Transporter Rate Details</h5>
      </div>
      <div class="card-body">
        <form id="form" action="{{ route('master-data.transporter-rate.store') }}" method="POST">
          @csrf
          <div class="row">
            <div class="col-12">
              <div class="mb-3">
                <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Name" required value="{{ old('name') }}" />
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="mb-3">
                <label class="form-label" for="start_date">Start Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="start_date" name="start_date" placeholder="Start Date" required value="{{ old('start_date') }}" />
                @error('start_date')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-6">
              <div class="mb-3">
                <label class="form-label" for="end_date">End Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="end_date" name="end_date" placeholder="End Date" required value="{{ old('end_date') }}" />
                @error('end_date')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="mb-3">
                <label class="form-label" for="area_uuid">Area <span class="text-danger">*</span></label>
                <select class="form-select @error('area_uuid') is-invalid @enderror" id="area_uuid" name="area_uuid" required>
                  <option value="">-- select --</option>
                  @foreach ($areas as $area)
                  <option value="{{$area->uuid}}" {{ old('area_uuid') == '$area->uuid' ? 'selected' : '' }}>{{$area->name}}({{$area->code}}) - {{$area->region?->name}}({{$area->region?->code}})</option>
                  @endforeach
                </select>
                @error('area_uuid')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="mb-3">
                <label class="form-label" for="vehicle_type_uuid">Vehicle Type <span class="text-danger">*</span></label>
                <select class="form-select @error('vehicle_type_uuid') is-invalid @enderror" id="vehicle_type_uuid" name="vehicle_type_uuid" required>
                  <option value="">-- select --</option>
                  @foreach ($vehicleTypes as $vehicleType)
                  <option value="{{$vehicleType->uuid}}" {{ old('vehicle_type_uuid') == '$vehicleType->uuid' ? 'selected' : '' }}>{{$vehicleType->code}} - {{$vehicleType->name}}</option>
                  @endforeach
                </select>
                @error('vehicle_type_uuid')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-6">
              <div class="mb-3">
                <label class="form-label" for="charge">Charge <span class="text-danger">*</span></label>
                <input type="number" min="0" class="form-control @error('charge') is-invalid @enderror" id="charge" name="charge" placeholder="Charge" required value="{{ old('charge') }}" />
                @error('charge')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  @endsection

  @section('page-script')
  <script>
    $(document).ready(function() {
      $('#area_uuid').select2({
        placeholder: 'Select a transporter rate',
        allowClear: true
      });

      $('#start_date').on('change', function() {
        let startDate = $(this).val();
        $('#end_date').attr('min', startDate);
      });

      $('#start_date').on('change', function () {
          const date = new Date(this.value);
          const formattedDate = ${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth() + 1).padStart(2, '0')}/${date.getFullYear()};
      });

      $('#end_date').on('change', function () {
          const date = new Date(this.value);
          const formattedDate = ${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth() + 1).padStart(2, '0')}/${date.getFullYear()};
      });
    });
  </script>
  @endsection
