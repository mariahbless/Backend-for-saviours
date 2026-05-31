<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
//use Filament\Pages\Dashboard;
use App\Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()

            // Branding
            ->brandName('Saviours')
            ->brandLogo(asset('images/onboard1.png'))
            ->brandLogoHeight('6rem')

            // Theme Color
            ->colors([
                'primary' => Color::hex('#1E40AF'),
            ])
            ->darkMode(false)


            ->renderHook(
    'panels::head.end',
    fn () => '
        <style>
            .fi-logo img {
                height: 6rem !important;  /* change height here */
                width: 15rem !important;  /* change width here */
                object-fit: contain;      /* keeps aspect ratio */
            }
            .fi-simple-layout {
                background-image: url("/images/login-bg.png") !important;
                background-size: cover !important;
                background-position: center !important;
                background-attachment: fixed !important;
            }
            .fi-simple-main {
                background-color: rgba(255, 255, 255, 0.95) !important;
                border-radius: 1rem !important;
                padding: 2.5rem !important;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
                backdrop-filter: blur(10px) !important;
            }
        </style>
    '
)



            // Auto Discover
            ->discoverResources(
                in: app_path('Filament/Resources'),
                for: 'App\\Filament\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Pages'),
                for: 'App\\Filament\\Pages'
            )
            ->discoverWidgets(
                in: app_path('Filament/Widgets'),
                for: 'App\\Filament\\Widgets'
            )

            // Dashboard
            ->pages([
                Dashboard::class,
            ])

            // Widgets
            ->widgets([
                AccountWidget::class,
            ])

            // Middleware
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])

            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}