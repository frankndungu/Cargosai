<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    public function calculateRates(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'country' => 'required|string',
            'postal_code' => 'required|string',
            'weight' => 'nullable|numeric|min:0', // Optional weight parameter
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Retrieve request data
        $country = $request->country;
        $postalCode = $request->postal_code;
        $weight = $request->weight ?? 1; // Default to 1kg if weight is not provided

        // Calculate base rate based on destination
        $baseRate = $this->calculateBaseRate($country, $postalCode);

        // Generate shipping options (express and economy) with mock rates and times
        $shippingOptions = [
            [
                'service' => 'Express Shipping',
                'price' => $baseRate * 1.2, // Express rate with markup
                'estimatedDelivery' => '2-4 Business Days'
            ],
            [
                'service' => 'Economy Shipping',
                'price' => $baseRate * 0.8, // Economy rate with discount
                'estimatedDelivery' => '5-7 Business Days'
            ],
        ];

        return response()->json(['shipping_rates' => $shippingOptions], 200);
    }

    private function calculateBaseRate($country, $postalCode)
    {
        // Base rates based on country; customize these as needed
        $baseRates = [
            'United States' => 15.00,
            'Canada' => 20.00,
            'United Kingdom' => 25.00,
            'Australia' => 30.00,
        ];

        $baseRate = $baseRates[$country] ?? 40.00; // Default rate if country is not in list

        // Add slight variation based on postal code to simulate distance factor
        $postalCodeFactor = $postalCode ? (int)substr($postalCode, -2) / 100 : 0;

        return $baseRate + ($postalCodeFactor * 5); // Adjust based on distance factor
    }
}
