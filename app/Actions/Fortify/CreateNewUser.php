<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        // Configure stripe
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        // Create the stripe account
        $account = \Stripe\Account::create([
            'type' => 'express',
            'country' => 'GB',
            'email' => $input['email'],
            'capabilities' => [
                // Not sure what this does?
                // 'card_payments' => ['requested' => true],
                'transfers' => ['requested' => true],
            ],
        ]);

        Log::info('Stripe account created', ['account' => $account]);

        $user->stripe_account_id = $account->id;
        $user->save();

        return $user;
    }
}
