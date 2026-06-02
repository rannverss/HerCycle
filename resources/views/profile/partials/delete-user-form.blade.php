<section>
    <p class="text-sm text-her-text-light mb-4">
        Once your account is deleted, all of its data will be permanently deleted.
    </p>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center px-4 py-2 border border-red-300 text-red-500 rounded-full hover:bg-red-50 transition-all duration-200 text-sm font-medium">
        Delete Account ⚠️
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-her-text mb-2">
                Are you sure you want to delete your account?
            </h2>

            <p class="text-sm text-her-text-light mb-4">
                Once deleted, all your data including moods, diaries, and period history will be gone forever.
            </p>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-her-text mb-1">Enter your password to confirm:</label>
                <input id="password" name="password" type="password" class="input-cute" placeholder="Password">
                @error('password', 'userDeletion')
                    <p class="mt-1 text-sm text-pink-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                        class="btn-pink-outline">Cancel</button>
                <button type="submit"
                        class="inline-flex items-center px-6 py-2.5 bg-red-500 text-white font-medium rounded-full hover:bg-red-600 transition-all duration-200">
                    Delete Account
                </button>
            </div>
        </form>
    </x-modal>
</section>
