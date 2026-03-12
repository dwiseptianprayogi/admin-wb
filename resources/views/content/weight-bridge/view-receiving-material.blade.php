@extends('layouts/contentNavbarLayout')

@section('title', ' Receiving Material - Forms')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms /</span> Receiving Material</h4>

<div class="row">
  <div class="col-xl">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Receiving Material Details</h5>
        <button class="btn btn-{{($weight_bridge->status == 'RM-OUT' ? 'success':'warning')}} btn-sm fw-bold">{{$weight_bridge->status}}</button>
      </div>
      <div class="card-body">
        <form id="weighbridgeForm">
          @csrf
          <div class="row">
            <div class="col-4">
              <!-- Weighbridge Slip No -->
              <div class="mb-3">
                <label class="form-label" for="weight-bridge-slip-no">Weighbridge Slip No</label>
                <input type="text" class="form-control" value="{{$weight_bridge->slip_no}}" id="weight-bridge-slip-no" placeholder="Auto generated" disabled />
              </div>
              <!-- Arrival Date -->
              <div class="mb-3">
                <label class="form-label" for="arrival-date">Arrival Date</label>
                <input type="date" class="form-control" id="arrival-date" value="{{$weight_bridge->arrival_date}}" disabled />
              </div>
              <!-- Weighing Type -->
              <div class="mb-3">
                <label class="form-label" for="weighing-type">Weighing Type</label>
                <input class="form-control" id="weighing-type" value="{{($weight_bridge->weight_type  == 'rm' ? 'Raw Material':'Finish Good' )}}" name="weighing_type" disabled />
              </div>
              <!-- Vehicle No -->
              <div class="mb-3">
                <label class="form-label" for="vehicle-no">Vehicle No</label>
                <input type="text" class="form-control @error('vehicle_no') is-invalid @enderror" maxlength="10" value="{{$weight_bridge->vehicle->register_number}}" id="vehicle-no" name="vehicle_no" placeholder="Enter vehicle number" disabled />
                <div class="invalid-feedback">
                  Vehicle number not found.
                </div>
              </div>
              <!-- Vehicle Type -->
              <div class="mb-3">
                <label class="form-label" for="vehicle-type">Vehicle Type</label>
                <input disabled type="text" class="form-control" value="{{$weight_bridge->vehicle->vehicle_type->name}}" id="vehicle-type" placeholder="Enter vehicle type" />
              </div>
              <!-- Tolerance -->
              <div class="mb-3">
                <label class="form-label" for="tolerance">Tolerance</label>
                <input disabled type="number" class="form-control" value="{{$weight_bridge->vehicle->vehicle_type->tolerance}}" id="tolerance" placeholder="Enter tolerance" />
              </div>
              <!-- Transporter Name -->
              <div class="mb-3">
                <label class="form-label" for="transporter-name">Transporter Name</label>
                <input disabled type="text" class="form-control" value="{{$weight_bridge->transporter_name}}" id="transporter-name" placeholder="Enter transporter name" />
              </div>
            </div>
            <div class="col-4">
              <!-- Weight In -->
              <div class="mb-3">
                <label class="form-label" for="weight-in">Weight In</label>
                <input disabled type="number" class="form-control  @error('weight_in') is-invalid @enderror" value="{{ round($weight_bridge->weight_in) }}" name="weight_in" id="weight-in" placeholder="Enter weight in" />
                @error('weight_in')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <!-- Weight Out -->
              <div class="mb-3">
                <label class="form-label" for="weight-out">Weight Out</label>
                <input disabled type="number" class="form-control" name="weight_out" value="{{ round($weight_bridge->weight_out) }}" id="weight-out" placeholder="Enter weight out" />
              </div>

              <!-- Weight Netto -->
              <div class="mb-3">
                <label class="form-label" for="weight-netto">Weight Netto</label>
                <input disabled type="number" class="form-control" id="weight-netto" value="{{$weight_bridge->weight_netto}}" placeholder="Enter weight netto" disabled />
              </div>
              <!-- Remark -->
              <div class="mb-3">
                <label class="form-label" for="remark">Remark</label>
                <textarea disabled id="remark" class="form-control @error('remark') is-invalid @enderror" name="remark" rows='12' maxlength="50" placeholder="Enter any remarks">{{$weight_bridge->remark}}
                </textarea>
                @error('remark')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-4">
              <!-- Date Weight In -->
              <div class="mb-3">
                <label class="form-label" for="date-weight-in">Date Weight In</label>
                <input type="datetime-local" class="form-control" id="date-weight-in" value="{{$weight_bridge->weight_in_date}}" disabled />
              </div>
              <!-- Date Weight Out -->
              <div class="mb-3">
                <label class="form-label" for="date-weight-out">Date Weight Out</label>
                <input type="datetime-local" class="form-control" id="date-weight-out" value="{{$weight_bridge->weight_out_date}}" disabled />
              </div>
              <!-- Date PO / DO -->
              <div class="mb-3">
                <label class="form-label" for="po_do">PO / DO</label>
                <input type="text" class="form-control" id="po_do" name="po_do" value="{{$weight_bridge->po_do}}" disabled placeholder="Enter PO / DO" />
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-end">
            <!-- Submit Button -->
            @can('print_rw')
            <a type="button" href="{{route('transaction.weight-bridge.printSlip',$weight_bridge->uuid)}}" target="_blank" class="btn btn-primary fw-bold">PRINT</a>
            @endcan
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('page-script')
<script>
  $(document).ready(function() {

  });
</script>
@endsection
