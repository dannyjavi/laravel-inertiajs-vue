<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            'password' => $this->passwordRules(),
        ])->validate();

        $admin = false;
        if ($input['name'] == 'admin') {
            $admin = true;
        }

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'isAdmin' => $admin,
            'password' => Hash::make($input['password']),
        ]);
    }
}
