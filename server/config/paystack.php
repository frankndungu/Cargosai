<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Paystack Public Key
    |--------------------------------------------------------------------------
    |
    | The public key is used for making payment requests from the frontend.
    | Ensure you use the appropriate key for your environment: test or live.
    |
    */
    'public_key' => env('PAYSTACK_PUBLIC_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Paystack Secret Key
    |--------------------------------------------------------------------------
    |
    | The secret key is used for making API calls from your backend to Paystack.
    | Keep this key secure and do not expose it in your frontend code.
    |
    */
    'secret_key' => env('PAYSTACK_SECRET_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Paystack Payment URL
    |--------------------------------------------------------------------------
    |
    | The base URL for making requests to Paystack's API. By default, this is
    | set to "https://api.paystack.co". You can override it in your .env file
    | if needed.
    |
    */
    'payment_url' => env('PAYSTACK_PAYMENT_URL', 'https://api.paystack.co'),

    /*
    |--------------------------------------------------------------------------
    | Paystack Webhook Secret (Optional)
    |--------------------------------------------------------------------------
    |
    | This is the secret key used to verify the integrity of webhook requests.
    | Set this value in your .env file to enable secure webhook handling.
    |
    */
    'webhook_secret' => env('PAYSTACK_WEBHOOK_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    |
    | This is the currency you use for transactions on Paystack.
    | The default is "NGN" for Nigerian Naira, but you can change it as needed.
    |
    */
    'currency' => env('PAYSTACK_CURRENCY', 'USD'),

];
