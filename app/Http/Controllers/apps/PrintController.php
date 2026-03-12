<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\WeightBridge;
use App\Models\WeightBridgeApproval;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use PDF;

class PrintController extends Controller
{
  public function generateSlipPDF($uuid)
  {
    // Declare @SlipNo NVARCHAR(MAX) = 'FG2411080006'

    // select NoDokumen_c, LegalNumber, T2.OrderLine, T3.PartNum, T3.NetWeight from ShipHead T1
    // left join ShipDtl T2 ON T1.Company = T2.Company AND T1.PackNum = T2.PackNum
    // left join Part T3 On T2.Company = T3.Company AND T2.PartNum = T3.PartNum
    // where NoDokumen_c = @SlipNo
    // Retrieve the data for the slip (replace with actual query)
    $slip = WeightBridge::findOrFail($uuid);

    $spbDetails = DB::select("
    SELECT T1.LegalNumber, T2.TotalNetWeight, T2.OrderLine, T3.PartNum, T3.NetWeight as beratStandarPergenteng
    FROM ShipHead AS T1
    LEFT JOIN ShipDtl AS T2 ON T1.PackNum = T2.PackNum AND T1.Company = T2.Company
    LEFT JOIN Part T3 On T2.Company = T3.Company AND T2.PartNum = T3.PartNum
    WHERE T1.NoDokumen_c = :slipNo
", ['slipNo' => $slip->slip_no]);

    $totalWeight = DB::select("
    SELECT SUM(T2.TotalNetWeight) AS beratStandart
    FROM ShipHead AS T1
    LEFT JOIN ShipDtl AS T2 ON T1.PackNum = T2.PackNum AND T1.Company = T2.Company
    WHERE T1.NoDokumen_c = :slipNo
    ", ['slipNo' => $slip->slip_no]);

    $totalQty = DB::select("
    SELECT COUNT(T2.OrderLine) AS totalQty
    FROM ShipHead AS T1
    LEFT JOIN ShipDtl AS T2 ON T1.PackNum = T2.PackNum AND T1.Company = T2.Company
    WHERE T1.NoDokumen_c = :slipNo
    ", ['slipNo' => $slip->slip_no]);

    $totalBeratStandart = 0;
    if ($slip->weight_type == 'fg') {
      if ($totalWeight[0]->beratStandart != 0) {
          $totalBeratStandart = round($slip->weight_netto / $totalWeight[0]->beratStandart, 2);
      }
    }

    // $totalWeightValue = $totalWeight[0]->TotalWeight ?? 0;
    // Define data to pass to the Blade view
    $data = [
      'slip_no' => $slip->slip_no,
      'weight_in_date' => $slip->weight_in_date,
      'vehicle_no' => $slip->vehicle_no ?? $slip->vehicle->register_number,
      'transporter_name' => $slip->transporter_name,
      'vehicle_type' => $slip->vehicle?->vehicle_type->name,
      'weight_type' => $slip->weight_type,
      'remark' => $slip->remark,
      'weight_in' => round($slip->weight_in),
      'weight_in_time' => Carbon::parse($slip->weight_in_date)->format('H:i:s'),
      'weight_in_date' => Carbon::parse($slip->weight_in_date)->format('Y-m-d'),
      'weight_out' => round($slip->weight_out),
      'weight_out_date' => $slip->weight_out_date,
      'weight_netto' => round($slip->weight_netto),
      'weight_out_time' => $slip->weight_out_date ? Carbon::parse($slip->weight_out_date)->format('H:i:s') : '',
      'weight_out_date' => $slip->weight_out_date ? Carbon::parse($slip->weight_out_date)->format('Y-m-d') : '',
      'weight_in_by' => $slip->weight_in_by,
      'driver_name' => $slip->vehicle?->driver_name,
      'po_do' => $slip->po_do,
      'actual_weight' => $slip->actual_weight,
      'status' => $slip->status,
      'spb_details' => $spbDetails,
      'total_qty' => $totalQty[0]->totalQty ?? 0,
      // 'total_weight' => $totalWeightValue
      'total_berat_standart' => $totalBeratStandart
    ];

    // Load the view and pass the data
    $pdf = PDF::loadView('content.weight-bridge.print.slip', $data);

    $defaultPaperHeight = 243;
    if (count($spbDetails) > 1) {
      $paperHeight = ((count($spbDetails) - 1) * 10) + 10 + $defaultPaperHeight;
    } else {
      $paperHeight = $defaultPaperHeight;
    }

    $paperWidth = 75 * 2.834645669; // Convert 75mm to points
    $pdf->setPaper([0, 0, $paperWidth, $paperHeight]);

    // Output the PDF for download or inline view
    return $pdf->stream("slip_$slip->slip_no.pdf"); // or ->download('weighbridge_slip.pdf') for direct download
  }
}
