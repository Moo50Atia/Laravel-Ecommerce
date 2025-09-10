<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Address Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Update your shipping or billing address.') }}
        </p>
    </header>

    <form method="POST"  action="{{ route('profile.updateAddress') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="address_line1" :value="__('Address Line 1')" />
                <x-text-input id="address_line1" name="address_line1" type="text" class="mt-1 block w-full"
                    value="{{ old('address_line1', $user->addresses->address_line1 ?? '') }}" />
                <x-input-error class="mt-2" :messages="$errors->get('address_line1')" />
            </div>

            <div>
                <x-input-label for="address_line2" :value="__('Address Line 2')" />
                <x-text-input id="address_line2" name="address_line2" type="text" class="mt-1 block w-full"
                    value="{{ old('address_line2', $user->addresses->address_line2 ?? '') }}" />
                <x-input-error class="mt-2" :messages="$errors->get('address_line2')" />
            </div>

            <div>
                <x-input-label for="city" :value="__('City')" />
                <x-text-input id="city" name="city" type="text" class="mt-1 block w-full"
                    value="{{ old('city', $user->addresses->city ?? '') }}" />
                <x-input-error class="mt-2" :messages="$errors->get('city')" />
            </div>

            <div>
                <x-input-label for="state" :value="__('State')" />
                <x-text-input id="state" name="state" type="text" class="mt-1 block w-full"
                    value="{{ old('state', $user->addresses->state ?? '') }}" />
                <x-input-error class="mt-2" :messages="$errors->get('state')" />
            </div>

            <div>
                <x-input-label for="postal_code" :value="__('Postal Code')" />
                <x-text-input id="postal_code" name="postal_code" type="text" class="mt-1 block w-full"
                    value="{{ old('postal_code', $user->addresses->postal_code ?? '') }}" />
                <x-input-error class="mt-2" :messages="$errors->get('postal_code')" />
            </div>

            <div>
                <x-input-label for="country" :value="__('Country')" />
                <x-text-input id="country" name="country" type="text" class="mt-1 block w-full"
                    value="{{ old('country', $user->addresses->country ?? '') }}" />
                <x-input-error class="mt-2" :messages="$errors->get('country')" />
            </div>
        </div>

        <div class="mt-4">
            <x-primary-button>{{ __('Save Address') }}</x-primary-button>
        </div>
    </form>
        @if(session('form_data'))
    <pre>{{ print_r(session('form_data'), true) }}</pre>
@endif
</section>
