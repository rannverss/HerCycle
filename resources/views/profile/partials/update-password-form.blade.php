<section>
    <p class="text-sm text-her-text-light mb-4">
        Ensure your account is using a strong password.
    </p>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-her-text mb-1">🔒 Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password" class="input-cute" autocomplete="current-password">
            @error('current_password', 'updatePassword')
                <p class="mt-1 text-sm text-pink-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-her-text mb-1">🔑 New Password</label>
            <input id="update_password_password" name="password" type="password" class="input-cute" autocomplete="new-password">
            @error('password', 'updatePassword')
                <p class="mt-1 text-sm text-pink-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-her-text mb-1">🔑 Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="input-cute" autocomplete="new-password">
            @error('password_confirmation', 'updatePassword')
                <p class="mt-1 text-sm text-pink-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-pink">Update Password 🔒</button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-green-600">Updated! ✨</p>
            @endif
        </div>
    </form>
</section>
