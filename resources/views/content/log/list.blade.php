@extends('layouts/contentNavbarLayout')

@section('title', 'Audit Logs - List')

@section('content')
<div class="d-flex mt-4 justify-content-between">
  <h4 class="py-2 mb-2">
    <span class="text-muted fw-light">Audit Logs /</span> List
  </h4>
  <div></div>
</div>

<div class="card">
  <div class="table-responsive text-nowrap p-4">
    <table id="auditLogTable" class="table table-hover">
      <thead>
        <tr>
          <th>Description</th>
          <th>Subject Type</th>
          <th>Subject uuid</th>
          <th>User</th>
          <th>Host</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($logs as $log)
        <tr>
          <td>{{ $log->description }}</td>
          <td>{{ $log->subject_type }}</td>
          <td>{{ $log->subject_uuid }}</td>
          <td>{{ $log->user?->name }}</td> <!-- Adjust to show user name if available -->
          <td>{{ $log->host }}</td>
          <td>{{ $log->created_at }}</td>
          <td><a href="{{route('setting.log.view',$log->uuid)}}" class="btn btn-sm btn-info">view</a></td>
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
    $('#auditLogTable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "order": [
        [5, "desc"]
      ]
    });
  });
</script>
@endsection