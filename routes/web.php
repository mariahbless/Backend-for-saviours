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
        $output = "";
        $basePath = base_path();
        $zipFile = $basePath . '/release.zip';

        if (file_exists($zipFile)) {
            $output .= "=== Extracting release.zip ===\n";
            $zip = new ZipArchive;
            if ($zip->open($zipFile) === TRUE) {
                $zip->extractTo($basePath);
                $zip->close();
                unlink($zipFile);
                $output .= "Successfully extracted and deleted release.zip\n\n";
            } else {
                $output .= "Failed to open release.zip\n\n";
            }
        }

        $output .= "=== Fixing Permissions ===\n";
        
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
        
        return response("Deployment successful!\n\n" . $output, 200)
            ->header('Content-Type', 'text/plain');
    } catch (\Exception $e) {
        return response("Deployment failed: " . $e->getMessage() . "\n" . $e->getTraceAsString(), 500)
            ->header('Content-Type', 'text/plain');
    }
});
