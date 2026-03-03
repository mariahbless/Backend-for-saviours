<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

// =====================
// REGISTER
// =====================
Route::post('/register', function (Request $request) {

    $validated = validator($request->all(), [
        'first_name' => 'required|string|max:255',
        'last_name'  => 'required|string|max:255',
        'email'      => 'required|email|unique:users,email',
        'password'   => 'required|string|confirmed|min:6',
        'phone'      => 'required|string',
        'location'   => 'required|string',
    ])->validate();

    $user = User::create([
        'name'     => $validated['first_name'].' '.$validated['last_name'],
        'email'    => $validated['email'],
        'password' => Hash::make($validated['password']),
        'phone'    => $validated['phone'],
        'location' => $validated['location'],
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'User registered successfully',
        'token'   => $token,
        'user'    => $user,
    ], 201);
});

// =====================
// LOGIN
// =====================
Route::post('/login', function (Request $request) {

    $validated = validator($request->all(), [
        'email'    => 'required|email',
        'password' => 'required|string',
    ])->validate();

    if (!Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password',
        ], 401);
    }

    $user  = Auth::user();
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Login successful',
        'token'   => $token,
        'user'    => $user,
    ], 200);
});


// =====================
// FORGOT PASSWORD
// =====================
Route::post('/forgot-password', function (Request $request) {

    $request->validate([
        'email' => 'required|email',
    ]);

    // Check if email exists in database
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'No account found with this email address',
        ], 404);
    }

    // Send reset link to email
    $status = Password::sendResetLink(
        $request->only('email')
    );

    if ($status === Password::RESET_LINK_SENT) {
        return response()->json([
            'success' => true,
            'message' => 'Password reset link sent to your email',
        ], 200);
    }

    return response()->json([
        'success' => false,
        'message' => 'Failed to send reset link. Please try again.',
    ], 500);
});


// =====================
// RESET PASSWORD
// =====================
Route::post('/reset-password', function (Request $request) {

    $request->validate([
        'token'    => 'required',
        'email'    => 'required|email',
        'password' => 'required|string|min:6|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->save();
        }
    );

    if ($status === Password::PASSWORD_RESET) {
        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully. You can now login.',
        ], 200);
    }

    return response()->json([
        'success' => false,
        'message' => 'Invalid or expired reset token',
    ], 400);
});



// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;
// use App\Models\User;
// use Illuminate\Support\Facades\Hash;

// Route::post('/register', function (Request $request) {

//     $validated = validator($request->all(), [
//         'first_name' => 'required|string|max:255',
//         'last_name' => 'required|string|max:255',
//         'email' => 'required|email|unique:users,email',
//         'password' => 'required|string|confirmed|min:6',
//         'phone' => 'required|string',
//         'location' => 'required|string',
//     ])->validate();

//     $user = User::create([
//         'name' => $validated['first_name'].' '.$validated['last_name'],
//         'email' => $validated['email'],
//         'password' => Hash::make($validated['password']),
//         'phone' => $validated['phone'],
//         'location' => $validated['location'],
//     ]);

//     $token = $user->createToken('auth_token')->plainTextToken;

//     return response()->json([
//         'message' => 'User registered successfully',
//         'token' => $token,
//         'user' => $user,
//     ], 201);
// });