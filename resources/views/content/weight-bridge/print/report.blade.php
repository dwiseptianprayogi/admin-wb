<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Report</title>
    <style>
      body {
          font-family: Arial, sans-serif;
          margin: 20px;
      }

      .container-fluid {
          padding: 15px;
      }

      h4 {
          text-align: center;
          margin-bottom: 20px;
      }

      table {
          width: 100%;
          border-collapse: collapse;
          margin-bottom: 20px;
          table-layout: fixed; /* Ensure table fits within the page */
      }

      .border {
          border: 1px solid #ddd;
          padding: 8px;
          text-align: left;
          word-wrap: break-word; /* Ensure long text wraps */
          word-break: break-all; /* Break words if necessary */
      }

      table td, table th {
          border: 1px solid #ddd;
          padding: 8px;
          text-align: left;
          overflow: hidden; /* Hide overflow text */
          word-wrap: break-word; /* Ensure long text wraps */
          word-break: break-all; /* Break words if necessary */
      }

      table th {
          background-color: #f4f4f4;
      }

      .bg-warning-subtle {
          background-color: #fff3cd;
      }

      .text-end {
          text-align: right;
      }

      .text-start {
          text-align: left;
      }

      .text-center {
          text-align: center;
      }

      .small {
          font-size: 12px;
      }

      .table-cell-yellow {
          background-color: #ffffe0;
      }

      .header, .footer {
          width: 100%;
          position: fixed;
      }

      .header {
          top: -20px;
          text-align: right;
          font-size: 12px;
      }

      .footer {
          bottom: -20px;
          text-align: right;
          font-size: 12px;
      }
  </style>
</head>

