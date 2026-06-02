<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DiaryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'content' => 'required|string|max:2000',
        ]);

        $request->user()->diaries()->updateOrCreate(
            ['date' => $request->date],
            ['content' => $request->content]
        );

        return back()->with('success', 'Diary saved! 📝');
    }
}
