<?php

namespace App\Utils;

use Carbon\Carbon;

class Generator
{
    public static function generateSlipNo(string $type)
    {
        // Ensure type is either FG or RM
        if (!in_array($type, ['FG', 'RM'])) {
            throw new \InvalidArgumentException("Invalid type. Use 'FG' or 'RM'.");
        }

        // Get the current date
        $date = Carbon::now()->format('ymd');

        // Get the latest slip number with today's date and increment sequence
        $latestSlip = \App\Models\WeightBridge::where('Character01', 'like', "$type$date%")
            ->orderBy('Character01', 'desc')
            ->first();

        // Extract the sequence number and increment it
        $sequence = $latestSlip ? (int)substr($latestSlip->slip_no, -4) + 1 : 1;

        // Format the sequence as a 4-digit number
        $sequence = str_pad($sequence, 4, '0', STR_PAD_LEFT);

        // Return the formatted slip number
        return "$type$date$sequence";
    }
}
