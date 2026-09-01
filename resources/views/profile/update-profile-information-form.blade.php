<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" id="photo" class="hidden"
                            wire:model.live="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-label for="photo" value="{{ __('Photo') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full size-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full size-20 bg-cover bg-no-repeat bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </x-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-secondary-button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Name') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" required autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="text-sm mt-2">
                    {{ __('Your email address is unverified.') }}

                    <button type="button" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>

        <!-- Phone — editable for all roles -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="phone" value="{{ __('Phone') }}" />
            <x-input id="phone" type="tel" class="mt-1 block w-full" wire:model="state.phone" autocomplete="tel" placeholder="+1 555-0101" />
            <x-input-error for="phone" class="mt-2" />
        </div>

        @if($this->user->role === \App\Models\User::ROLE_COMPANY)
            {{-- ======================== Company-only section ======================== --}}
            <div class="col-span-6">
                <hr class="my-2 border-gray-200">
                <h3 class="text-base font-semibold text-gray-900 mt-4">{{ __('Company Details') }}</h3>
                <p class="text-sm text-gray-500 mt-1">{{ __('Help job seekers learn more about your company.') }}</p>
            </div>

            <!-- Website -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="website" value="{{ __('Website') }}" />
                <x-input id="website" type="url" class="mt-1 block w-full" wire:model="state.website" placeholder="https://yourcompany.com" />
                <x-input-error for="website" class="mt-2" />
            </div>

            <!-- Headline / Tagline -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="headline" value="{{ __('Tagline / Headline') }}" />
                <x-input id="headline" type="text" class="mt-1 block w-full" wire:model="state.headline" placeholder="One-line about your company" maxlength="191" />
                <x-input-error for="headline" class="mt-2" />
            </div>

            <!-- About / Bio -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="bio" value="{{ __('About the Company') }}" />
                <textarea id="bio" rows="4" wire:model="state.bio"
                          class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                          placeholder="What does your company do? What's it like to work there?"></textarea>
                <x-input-error for="bio" class="mt-2" />
            </div>

            <!-- Address -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="address" value="{{ __('Address') }}" />
                <x-input id="address" type="text" class="mt-1 block w-full" wire:model="state.address" placeholder="HQ city / full address" />
                <x-input-error for="address" class="mt-2" />
            </div>

            <!-- Company Size -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="company_size" value="{{ __('Company Size') }}" />
                <select id="company_size" wire:model="state.company_size"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                    <option value="">— {{ __('Select') }} —</option>
                    <option value="1-10">1–10 employees</option>
                    <option value="11-50">11–50 employees</option>
                    <option value="51-200">51–200 employees</option>
                    <option value="201-500">201–500 employees</option>
                    <option value="501-1000">501–1,000 employees</option>
                    <option value="1000+">1,000+ employees</option>
                </select>
                <x-input-error for="company_size" class="mt-2" />
            </div>
        @endif
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>
