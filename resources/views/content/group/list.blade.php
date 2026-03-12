@extends('layouts/contentNavbarLayout')

@section('title', 'Group - List')

@section('content')
<div class="d-flex mt-4 justify-content-between">
  <h4 class="py-2 mb-2">
    <span class="text-muted fw-light">Group /</span> List
  </h4>
  <div>
    @can('create group')
    <a href="{{ route('account.group.create') }}" class="btn btn-primary mb-4 fw-bold">Add New Group</a>
    @endcan

  </div>
</div>

<div class="card">
  <div class="table-responsive text-nowrap p-4">
    <table id="groupTable" class="table table-hover">
      <thead>
        <tr>
          <th>Name</th>
          <th>Created At</th>
          <th>Updated At</th>
          <th>Created By</th>
          <th>Updated By</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($roles as $role)
        <tr>
          <td>{{ $role->name }}</td>
          <td>{{ $role->created_at }}</td>
          <td>{{ $role->updated_at }}</td>
          <td>{{ $role->created_by }}</td>
          <td>{{ $role->updated_by }}</td>
          <td>
            <div class="d-flex">
              @can('edit group')
              <a href="{{ route('account.group.edit', $role->uuid) }}" class="btn btn-sm btn-info" style="margin-right: 5px;">edit</a>
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
    $('#groupTable').DataTable({
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