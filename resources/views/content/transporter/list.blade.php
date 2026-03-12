@extends('layouts/contentNavbarLayout')

@section('title', 'Transporter - List')

@section('content')
<div class="d-flex mt-4 justify-content-between">
  <h4 class="py-2 mb-2">
    <span class="text-muted fw-light">Transporter /</span> List
  </h4>
  <div>
    @can('import transporter')
    <a target="_blank" href="{{ route('data.download') }}?table=Transporter" class="btn btn-secondary mb-4 fw-bold">Download Template</a>
    <button class="btn btn-info mb-4 fw-bold btn-import" id="btn-import" data-table-name="Transporter" type="button" data-bs-toggle="modal" data-bs-target="#importModal">Import Data</button>
    @endcan
    @can('export transporter')
    <a target="_blank" href="{{ route('data.export') }}?table=Transporter" class="btn btn-success mb-4 fw-bold">Export Data</a>
    @endcan
    @can('create transporter')
    <a href="{{ route('master-data.transporter.create') }}" class="btn btn-primary mb-4 fw-bold">Add New Transporter</a>
    @endcan
  </div>
</div>

<!-- Hoverable Table rows -->
<div class="card">
  <div class="table-responsive text-nowrap p-4">
    <table id="transporterTable" class="table table-hover">
      <thead>
        <tr>
          <th>Code</th>
          <th>Name</th>
          <th>Address</th>
          <th>Contact Name</th>
          <th>Phone</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @foreach ($transporters as $transporter)
        <tr>
          <td>{{ $transporter->code }}</td>
          <td>{{ $transporter->name }}</td>
          <td>{{ $transporter->address }}</td>
          <td>{{ $transporter->contact_name }}</td>
          <td>{{ $transporter->phone }}</td>
          <td>
            <div class="d-flex">
              @can('edit transporter')
              <a href="{{ route('master-data.transporter.edit', $transporter->uuid) }}" class="btn btn-sm btn-info" style="margin-right: 5px;">edit</a>
              @endcan
              @can('delete transporter')
              <form action="{{ route('master-data.transporter.delete', $transporter->uuid) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Transporter?');">
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
    $('#transporterTable').DataTable({
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
