<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Timelog;
use Illuminate\Support\Facades\Auth;

class TimeController extends Controller
{
    //
public function timeIn()
{
    $user = Auth::user();
    $log = Timelog::create([
     'user_id' => $user->id,
    'time_in' => now()
    ]);

    return response()->json([
        "ok" => true,
        "message" => "Time In Recorded",
        "data" => $log
    ]);
}

public function timeout()
{
    $user = Auth::user();
    $log = Timelog::where(
      'user_id', $user->id
    ) -> whereNull('time_out')->latest()->first();

    if(!$log) {
        return response() -> json(["ok" => false, "message" => "No active time-in found"], 400);
    }

    $log->update(['time_out' => now()]);

    return response()->json([
        "ok" => true,
        "message" => "Time Out Recorded",
        "data" => $log
    ]);


}

    
    public function totalTime()
    {
        $user = Auth::user();
        
        $totalHours = Timelog::where('user_id', $user->id)
            ->whereNotNull('time_in')
            ->whereNotNull('time_out')
            ->sum(\DB::raw("TIMESTAMPDIFF(HOUR, time_in, time_out)"));

        return response()->json([
            "ok" => true,
            "message" => "Total Time Calculated",
            "total_hours" => $totalHours
        ]);
    }


}
