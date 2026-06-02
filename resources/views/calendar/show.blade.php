<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- Back Button --}}
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-her-text-light hover:text-pink-500 transition-colors">
            ← Back to Dashboard
        </a>

        {{-- Date Header --}}
        <div class="card-cute bg-gradient-to-r from-pink-100 via-white to-accent-lavender animate-fade-in">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-her-text">{{ $dateObj->format('d F Y') }}</h1>
                <p class="text-her-text-light mt-1">{{ $dateObj->format('l') }}</p>
                @if ($dateObj->isToday())
                    <span class="inline-block mt-2 px-3 py-1 bg-pink-200 text-pink-700 rounded-full text-xs font-semibold">✨ Today</span>
                @endif
            </div>
        </div>

        {{-- Cycle Phase --}}
        <div class="card-cute animate-slide-up">
            <h2 class="text-lg font-bold text-her-text flex items-center gap-2 mb-3">
                {{ match($phase) { 'period' => '🔴', 'fertile' => '🟣', 'predicted' => '🔵', default => '🟢' } }}
                Cycle Phase
            </h2>
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl
                    {{ match($phase) {
                        'period' => 'bg-red-50',
                        'fertile' => 'bg-purple-50',
                        'predicted' => 'bg-blue-50',
                        default => 'bg-green-50'
                    } }}">
                    {{ match($phase) { 'period' => '🩸', 'fertile' => '🌸', 'predicted' => '🔮', default => '🌿' } }}
                </div>
                <div>
                    <p class="text-xl font-bold {{ match($phase) {
                        'period' => 'text-her-period',
                        'fertile' => 'text-her-fertile',
                        'predicted' => 'text-her-predict',
                        default => 'text-her-normal'
                    } }}">{{ ucfirst($phase) }} Phase</p>
                    <p class="text-sm text-her-text-light">
                        @if ($dayOfCycle)
                            Day {{ $dayOfCycle }} of your cycle
                        @else
                            No cycle data available yet
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Mood --}}
        <div class="card-cute animate-slide-up" style="animation-delay: 0.1s">
            <h2 class="text-lg font-bold text-her-text flex items-center gap-2 mb-3">
                😊 Mood
            </h2>
            @if ($mood)
                <div class="flex items-center gap-4">
                    <span class="text-5xl">{{ $mood->emoji }}</span>
                    <div>
                        <p class="text-xl font-bold text-her-text">{{ $mood->label }}</p>
                        <div class="flex items-center gap-1 mt-1">
                            <span class="text-sm text-her-text-light">Intensity:</span>
                            <div class="flex gap-0.5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="w-5 h-5 rounded-full text-xs flex items-center justify-center
                                        {{ $i <= $mood->level ? 'bg-pink-400 text-white' : 'bg-pink-100 text-pink-300' }}">
                                        {{ $i }}
                                    </span>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <p class="text-her-text-light text-sm">No mood logged for this day</p>
                @if ($dateObj->isToday() || $dateObj->isPast())
                    <form method="POST" action="{{ route('moods.store') }}" class="mt-4">
                        @csrf
                        <input type="hidden" name="date" value="{{ $dateStr }}">
                        <input type="hidden" name="mood" id="detail-mood-input" value="">
                        <input type="hidden" name="level" id="detail-level-input" value="3">

                        <div class="flex justify-center gap-4 mb-4">
                            @foreach ([['happy', '😊', 'Happy'], ['neutral', '😐', 'Neutral'], ['angry', '😡', 'Angry']] as [$moodType, $emoji, $label])
                                <button type="button"
                                        onclick="selectDetailMood('{{ $moodType }}')"
                                        id="detail-mood-btn-{{ $moodType }}"
                                        class="emoji-btn flex flex-col items-center gap-1">
                                    <span class="text-3xl">{{ $emoji }}</span>
                                    <span class="text-xs text-her-text-light">{{ $label }}</span>
                                </button>
                            @endforeach
                        </div>

                        <input type="range" min="1" max="5" value="3"
                               onchange="document.getElementById('detail-level-input').value = this.value;"
                               class="w-full h-2 bg-pink-100 rounded-lg appearance-none cursor-pointer accent-pink-400 mb-4">

                        <button type="submit" class="btn-pink w-full">Save Mood 💕</button>
                    </form>
                @endif
            @endif
        </div>

        {{-- Diary --}}
        <div class="card-cute animate-slide-up" style="animation-delay: 0.2s">
            <h2 class="text-lg font-bold text-her-text flex items-center gap-2 mb-3">
                📝 Diary
            </h2>
            @if ($diary)
                <div class="bg-pink-50/50 rounded-xl p-4">
                    <p class="text-her-text whitespace-pre-wrap leading-relaxed">{{ $diary->content }}</p>
                    <p class="text-xs text-her-text-light mt-3">Written {{ $diary->updated_at->diffForHumans() }}</p>
                </div>

                {{-- Edit Form --}}
                <details class="mt-4">
                    <summary class="text-sm text-pink-500 cursor-pointer hover:text-pink-600 transition-colors">✏️ Edit diary entry</summary>
                    <form method="POST" action="{{ route('diaries.store') }}" class="mt-3">
                        @csrf
                        <input type="hidden" name="date" value="{{ $dateStr }}">
                        <textarea name="content" rows="4" class="input-cute resize-none mb-3">{{ $diary->content }}</textarea>
                        <button type="submit" class="btn-pink">Update 📝</button>
                    </form>
                </details>
            @else
                <p class="text-her-text-light text-sm mb-3">No diary entry for this day</p>
                @if ($dateObj->isToday() || $dateObj->isPast())
                    <form method="POST" action="{{ route('diaries.store') }}">
                        @csrf
                        <input type="hidden" name="date" value="{{ $dateStr }}">
                        <textarea name="content" rows="4" class="input-cute resize-none mb-3" placeholder="Write about this day... 🌸"></textarea>
                        <button type="submit" class="btn-pink w-full">Save Diary 📝</button>
                    </form>
                @endif
            @endif
        </div>
    </div>

    <script>
        function selectDetailMood(mood) {
            document.getElementById('detail-mood-input').value = mood;
            document.querySelectorAll('[id^="detail-mood-btn-"]').forEach(btn => btn.classList.remove('active'));
            document.getElementById('detail-mood-btn-' + mood).classList.add('active');
        }
    </script>
</x-app-layout>
