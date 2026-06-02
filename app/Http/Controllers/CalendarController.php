<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function show(Request $request, string $date)
    {
        $user = $request->user();
        $dateObj = Carbon::parse($date);
        $dateStr = $dateObj->format('Y-m-d');

        // Get cycle phase
        $phase = $user->getCyclePhase($dateObj);

        // Get mood for this date
        $mood = $user->moods()->whereDate('date', $dateStr)->first();

        // Get diary for this date
        $diary = $user->diaries()->whereDate('date', $dateStr)->first();

        // Calculate day of cycle
        $lastPeriod = $user->periods()->orderBy('start_date', 'desc')
            ->where('start_date', '<=', $dateStr)
            ->first();
        $dayOfCycle = $lastPeriod
            ? $lastPeriod->start_date->diffInDays($dateObj) + 1
            : null;

        return view('calendar.show', compact(
            'dateObj', 'dateStr', 'phase', 'mood', 'diary', 'dayOfCycle'
        ));
    }
}
