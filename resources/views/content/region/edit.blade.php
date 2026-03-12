@extends('layouts/contentNavbarLayout')

@section('title', ' Region - Forms')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms /</span> Region / Edit</h4>

<div class="row">
  <div class="col-xl">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Region Details</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('master-data.region.update',$region->uuid) }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label" for="code">Code</label>
            <input type="text" maxlength="50" class="form-control" id="code" name="code" placeholder="Code" value="{{ old('code', $region->code) }}" />
            @error('code')
            <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="name">Name</label>
            <input type="text" maxlength="255" class="form-control" id="name" name="name" placeholder="Name" value="{{ old('name', $region->name) }}" />
            @error('name')
            <div class="text-danger">{{ $message }}</div>
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