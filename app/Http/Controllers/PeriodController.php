<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|before_or_equal:today',
        ]);

        $request->user()->periods()->updateOrCreate(
            ['start_date' => $request->start_date],
            ['start_date' => $request->start_date]
        );

        return back()->with('success', 'Period logged successfully! 🌸');
    }

    public function destroy(Request $request, Period $period)
    {
        // Ensure user owns this period
        if ($period->user_id !== $request->user()->id) {
            abort(403);
        }

        $period->delete();

        return back()->with('success', 'Period entry removed! ✨');
    }
}
