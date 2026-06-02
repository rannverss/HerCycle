<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- Header --}}
        <div class="card-cute bg-gradient-to-r from-pink-100 via-white to-accent-lavender animate-fade-in">
            <h1 class="text-2xl sm:text-3xl font-bold text-her-text flex items-center gap-2">
                💡 Your Insights
            </h1>
            <p class="text-her-text-light mt-1">Personal patterns based on your tracking data</p>
        </div>

        {{-- Insight Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($insights as $insight)
                @php
                    $borderColor = match($insight['color']) {
                        'green' => 'border-her-normal',
                        'purple' => 'border-her-fertile',
                        'pink' => 'border-her-period',
                        'blue' => 'border-her-predict',
                        'yellow' => 'border-yellow-400',
                        'orange' => 'border-orange-400',
                        default => 'border-pink-300',
                    };
                    $bgColor = match($insight['color']) {
                        'green' => 'bg-green-50',
                        'purple' => 'bg-purple-50',
                        'pink' => 'bg-pink-50',
                        'blue' => 'bg-blue-50',
                        'yellow' => 'bg-yellow-50',
                        'orange' => 'bg-orange-50',
                        default => 'bg-pink-50',
                    };
                @endphp
                <div class="card-cute border-l-4 {{ $borderColor }} animate-slide-up">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl {{ $bgColor }} flex items-center justify-center text-2xl flex-shrink-0">
                            {{ $insight['emoji'] }}
                        </div>
                        <div>
                            <h3 class="font-bold text-her-text">{{ $insight['title'] }}</h3>
                            <p class="text-sm text-her-text-light mt-1 leading-relaxed">{{ $insight['text'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Mood Distribution Chart --}}
        @if (isset($moodStats) && $moodStats)
            <div class="card-cute animate-slide-up" style="animation-delay: 0.2s">
                <h2 class="text-xl font-bold text-her-text flex items-center gap-2 mb-6">
                    📊 Mood Distribution
                </h2>

                <div class="space-y-4">
                    {{-- Happy --}}
                    <div class="flex items-center gap-3">
                        <span class="text-2xl w-10">😊</span>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-her-text">Happy</span>
                                <span class="text-sm text-her-text-light">{{ $moodStats['happy'] }} ({{ $moodStats['total'] > 0 ? round($moodStats['happy'] / $moodStats['total'] * 100) : 0 }}%)</span>
                            </div>
                            <div class="w-full bg-pink-100 rounded-full h-3">
                                <div class="bg-gradient-to-r from-green-300 to-green-400 h-3 rounded-full transition-all duration-700"
                                     style="width: {{ $moodStats['total'] > 0 ? ($moodStats['happy'] / $moodStats['total'] * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Neutral --}}
                    <div class="flex items-center gap-3">
                        <span class="text-2xl w-10">😐</span>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-her-text">Neutral</span>
                                <span class="text-sm text-her-text-light">{{ $moodStats['neutral'] }} ({{ $moodStats['total'] > 0 ? round($moodStats['neutral'] / $moodStats['total'] * 100) : 0 }}%)</span>
                            </div>
                            <div class="w-full bg-pink-100 rounded-full h-3">
                                <div class="bg-gradient-to-r from-yellow-300 to-yellow-400 h-3 rounded-full transition-all duration-700"
                                     style="width: {{ $moodStats['total'] > 0 ? ($moodStats['neutral'] / $moodStats['total'] * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Angry --}}
                    <div class="flex items-center gap-3">
                        <span class="text-2xl w-10">😡</span>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-her-text">Angry</span>
                                <span class="text-sm text-her-text-light">{{ $moodStats['angry'] }} ({{ $moodStats['total'] > 0 ? round($moodStats['angry'] / $moodStats['total'] * 100) : 0 }}%)</span>
                            </div>
                            <div class="w-full bg-pink-100 rounded-full h-3">
                                <div class="bg-gradient-to-r from-red-300 to-red-400 h-3 rounded-full transition-all duration-700"
                                     style="width: {{ $moodStats['total'] > 0 ? ($moodStats['angry'] / $moodStats['total'] * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Recent Mood Timeline --}}
        @if ($moods->count() > 0)
            <div class="card-cute animate-slide-up" style="animation-delay: 0.3s">
                <h2 class="text-xl font-bold text-her-text flex items-center gap-2 mb-4">
                    📈 Recent Moods
                </h2>
                <div class="flex flex-wrap gap-2">
                    @foreach ($moods->take(14) as $m)
                        <a href="{{ route('calendar.show', $m->date->format('Y-m-d')) }}"
                           class="flex flex-col items-center p-2 rounded-xl hover:bg-pink-50 transition-colors group">
                            <span class="text-xl group-hover:scale-110 transition-transform">{{ $m->emoji }}</span>
                            <span class="text-[10px] text-her-text-light mt-1">{{ $m->date->format('d/m') }}</span>
                            <div class="flex gap-px mt-0.5">
                                @for ($i = 0; $i < $m->level; $i++)
                                    <span class="w-1 h-1 rounded-full bg-pink-400"></span>
                                @endfor
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
