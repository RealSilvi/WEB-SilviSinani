<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Date;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param array<string, string> $input
     * @return User
     * @throws Throwable
     */
    public function create(array $input): User
    {
        $this->validateInput($input);

        return DB::transaction(function () use ($input) {

            $user = User::create([
                'first_name' => $input['first_name'],
                'last_name' => Arr::get($input, 'last_name'),
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'date_of_birth' => Date::make($input['date_of_birth']),
            ]);
            return $user;
        });
    }

    protected function validateInput(array $input): void
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string'],
            'date_of_birth' => ['nullable', 'date'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ];

        Validator::make($input, $rules)->validate();
    }

}