<body>
    <div class="footer">
        Retrieved Date: {{ $current_date_time }}
    </div>
    <div class="container-fluid">
        <h4>PT KERAMINDO MEGAH PERTIWI<br>ESTIMATED PAYMENT TO TRANSPORTER</h4>
        @php
        // Initialize totals for the footer
        $totalQuantity = 0;
        $totalStdWeight = 0;
        $totalWeight = 0;
        $totalVar = 0;
        $totalRate = 0;
        $totalAmount = 0;
        @endphp
        @foreach ($reports as $key => $report)
        <table>
            <tr>
                <th colspan="1" style="background: white; width:120px;">
                    <div class="small text-start">
                        <strong>Kode Suplier:</strong>
                    </div>
                </th>
                <th colspan="4" style="background: white;">
                    <div class="small text-start">
                        <strong>{{ $report[0]->TransporterCode ?? 'N/A' }}</strong>
                    </div>
                </th>
                <th colspan="1" style="background: white; border: none;">
                </th>
            </tr>
            <tr>
                <th colspan="1" style="background: white;">
                    <div class="small text-start">
                        <strong>Nama Suplier:</strong>
                    </div>
                </th>
                <th colspan="4" style="background: white;">
                    <div class="small text-start">
                        <strong>{{ empty($key) ? 'N/A' : $key }}</strong>
                    </div>
                </th>
                <th colspan="1" style="background: white; border: none;">
                </th>
                </th>
            </tr>
            <tr>
                <th class="small border" style="width: 70px;">D/O NO</th>
                <th class="small border" style="width: 90px;">Date</th>
                <th class="small border" style="width: 70px;">Plate NO</th>
                <th class="small border" style="width: 70px;">Vehicle Group</th>
                <th class="small border" style="width: 90px;">Area</th>
                <th class="small border" style="width: 70px;">Quantity</th>
                <th class="small border" style="width: 70px;">WB.Doc</th>
                <th class="small border" style="width: 70px;">STD Weight (Kg)</th>
                <th class="small border" style="width: 70px;">Weight (Kg)</th>
                <th class="small border" style="width: 70px;">Var (Kg)</th>
                <th class="small border" style="width: 70px;">Rate</th>
                <th class="small border" style="width: 100px;">Amount (Rp)</th>
                <th class="small border" style="width: 90px;">Kwitansi NO</th>
            </tr>

            <!-- Body Section -->
            <tbody>
                @php
                $subtotalQuantity = 0;
                $subtotalStdWeight = 0;
                $subtotalWeight = 0;
                $subtotalVar = 0;
                $subtotalRate = 0;
                $subtotalAmount = 0;
                @endphp

                @foreach($report as $data)
                @php
                $subtotalQuantity += $data->Quantity ?? 0;
                $subtotalStdWeight += $data->StdWeight ?? 0;
                $subtotalWeight += $data->Weight ?? 0;
                $subtotalVar += $data->VarKg ?? 0;
                $subtotalRate += $data->Rate ?? 0;
                $subtotalAmount += $data->Amount ?? 0;

                // Accumulate into the footer totals
                $totalQuantity += $data->Quantity ?? 0;
                $totalStdWeight += $data->StdWeight ?? 0;
                $totalWeight += $data->Weight ?? 0;
                $totalVar += $data->VarKg ?? 0;
                $totalRate += $data->Rate ?? 0;
                $totalAmount += $data->Amount ?? 0;
                @endphp
                <tr class="small">
                    <td>{{ empty($data->DoNo) ? 'N/A' : $data->DoNo }}</td>
                    <td>
                        {{ $data->date ? \Carbon\Carbon::parse(str_replace(':AM', ' AM', str_replace(':PM', ' PM', $data->date)))->format('d-m-Y') : 'N/A' }}
                    </td>
                    <td>{{ empty($data->PlateNo) ? 'N/A' : $data->PlateNo }}</td>
                    <td>{{ empty($data->VehicleGroup) ? 'N/A' : $data->VehicleGroup }}</td>
                    <td>{{ empty($data->Area) ? 'N/A' : $data->Area }}</td>
                    <td>{{ number_format($data->Quantity ?? 0, 0) }}</td>
                    <td>{{ empty($data->WbDoc) ? 'N/A' : $data->WbDoc }}</td>
                    <td>{{ number_format($data->StdWeight ?? 0, 2) }}</td>
                    <td>{{ number_format($data->Weight ?? 0, 2) }}</td>
                    <td>{{ number_format($data->VarKg ?? 0, 2) }}</td>
                    <td>{{ number_format($data->Rate ?? 0, 2) }}</td>
                    <td>{{ number_format($data->Amount ?? 0, 2) }}</td>
                    <td>{{ empty($data->Kwitansi_NO) ? 'N/A' : $data->Kwitansi_NO }}</td>
                </tr>
                @endforeach
                <!-- Subtotal Row -->

                <tr class="table-secondary fw-bold small" style="background: #f4f4f4;">
                    <td colspan="5" class="text-end">@if($is_multi_transporter)Sub @endif Total</td>
                    <td class="text-start">{{ number_format($subtotalQuantity, 0) }}</td>
                    <td></td>
                    <td class="text-start">{{ number_format($subtotalStdWeight, 2) }}</td>
                    <td class="text-start">{{ number_format($subtotalWeight, 2) }}</td>
                    <td class="text-start">{{ number_format($subtotalVar, 2) }}</td>
                    <td class="text-start">{{ number_format($subtotalRate, 2) }}</td>
                    <td class="text-start">{{ number_format($subtotalAmount, 0) }}</td>
                    <td></td>
                </tr>
            </tbody>
            <!-- Footer Totals -->
            @if($is_multi_transporter)
            @if($loop->last)
            <tr class="table-dark fw-bold small" style="background: #f4f4f4;">
                <td colspan="5" class="text-end">Total</td>
                <td class="text-start">{{ number_format($totalQuantity, 0) }}</td>
                <td></td>
                <td class="text-start">{{ number_format($totalStdWeight, 2) }}</td>
                <td class="text-start">{{ number_format($totalWeight, 2) }}</td>
                <td class="text-start">{{ number_format($totalVar, 2) }}</td>
                <td class="text-start">{{ number_format($totalRate, 2) }}</td>
                <td class="text-start">{{ number_format($totalAmount, 0) }}</td>
                <td></td>
            </tr>
            @endif
            @endif
        </table>
        @endforeach
    </div>
    <script type="text/php">
      if (isset($pdf)) {
        $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
        $size = 10;
        $font = $fontMetrics->getFont("Verdana");
        $width = $fontMetrics->get_text_width($text, $font, $size);
        $x = $pdf->get_width() - $width - 10;
        $y = 10; // Adjust the y position to the top
        $pdf->page_text($x, $y, $text, $font, $size);
      }
  </script>
</body>

</html>
