<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/stripe/account-session', function(Request $request) {
    // Get stripe_account_id from the request
    // $stripe_account_id = $request->stripe_account_id;

    $stripe = new \Stripe\StripeClient([
        // This is a placeholder - it should be replaced with your secret API key.
        // Sign in to see your own test API key embedded in code samples.
        // Don’t submit any personally identifiable information in requests made with this key.
        "api_key" => config('services.stripe.secret'),
    ]);

    try {
        $account_session = $stripe->accountSessions->create([
            'account' => "acct_1Q4JyZCYLYCd9GMg", // Replace with post body
            'components' => [
                'payouts' => [
                    'enabled' => true,
                ],
//                'account_management' => [
//                  'enabled' => true,
//                  'features' => ['external_account_collection' => true],
//                ]
//                'notification_banner' => [
//                    'enabled' => true,
//                    'features' => ['external_account_collection' => true],
//                ],
//                'payments' => [
//                    'enabled' => true,
//                    'features' => [
//                        'refund_management' => true,
//                        'dispute_management' => true,
//                        'capture_payments' => true,
//                        'destination_on_behalf_of_charge_management' => false,
//                    ],
//                ],
            ]
        ]);

        return response()->json([
            'client_secret' => $account_session->client_secret
        ]);
    } catch (Exception $e) {
        error_log("An error occurred when calling the Stripe API to create an account session: {$e->getMessage()}");

        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
});


