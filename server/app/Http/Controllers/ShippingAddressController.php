<?php

namespace App\Http\Controllers;

use App\Models\ShippingAddress;
use App\Models\User;
use Illuminate\Http\Request;

class ShippingAddressController extends Controller
{
    // Get all shipping addresses for a specific user
    public function index($userId)
    {
        $addresses = ShippingAddress::where('user_id', $userId)->get(); // Fetch addresses by user ID
        
        return response()->json($addresses, 200);
    }

    // Get a specific shipping address by ID
    public function show($id)
    {
        $address = ShippingAddress::find($id);
        
        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }

        return response()->json($address, 200);
    }

    // Store a new shipping address
    public function store(Request $request)
    {
        // Validate incoming data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'address1' => 'required|string|max:255',
            'country' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
        ]);

        $address = ShippingAddress::create($validated); // Create new shipping address

        return response()->json($address, 201); // Return created address
    }

    // Update an existing shipping address
    public function update(Request $request, $id)
    {
        $address = ShippingAddress::find($id);
        
        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }

        // Validate incoming data
        $validated = $request->validate([
            'address1' => 'required|string|max:255',
            'country' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
        ]);

        $address->update($validated); // Update shipping address

        return response()->json($address, 200); // Return updated address
    }
    
    // Additional methods for delete can be added similarly
}