<div>
    <x-form-section class="d-none" submit="updateProfileInformation">

        <x-slot name="title">
            {{ __('Profile Information') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Update your account\'s profile information.') }}
        </x-slot>

        <x-slot name="form">
            <div class="grid grid-cols-1 col-span-full">
                <div class="grid grid-cols-2  w-full [&>*]:p-4">
                    <!-- Profile Photo -->
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                            <!-- Profile Photo File Input -->
                            <input type="file" class="hidden"
                                   wire:model="photo"
                                   x-ref="photo"
                                   x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            "/>

                            <x-label for="photo" value="{{ __('Photo') }}"/>

                            <!-- Current Profile Photo -->
                            <div class="mt-2" x-show="! photoPreview">
                                <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}"
                                     class="rounded-full h-20 w-20 object-cover">
                            </div>

                            <!-- New Profile Photo Preview -->
                            <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                            </div>

                            <x-secondary-button class="mt-2 mr-2 text-xs" type="button"
                                                x-on:click.prevent="$refs.photo.click()">
                                {{ __('Select A New Photo') }}
                            </x-secondary-button>

                            @if ($this->user->profile_photo_path)
                                <x-secondary-button type="button" class="mt-2 text-xs" wire:click="deleteProfilePhoto">
                                    {{ __('Remove Photo') }}
                                </x-secondary-button>
                            @endif

                            <x-input-error for="photo" class="mt-2"/>
                        </div>
                    @endif
                </div>


                <div class="grid grid-cols-1 md:grid-cols-2  w-full [&>*]:p-4">
                    <!-- First name -->
                    <div>
                        <x-label for="first_name" value="{{ __('First name') }}"/>
                        <x-input id="first_name" type="text" class="mt-1 block w-full"
                                 wire:model.defer="state.first_name"
                                 autocomplete="first_name"/>
                        <x-input-error for="first_name" class="mt-2"/>
                    </div>

                    <!-- Last name -->
                    <div>
                        <x-label for="last_name" value="{{ __('Last name') }}"/>
                        <x-input id="last_name" type="text" class="mt-1 block w-full" wire:model.defer="state.last_name"
                                 autocomplete="last_name"/>
                        <x-input-error for="last_name" class="mt-2"/>
                    </div>

                    <!-- Name -->
                    <div>
                        <x-label for="name" value="{{ __('Username') }}"/>
                        <x-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="state.name"
                                 autocomplete="name"/>
                        <x-input-error for="name" class="mt-2"/>
                    </div>

                    <!-- Email -->
                    <div>
                        <x-label for="email" value="{{ __('Email') }}"/>
                        <x-input id="email" type="email" class="mt-1 block w-full" wire:model.defer="state.email"
                                 autocomplete="username"/>
                        <x-input-error for="email" class="mt-2"/>

                        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                            <p class="text-sm mt-2">
                                {{ __('Your email address is unverified.') }}

                                <button type="button"
                                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                        wire:click.prevent="sendEmailVerification">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>

                            @if ($this->verificationLinkSent)
                                <p v-show="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        @endif
                    </div>

                    <!-- Phone number -->
                    <div>
                        <x-label for="telephone" value="{{ __('Phone number') }}"/>
                        <x-input id="telephone" type="text" class="mt-1 block w-full" wire:model.defer="state.telephone"
                                 autocomplete="telephone"/>
                        <x-input-error for="telephone" class="mt-2"/>
                    </div>

                    <!-- Date of birth -->
                    <div>
                        <x-label for="date_of_birth" value="{{ __('Date of birth') }}"/>
                        <x-input id="date_of_birth" type="date" class="mt-1 block w-full"
                                 wire:model.defer="state.date_of_birth"
                                 autocomplete="date_of_birth"/>
                        <x-input-error for="date_of_birth" class="mt-2"/>
                    </div>

                    <!-- Bank account number -->
                    <div>
                        <x-label for="bank_account_number" value="{{ __('Bank account number') }}"/>
                        <x-input id="bank_account_number" type="text" class="mt-1 block w-full"
                                 wire:model.defer="state.bank_account_number"
                                 autocomplete="bank_account_number"/>
                        <x-input-error for="bank_account_number" class="mt-2"/>
                    </div>

                    <!-- street name and number -->
                    <div>
                        <x-label for="street_name_and_number" value="{{ __('Street name and number') }}"/>
                        <x-input id="street_name_and_number" type="text" class="mt-1 block w-full"
                                 wire:model.defer="state.street_name_and_number"
                                 autocomplete="street_name_and_number"/>
                        <x-input-error for="street_name_and_number" class="mt-2"/>
                    </div>

                    <!-- City -->
                    <div>
                        <x-label for="city" value="{{ __('City') }}"/>
                        <x-input id="city" type="text" class="mt-1 block w-full"
                                 wire:model.defer="state.city"
                                 autocomplete="city"/>
                        <x-input-error for="city" class="mt-2"/>
                    </div>

                    <!-- Postal code -->
                    <div>
                        <x-label for="postal_code" value="{{ __('Postal code') }}"/>
                        <x-input id="postal_code" type="text" class="mt-1 block w-full"
                                 wire:model.defer="state.postal_code"
                                 autocomplete="postal_code"/>
                        <x-input-error for="postal_code" class="mt-2"/>
                    </div>

                    <!-- Allergies -->
                    <div>
                        <x-label for="allergy" value="{{ __('Allergies') }}"/>
                        <x-tmk.form.textarea id="allergy" class="mt-1 block w-full h-20 p-2"
                                             wire:model.defer="state.allergy"></x-tmk.form.textarea>
                        <x-input-error for="allergy" class="mt-2"/>
                    </div>

                    <!-- Dislikes -->
                    <div>
                        <x-label for="dislike" value="{{ __('Dislikes (food)') }}"/>
                        <x-tmk.form.textarea id="dislike" class="mt-1 block w-full h-20 p-2"
                                             wire:model.defer="state.dislike"></x-tmk.form.textarea>
                        <x-input-error for="dislike" class="mt-2"/>
                    </div>

                </div>
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-action-message class="mr-3" on="saved">
                {{ __('Saved.') }}
            </x-action-message>

            <x-button wire:loading.attr="disabled" wire:target="photo">
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-form-section>
</div>
