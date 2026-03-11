<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use UnitEnum;
use BackedEnum;

class Settings extends Page
{
    protected string $view = 'filament.pages.settings';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static UnitEnum|string|null $navigationGroup = 'Reports & Settings';
    protected static ?int $navigationSort = 4;
    protected static ?string $title = 'Settings';

    public string $appName = '';
    public string $appTagline = '';
    public bool $maintenanceMode = false;

    public function mount(): void
    {
        $this->appName        = config('app.name');
        $this->appTagline     = Cache::get('app_tagline', 'Your trusted loan partner');
        $this->maintenanceMode = app()->isDownForMaintenance();
    }

    public function saveBranding(): void
    {
        Cache::put('app_name', $this->appName);
        Cache::put('app_tagline', $this->appTagline);

        Notification::make()
            ->title('Branding saved successfully!')
            ->success()
            ->send();
    }

    public function toggleMaintenance(): void
    {
        if (app()->isDownForMaintenance()) {
            Artisan::call('up');
            Notification::make()
                ->title('Maintenance mode OFF — App is live!')
                ->success()
                ->send();
        } else {
            Artisan::call('down', ['--secret' => 'admin-bypass']);
            Notification::make()
                ->title('Maintenance mode ON — App is down for users!')
                ->warning()
                ->send();
        }

        $this->maintenanceMode = app()->isDownForMaintenance();
    }
}