@extends('layouts/contentNavbarLayout')

@section('title', ' Vehicle - Forms')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Vehicle</h4>

<div class="row">
  <div class="col-xl">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Vehicle Information</h5>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('master-data.vehicle.update',$vehicle->uuid) }}">
          @csrf <!-- CSRF Protection -->

          <div class="row">
            <div class="col">
              <div class="mb-3">
                <label class="form-label" for="register-number">Register Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('register_number') is-invalid @enderror" id="register-number" name="register_number" placeholder="Enter Register Number" value="{{ old('register_number',$vehicle->register_number) }}" required disabled readonly />
                @error('register_number')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="mb-3 d-none">
            <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
            <input type="hidden" name="status" value="active">
            {{-- <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
              <option value="active" {{ $vehicle->status == 'active' ? 'selected' : '' }}>Active</option>
              <option value="inactive" {{ $vehicle->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select> --}}
            @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="type">Type of Plat <span class="text-danger">*</span></label>
            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
              <option value="">-- select --</option>
              <option value="kuning" {{ trim($vehicle->type) == 'kuning' ? 'selected' : '' }}>Kuning</option>
              <option value="putih" {{ trim($vehicle->type) == 'putih' ? 'selected' : '' }}>Putih</option>
              <option value="hitam" {{ trim($vehicle->type) == 'hitam' ? 'selected' : '' }}>Hitam</option>
            </select>
            @error('type')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="vehicle_type_uuid">Vehicle Type <span class="text-danger">*</span></label>
            <select class="form-select @error('vehicle_type_uuid') is-invalid @enderror" id="vehicle_type_uuid" name="vehicle_type_uuid" required>
              <option value="">-- select --</option>
              @foreach ($vehicleTypes as $vehicleType)
              <option value="{{$vehicleType->uuid}}" {{ $vehicle->vehicle_type?->uuid == $vehicleType->uuid ? 'selected' : '' }}>{{$vehicleType->code}} - {{$vehicleType->name}}</option>
              @endforeach
            </select>
            @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="description">Description <span class="text-danger">*</span> </label>
            <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" placeholder="Enter Description" required>{{ old('description',$vehicle->description) }}</textarea>
            @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="transporter_uuid">Active Transporter <span class="text-danger">*</span></label>
            <select class="form-select @error('transporter_uuid') is-invalid @enderror" id="transporter_uuid" name="transporter_uuid" required>
              @foreach ($transporters as $transporter)
              <option value="{{ $transporter->uuid }}"
                {{  ($vehicle->transporter?->uuid == $transporter->uuid) ? 'selected' : '' }}>
                {{ $transporter->code }} - {{ $transporter->name }}
              </option>
              @endforeach
            </select>
            @error('transporter_uuid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="multi_transporter_uuid">Other Transporter <span class="text-danger">*</span></label>
            <select class="form-select @error('multi_transporter_uuid') is-invalid @enderror" id="multi_transporter_uuid" name="multi_transporter_uuid[]" multiple required>
              <option value="">-- select --</option>
              @foreach ($transporters as $transporter)
              <option value="{{ $transporter->uuid }}"
                {{ in_array($transporter->uuid, $vehicleTransporters->pluck('transporter_uuid')->toArray()) ? 'selected' : '' }}>
                {{ $transporter->code }} - {{ $transporter->name }}
              </option>
              @endforeach
            </select>
            @error('multi_transporter_uuid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="ownership">Ownership <span class="text-danger">*</span></label>
            <select class="form-select @error('ownership') is-invalid @enderror" id="ownership" name="ownership" required>
              <option value="own" {{ $vehicle->ownership == 'own' ? 'selected' : '' }}>Own</option>
              <option value="transporter" {{ $vehicle->ownership == 'transporter' ? 'selected' : '' }}>Transporter</option>
            </select>
            @error('ownership')
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

@section('page-script')
<script>
  $(document).ready(function() {
    $('#transporter_uuid').select2({
      placeholder: 'Select a Active transporter',
      allowClear: true,
      multiple: false
    });
    $('#multi_transporter_uuid').select2({
      placeholder: 'Select a transporter',
      allowClear: true,
      multiple: true
    });
  });
</script>
@endsection
