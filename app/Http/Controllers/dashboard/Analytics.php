<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\apps\ApprovalController;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Region;
use App\Models\Transporter;
use App\Models\Vehicle;
use App\Models\WeightBridge;
use App\Models\WeightBridgeApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Analytics extends Controller
{
  public function index()
  {

    $currentMonth = Carbon::now()->month;
    $currentYear = Carbon::now()->year;
    $currentMonthString = Carbon::now()->format('F');
    $weightData = WeightBridge::select(
      DB::raw("CONVERT(DATE, Date01) as date_weight"),
      DB::raw('count(CASE WHEN Number02 IS NOT NULL THEN 1 END) as total_weight_out'),
      DB::raw('count(CASE WHEN Number01 IS NOT NULL AND Number02 IS NULL THEN 1 END) as total_weight_in')
    )
      ->whereMonth('Date01', $currentMonth)
      ->whereYear('Date01', $currentYear)
      ->groupBy('Date01')
      ->get()
      ->toArray();

    $weightOut = [];
    $weightIn = [];
    $arrivalDate = [];

    foreach ($weightData as $data) {
      $weightOut[] = $data['total_weight_out'];
      $weightIn[] = $data['total_weight_out'] ? $data['total_weight_out'] + $data['total_weight_in'] : $data['total_weight_in'];
      // $arrivalDate[] = Carbon::parse($data['date_weight'])?->format('d');
    }
    return view(
      'content.dashboard.dashboards-analytics',
      [
        'user' => auth()->user(),
        'total_vehicle' => Vehicle::get()->count(),
        'total_transporter' => Transporter::get()->count(),
        'total_area' => Area::get()->count(),
        'total_region' => Region::get()->count(),
        'waiting_approval' => WeightBridgeApproval::whereNull('Date01')->get()->count(),
        'total_approved' => WeightBridgeApproval::where('CheckBox01', true)->get()->count(),
        'total_rejected' => WeightBridgeApproval::where('CheckBox02', true)->get()->count(),
        'weight_out' => $weightOut,
        'weight_in' => $weightIn,
        'weight_out_date' => $arrivalDate,
        'current_month' => $currentMonthString
      ]
    );
  }
}
