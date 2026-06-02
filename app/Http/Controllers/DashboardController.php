<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today();

        // Calendar data for current month
        $year = $request->get('year', $today->year);
        $month = $request->get('month', $today->month);
        $calendarData = $user->getCalendarData($year, $month);

        // Next period prediction
        $nextPeriod = $user->predictNextPeriod();
        $avgCycle = $user->averageCycleLength();

        // Today's mood
        $todayMood = $user->moods()->whereDate('date', $today)->first();

        // Today's diary
        $todayDiary = $user->diaries()->whereDate('date', $today)->first();

        // Recent diary entries
        $recentDiaries = $user->diaries()->take(3)->get();

        // Check if mood logged today (for reminder)
        $hasMoodToday = $todayMood !== null;

        return view('dashboard', compact(
            'user', 'today', 'year', 'month',
            'calendarData', 'nextPeriod', 'avgCycle',
            'todayMood', 'todayDiary', 'recentDiaries',
            'hasMoodToday'
        ));
    }
}
