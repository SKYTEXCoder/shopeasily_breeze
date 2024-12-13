<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Http\UploadedFile;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new #[Layout('layouts.guest')] class extends Component {
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $first_name = '';
    public string $last_name = '';
    public string $phone_number = '';
    public string $password = '';
    public string $password_confirmation = '';
    public ?UploadedFile $image = null;
    public bool $is_admin = false;

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255', 'unique:' . User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:15'],
            'image' => ['nullable', 'image', 'max:10240'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'is_admin' => ['boolean'],
        ]);

        if ($this->image) {
            $validated['image'] = $this->image->store('profile_pictures', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $authenticatedUserRole = Auth::user()->is_admin ? 'admin' : 'customer';

        if ($authenticatedUserRole === 'admin') {
            $this->redirect(url('admin'), navigate: false);
        } else {
            $this->redirect(route('index', absolute: true), navigate: true);
        }
    }
}; ?>

<div>
    <form wire:submit="register">

        <div>
            <x-input-label for="image" :value="__('Profile Picture')" />
            <input wire:model="image" id="image" name="image" type="file" class="mt-1 block w-full"
                accept="image/*" />
            <x-input-error class="mt-2" :messages="$errors->get('image')" />

            <!-- Preview uploaded image -->
            @if ($image)
                <div class="mt-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Image Preview:') }}</p>
                    <img src="{{ $image->temporaryUrl() }}" alt="Image Preview"
                        class="mt-2 w-24 h-24 object-cover rounded-full">
                </div>
            @endif
        </div>



        <!-- Name -->
        <div class="mt-4">
            <x-input-label for="name" :value="__('Username')" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name"
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- First Name -->
        <div class="mt-4">
            <x-input-label for="first_name" :value="__('First Name')" />
            <x-text-input wire:model="first_name" id="first_name" class="block mt-1 w-full" type="text"
                name="first_name" autofocus autocomplete="first_name" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <!-- Last Name -->
        <div class="mt-4">
            <x-input-label for="last_name" :value="__('Last Name')" />
            <x-text-input wire:model="last_name" id="last_name" class="block mt-1 w-full" type="text"
                name="last_name" autofocus autocomplete="last_name" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <!-- Phone Number -->
        <div class="mt-4">
            <x-input-label for="phone_number" :value="__('Phone Number')" />
            <x-text-input wire:model="phone_number" id="phone_number" class="block mt-1 w-full" type="text"
                name="phone_number" autofocus autocomplete="phone_number" />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="password" id="password" class="block mt-1 w-full" type="password" name="password"
                required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                type="password" name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>
