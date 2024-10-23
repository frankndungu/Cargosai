<?php

namespace App\Http\Controllers;

use App\Models\BillingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingAddressController extends Controller
{
    // Fetch all billing addresses for a specific user
    public function index($userId)
    {
        $addresses = BillingAddress::where('user_id', $userId)->get();
        return response()->json($addresses, 200);
    }

    // Store a new billing address
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'address1' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
        ]);

        $billingAddress = BillingAddress::create([
            'user_id' => $request->user_id,
            'address1' => $request->address1,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
        ]);

        return response()->json($billingAddress, 201);
    }

    // Update an existing billing address
    public function update(Request $request, $id)
    {
        $request->validate([
            'address1' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:255',
            'state' => 'sometimes|required|string|max:255',
            'country' => 'sometimes|required|string|max:255',
            'postal_code' => 'sometimes|required|string|max:20',
        ]);

        $billingAddress = BillingAddress::findOrFail($id);

        if ($billingAddress->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $billingAddress->update($request->only('address1', 'city', 'state', 'country', 'postal_code'));

        return response()->json($billingAddress, 200);
    }

    // Get a specific billing address by ID
    public function show($id)
    {
        $billingAddress = BillingAddress::findOrFail($id);

        if ($billingAddress->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($billingAddress, 200);
    }
}
