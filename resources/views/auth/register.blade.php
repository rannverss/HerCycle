<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-her-text">Join HerCycle! 🌸</h1>
        <p class="text-her-text-light text-sm mt-1">Start tracking your wellness journey</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-her-text mb-1.5">💁 Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="input-cute" placeholder="Your lovely name">
            @error('name')
                <p class="mt-1 text-sm text-pink-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-her-text mb-1.5">📧 Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="input-cute" placeholder="your@email.com">
            @error('email')
                <p class="mt-1 text-sm text-pink-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-her-text mb-1.5">🔒 Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="input-cute" placeholder="••••••••">
            @error('password')
                <p class="mt-1 text-sm text-pink-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-her-text mb-1.5">🔒 Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="input-cute" placeholder="••••••••">
            @error('password_confirmation')
                <p class="mt-1 text-sm text-pink-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-pink w-full text-center">
            Create Account 💖
        </button>

        <!-- Login Link -->
        <p class="text-center text-sm text-her-text-light">
            Already have an account?
            <a href="{{ route('login') }}" class="text-pink-400 hover:text-pink-500 font-medium transition-colors">
                Sign In 🌸
            </a>
        </p>
    </form>
</x-guest-layout>
