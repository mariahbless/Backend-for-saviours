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

            // 🔥 Branding
            ->brandName('Saviours Admin')
            ->brandLogo(asset('images/onboard1.png'))
            ->brandLogoHeight('6rem')

            // 🎨 Theme Color
            ->colors([
                'primary' => Color::hex('#1E40AF'),
            ])


            ->renderHook(
    'panels::head.end',
    fn () => '
        <style>
            .fi-logo img {
                height: 6rem !important;  /* 🔼 change height here */
                width: 15rem !important;  /* 🔼 change width here */
                object-fit: contain;      /* keeps aspect ratio */
            }
        </style>
    '
)

         ->renderHook(
    'panels::head.end',
    fn () => '
        <style>
            /* 🎨 Sidebar background */
            .fi-sidebar {
                background-color: #1E40AF !important;
            }
            .fi-sidebar-header {
                background-color: #1E40AF !important;
            }

            /* ⚪ Group labels like "Loan Management" */
            .fi-sidebar-group-label {
                color: #ffffff !important;
            }

            /* ⚪ Nav item links and text */
            .fi-sidebar-item-label {
                color: #ffffff !important;
            }

            /* ⚪ Nav icons */
            .fi-sidebar-item-icon {
                color: #ffffff !important;
            }

            /* ✅ Active item highlight */
            .fi-sidebar-item-button.fi-active {
                background-color: rgba(255,255,255,0.2) !important;
            }

            /* 🖱️ Hover effect */
            .fi-sidebar-item-button:hover {
                background-color: #bac5e9 !important;
            }
        </style>
    '
)

            // 💅 Fix brand name visibility
            ->renderHook(
    'panels::head.end',
    fn () => '<style>.fi-brand-name, .fi-logo span { color: white !important; }</style>'
)

            // 📦 Auto Discover
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

            // 📊 Dashboard
            ->pages([
                Dashboard::class,
            ])

            // 👤 Widgets
            ->widgets([
                AccountWidget::class,
            ])

            // 🔐 Middleware
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