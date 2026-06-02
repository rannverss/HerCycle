<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiaryController;
use App\Http\Controllers\InsightController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Period tracking
    Route::post('/periods', [PeriodController::class, 'store'])->name('periods.store');
    Route::delete('/periods/{period}', [PeriodController::class, 'destroy'])->name('periods.destroy');

    // Mood tracking
    Route::post('/moods', [MoodController::class, 'store'])->name('moods.store');

    // Diary
    Route::post('/diaries', [DiaryController::class, 'store'])->name('diaries.store');

    // Calendar detail
    Route::get('/calendar/{date}', [CalendarController::class, 'show'])->name('calendar.show');

    // Insights
    Route::get('/insights', [InsightController::class, 'index'])->name('insights.index');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
