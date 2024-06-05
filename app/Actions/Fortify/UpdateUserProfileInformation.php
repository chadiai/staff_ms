<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Auth;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Livewire\Livewire;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param array<string, string> $input
     */

//update the user's info
    public function update(User $user, array $input)
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'city' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:255'],
            'street_name_and_number' => ['nullable', 'string', 'max:255'],
            'dislike' => ['nullable', 'string'],
            'allergy' => ['nullable', 'string'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'telephone' => ['required', 'string', 'max:255','min:7'],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'name' => $input['name'],
                'email' => $input['email'],
                'telephone' => $input['telephone'],
                'date_of_birth' => $input['date_of_birth'],
                'city' => $input['city'],
                'postal_code' => $input['postal_code'],
                'dislike' => $input['dislike'],
                'allergy' => $input['allergy'],
                'street_name_and_number' => $input['street_name_and_number'],
                'bank_account_number' => $input['bank_account_number'],
            ])->save();
        }
    }


    /**
     * Update the given verified user's profile information.
     *
     * @param array<string, string> $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {

        $user->forceFill([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'name' => $input['name'],
            'email' => $input['email'],
            'telephone' => $input['telephone'],
            'date_of_birth' => $input['date_of_birth'],
            'street_name_and_number' => $input['street_name_and_number'],
            'city' => $input['city'],
            'postal_code' => $input['postal_code'],
            'bank_account_number' => $input['bank_account_number'],
            'email_verified_at' => null,
        ])->save();
        $user->sendEmailVerificationNotification();
    }
}
