@extends('layouts/contentNavbarLayout')

@section('title', ' Area - Forms')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms /</span> Area / Edit</h4>

<div class="row">
  <div class="col-xl">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Area Details</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('master-data.area.update',$area->uuid) }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label" for="code">Code</label>
            <input type="text" maxlength="50" class="form-control" id="code" name="code" placeholder="Code" value="{{ old('code', $area->code) }}" />
            @error('code')
            <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="name">Name</label>
            <input type="text" maxlength="255" class="form-control" id="name" name="name" placeholder="Name" value="{{ old('name', $area->name) }}" />
            @error('name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="region_uuid">Region <span class="text-danger">*</span></label>
            <select class="form-select @error('region_uuid') is-invalid @enderror" id="region_uuid" name="region_uuid" required>
              @foreach ($regions as $region)
              <option value="{{$region->uuid}}" {{ $area->region?->uuid == $region->uuid ? 'selected' : '' }}>{{$region->code}} - {{$region->name}}</option>
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