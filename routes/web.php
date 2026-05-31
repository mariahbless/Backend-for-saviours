<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';

use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;

Route::get('/system/deploy', function (Request $request) {
    if ($request->query('token') !== env('APP_DEPLOY_TOKEN', 'deploy_secret_123')) {
        abort(403, 'Unauthorized action.');
    }

    try {
        Artisan::call('migrate', ['--force' => true, '--seed' => true]);
        $output = Artisan::output();
        
        Artisan::call('optimize:clear');
        $output .= "\n" . Artisan::output();
        
        return response("Deployment successful!\n" . $output, 200)
            ->header('Content-Type', 'text/plain');
    } catch (\Exception $e) {
        return response("Deployment failed: " . $e->getMessage(), 500)
            ->header('Content-Type', 'text/plain');
    }
});
