<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip No {{$slip_no}}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 6px;
            /* Set the base font size */
        }

        .header,
        .footer {
            font-size: 6px;
            /* Smaller text for header/footer */
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 5px;
            /* Smaller table text */
        }

        .table th,
        .table td {
            padding: 2px;
            border: 1px solid black;
            text-align: left;
        }

        .title {
            font-size: 6px;
            font-weight: bold;
            /* Slightly larger for the title */
        }

        .doc-name {
            font-size: 6px;
            font-weight: bolder;
            /* Slightly larger for the title */
        }

        .small-text {
            font-size: 6px;
            /* The smallest text */
        }
    </style>
</head>

<body>
    <pre style="font-family: monospace; font-size: 6px; line-height: 1.2;">
PT. KERAMINDO MEGAH PERTIWI <br>TANGERANG
<br>
SLIP TIMBANGAN
----------------------------------------
No.Doc     :{{ $slip_no }} Date:{{ date('d-m-Y', strtotime($weight_in_date)) }}
No Polisi  :{{ $vehicle_no }}           > {{ ($status == 'FG-OUT' || $status == 'RM-OUT') ? 'Keluar' : 'Masuk' }}
@if($weight_type == "fg")
Transporter:{{ $transporter_name }}
----------------------------------------
Jenis Kendaraan: {{ $vehicle_type }}
Jenis Muatan   : {{ ($weight_type == 'rm' ? 'Raw Material' : 'Finish Good') }}
Total Qty      : {{ $total_qty }}
Keterangan     : {{$remark}}
----------------------------------------
@else
----------------------------------------
Jenis Kendaraan: {{ $vehicle_type }}
Jenis Muatan   : {{ ($weight_type == 'rm' ? 'Raw Material' : 'Finish Good') }}
Total Qty      : {{ $total_qty }}
Keterangan     : {{$remark}}
----------------------------------------
@endif
Masuk (KG):{{ number_format($weight_in) }}Kg Time In:{{ $weight_in_time }}
Keluar(KG):{{ number_format($weight_out) }}Kg Date Out:{{ date('d-m-Y', strtotime($weight_out_date)) }}
Netto (KG):{{ number_format($weight_netto) }}Kg Time Out:{{ $weight_out_time }}
----------------------------------------
Petugas Timbangan              Pengemudi
&nbsp;
&nbsp;
(               )      (               )
&nbsp;
&nbsp;
@if($weight_type == 'fg' and $status == 'FG-OUT')
DO/SPB No:         Dist Weight (KG)
------------------ -------------------
@php
  $totalWeight = 0;
@endphp
@foreach($spb_details as $spbDetail)
@php
  $distWeight = round($spbDetail->TotalNetWeight * $total_berat_standart, 2);
  $totalWeight += $distWeight;
@endphp
{{ str_pad($spbDetail->LegalNumber, 18) }} {{ sprintf('%10s', number_format($distWeight, 2, '.', ',')) }}
@endforeach
------------------- -------------------
{{ str_pad('Total Weight:', 18) }} {{ sprintf('%10s', number_format($totalWeight, 2, '.', ',')) }}
@endif





----------------------------------------
</pre>
</body>

</html>
