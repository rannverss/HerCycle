<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-her-text">Welcome Back! 💕</h1>
        <p class="text-her-text-light text-sm mt-1">Sign in to continue your journey</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 text-sm text-green-600 bg-green-50 rounded-xl p-3 text-center">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-her-text mb-1.5">📧 Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="input-cute" placeholder="your@email.com">
            @error('email')
                <p class="mt-1 text-sm text-pink-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-her-text mb-1.5">🔒 Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="input-cute" placeholder="••••••••">
            @error('password')
                <p class="mt-1 text-sm text-pink-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember"
                       class="rounded-lg border-pink-300 text-pink-400 focus:ring-pink-300">
                <span class="ml-2 text-sm text-her-text-light">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-pink-400 hover:text-pink-500 transition-colors">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-pink w-full text-center">
            Sign In 🌸
        </button>

        <!-- Register Link -->
        <p class="text-center text-sm text-her-text-light">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-pink-400 hover:text-pink-500 font-medium transition-colors">
                Sign Up 💖
            </a>
        </p>
    </form>
</x-guest-layout>
