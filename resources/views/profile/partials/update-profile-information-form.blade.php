<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    {{-- Avatar Preview --}}
    <div class="mt-4">
        <h3 class="mb-2 text-sm font-semibold text-gray-700">Avatar</h3>

        @if ($user->avatar)
            <img id="avatar-preview" src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar Preview"
                class="object-cover w-24 h-24 border border-gray-300 rounded-full">
        @else
            {{-- Generate avatar inisial via laravolt --}}
            <img id="avatar-preview" src="{{ \Laravolt\Avatar\Facade::create($user->name)->toBase64() }}"
                alt="Avatar Preview" class="object-cover w-24 h-24 border border-gray-300 rounded-full">
        @endif
    </div>


    {{-- Upload Form --}}
    <form method="POST" action="{{ route('profile.update.avatar') }}" enctype="multipart/form-data" class="mt-4">
        @csrf
        @method('PATCH')

        <input type="file" id="avatar-input" name="avatar" accept="image/*" required
            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">

        <x-input-error :messages="$errors->get('avatar')" class="mt-2" />

        <x-primary-button class="mt-2">
            {{ __('Upload Avatar') }}
        </x-primary-button>

        @if (session('status') === 'avatar-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                class="mt-2 text-sm text-green-600">{{ __('Avatar updated.') }}</p>
        @endif
    </form>

    {{-- Form update nama dan email --}}
    <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('PATCH')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="block w-full mt-1" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="block w-full mt-1" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="mt-2 text-sm text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>


</section>

@push('scripts')
    <script>
        document.getElementById('avatar-input').addEventListener('change', function(event) {
            const preview = document.getElementById('avatar-preview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
