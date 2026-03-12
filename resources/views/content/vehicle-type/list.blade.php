@extends('layouts/contentNavbarLayout')

@section('title', 'Vehicle Type - List')

@section('content')
<div class="d-flex mt-4 justify-content-between">
  <h4 class="py-2 mb-2">
    <span class="text-muted fw-light">Vehicle Type /</span> List
  </h4>
  <div>
    @can('import vehicle_type')
    <a target="_blank" href="{{ route('data.download') }}?table=VehicleType" class="btn btn-secondary mb-4 fw-bold">Download Template</a>
    <button class="btn btn-info mb-4 fw-bold btn-import" id="btn-import" data-table-name="VehicleType" type="button" data-bs-toggle="modal" data-bs-target="#importModal">Import Data</button>
    @endcan
    @can('export vehicle_type')
    <a target="_blank" href="{{ route('data.export') }}?table=VehicleType" class="btn btn-success mb-4 fw-bold">Export Data</a>
    @endcan
    @can('create vehicle_type')
    <a href="{{ route('master-data.vehicle-type.create') }}" class="btn btn-primary mb-4 fw-bold">Add New Vehicle Type</a>
    @endcan
  </div>
</div>

<div class="card">
  <div class="table-responsive text-nowrap p-4">
    <table id="vehicleTypeTable" class="table table-hover">
      <thead>
        <tr>
          <th>Name</th>
          <th>Code</th>
          <th>Tolerance</th>
          {{-- <th>Capacity</th> --}}
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($vehicle_types as $vehicle_type)
        <tr>
          <td>{{ $vehicle_type->name }}</td>
          <td>{{ $vehicle_type->code }}</td>
          <td>{{ number_format($vehicle_type->tolerance, 2) }}</td>
          {{-- <td>{{ $vehicle_type->weight_standart }}</td> --}}
          <td>
            <div class="d-flex">
              @can('edit vehicle_type')
              <a href="{{ route('master-data.vehicle-type.edit', $vehicle_type->uuid) }}" class="btn btn-sm btn-info" style="margin-right: 5px;">edit</a>
              @endcan
              @can('delete vehicle_type')
              <form action="{{ route('master-data.vehicle-type.delete', $vehicle_type->uuid) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this type of vehicle?');">
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
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
    $('#vehicleTypeTable').DataTable({
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
