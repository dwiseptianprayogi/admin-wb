<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\WeightBridge;
use App\Models\WeightBridgeApproval;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use PDF;

class LogController extends Controller
{
  public function index()
  {
    return view(
      'content.log.list',
      [
        'logs' => AuditLog::orderBy('Date01', 'DESC')->get(),
      ]
    );
  }
  public function view($uuid)
  {
    $log = AuditLog::where('Key1', $uuid)->firstOrFail();  // Fetch log by UUID
    return view('content.log.view', [
      'log' => $log
    ]);
  }
}
