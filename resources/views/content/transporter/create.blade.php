@extends('layouts/contentNavbarLayout')

@section('title', ' Transporter - Forms')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Transporter</h4>

<div class="row">
  <div class="col-xl">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Transporter Details</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('master-data.transporter.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label" for="code">Code</label>
            <input type="text" class="form-control" id="code" name="code" placeholder="Code" required value="{{ old('code') }}" />
            @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label class="form-label" for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Name" required value="{{ old('name') }}" />
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label class="form-label" for="address">Address</label>
            <textarea type="text" class="form-control" id="address" name="address" placeholder="Address" value="{{ old('address') }}" rows="10"></textarea>
            @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label class="form-label" for="contact_name">Contact Name</label>
            <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Contact Name" required value="{{ old('contact_name') }}" />
            @error('contact_name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label class="form-label" for="phone">Phone</label>
            <input type="number" class="form-control" id="phone" name="phone" placeholder="Phone" required value="{{ old('phone') }}" />
            @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          {{-- <div class="mb-3">
            <label class="form-label" for="area_uuid">Area <span class="text-danger">*</span></label>
            <select class="form-select @error('area_uuid') is-invalid @enderror" id="area_uuid" name="area_uuid" required>
              <option value="">-- select --</option>
              @foreach ($areas as $area)
              <option value="{{$area->uuid}}" {{ old('area_uuid') == '$area->uuid' ? 'selected' : '' }}>{{$area->region?->name}} - {{$area->name}}</option>
              @endforeach
            </select>
            @error('area_uuid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div> --}}
          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
