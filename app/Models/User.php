<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ── Relationships ──

    public function periods(): HasMany
    {
        return $this->hasMany(Period::class)->orderBy('start_date', 'desc');
    }

    public function moods(): HasMany
    {
        return $this->hasMany(Mood::class)->orderBy('date', 'desc');
    }

    public function diaries(): HasMany
    {
        return $this->hasMany(Diary::class)->orderBy('date', 'desc');
    }

    // ── Cycle Helpers ──

    /**
     * Calculate the average cycle length in days.
     * Returns 28 as default if not enough data.
     */
    public function averageCycleLength(): int
    {
        $periods = $this->periods()->orderBy('start_date', 'asc')->pluck('start_date');

        if ($periods->count() < 2) {
            return 28; // default
        }

        $intervals = [];
        for ($i = 1; $i < $periods->count(); $i++) {
            $intervals[] = $periods[$i - 1]->diffInDays($periods[$i]);
        }

        return (int) round(array_sum($intervals) / count($intervals));
    }

    /**
     * Default period duration (days of bleeding).
     */
    public function periodDuration(): int
    {
        return 5;
    }

    /**
     * Predict the next period start date.
     */
    public function predictNextPeriod(): ?Carbon
    {
        $lastPeriod = $this->periods()->orderBy('start_date', 'desc')->first();

        if (!$lastPeriod) {
            return null;
        }

        return $lastPeriod->start_date->copy()->addDays($this->averageCycleLength());
    }

    /**
     * Determine the cycle phase for a given date.
     * Returns: 'period', 'fertile', 'normal', or 'predicted'
     */
    public function getCyclePhase(Carbon $date): string
    {
        $periods = $this->periods()->orderBy('start_date', 'asc')->pluck('start_date');
        $duration = $this->periodDuration();
        $avgCycle = $this->averageCycleLength();

        // Check if date falls within any recorded period
        foreach ($periods as $periodStart) {
            $periodEnd = $periodStart->copy()->addDays($duration - 1);
            if ($date->between($periodStart, $periodEnd)) {
                return 'period';
            }
        }

        // Check predicted period
        $predicted = $this->predictNextPeriod();
        if ($predicted) {
            $predictedEnd = $predicted->copy()->addDays($duration - 1);
            if ($date->between($predicted, $predictedEnd)) {
                return 'predicted';
            }
        }

        // Check fertile window (days 12-20 of cycle)
        if ($periods->isNotEmpty()) {
            $lastPeriod = $periods->last();
            $dayOfCycle = $lastPeriod->diffInDays($date) + 1;

            // Handle the current cycle
            if ($dayOfCycle > 0 && $dayOfCycle <= $avgCycle) {
                if ($dayOfCycle >= 12 && $dayOfCycle <= 20) {
                    return 'fertile';
                }
            }

            // Also check from predicted
            if ($predicted) {
                $dayFromPredicted = $predicted->diffInDays($date) + 1;
                if ($dayFromPredicted > 0 && $dayFromPredicted <= $avgCycle) {
                    if ($dayFromPredicted >= 12 && $dayFromPredicted <= 20) {
                        return 'fertile';
                    }
                }
            }
        }

        return 'normal';
    }

    /**
     * Get calendar data for a given month.
     * Returns an array of dates with their cycle phases.
     */
    public function getCalendarData(int $year, int $month): array
    {
        $startOfMonth = Carbon::create($year, $month, 1)->startOfDay();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        $data = [];

        for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
            $dateStr = $date->format('Y-m-d');
            $data[$dateStr] = [
                'phase' => $this->getCyclePhase($date->copy()),
                'mood' => $this->moods()->whereDate('date', $dateStr)->first(),
                'diary' => $this->diaries()->whereDate('date', $dateStr)->first(),
            ];
        }

        return $data;
    }
}
