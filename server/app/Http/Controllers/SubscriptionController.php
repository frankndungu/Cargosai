<?php

namespace App\Http\Controllers;

use App\Mail\SubscriptionMail;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        // Validate email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscriptions,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Store email
        $subscription = Subscription::create(['email' => $request->email]);

        // Send confirmation email
        Mail::to($subscription->email)->send(new SubscriptionMail($subscription->email));

        return response()->json(['message' => 'Thank you for subscribing! A confirmation email has been sent.'], 201);
    }
}
