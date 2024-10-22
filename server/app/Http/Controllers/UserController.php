<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Get all users
    public function index()
    {
        return User::all(); // Return all users
    }

    // Get a specific user by ID
    public function show($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user, 200); // Return the user data
    }

    // Update a specific user
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Validate incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phonenumber' => 'nullable|string|max:15|unique:users,phonenumber,' . $id, // Unique validation excluding the current user's ID
        ]);

        // Update user data
        $user->update($validated);

        return response()->json($user, 200); // Return updated user data
    }

    // Create a phone number for a user
    public function createPhoneNumber(Request $request, $id)
    {
        $request->validate([
            'phonenumber' => 'required|string|max:20|unique:users,phonenumber',
        ]);

        $user = User::find($id);
        if ($user) {
            $user->phonenumber = $request->phonenumber; // Set the phone number
            $user->save();
            return response()->json(['message' => 'Phone number created successfully!'], 201);
        } else {
            return response()->json(['message' => 'User not found!'], 404);
        }
    }

    // Update the phone number for a user
    public function updatePhoneNumber(Request $request, $id)
    {
        $request->validate([
            'phonenumber' => 'required|string|max:20|unique:users,phonenumber,' . $id, // Ensure phone number is unique but exclude the current user's ID
        ]);

        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->phonenumber = $request->phonenumber;
        $user->save();

        return response()->json(['message' => 'Phone number updated successfully.'], 200);
    }

    // Delete the phone number for a user
    public function deletePhoneNumber($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->phonenumber = null; // Remove the phone number
            $user->save();
            return response()->json(['message' => 'Phone number deleted successfully!'], 200);
        } else {
            return response()->json(['message' => 'User not found!'], 404);
        }
    }
}
