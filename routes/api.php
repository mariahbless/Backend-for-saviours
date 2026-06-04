<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\LoanController;


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


// =====================
// LOAN API ROUTES
// =====================

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/loans', [LoanController::class, 'index']);
    Route::get('/loans/{id}', [LoanController::class, 'show']);
    Route::post('/loans', [LoanController::class, 'store']);
    Route::put('/loans/{id}', [LoanController::class, 'update']);
    Route::delete('/loans/{id}', [LoanController::class, 'destroy']);
});


Route::middleware('auth:sanctum')->group(function () {
 
    // NEW: Logout
    Route::post('/logout', [AuthController::class, 'logout']);
 
    // Loan routes
    Route::get('/loans',          [LoanController::class, 'index']);    // NEW: get user's loans
    Route::post('/loans',         [LoanController::class, 'store']);    // already exists
    Route::get('/loans/{id}',     [LoanController::class, 'show']);     // already exists
    Route::put('/loans/{id}',     [LoanController::class, 'update']);   // already exists (now with pending check)
    Route::delete('/loans/{id}',  [LoanController::class, 'destroy']); // already exists
 
    // NEW: Repayment routes
    Route::post('/loans/{id}/repay',       [LoanController::class, 'repay']);
    Route::get('/loans/{id}/repayments',   [LoanController::class, 'repayments']);
});
 
use Illuminate\Support\Facades\Artisan;

Route::get('/system/deploy', function (Request $request) {
    if ($request->query('token') !== env('APP_DEPLOY_TOKEN', 'deploy_secret_123')) {
        abort(403, 'Unauthorized action.');
    }

    try {
        $output = "=== Fixing Permissions ===\n";
        $basePath = base_path();
        
        // Fix directory permissions
        $dirs = ['public', 'storage', 'bootstrap/cache'];
        foreach ($dirs as $dir) {
            $path = $basePath . '/' . $dir;
            if (is_dir($path)) {
                chmod($path, 0755);
                $output .= "Fixed: $dir -> 0755\n";
            }
        }
        
        // Fix storage subdirectories recursively
        $storageIterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($basePath . '/storage', \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($storageIterator as $item) {
            if ($item->isDir()) {
                chmod($item->getPathname(), 0755);
            }
        }
        $output .= "Fixed: storage/** directories -> 0755\n";

        // Create storage link if it doesn't exist
        $output .= "\n=== Storage Link ===\n";
        if (!file_exists(public_path('storage'))) {
            Artisan::call('storage:link');
            $output .= Artisan::output();
        } else {
            $output .= "Storage link already exists\n";
        }

        // Run migrations and seeders
        $output .= "\n=== Running Migrations & Seeders ===\n";
        Artisan::call('migrate', ['--force' => true, '--seed' => true]);
        $output .= Artisan::output();
        
        // Clear and optimize
        $output .= "\n=== Optimizing ===\n";
        Artisan::call('optimize:clear');
        $output .= Artisan::output();
        
        if (function_exists('opcache_reset')) {
            opcache_reset();
            $output .= "\nOPcache reset successfully.\n";
        }
        
        return response("Deployment successful!\n\n" . $output, 200)
            ->header('Content-Type', 'text/plain');
    } catch (\Exception $e) {
        return response("Deployment failed: " . $e->getMessage() . "\n" . $e->getTraceAsString(), 500)
            ->header('Content-Type', 'text/plain');
    }
});

Route::get('/debug-db', function () {
    try {
        $dbName = \Illuminate\Support\Facades\DB::connection()->getDatabaseName();
        $userCount = \App\Models\User::count();
        $loanCount = \App\Models\Loan::count();
        $users = \App\Models\User::select('id', 'name', 'email', 'created_at')->latest()->take(5)->get();
        $loans = \App\Models\Loan::select('id', 'user_id', 'name', 'amount', 'created_at')->latest()->take(5)->get();
        
        return response()->json([
            'success' => true,
            'default_connection' => config('database.default'),
            'database_name' => $dbName,
            'mysql_host' => config('database.connections.mysql.host'),
            'mysql_database' => config('database.connections.mysql.database'),
            'user_count' => $userCount,
            'loan_count' => $loanCount,
            'recent_users' => $users,
            'recent_loans' => $loans,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
});