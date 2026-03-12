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

    <div class="header">
        <p class="title">PT. KERAMINDO MEGAH PERKASA </br> TANGERANG</p>
        <p class="doc-name">SLIP TIMBANGAN</p>
    </div>

    <table class="table">
        <tr>
            <td>Doc No:</td>
            <td>{{ $slip_no }}</td>
            <td>Date:</td>
            <td>{{ date('d-m-Y', strtotime($weight_in_date)) }}</td>
        </tr>
        <tr>
            <td>No Polisi / Transporter:</td>
            <td colspan="3">{{ $vehicle_no }} / {{ $transporter_name }}</td>
        </tr>
        <tr>
            <td>Jenis Kendaraan:</td>
            <td colspan="3">{{ $vehicle_type }}</td>
        </tr>
        <tr>
            <td>Jenis Muatan:</td>
            <td colspan="3">{{ ($weight_type == 'rm' ? 'Raw Material' : 'Finish Good') }}</td>
        </tr>
        <tr>
            <td>Keterangan:</td>
            <td colspan="3">{{ $remark }}</td>
        </tr>
    </table>

    <table class="table">
        <tr>
            <td style="width: 40px;">Masuk (KG) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
            <td style="width: 15px;">{{ $weight_in }}</td>
            <td style="width: 20px;">Time In:</td>
            <td style="width: 25px;">{{ $weight_in_time }}</td>
        </tr>
        <tr>
            <td style="width: 40px;">Keluar (KG) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
            <td style="width: 15px;">{{ $weight_out }}</td>
            <td style="width: 20px;">Date Out:</td>
            <td style="width: 25px;">{{ date('d-m-Y', strtotime($weight_out_date)) }}</td>
        </tr>
        <tr>
            <td style="width: 40px;">Netto (KG) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
            <td style="width: 15px;">{{ $weight_netto }}</td>
            <td style="width: 20px;">Time Out:</td>
            <td style="width: 25px;">{{ $weight_out_time }}</td>
        </tr>
    </table>

    <table style="width: 100%;font-size:5px;">
        <tr>
            <td style="width: 70%;">Petugas Timbangan</td>
            <td>Pengemudi</td>
        </tr>
    </table>

    @if($weight_type == 'fg' and $status == 'FG-OUT')
    <table style="width: 100%; font-size:5px;">
        <tr>
            <td style="width: 70%;">&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>DO/SPB No:</td>
            <td>Dist Weight (KG)</td>
        </tr>
        @foreach($spb_details as $spbDetail)
        <tr>
            <td>{{ $spbDetail->LegalNumber }}</td>
            <td>{{ $spbDetail->TotalNetWeight }}</td>
        </tr>
        @endforeach
    </table>
    <table style="width: 100%; font-size:5px;">
        <tr>
            <td style="width: 70%;">&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Total</td>
            <td>{{$total_weight}}</td>
        </tr>
    </table>
    @endif
</body>

</html>