<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

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

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';


