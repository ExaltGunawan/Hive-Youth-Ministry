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
            ->userMenuItems([
                'profile' => \Filament\Navigation\MenuItem::make()
                    ->url(fn (): string => \App\Filament\Pages\MyProfile::getUrl())
                    ->label(fn() => auth()->user()->name),
            ])
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->brandLogo(asset('assets/Logoi.png'))
            ->brandLogoHeight('20rem')
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
                        <div class="custom-header-bg mb-8 p-8 md:p-12 rounded-[40px] relative overflow-hidden">
                            <div class="relative z-10 flex flex-col gap-8">
                                <div class="flex flex-col md:flex-row justify-between items-center md:items-end w-full gap-8 md:gap-0">
                                    <div class="text-center md:text-left">
                                        <h2 class="text-xl md:text-2xl font-medium opacity-70 tracking-tight">{{ $greeting }}</h2>
                                        <h1 class="text-4xl md:text-6xl font-black uppercase tracking-tighter leading-none mt-2">{{ auth()->user()->name }}</h1>
                                    </div>
                                    <div class="text-center md:text-right mt-4 md:mt-0" 
                                         x-data="{ 
                                            time: \'\',
                                            updateTime() {
                                                const now = new Date();
                                                this.time = now.toLocaleTimeString(\'en-GB\', { 
                                                    hour: \'2-digit\', 
                                                    minute: \'2-digit\',
                                                    timeZone: \'Asia/Jakarta\'
                                                });
                                            }
                                         }" 
                                         x-init="updateTime(); setInterval(() => updateTime(), 1000)">
                                        <p class="text-sm md:text-lg font-bold opacity-60 tracking-widest uppercase">{{ now()->timezone("Asia/Jakarta")->format("l, d F Y") }}</p>
                                        <p class="text-3xl md:text-5xl font-black tracking-tighter mt-1" x-text="time"></p>
                                    </div>
                                </div>

                                {{-- Integrated Quick Actions --}}
                                <div class="flex flex-wrap gap-4 justify-center md:justify-start mt-4">
                                    <a href="/admin/schedules/create" class="header-shortcut-card">
                                        <div class="shortcut-icon-bg bg-emerald-500/20 text-emerald-400">
                                            <x-heroicon-m-calendar-days class="w-5 h-5"/>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="shortcut-label">Schedule</span>
                                            <span class="shortcut-desc">Add New</span>
                                        </div>
                                    </a>
                                    <a href="/admin/notes/create" class="header-shortcut-card">
                                        <div class="shortcut-icon-bg bg-blue-500/20 text-blue-400">
                                            <x-heroicon-m-document-text class="w-5 h-5"/>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="shortcut-label">Notulensi</span>
                                            <span class="shortcut-desc">Create Note</span>
                                        </div>
                                    </a>
                                    <a href="/admin/worship-titles/create" class="header-shortcut-card">
                                        <div class="shortcut-icon-bg bg-amber-500/20 text-amber-400">
                                            <x-heroicon-m-book-open class="w-5 h-5"/>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="shortcut-label">Judul</span>
                                            <span class="shortcut-desc">Set Title</span>
                                        </div>
                                    </a>
                                    <a href="/admin/worship-themes/create" class="header-shortcut-card">
                                        <div class="shortcut-icon-bg bg-rose-500/20 text-rose-400">
                                            <x-heroicon-m-swatch class="w-5 h-5"/>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="shortcut-label">Tema</span>
                                            <span class="shortcut-desc">Monthly</span>
                                        </div>
                                    </a>
                                    <a href="/admin/service-schedules/create" class="header-shortcut-card">
                                        <div class="shortcut-icon-bg bg-purple-500/20 text-purple-400">
                                            <x-heroicon-m-user-group class="w-5 h-5"/>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="shortcut-label">Petugas</span>
                                            <span class="shortcut-desc">Assignment</span>
                                        </div>
                                    </a>
                                    <a href="/admin/withdrawal-requests/create" class="header-shortcut-card">
                                        <div class="shortcut-icon-bg bg-cyan-500/20 text-cyan-400">
                                            <x-heroicon-m-banknotes class="w-5 h-5"/>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="shortcut-label">Dana</span>
                                            <span class="shortcut-desc">Ambil Uang</span>
                                        </div>
                                    </a>
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
            ->resources([
                \App\Filament\Resources\ActivityResource::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
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
