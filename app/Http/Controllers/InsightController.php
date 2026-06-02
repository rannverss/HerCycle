<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class InsightController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $insights = [];

        $avgCycle = $user->averageCycleLength();
        $periods = $user->periods()->orderBy('start_date', 'asc')->pluck('start_date');
        $moods = $user->moods()->get();

        // Insight 1: Cycle regularity
        if ($periods->count() >= 3) {
            $intervals = [];
            for ($i = 1; $i < $periods->count(); $i++) {
                $intervals[] = $periods[$i - 1]->diffInDays($periods[$i]);
            }
            $variance = $this->variance($intervals);
            if ($variance < 4) {
                $insights[] = [
                    'emoji' => '✨',
                    'title' => 'Regular Cycle',
                    'text' => "Your cycle is very regular at around {$avgCycle} days. Great job tracking!",
                    'color' => 'green',
                ];
            } else {
                $insights[] = [
                    'emoji' => '🌊',
                    'title' => 'Variable Cycle',
                    'text' => "Your cycle varies a bit. This is normal! Average is {$avgCycle} days.",
                    'color' => 'purple',
                ];
            }
        }

        // Insight 2: Pre-period mood analysis
        if ($moods->count() >= 5 && $periods->count() >= 2) {
            $prePeriodMoods = [];
            $midCycleMoods = [];

            foreach ($moods as $mood) {
                // Find which cycle this mood belongs to
                $closestPeriod = $periods->filter(fn($p) => $p->lte($mood->date))->last();
                if ($closestPeriod) {
                    $dayOfCycle = $closestPeriod->diffInDays($mood->date) + 1;

                    // Pre-period: last 5 days of cycle
                    if ($dayOfCycle > ($avgCycle - 5) && $dayOfCycle <= $avgCycle) {
                        $prePeriodMoods[] = $mood;
                    }
                    // Mid-cycle: days 10-18
                    if ($dayOfCycle >= 10 && $dayOfCycle <= 18) {
                        $midCycleMoods[] = $mood;
                    }
                }
            }

            // Analyze pre-period moods
            $angryPrePeriod = collect($prePeriodMoods)->where('mood', 'angry')->count();
            if ($angryPrePeriod > 0 && count($prePeriodMoods) > 0) {
                $percentage = round(($angryPrePeriod / count($prePeriodMoods)) * 100);
                if ($percentage > 30) {
                    $insights[] = [
                        'emoji' => '🌙',
                        'title' => 'Pre-Period Patterns',
                        'text' => "You tend to feel more emotional before your period. This is completely normal! 💕",
                        'color' => 'pink',
                    ];
                }
            }

            // Analyze mid-cycle mood stability
            $happyMid = collect($midCycleMoods)->where('mood', 'happy')->count();
            if ($happyMid > 0 && count($midCycleMoods) > 0) {
                $percentage = round(($happyMid / count($midCycleMoods)) * 100);
                if ($percentage > 50) {
                    $insights[] = [
                        'emoji' => '☀️',
                        'title' => 'Mid-Cycle Happiness',
                        'text' => "Your mood is more positive during mid-cycle. You're shining! ✨",
                        'color' => 'yellow',
                    ];
                }
            }
        }

        // Insight 3: Mood distribution
        if ($moods->count() >= 3) {
            $happyCount = $moods->where('mood', 'happy')->count();
            $neutralCount = $moods->where('mood', 'neutral')->count();
            $angryCount = $moods->where('mood', 'angry')->count();
            $total = $moods->count();

            $dominant = 'neutral';
            $dominantEmoji = '😐';
            if ($happyCount >= $neutralCount && $happyCount >= $angryCount) {
                $dominant = 'happy';
                $dominantEmoji = '😊';
            } elseif ($angryCount >= $happyCount && $angryCount >= $neutralCount) {
                $dominant = 'angry';
                $dominantEmoji = '😡';
            }

            $insights[] = [
                'emoji' => $dominantEmoji,
                'title' => 'Overall Mood',
                'text' => "Your most common mood is " . ucfirst($dominant) . ". You've logged {$total} mood entries!",
                'color' => 'blue',
            ];

            // Pass mood stats for chart
            $moodStats = [
                'happy' => $happyCount,
                'neutral' => $neutralCount,
                'angry' => $angryCount,
                'total' => $total,
            ];
        }

        // Insight 4: Tracking streak
        $recentMoods = $user->moods()->orderBy('date', 'desc')->take(30)->pluck('date');
        if ($recentMoods->count() >= 2) {
            $streak = 1;
            $today = Carbon::today();
            if ($recentMoods->first()->isSameDay($today) || $recentMoods->first()->isSameDay($today->subDay())) {
                for ($i = 0; $i < $recentMoods->count() - 1; $i++) {
                    if ($recentMoods[$i]->diffInDays($recentMoods[$i + 1]) === 1) {
                        $streak++;
                    } else {
                        break;
                    }
                }
                if ($streak >= 3) {
                    $insights[] = [
                        'emoji' => '🔥',
                        'title' => 'Tracking Streak!',
                        'text' => "You've been tracking for {$streak} days in a row! Keep it up! 💪",
                        'color' => 'orange',
                    ];
                }
            }
        }

        // Default insights if none generated
        if (empty($insights)) {
            $insights[] = [
                'emoji' => '🌸',
                'title' => 'Start Tracking',
                'text' => 'Log your mood and period data to get personalized insights! The more you track, the better the insights.',
                'color' => 'pink',
            ];
        }

        return view('insights.index', compact('insights', 'moods'))
            ->with('moodStats', $moodStats ?? null);
    }

    private function variance(array $values): float
    {
        $count = count($values);
        if ($count < 2) return 0;

        $mean = array_sum($values) / $count;
        $sumOfSquares = 0;
        foreach ($values as $value) {
            $sumOfSquares += pow($value - $mean, 2);
        }
        return $sumOfSquares / $count;
    }
}
