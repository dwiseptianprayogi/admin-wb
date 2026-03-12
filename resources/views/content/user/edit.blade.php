@extends('layouts/contentNavbarLayout')

@section('title', 'User - Forms')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms /</span> User / Edit</h4>

<div class="row">
  <div class="col-xl">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">User Details</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('account.user.update', $user->uuid) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="mb-3">
            <label class="form-label" for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{ old('name', $user->name) }}" />
            @error('name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email', $user->email) }}" />
            @error('email')
            <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="New Password (leave blank to keep unchanged)" />
            @error('password')
            <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="group">Group <span class="text-danger">*</span></label>
            <select class="form-select @error('group') is-invalid @enderror" id="group" name="group" required>
              <option value="">-- Select Group --</option>
              @foreach ($roles as $role)
              <option value="{{$role->uuid}}" {{ $user_role?->uuid == $role->uuid ? 'selected' : '' }}>{{$role->name}}</option>
              @endforeach
            </select>
            @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="company">Company</label>
            <input type="text" class="form-control" id="company" name="company" placeholder="Company" required readonly value="KMP" />
            @error('company')
            <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="is_active" name="is_active" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">Is Active</label>
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