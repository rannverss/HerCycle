<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- Profile Header --}}
        <div class="card-cute bg-gradient-to-r from-pink-100 via-white to-accent-lavender animate-fade-in">
            <div class="flex flex-col sm:flex-row items-center gap-6">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-pink-300 to-pink-200 flex items-center justify-center text-white text-3xl font-bold shadow-cute">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="text-center sm:text-left">
                    <h1 class="text-2xl font-bold text-her-text">{{ Auth::user()->name }} 💕</h1>
                    <p class="text-her-text-light">{{ Auth::user()->email }}</p>
                    <p class="text-sm text-pink-400 mt-1">Member since {{ Auth::user()->created_at->format('F Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Cycle Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 animate-slide-up">
            <div class="card-cute text-center">
                <span class="text-3xl">🔄</span>
                <p class="text-2xl font-bold text-her-fertile mt-2">{{ Auth::user()->averageCycleLength() }} days</p>
                <p class="text-sm text-her-text-light">Average Cycle</p>
            </div>
            <div class="card-cute text-center">
                <span class="text-3xl">🩸</span>
                <p class="text-2xl font-bold text-her-period mt-2">{{ Auth::user()->periodDuration() }} days</p>
                <p class="text-sm text-her-text-light">Period Duration</p>
            </div>
            <div class="card-cute text-center">
                <span class="text-3xl">📊</span>
                <p class="text-2xl font-bold text-pink-500 mt-2">{{ Auth::user()->periods()->count() }}</p>
                <p class="text-sm text-her-text-light">Periods Logged</p>
            </div>
        </div>

        {{-- Period History --}}
        <div class="card-cute animate-slide-up" style="animation-delay: 0.1s">
            <h2 class="text-xl font-bold text-her-text flex items-center gap-2 mb-4">
                📅 Period History
            </h2>

            {{-- Add New Period --}}
            <form method="POST" action="{{ route('periods.store') }}" class="flex flex-col sm:flex-row gap-3 mb-6 p-4 bg-pink-50/50 rounded-xl">
                @csrf
                <input type="date" name="start_date" value="{{ now()->format('Y-m-d') }}"
                       max="{{ now()->format('Y-m-d') }}"
                       class="input-cute flex-1">
                <button type="submit" class="btn-pink whitespace-nowrap">
                    Add Period 🌸
                </button>
            </form>

            {{-- Period List --}}
            @php $periods = Auth::user()->periods()->get(); @endphp
            @if ($periods->isNotEmpty())
                <div class="space-y-2">
                    @foreach ($periods as $index => $period)
                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-pink-50 transition-colors group">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center text-sm text-pink-500 font-semibold">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <p class="font-medium text-her-text">{{ $period->start_date->format('d F Y') }}</p>
                                    <p class="text-xs text-her-text-light">{{ $period->start_date->diffForHumans() }}</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('periods.destroy', $period) }}" class="opacity-0 group-hover:opacity-100 transition-opacity">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-her-text-light hover:text-red-400 transition-colors text-sm"
                                        onclick="return confirm('Remove this period entry?')">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-her-text-light text-sm text-center py-4">No periods logged yet. Start tracking! 🌸</p>
            @endif
        </div>

        {{-- Update Profile --}}
        <div class="card-cute animate-slide-up" style="animation-delay: 0.2s">
            <h2 class="text-xl font-bold text-her-text flex items-center gap-2 mb-4">
                ✏️ Update Profile
            </h2>
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Update Password --}}
        <div class="card-cute animate-slide-up" style="animation-delay: 0.3s">
            <h2 class="text-xl font-bold text-her-text flex items-center gap-2 mb-4">
                🔒 Change Password
            </h2>
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Delete Account --}}
        <div class="card-cute border border-red-100 animate-slide-up" style="animation-delay: 0.4s">
            <h2 class="text-xl font-bold text-red-400 flex items-center gap-2 mb-4">
                ⚠️ Danger Zone
            </h2>
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
