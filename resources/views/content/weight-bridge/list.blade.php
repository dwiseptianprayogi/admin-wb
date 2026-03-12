@extends('layouts/contentNavbarLayout')

@section('title', 'Transaction - List')

@section('content')
<div class="d-flex mt-4 justify-content-between">
  <h4 class="py-2 mb-2">
    <span class="text-muted fw-light">Transaction /</span> Data
  </h4>
  <div>
    <a target="_blank" href="{{ route('data.export') }}?table=WeightBridge&type=fg" class="btn btn-success mb-4 fw-bold mr-2">Export Data Finish Good</a>
    <a target="_blank" href="{{ route('data.export') }}?table=WeightBridge&type=rm" class="btn btn-success mb-4 fw-bold">Export Data Receiving Material</a>
  </div>
</div>

<!-- Hoverable Table rows -->
<div class="row">
  <div class="col mt-2">
    <div class="card">
      <h5 class="card-header">Transaction Raw Materials</h5>
      <div class="table-responsive text-nowrap p-4">
        <table id="rmTable" class="table table-hover">
          <thead>
            <tr>
              <th>WB Slip No</th>
              <th>Vehicle No</th>
              <th>Date</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @foreach($raw_materials as $raw_material)
            <tr>
              <td>{{$raw_material->slip_no}}</td>
              <td>{{$raw_material->vehicle->register_number ?? $raw_material->vehicle_no}}</td>
              <td>{{ date('d-m-Y H:i:s', strtotime($raw_material?->arrival_date)) }}</td>
              <td><span class="badge text-secondary bg-label-{{($raw_material->status == 'RM-OUT') ? 'success' : ($raw_material->status == 'RM-IN' ? 'warning' : 'danger')}} me-1">{{$raw_material->status}}</span></td>
              <td>
                <div class="d-flex">
                  <!--                  <a href="{{route('transaction.weight-bridge.view',$raw_material->uuid)}}" class="btn btn-sm btn-info">view</a>-->
                  <a type="button" href="{{route('transaction.weight-bridge.printSlip',$raw_material->uuid)}}" target="_blank" class="btn btn-sm btn-info">view</a>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col mt-2">
    <div class="card">
      <h5 class="card-header">Transaction Finish Good's</h5>
      <div class="table-responsive text-nowrap p-4">
        <table id="fgTable" class="table table-hover">
          <thead>
            <tr>
              <th>WB Slip No</th>
              <th>Vehicle No</th>
              <th>Date</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @foreach($finish_goods as $finish_good)
            <tr>
              <td>{{$finish_good->slip_no}}</td>
              <td>{{$finish_good->vehicle?->register_number ?? '-'}}</td>
              <td>{{ date('d-m-Y H:i:s', strtotime($finish_good?->arrival_date)) }}</td>
              <td><span class="badge text-secondary bg-label-{{($finish_good->status == 'FG-OUT') ? 'success' : ($finish_good->status == 'FG-IN' ? 'warning' : 'danger')}} me-1">{{$finish_good->status}}</span></td>
              <td>
                <div class="d-flex">
                  <a href="{{route('transaction.weight-bridge.view',$finish_good->uuid)}}" class="btn btn-sm btn-info">view</a>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!--/ Hoverable Table rows -->
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
    $('#fgTable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "order": []
    });
    $('#rmTable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "order": []
    });
  });
</script>
@endsection
