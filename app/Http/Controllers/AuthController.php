<?php

namespace App\Http\Controllers;

// ✅ use statements go HERE — at the top, before the class
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // ✅ your existing register method should already be here
    public function register(Request $request)
    {
        // ... your existing registration code stays here, don't touch it
    }

    // ✅ login method goes HERE — inside the class, after register
    public function login(Request $request)
    {
        // 1. Validate the incoming data
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // 2. Check if email exists and password is correct
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password',
            ], 401);
        }

        // 3. Get the authenticated user
        $user = Auth::user();

        // 4. Create a new token for this user
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Return success response with token and user info
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token'   => $token,
            'user'    => [
                'id'       => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'phone'    => $user->phone,
                'location' => $user->location,
            ],
        ], 200);
    }
}