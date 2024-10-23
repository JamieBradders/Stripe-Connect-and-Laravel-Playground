<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/stripe/onboarding', function () {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $accountLink = \Stripe\AccountLink::create([
            'account' => auth()->user()->stripe_account_id,
            'refresh_url' => route('stripe.onboarding'),
            'return_url' => route('dashboard'),
            'type' => 'account_onboarding',
        ]);

        return redirect($accountLink->url);
    })->name('stripe.onboarding');

    Route::post('/stripe/transfer', function () {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $transfer = \Stripe\Transfer::create([
            'amount' => 1000, // £10.00
            'currency' => 'GBP',
            'destination' => auth()->user()->stripe_account_id,
            'transfer_group' => 'BOOKING_123',
        ]);

        return redirect('/dashboard');
    })->name('stripe.transfer');

    Route::get('/stripe/dashboard', function () {
        // \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $res = $stripe->accounts->createLoginLink(auth()->user()->stripe_account_id, []);

        return redirect($res->url);
    })->name('stripe.dashboard');
});
