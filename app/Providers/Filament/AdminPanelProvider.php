<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->brandLogo(asset('assets/Logo.png'))
            ->brandLogoHeight('5rem')
            ->renderHook(
                'panels::styles.after',
                fn (): string => Blade::render('<link rel="stylesheet" href="{{ asset(\'css/custom-filament.css\') }}">'),
            )
            ->renderHook(
                PanelsRenderHook::CONTENT_START,
                fn (): string => Blade::render('
                    @if(request()->routeIs("filament.admin.pages.dashboard"))
                        @php
                            $hour = now()->timezone("Asia/Jakarta")->format("H");
                            if ($hour < 12) {
                                $greeting = "Good Morning,";
                            } elseif ($hour < 15) {
                                $greeting = "Good Afternoon,";
                            } elseif ($hour < 18) {
                                $greeting = "Good Afternoon,";
                            } else {
                                $greeting = "Good Evening,";
                            }
                        @endphp
                        <div class="custom-header-bg mb-8">
                            <div class="flex justify-between items-center w-full">
                                <div>
                                    <h2 class="text-3xl font-medium opacity-90 tracking-tight">{{ $greeting }}</h2>
                                    <h1 class="text-7xl font-black uppercase tracking-normal leading-tight mt-4">{{ auth()->user()->name }}</h1>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold opacity-80">{{ now()->timezone("Asia/Jakarta")->format("l, d F Y") }}</p>
                                    <p class="text-7xl font-black tracking-normal mt-2">{{ now()->timezone("Asia/Jakarta")->format("H:i") }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                '),
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
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
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
