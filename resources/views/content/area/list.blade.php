@extends('layouts/contentNavbarLayout')

@section('title', 'Area - List')

@section('content')
<div class="d-flex mt-4 justify-content-between">
  <h4 class="py-2 mb-2">
    <span class="text-muted fw-light">Area /</span> List
  </h4>
  <div>
    @can('import area')
    <a target="_blank" href="{{ route('data.download') }}?table=Area" class="btn btn-secondary mb-4 fw-bold">Download Template</a>
    <button class="btn btn-info mb-4 fw-bold btn-import" id="btn-import" data-table-name="Area" type="button" data-bs-toggle="modal" data-bs-target="#importModal">Import Data</button>
    @endcan
    @can('export area')
    <a target="_blank" href="{{ route('data.export') }}?table=Area" class="btn btn-success mb-4 fw-bold">Export Data</a>
    @endcan
    @can('create area')
    <a href="{{ route('master-data.area.create') }}" class="btn btn-primary mb-4 fw-bold">Add New Area</a>
    @endcan
  </div>
</div>

<!-- Hoverable Table rows -->
<div class="card">
  <div class="table-responsive text-nowrap p-4">
    <table id="areasTable" class="table table-hover">
      <thead>
        <tr>
          <th>Code</th>
          <th>Name</th>
          <th>Region</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @foreach ($areas as $area)
        <tr>
          <td>{{ $area->code }}</td>
          <td>{{ $area->name }}</td>
          <td>{{ $area->region?->name }}</td>
          <td>
            <div class="d-flex">
              @can('edit area')
              <a href="{{ route('master-data.area.edit', $area->uuid) }}" class="btn btn-sm btn-info" style="margin-right: 5px;">edit</i></a>
              @endcan
              @can('delete area')
              <form action="{{ route('master-data.area.delete', $area->uuid) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Area?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">
                  delete
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
    $('#areasTable').DataTable({
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