@extends('layouts/contentNavbarLayout')

@section('title', 'Transporter Rate - List')

@section('content')
<div class="d-flex mt-4 justify-content-between">
  <h4 class="py-2 mb-2">
    <span class="text-muted fw-light">Transporter Rate /</span> List
  </h4>
  <div>
    @can('import transporter_rate')
    <a target="_blank" href="{{ route('data.download') }}?table=TransporterRate" class="btn btn-secondary mb-4 fw-bold">Download Template</a>
    <button class="btn btn-info mb-4 fw-bold btn-import" id="btn-import" data-table-name="TransporterRate" type="button" data-bs-toggle="modal" data-bs-target="#importModal">Import Data</button>
    @endcan
    @can('export transporter_rate')
    <a target="_blank" href="{{ route('data.export') }}?table=TransporterRate" class="btn btn-success mb-4 fw-bold">Export Data</a>
    @endcan
    @can('create transporter_rate')
    <a href="{{ route('master-data.transporter-rate.create') }}" class="btn btn-primary mb-4 fw-bold">Add New Transporter Rate</a>
    @endcan
  </div>
</div>

<!-- Hoverable Table rows -->
<div class="card">
  <h5 class="card-header">Transporter Rates</h5>
  <div class="table-responsive text-nowrap p-4">
    <table id="transporterRateTable" class="table table-hover">
      <thead>
        <tr>
          <th>Name</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Area</th>
          <th>Vehicle Type</th>
          <th>charge</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @foreach ($transporterRates as $transporterRate)
        @php
             // Check the format and parse accordingly
             $startDate = DateTime::createFromFormat('M j Y h:i:s:A', $transporterRate->start_date) ?: DateTime::createFromFormat('Y-m-d', $transporterRate->start_date);
             $startFormatted = $startDate ? $startDate->format('d/m/Y') : 'Invalid Date';
             $endDate = DateTime::createFromFormat('M j Y h:i:s:A', $transporterRate->end_date)
                       ?: DateTime::createFromFormat('Y-m-d', $transporterRate->end_date);
             $endFormatted = $endDate ? $endDate->format('d/m/Y') : 'Invalid Date';
        @endphp
        <tr>
          <td>{{ $transporterRate->name }}</td>
          <td>{{ $startFormatted }}</td>
          <td>{{ $endFormatted }}</td>
          <td>{{ $transporterRate->area?->name }}</td>
          <td>{{ $transporterRate->vehicle_type?->name }}</td>
          <td>{{ ((int)$transporterRate->charge) }}</td>
          <td>
            <div class="d-flex">
              @can('edit transporter_rate')
              <a href="{{ route('master-data.transporter-rate.edit', $transporterRate->uuid) }}" class="btn btn-sm btn-info" style="margin-right: 5px;">edit</a>
              @endcan
              @can('delete transporter_rate')
              <form action="{{ route('master-data.transporter-rate.delete', $transporterRate->uuid) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Transporter Rate?');">
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
    $('#transporterRateTable').DataTable({
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
