<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Report</title>
    <style>
      @page {
          margin: 40px 15px 25px 15px;
      }

      body {
          font-family: Arial, sans-serif;
          margin: 0;
      }

      .container-fluid {
          padding: 0;
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
          padding: 5px 4px;
          text-align: left;
          overflow: hidden;
          word-wrap: break-word;
          word-break: break-word;
      }

      table td {
          font-size: 10px;
      }

      table th {
          background-color: #f4f4f4;
      }

      /* Repeat table header on every PDF page */
      thead {
          display: table-header-group;
      }

      tfoot {
          display: table-footer-group;
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
          bottom: 0px;
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
        $fmtRound = fn($value) => number_format(round((float)($value ?? 0), 0, PHP_ROUND_HALF_UP), 0);
        @endphp
        @foreach ($reports as $key => $report)

        {{-- Info Suplier: tabel terpisah, TIDAK masuk thead, hanya muncul sekali --}}
        <table style="margin-bottom: 0; border-collapse: collapse; width: 100%;">
            <tr>
                <td style="border: 1px solid #ddd; width: 120px; font-size: 11px; padding: 5px 6px; font-weight: bold;">
                    Supplier Code:
                </td>
                <td style="border: 1px solid #ddd; font-size: 11px; padding: 5px 6px; font-weight: bold;">
                    {{ $report[0]->TransporterCode ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid #ddd; width: 120px; font-size: 11px; padding: 5px 6px; font-weight: bold;">
                    Supplier Name:
                </td>
                <td style="border: 1px solid #ddd; font-size: 11px; padding: 5px 6px; font-weight: bold;">
                    {{ empty($key) ? 'N/A' : $key }}
                </td>
            </tr>
        </table>

        {{-- Tabel data: thead hanya berisi baris kolom agar dompdf repeat di setiap halaman --}}
        <table>
            <thead>
                <tr>
                    <th class="small border" style="width: 80px; background-color: #f4f4f4;">D/O NO</th>
                    <th class="small border" style="width: 65px; background-color: #f4f4f4;">Date</th>
                    <th class="small border" style="width: 62px; background-color: #f4f4f4;">Plate NO</th>
                    <th class="small border" style="width: 60px; background-color: #f4f4f4;">Vehicle Group</th>
                    <th class="small border" style="width: 65px; background-color: #f4f4f4;">Area</th>
                    <th class="small border" style="width: 45px; background-color: #f4f4f4; text-align: center;">Quantity</th>
                    <th class="small border" style="width: 72px; background-color: #f4f4f4;">WB.Doc</th>
                    <th class="small border" style="width: 63px; background-color: #f4f4f4;">STD Weight (Kg)</th>
                    <th class="small border" style="width: 63px; background-color: #f4f4f4;">Weight (Kg)</th>
                    <th class="small border" style="width: 52px; background-color: #f4f4f4;">Var (Kg)</th>
                    <th class="small border" style="width: 60px; background-color: #f4f4f4;">Rate</th>
                    <th class="small border" style="width: 150px; background-color: #f4f4f4;">Amount (Rp)</th>
                    <th class="small border" style="width: 68px; background-color: #f4f4f4;">Kwitansi NO</th>
                </tr>
            </thead>

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
                    <td style="text-align: center;">{{ $fmtRound($data->Quantity) }}</td>
                    <td>{{ empty($data->WbDoc) ? 'N/A' : $data->WbDoc }}</td>
                    <td>{{ $fmtRound($data->StdWeight) }}</td>
                    <td>{{ $fmtRound($data->Weight) }}</td>
                    <td>{{ $fmtRound($data->VarKg) }}</td>
                    <td>{{ $fmtRound($data->Rate) }}</td>
                    <td>{{ $fmtRound($data->Amount) }}</td>
                    <td>{{ empty($data->Kwitansi_NO) ? 'N/A' : $data->Kwitansi_NO }}</td>
                </tr>
                @endforeach

                <!-- Subtotal Row -->
                <tr class="table-secondary fw-bold small" style="background: #f4f4f4;">
                    <td colspan="5" class="text-end">@if($is_multi_transporter)Sub @endif Total</td>
                    <td style="text-align: center;">{{ $fmtRound($subtotalQuantity) }}</td>
                    <td></td>
                    <td class="text-start">{{ $fmtRound($subtotalStdWeight) }}</td>
                    <td class="text-start">{{ $fmtRound($subtotalWeight) }}</td>
                    <td class="text-start">{{ $fmtRound($subtotalVar) }}</td>
                    <td></td>
                    <td class="text-start">{{ $fmtRound($subtotalAmount) }}</td>
                    <td></td>
                </tr>
            </tbody>
            <!-- Footer Totals -->
            @if($is_multi_transporter)
            @if($loop->last)
            <tr class="table-dark fw-bold small" style="background: #f4f4f4;">
                <td colspan="5" class="text-end">Total</td>
                <td style="text-align: center;">{{ $fmtRound($totalQuantity) }}</td>
                <td></td>
                <td class="text-start">{{ $fmtRound($totalStdWeight) }}</td>
                <td class="text-start">{{ $fmtRound($totalWeight) }}</td>
                <td class="text-start">{{ $fmtRound($totalVar) }}</td>
                <td></td>
                <td class="text-start">{{ $fmtRound($totalAmount) }}</td>
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
        $size = 12;
        $font = $fontMetrics->getFont("Arial");
        $width = $fontMetrics->get_text_width($text, $font, $size);
        $x = $pdf->get_width() - $width + 50; // Di atas kolom Amount
        $y = 15; // Atas (di area margin 40px)
        $pdf->page_text($x, $y, $text, $font, $size);
      }
    </script>
</body>

</html>