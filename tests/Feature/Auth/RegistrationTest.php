<?php

use App\Models\User;

test('new users can register', function () {
    $response = $this->post('/auth/register', [
        'first_name' => 'Name',
        'last_name' => 'Surname',
        'date_of_birth' => '2001/03/12',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertSessionHasNoErrors();

    /** @var User $user */
    $user = User::query()->where('email', 'test@example.com')->first();

    expect($user)->not->toBeNull();
    expect($user->hasVerifiedEmail())->toBeFalse();
    expect(Hash::check('password', $user->password))->toBeTrue();

    expect($user)->toMatchArray([
        'first_name' => 'Name',
        'last_name' => 'Surname',
        'email' => 'test@example.com',
        'date_of_birth' => Date::create(2001,03,12)->toDateString(),
    ]);

});
