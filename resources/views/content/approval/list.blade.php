@extends('layouts/contentNavbarLayout')

@section('title', 'Transaction - List')

@section('content')
<div class="d-flex mt-4 justify-content-between">
  <h4 class="py-2 mb-2">
    <span class="text-muted fw-light">Transaction /</span> Approval
  </h4>
  <div>
    <a target="_blank" href="{{ route('data.export') }}?table=WeightBridgeApproval" class="btn btn-success mb-4 fw-bold">Export Data</a>
  </div>
</div>

<!-- Hoverable Table rows -->
<div class="card">
  <div class="table-responsive text-nowrap p-4">
    <table id="approvalTable" class="table table-hover">
      <thead>
        <tr>
          {{-- <th>PO / DO</th> --}}
          <th>Vehicle No</th>
          <th>Weight Netto</th>
          <th>Tolerance</th>
          <th>Total Weight Standart</th>
          <th>Weight Difference</th>
          <th>Date</th>
          <th>Status</th>
          <th>Action Date Approval 1</th>
          <th>Action Date Approval 2</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @foreach($approvals as $approval)
        <tr>
          {{-- <td>{{$approval->weight_bridge->po_do}}</td> --}}
          <td>{{$approval->weight_bridge->vehicle->register_number ?? '-'}}</td>
          <td>{{$approval->weight_bridge->weight_netto}} KG</td>
          <td>{{$approval->weight_bridge->vehicle->vehicle_type->tolerance ?? '-'}} KG</td>
          <td>{{$approval->weight_bridge->weight_standart ?? '-'}} KG</td>
          <td class="text-danger">{{$approval->weight_bridge->difference}} KG</td>
          <td>{{$approval->weight_bridge->weight_out_date}}</td>
          <td><span class="badge fw-bold text-secondary bg-label-{{ (($approval->is_approve == true) ? 'success' : (($approval->is_reject == true) ? 'danger' : 'warning'))}}">{{$approval->weight_bridge->status}}</span></td>
          <td>{{$approval->action_date}}</td>
          <td>{{$approval->action_date_2}}</td>
          <td>
            <div class="d-flex">
              <a href="{{route('transaction.weight-bridge.view',$approval->weight_bridge->uuid)}}" target="_blank" class="btn btn-sm btn-info" style="margin-right: 0.2rem;">view</a>
              @if($approval->action_date == null)
              @can('approve')
              <form action="{{ route('transaction.weight-bridge.approval.approve', $approval->uuid) }}" method="POST" onsubmit="return confirm('Are you sure you want to approve weight out of this slip No?');">
                @csrf
                @method('POST')
                <button type="submit" class="btn btn-sm btn-success" style="margin-right: 0.2rem;">Approve</button>
              </form>
              @endcan
              @can('reject')
              <form action="{{ route('transaction.weight-bridge.approval.reject', $approval->uuid) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject weight out of this slip No?');">
                @csrf
                @method('POST')
                <button type="submit" class="btn btn-sm btn-danger" style="margin-right: 0.2rem;">Reject</button>
              </form>
              @endcan
              @endif
              @if($approval->action_date != null && $approval->action_date_2 == null)
              @can('approve 2')
              <form action="{{ route('transaction.weight-bridge.approval.approve', $approval->uuid) }}" method="POST" onsubmit="return confirm('Are you sure you want to approve weight out of this slip No?');">
                @csrf
                @method('POST')
                <button type="submit" class="btn btn-sm btn-success" style="margin-right: 0.2rem;">Approve</button>
              </form>
              @endcan
              @can('reject 2')
              <form action="{{ route('transaction.weight-bridge.approval.reject', $approval->uuid) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject weight out of this slip No?');">
                @csrf
                @method('POST')
                <button type="submit" class="btn btn-sm btn-danger" style="margin-right: 0.2rem;">Reject</button>
              </form>
              @endcan
              @endif
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
    $('#approvalTable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "order": [
        [6, "asc"]
      ]
    });
  });
</script>
@endsection
