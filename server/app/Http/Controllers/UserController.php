<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

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

    // Get new users within a specific timeframe
    public function getNewUsers(Request $request)
    {
        $days = $request->query('days', 30); // Default to the last 30 days if not provided

        // Get users created within the specified number of days
        $newUsersCount = User::where('created_at', '>=', now()->subDays($days))->count();

        return response()->json(['new_users_count' => $newUsersCount], 200);
    }

    // Get percentage change of new users within a specific timeframe
    public function getPercentageChangeOfNewUsers(Request $request)
    {
        $days = $request->query('days', 30); // Default to the last 30 days if not provided

        try {
            // Calculate new users for the current period
            $currentPeriodNewUsers = User::where('created_at', '>=', now()->subDays($days))->count();

            // Calculate new users for the previous period
            $previousPeriodNewUsers = User::whereBetween('created_at', [
                now()->subDays($days * 2), // Start of the previous period
                now()->subDays($days),    // End of the previous period
            ])->count();

            // Calculate percentage change
            if ($previousPeriodNewUsers == 0) {
                // If there were no users in the previous period, avoid division by zero
                $percentageChange = $currentPeriodNewUsers > 0 ? 100 : 0;
            } else {
                $percentageChange = (($currentPeriodNewUsers - $previousPeriodNewUsers) / $previousPeriodNewUsers) * 100;
            }

            return response()->json([
                'current_period_new_users' => $currentPeriodNewUsers,
                'previous_period_new_users' => $previousPeriodNewUsers,
                'percentage_change' => round($percentageChange, 2),
            ], 200);
        } catch (Exception $e) {
            // Log the error for debugging
            Log::error('Error calculating percentage change of new users: ' . $e->getMessage());

            return response()->json(['message' => 'An error occurred while calculating percentage change'], 500);
        }
    }
    
}
