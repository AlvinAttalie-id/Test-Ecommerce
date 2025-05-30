<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <!-- Avatar Upload & Preview -->
        <div class="mt-4">
            <x-input-label for="avatar" :value="__('Avatar')" />
            <div class="flex items-center space-x-4 mt-2">
                <!-- Preview Avatar -->
                <img id="avatar-preview" src="{{ asset('img/logo/Default-Avatar.jpg') }}" alt="Preview Avatar"
                    class="w-16 h-16 rounded-full object-cover border border-gray-300 shadow-sm" />

                <!-- File Input -->
                <input id="avatar" name="avatar" type="file" accept="image/*"
                    class="block text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0 file:text-sm file:font-semibold
                        file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                    onchange="previewAvatar(event)">
            </div>
            <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
        </div>

        <!-- Name -->
        <div class="mt-4">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

@push('scripts')
    <script>
        function previewAvatar(event) {
            const input = event.target;
            const preview = document.getElementById('avatar-preview');

            if (input.files && input.files[0]) {
                const file = input.files[0];

                if (!file.type.startsWith('image/')) {
                    alert('File yang dipilih bukan gambar.');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    console.log("Preview berhasil dimuat.");
                };
                reader.onerror = function(err) {
                    console.error("Error memuat gambar:", err);
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endpush
