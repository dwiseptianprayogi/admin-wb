<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\WeightBridge;
use App\Models\WeightBridgeApproval;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
  public function index()
  {

    $approvals = WeightBridgeApproval::where('CheckBox01', false)->where('CheckBox02', false)->orderBy('Character01', 'DESC')->get();
    if (auth()->user()->can('approve 2')) {
      $approvals = WeightBridgeApproval::where('CheckBox01', true)
        ->where('CheckBox03', false)
        ->where('CheckBox04', false)
        ->orderBy('Character01', 'DESC')->get();
    }
    return view(
      'content.approval.list',
      [
        'approvals' => $approvals,
      ]
    );
  }

  public function view()
  {
    return view('content.weight-bridge.create');
  }

  public function approve($approvalUuid)
  {
    $approval = WeightBridgeApproval::findOrFail($approvalUuid);
    if ($approval->action_date == null) {
      $approval->is_approve = true;
      $approval->action_date = Carbon::now();
      $approval->action_by = auth()->user()->name ?? 'admin';
    } else {
      $approval->is_approve_2 = true;
      $approval->action_date_2 = Carbon::now();
      $approval->action_by_2 = auth()->user()->name ?? 'admin';
    }

    if ($approval->is_approve == true and $approval->is_approve_2 == true) {
      $weightBridge = $approval->weight_bridge;
      $weightBridge->status = 'FG-OUT';
      $weightBridge->update();
    }
    $approval->update();
    return redirect()->route('transaction.weight-bridge.approval.list')->with('success', 'Approved. Detail: successfuly approve Slip No: ' . $approval->weight_bridge->slip_no);
  }

  public function reject($approvalUuid)
  {
    $approval = WeightBridgeApproval::findOrFail($approvalUuid);

    DB::transaction(function () use ($approvalUuid, $approval) {
      if ($approval->action_date == null) {
        $approval->is_reject = true;
        $approval->action_date = Carbon::now();
        $approval->action_by = auth()->user()->name ?? 'admin';
      } else {
        $approval->is_reject_2 = true;
        $approval->action_date_2 = Carbon::now();
        $approval->action_by_2 = auth()->user()->name ?? 'admin';
      }

      $weightBridge = $approval->weight_bridge;
      $fillable = $weightBridge->getFillable();
      $weightBridge->status = 'FG-IN';
      $newWeightBridge = [];
      foreach ($fillable as $key) {
        $newWeightBridge[$key] = $weightBridge->$key;
      }
      $weightBridge->status = 'REJECTED';
      $approval->save();
      $weightBridge->save();
      $newWeightBridge = WeightBridge::create($newWeightBridge);
    });

    return redirect()->route('transaction.weight-bridge.approval.list')
      ->with('success', 'Rejected. Detail: successfully rejected Slip No: ' . $approval->weight_bridge->slip_no);
  }

  public function update()
  {
    return view('content.weight-bridge.create');
  }

  public function delete()
  {
    return view('content.weight-bridge.create');
  }
}
