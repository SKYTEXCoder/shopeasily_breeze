<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $street_address = '';
    public string $address_line_2 = '';
    public string $city_or_regency = '';
    public string $state = '';
    public string $zip_code = '';

    public function mount() {
        $shippingInformation = Auth::user()->shipping_information()->first();
        if ($shippingInformation) {
            $this->street_address = $shippingInformation->street_address;
            $this->address_line_2 = $shippingInformation->address_line_2;
            $this->city_or_regency = $shippingInformation->city_or_regency;
            $this->state = $shippingInformation->state;
            $this->zip_code = $shippingInformation->zip_code;
        }
    }

    /**
     * Update the shipping information for the currently authenticated customer.
     */
    public function updateCustomerShippingInformation(): void
    {
        $validatedData = $this->validate([
            'street_address' => ['required', 'string'],
            'address_line_2' => ['nullable', 'string'],
            'city_or_regency' => ['required', 'string'],
            'state' => ['required', 'string'],
            'zip_code' => ['required', 'string'],
        ]);

        $user = Auth::user();

        $user->shipping_information()->updateOrCreate(
            ['user_id' => $user->id],
            $validatedData
        );

        $this->dispatch('shipping-information-updated');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Your Shipping Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('You can either add or update your very own shipping information here.') }}
        </p>
    </header>

    <form wire:submit="updateCustomerShippingInformation" class="mt-6 space-y-6">
        <div>
            <x-input-label for="update_shipping_information_address_line1" :value="__('Address Line 1 / Street Address')" />
            <x-text-input wire:model="street_address" id="update_shipping_information_address_line1" name="street_address" type="text" class="mt-1 block w-full" autocomplete="address-line1" />
            <x-input-error :messages="$errors->get('street_address')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_shipping_information_address_line_2" :value="__('Address Line 2')" />
            <x-text-input wire:model="address_line_2" id="update_shipping_information_address_line_2" name="address_line_2" type="text" class="mt-1 block w-full" autocomplete="address-line2" />
            <x-input-error :messages="$errors->get('address_line_2')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_shipping_information_city_or_regency" :value="__('City / Regency')" />
            <x-text-input wire:model="city_or_regency" id="update_shipping_information_city_or_regency" name="city_or_regency" type="text" class="mt-1 block w-full" autocomplete="city" />
            <x-input-error :messages="$errors->get('city_or_regency')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_shipping_information_state" :value="__('State')" />
            <x-text-input wire:model="state" id="update_shipping_information_state" name="state" type="text" class="mt-1 block w-full" autocomplete="state" />
            <x-input-error :messages="$errors->get('state')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_shipping_information_zip_code" :value="__('ZIP Code')" />
            <x-text-input wire:model="zip_code" id="update_shipping_information_zipcode" name="zip_code" type="text" class="mt-1 block w-full" autocomplete="postal-code" />
            <x-input-error :messages="$errors->get('zip_code')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="shipping-information-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
