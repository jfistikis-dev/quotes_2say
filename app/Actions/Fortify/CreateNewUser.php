<?php

namespace App\Actions\Fortify;

use App\Models\Device;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
        ]);

        // Check if there's a device UUID in session (from iOS app)
        if (session()->has('device_uuid')) {
            $deviceUuid = session()->get('device_uuid');
            
            // Create device record for this user
            Device::create([
                'user_id' => $user->id,
                'device_uuid' => $deviceUuid,
                'last_used_at' => now(),
            ]);
        }

        return $user;
    }
}
