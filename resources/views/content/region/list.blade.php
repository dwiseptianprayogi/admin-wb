@extends('layouts/contentNavbarLayout')

@section('title', 'Region - List')

@section('content')
<div class="d-flex mt-4 justify-content-between">
  <h4 class="py-2 mb-2">
    <span class="text-muted fw-light">Region /</span> List
  </h4>
  <div>
    @can('import region')
    <a target="_blank" href="{{ route('data.download') }}?table=Region" class="btn btn-secondary mb-4 fw-bold">Download Template</a>
    <button class="btn btn-info mb-4 fw-bold btn-import" id="btn-import" data-table-name="Region" type="button" data-bs-toggle="modal" data-bs-target="#importModal">Import Data</button>
    @endcan
    @can('export region')
    <a target="_blank" href="{{ route('data.export') }}?table=Region" class="btn btn-success mb-4 fw-bold">Export Data</a>
    @endcan
    @can('create region')
    <a href="{{ route('master-data.region.create') }}" class="btn btn-primary mb-4 fw-bold">Add New Region</a>
    @endcan
  </div>
</div>
<!-- Hoverable Table rows -->
<div class="card">
  <div class="table-responsive text-nowrap p-4">
    <table id="regionTable" class="table table-hover">
      <thead>
        <tr>
          <th>Code</th>
          <th>Name</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @foreach ($regions as $region)
        <tr>
          <td>{{ $region->code }}</td>
          <td>{{ $region->name }}</td>
          <td>
            <div class="d-flex">
              @can('edit region')
              <a href="{{ route('master-data.region.edit', $region->uuid) }}" class="btn btn-sm btn-info" style="margin-right: 5px;">edit</a>
              @endcan
              @can('delete region')
              <form action="{{ route('master-data.region.delete', $region->uuid) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Region?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">delete
                </button>
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
<!--/ Hoverable Table rows -->
@endsection
@section('page-script')
<script>
  $(document).ready(function() {
    $('#regionTable').DataTable({
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