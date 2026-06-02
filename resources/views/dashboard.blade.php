<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- ═══ GREETING SECTION ═══ --}}
        <div class="card-cute bg-gradient-to-r from-pink-100 via-white to-accent-lavender animate-fade-in">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-her-text">
                        Hi, {{ explode(' ', $user->name)[0] }} 💕
                    </h1>
                    <p class="text-her-text-light mt-1">
                        {{ $today->format('l, d F Y') }} • How are you feeling today?
                    </p>
                </div>
                @if (!$hasMoodToday)
                    <div class="bg-pink-50 border border-pink-200 rounded-2xl px-4 py-3 text-sm text-pink-600 flex items-center gap-2 animate-pulse-soft">
                        <span class="text-lg">💭</span>
                        <span>Don't forget to log your mood today!</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- ═══ PREDICTION + QUICK STATS ═══ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 animate-slide-up">
            {{-- Next Period Prediction --}}
            <div class="card-cute border-l-4 border-her-period">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-2xl">🩸</span>
                    <h3 class="font-semibold text-her-text">Next Period</h3>
                </div>
                @if ($nextPeriod)
                    <p class="text-2xl font-bold text-her-period">{{ $nextPeriod->format('d M Y') }}</p>
                    <p class="text-sm text-her-text-light mt-1">
                        @php
                            $daysUntil = $today->diffInDays($nextPeriod, false);
                        @endphp
                        @if ($daysUntil > 0)
                            In {{ $daysUntil }} days
                        @elseif ($daysUntil == 0)
                            Today! Take care 💕
                        @else
                            {{ abs($daysUntil) }} days ago
                        @endif
                    </p>
                @else
                    <p class="text-her-text-light text-sm">Log your first period to get predictions! 🌸</p>
                @endif
            </div>

            {{-- Cycle Length --}}
            <div class="card-cute border-l-4 border-her-fertile">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-2xl">🔄</span>
                    <h3 class="font-semibold text-her-text">Cycle Length</h3>
                </div>
                <p class="text-2xl font-bold text-her-fertile">{{ $avgCycle }} days</p>
                <p class="text-sm text-her-text-light mt-1">Average cycle length</p>
            </div>

            {{-- Today's Phase --}}
            <div class="card-cute border-l-4 border-her-normal">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-2xl">
                        @php $todayPhase = $user->getCyclePhase($today); @endphp
                        {{ match($todayPhase) { 'period' => '🔴', 'fertile' => '🟣', 'predicted' => '🔵', default => '🟢' } }}
                    </span>
                    <h3 class="font-semibold text-her-text">Today's Phase</h3>
                </div>
                <p class="text-2xl font-bold {{ match($todayPhase) { 'period' => 'text-her-period', 'fertile' => 'text-her-fertile', 'predicted' => 'text-her-predict', default => 'text-her-normal' } }}">
                    {{ ucfirst($todayPhase) }}
                </p>
                <p class="text-sm text-her-text-light mt-1">Current cycle phase</p>
            </div>
        </div>

        {{-- ═══ CALENDAR ═══ --}}
        <div class="card-cute animate-slide-up" style="animation-delay: 0.1s">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-her-text flex items-center gap-2">
                    <span>📅</span> Calendar
                </h2>
                <div class="flex items-center gap-2">
                    @php
                        $prevMonth = \Carbon\Carbon::create($year, $month, 1)->subMonth();
                        $nextMonth = \Carbon\Carbon::create($year, $month, 1)->addMonth();
                    @endphp
                    <a href="{{ route('dashboard', ['year' => $prevMonth->year, 'month' => $prevMonth->month]) }}"
                       class="p-2 rounded-xl hover:bg-pink-50 transition-colors text-her-text">
                        ◀
                    </a>
                    <span class="font-semibold text-her-text px-3">
                        {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}
                    </span>
                    <a href="{{ route('dashboard', ['year' => $nextMonth->year, 'month' => $nextMonth->month]) }}"
                       class="p-2 rounded-xl hover:bg-pink-50 transition-colors text-her-text">
                        ▶
                    </a>
                </div>
            </div>

            {{-- Legend --}}
            <div class="flex flex-wrap gap-4 mb-4 text-xs text-her-text-light">
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-her-period inline-block"></span> Period</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-her-normal inline-block"></span> Normal</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-her-fertile inline-block"></span> Fertile</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-her-predict inline-block"></span> Predicted</span>
            </div>

            {{-- Calendar Grid --}}
            <div class="grid grid-cols-7 gap-1">
                {{-- Day Headers --}}
                @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                    <div class="text-center text-xs font-semibold text-her-text-light py-2">{{ $day }}</div>
                @endforeach

                {{-- Empty cells before first day --}}
                @php
                    $firstDay = \Carbon\Carbon::create($year, $month, 1);
                    $startDow = $firstDay->dayOfWeek;
                @endphp
                @for ($i = 0; $i < $startDow; $i++)
                    <div></div>
                @endfor

                {{-- Calendar Days --}}
                @foreach ($calendarData as $dateStr => $data)
                    @php
                        $dateCarbon = \Carbon\Carbon::parse($dateStr);
                        $isToday = $dateCarbon->isToday();
                        $phase = $data['phase'];
                        $hasMood = $data['mood'] !== null;
                        $hasDiary = $data['diary'] !== null;

                        $dotColor = match($phase) {
                            'period' => 'bg-her-period',
                            'fertile' => 'bg-her-fertile',
                            'predicted' => 'bg-her-predict',
                            default => 'bg-her-normal',
                        };
                    @endphp
                    <a href="{{ route('calendar.show', $dateStr) }}"
                       class="relative flex flex-col items-center justify-center p-1.5 sm:p-2 rounded-xl transition-all duration-200 hover:bg-pink-50 hover:scale-105 group
                              {{ $isToday ? 'bg-pink-100 ring-2 ring-pink-300 font-bold' : '' }}">
                        <span class="text-xs sm:text-sm {{ $isToday ? 'text-pink-600' : 'text-her-text' }} group-hover:text-pink-600">
                            {{ $dateCarbon->day }}
                        </span>
                        {{-- Phase dot --}}
                        <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full {{ $dotColor }} mt-0.5"></span>
                        {{-- Mood indicator --}}
                        @if ($hasMood)
                            <span class="absolute -top-0.5 -right-0.5 text-[10px]">{{ $data['mood']->emoji }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        {{-- ═══ MOOD + DIARY INPUT ═══ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 animate-slide-up" style="animation-delay: 0.2s">

            {{-- Mood Input --}}
            <div class="card-cute">
                <h2 class="text-xl font-bold text-her-text flex items-center gap-2 mb-4">
                    <span>😊</span> How are you feeling?
                </h2>

                <form method="POST" action="{{ route('moods.store') }}" id="mood-form">
                    @csrf
                    <input type="hidden" name="date" value="{{ $today->format('Y-m-d') }}">
                    <input type="hidden" name="mood" id="mood-input" value="{{ $todayMood?->mood ?? '' }}">
                    <input type="hidden" name="level" id="level-input" value="{{ $todayMood?->level ?? 3 }}">

                    {{-- Emoji Selector --}}
                    <div class="flex justify-center gap-4 mb-6">
                        @foreach ([['happy', '😊', 'Happy'], ['neutral', '😐', 'Neutral'], ['angry', '😡', 'Angry']] as [$mood, $emoji, $label])
                            <button type="button"
                                    onclick="selectMood('{{ $mood }}')"
                                    id="mood-btn-{{ $mood }}"
                                    class="emoji-btn flex flex-col items-center gap-1 {{ ($todayMood?->mood ?? '') === $mood ? 'active' : '' }}">
                                <span class="text-4xl">{{ $emoji }}</span>
                                <span class="text-xs text-her-text-light">{{ $label }}</span>
                            </button>
                        @endforeach
                    </div>

                    {{-- Intensity Slider --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-her-text mb-2">
                            Intensity: <span id="level-display" class="text-pink-500 font-bold">{{ $todayMood?->level ?? 3 }}</span> / 5
                        </label>
                        <input type="range" min="1" max="5" value="{{ $todayMood?->level ?? 3 }}"
                               id="level-slider"
                               onchange="document.getElementById('level-input').value = this.value; document.getElementById('level-display').textContent = this.value;"
                               class="w-full h-2 bg-pink-100 rounded-lg appearance-none cursor-pointer accent-pink-400">
                        <div class="flex justify-between text-xs text-her-text-light mt-1">
                            <span>Mild</span>
                            <span>Strong</span>
                        </div>
                    </div>

                    <button type="submit" class="btn-pink w-full">
                        {{ $todayMood ? 'Update Mood 💕' : 'Save Mood 💕' }}
                    </button>
                </form>
            </div>

            {{-- Diary Input --}}
            <div class="card-cute">
                <h2 class="text-xl font-bold text-her-text flex items-center gap-2 mb-4">
                    <span>📝</span> Today's Diary
                </h2>

                <form method="POST" action="{{ route('diaries.store') }}">
                    @csrf
                    <input type="hidden" name="date" value="{{ $today->format('Y-m-d') }}">

                    <textarea name="content" rows="5"
                              class="input-cute resize-none mb-4"
                              placeholder="Write about your day... 🌸">{{ $todayDiary?->content ?? '' }}</textarea>

                    <button type="submit" class="btn-pink w-full">
                        {{ $todayDiary ? 'Update Diary 📝' : 'Save Diary 📝' }}
                    </button>
                </form>
            </div>
        </div>

        {{-- ═══ LOG PERIOD ═══ --}}
        <div class="card-cute animate-slide-up" style="animation-delay: 0.3s">
            <h2 class="text-xl font-bold text-her-text flex items-center gap-2 mb-4">
                <span>🩸</span> Log Period
            </h2>
            <form method="POST" action="{{ route('periods.store') }}" class="flex flex-col sm:flex-row gap-3">
                @csrf
                <input type="date" name="start_date" value="{{ $today->format('Y-m-d') }}"
                       max="{{ $today->format('Y-m-d') }}"
                       class="input-cute flex-1">
                <button type="submit" class="btn-pink whitespace-nowrap">
                    Log Period Start 🌸
                </button>
            </form>
        </div>

        {{-- ═══ RECENT DIARY ENTRIES ═══ --}}
        @if ($recentDiaries->isNotEmpty())
            <div class="card-cute animate-slide-up" style="animation-delay: 0.4s">
                <h2 class="text-xl font-bold text-her-text flex items-center gap-2 mb-4">
                    <span>📖</span> Recent Diary Entries
                </h2>
                <div class="space-y-3">
                    @foreach ($recentDiaries as $diary)
                        <a href="{{ route('calendar.show', $diary->date->format('Y-m-d')) }}"
                           class="block p-4 bg-pink-50/50 rounded-xl hover:bg-pink-50 transition-colors">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-semibold text-pink-500">{{ $diary->date->format('d M Y') }}</span>
                                <span class="text-xs text-her-text-light">{{ $diary->date->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-her-text line-clamp-2">{{ $diary->content }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Mood selector script --}}
    <script>
        function selectMood(mood) {
            document.getElementById('mood-input').value = mood;
            // Update active state
            document.querySelectorAll('.emoji-btn').forEach(btn => btn.classList.remove('active'));
            document.getElementById('mood-btn-' + mood).classList.add('active');
        }
    </script>
</x-app-layout>
