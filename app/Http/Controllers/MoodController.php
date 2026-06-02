<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MoodController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'mood' => 'required|in:happy,neutral,angry',
            'level' => 'required|integer|min:1|max:5',
        ]);

        $request->user()->moods()->updateOrCreate(
            ['date' => $request->date],
            [
                'mood' => $request->mood,
                'level' => $request->level,
            ]
        );

        return back()->with('success', 'Mood saved! 💕');
    }
}
