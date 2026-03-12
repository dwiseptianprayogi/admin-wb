@extends('layouts/contentNavbarLayout')

@section('title', ' Area - Forms')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Area</h4>

<div class="row">
  <div class="col-xl">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Area Details</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('master-data.area.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label" for="code">Code</label>
            <input type="text" maxlength="50" class="form-control @error('code') is-invalid @enderror" id="code" name="code" placeholder="Code" required value="{{ old('code') }}" />
            @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label class="form-label" for="name">Name</label>
            <input type="text" maxlength="255" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Name" required value="{{ old('name') }}" />
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="region_uuid">Region <span class="text-danger">*</span></label>
            <select class="form-select @error('region_uuid') is-invalid @enderror" id="region_uuid" name="region_uuid" required>
              <option value="">-- Select --</option>
              @foreach ($regions as $region)
              <option value="{{$region->uuid}}" {{ old('region_uuid') == '$region->uuid' ? 'selected' : '' }}>{{$region->code}} - {{$region->name}}</option>
              @endforeach
            </select>
            @error('status')
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
    $('#region_uuid').select2({
      placeholder: 'Select a transporter rate',
      allowClear: true
    });
  });
</script>
@endsection