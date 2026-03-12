@extends('layouts/contentNavbarLayout')

@section('title', 'User - List')

@section('content')
<div class="d-flex mt-4 justify-content-between">
  <h4 class="py-2 mb-2">
    <span class="text-muted fw-light">User /</span> List
  </h4>
  <div>

    @can('import user')
    <a target="_blank" href="{{ route('data.download') }}?table=User" class="btn btn-secondary mb-4 fw-bold">Download Template</a>
    <a href="{{ route('account.user.create') }}" class="btn btn-info mb-4 fw-bold">Import Data</a>
    @endcan
    @can('export user')
    <a target="_blank" href="{{ route('data.export') }}?table=User" class="btn btn-success mb-4 fw-bold">Export Data</a>
    @endcan
    @can('create user')
    <a href="{{ route('account.user.create') }}" class="btn btn-primary mb-4 fw-bold">Add New User</a>
    @endcan
  </div>
</div>

<div class="card">
  <div class="table-responsive text-nowrap p-4">
    <table id="userTable" class="table table-hover">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Is Active</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($users as $user)
        <tr>
          <td>{{ $user->name }}</td>
          <td>{{ $user->email }}</td>
          <td> <span class="badge bg-{{ ($user->is_active) ? 'success' : 'danger'}}">{{ ($user->is_active) ? 'Active' : 'Not Active'}}</td>
          <td>{{ $user->created_at }}</td>
          <td>
            <div class="d-flex">
              @can('edit user')
              <a href="{{ route('account.user.edit', $user->uuid) }}" class="btn btn-sm btn-info" style="margin-right: 5px;">edit</a>
              @endcan
              @can('delete user')
              <form action="{{ route('account.user.delete', $user->uuid) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">delete</button>
              </form>
              @endcan
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
    $('#userTable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true
    });
  });
</script>
@endsection