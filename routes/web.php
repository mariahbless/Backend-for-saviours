<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
})->name('home');

Route::get('/debug-database-users', function () {
    try {
        $users = \App\Models\User::all(['id', 'name', 'email', 'created_at'])->toArray();

        return response()->json([
            'database_name' => DB::connection()->getDatabaseName(),
            'users_count' => count($users),
            'users_in_db' => $users,
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::get('dashboard/activity-logs', function () {
        $logs = \App\Models\ActivityLog::latest()->paginate(15);

        return view('pages.activity-logs', compact('logs'));
    })->name('dashboard.activity-logs');

    Route::get('dashboard/roles-permissions', function () {
        // Only allow admin to view this page
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        $roles = \Spatie\Permission\Models\Role::with('permissions')->get();
        $users = \App\Models\User::with('roles')->get();

        return view('pages.roles-permissions', compact('roles', 'users'));
    })->name('dashboard.roles-permissions');

    Route::get('dashboard/guarantors', function () {
        $guarantors = \App\Models\Guarantor::with('loan')->latest()->paginate(10);

        return view('pages.guarantors', compact('guarantors'));
    })->name('dashboard.guarantors');
});

require __DIR__.'/settings.php';
