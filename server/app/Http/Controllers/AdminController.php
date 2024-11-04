<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Admin login method
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        // Generate a token for the admin
        $token = $user->createToken('admin-auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Admin logged in successfully',
            'token' => $token,
        ], 200);
    }

    // Method to list all users
    public function listUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Method to update a user's role
    public function updateUserRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|string',
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->role = $request->role;
        $user->save();

        return response()->json(['message' => 'User role updated successfully']);
    }

    // Method to delete a user
    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
