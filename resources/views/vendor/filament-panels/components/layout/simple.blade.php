@php
    use Filament\Support\Enums\MaxWidth;

    $livewire ??= null;
@endphp

<x-filament-panels::layout.base :livewire="$livewire">
    @push('styles')
    <style>
        .fi-simple-layout {
            background-color: #000 !important;
            position: fixed !important;
            inset: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            overflow: hidden !important;
            z-index: 1 !important;
        }
        .login-bg-image {
            position: fixed !important;
            inset: 0 !important;
            background-image: url("/assets/BG.png") !important;
            background-size: cover !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            z-index: 2 !important;
            display: block !important;
            width: 100vw !important;
            height: 100vh !important;
        }
        .login-overlay {
            position: fixed !important;
            inset: 0 !important;
            background: linear-gradient(180deg, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,1) 100%) !important;
            z-index: 3 !important;
        }
        .fi-simple-main-ctn {
            position: fixed !important;
            inset: 0 !important;
            z-index: 30 !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            overflow-y: auto !important;
            padding: 2rem !important;
            background: transparent !important;
        }
        .fi-simple-main,
        .fi-pa-auth-card {
            background-color: #ffffff !important;
            border-radius: 0.5rem !important;
            padding: 1.5rem !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
            width: 100% !important;
            max-width: 20rem !important;
            border: none !important;
            position: relative !important;
            z-index: 40 !important;
            margin: 0 !important;
        }
        .fi-simple-main .fi-fo-field-wrp,
        .fi-pa-auth-card .fi-fo-field-wrp {
            margin-bottom: 0.125rem !important;
        }
        .fi-simple-main .fi-fo-field-wrp-label,
        .fi-simple-main label,
        .fi-pa-auth-card .fi-fo-field-wrp-label,
        .fi-pa-auth-card label {
            color: #1a1a1a !important;
            font-weight: 600 !important;
            font-size: 0.875rem !important;
            margin-bottom: 0.0625rem !important;
            display: block !important;
        }
        .fi-simple-main input[type="email"],
        .fi-simple-main input[type="password"],
        .fi-simple-main input[type="text"] {
            background-color: #ffffff !important;
            color: #1a1a1a !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            padding: 0.5rem 0.75rem !important;
            font-size: 0.95rem !important;
            width: 100% !important;
            box-sizing: border-box !important;
            box-shadow: none !important;
            transition: border-color 0.2s ease !important;
        }
        .fi-simple-main input[type="email"]:focus,
        .fi-simple-main input[type="password"]:focus,
        .fi-simple-main input[type="text"]:focus {
            border-color: #1a1a1a !important;
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(26, 26, 26, 0.1) !important;
        }
        .fi-simple-main .fi-checkbox-input,
        .fi-simple-main input[type="checkbox"] {
            width: 1rem !important;
            height: 1rem !important;
            border-radius: 0.125rem !important;
            border: 1px solid #d1d5db !important;
            background-color: #fff !important;
            appearance: checkbox !important;
            -webkit-appearance: checkbox !important;
            -moz-appearance: checkbox !important;
            cursor: pointer !important;
            margin-right: 0.5rem !important;
            flex-shrink: 0 !important;
            filter: none !important;
            display: inline-block !important;
            vertical-align: middle !important;
        }
        .fi-simple-main .fi-checkbox-input-ctn,
        .fi-simple-main .flex.items-center.gap-3,
        .fi-simple-main .fi-fo-field-wrp > div > div {
            display: flex !important;
            align-items: center !important;
            margin-top: 0.0625rem !important;
            margin-bottom: 0.125rem !important;
        }
        .fi-simple-main span,
        .fi-simple-main label[for="remember"] {
            color: #4b5563 !important;
            font-weight: 500 !important;
            font-size: 0.875rem !important;
            line-height: 1 !important;
            margin: 0 !important;
        }
        .fi-simple-main button[type="submit"],
        .fi-pa-auth-card button[type="submit"],
        .fi-simple-main .fi-btn,
        .fi-pa-auth-card .fi-btn,
        button[type="submit"] {
            background-color: #1a1a1a !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            padding: 0.5rem !important;
            border-radius: 0.375rem !important;
            width: 100% !important;
            margin-top: 0.0625rem !important;
            font-size: 0.875rem !important;
            text-transform: none !important;
            letter-spacing: 0.025em !important;
            transition: all 0.2s ease-in-out !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            border: none !important;
            cursor: pointer !important;
            box-sizing: border-box !important;
        }
        .fi-simple-main button[type="submit"]:hover,
        .fi-pa-auth-card button[type="submit"]:hover,
        .fi-simple-main .fi-btn:hover,
        .fi-pa-auth-card .fi-btn:hover,
        button[type="submit"]:hover {
            background-color: #000000 !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
            transform: translateY(-1px) !important;
            color: #ffffff !important;
        }
        .fi-simple-main button[type="submit"] span,
        .fi-pa-auth-card button[type="submit"] span,
        .fi-simple-main .fi-btn span,
        .fi-pa-auth-card .fi-btn span,
        button[type="submit"] span {
            color: #ffffff !important;
        }
        .gki-logo {
            height: 3.5rem !important;
            width: auto !important;
            filter: brightness(0) invert(1) !important;
            display: inline-block !important;
        }
        .hive-logo {
            height: 8rem !important;
            width: auto !important;
            filter: brightness(0) invert(1) !important;
            display: inline-block !important;
        }
    </style>
    @endpush

    @props([
        'after' => null,
        'heading' => null,
        'subheading' => null,
    ])

    <div class="fi-simple-layout flex min-h-screen flex-col items-center" style="background: #000 !important; position: fixed !important; inset: 0 !important; width: 100vw !important; height: 100vh !important; overflow: hidden !important; z-index: 1 !important;">
        {{-- Fixed Background --}}
        <div class="login-bg-image" style="background-image: url('/assets/BG.png') !important; position: absolute !important; inset: 0 !important; background-size: cover !important; background-position: center !important; z-index: 2 !important; display: block !important;"></div>
        {{-- Dark Overlay --}}
        <div class="login-overlay" style="position: absolute !important; inset: 0 !important; background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.8) 100%) !important; z-index: 3 !important; display: block !important;"></div>

        <div class="fi-simple-main-ctn" style="position: absolute !important; inset: 0 !important; z-index: 30 !important; display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; overflow-y: auto !important; padding: 2rem !important; background: transparent !important;">
            @if(!filament()->auth()->check())
                {{-- Top Left Logos --}}
                <div style="position: fixed; top: 3rem; left: 3rem; z-index: 100; display: flex; align-items: center; gap: 2.5rem; pointer-events: none;">
                    <img src="/assets/gki.png" alt="GKI" class="gki-logo">
                    <img src="/assets/Logo.png" alt="Logo" class="hive-logo">
                </div>

                <div style="margin-bottom: 0.75rem; text-align: center; z-index: 60; position: relative;">
                    <h1 style="font-size: 2.5rem; font-weight: 700; color: white; letter-spacing: -0.025em; margin-bottom: 0.125rem; font-family: sans-serif; text-shadow: 0 4px 10px rgba(0,0,0,0.5);">Login</h1>
                    <p style="font-size: 1rem; color: rgba(255,255,255,0.9); font-weight: 500; font-family: sans-serif; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">Your Account</p>
                </div>
            @endif

            <main
                @class([
                    'fi-simple-main w-full bg-white px-6 py-12 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 sm:rounded-xl sm:px-12',
                    'my-16' => filament()->auth()->check(),
                    'z-70 relative' => !filament()->auth()->check(),
                    match ($maxWidth ??= (filament()->getSimplePageMaxContentWidth() ?? MaxWidth::Large)) {
                        MaxWidth::ExtraSmall, 'xs' => 'max-w-xs',
                        MaxWidth::Small, 'sm' => 'max-w-sm',
                        MaxWidth::Medium, 'md' => 'max-w-md',
                        MaxWidth::Large, 'lg' => 'max-w-lg',
                        MaxWidth::ExtraLarge, 'xl' => 'max-w-xl',
                        MaxWidth::TwoExtraLarge, '2xl' => 'max-w-2xl',
                        MaxWidth::ThreeExtraLarge, '3xl' => 'max-w-3xl',
                        MaxWidth::FourExtraLarge, '4xl' => 'max-w-4xl',
                        MaxWidth::FiveExtraLarge, '5xl' => 'max-w-5xl',
                        MaxWidth::SixExtraLarge, '6xl' => 'max-w-6xl',
                        MaxWidth::SevenExtraLarge, '7xl' => 'max-w-7xl',
                        MaxWidth::Full, 'full' => 'max-w-full',
                        MaxWidth::MinContent, 'min' => 'max-w-min',
                        MaxWidth::MaxContent, 'max' => 'max-w-max',
                        MaxWidth::FitContent, 'fit' => 'max-w-fit',
                        MaxWidth::Prose, 'prose' => 'max-w-prose',
                        MaxWidth::ScreenSmall, 'screen-sm' => 'max-w-screen-sm',
                        MaxWidth::ScreenMedium, 'screen-md' => 'max-w-screen-md',
                        MaxWidth::ScreenLarge, 'screen-lg' => 'max-w-screen-lg',
                        MaxWidth::ScreenExtraLarge, 'screen-xl' => 'max-w-screen-xl',
                        MaxWidth::ScreenTwoExtraLarge, 'screen-2xl' => 'max-w-screen-2xl',
                        default => $maxWidth,
                    },
                ])
                style="background-color: #ffffff !important; border-radius: 0.5rem !important; padding: 1.5rem !important; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important; width: 100% !important; max-width: 20rem !important; border: none !important; position: relative !important; z-index: 40 !important; margin: 0 !important;"
            >
                {{ $slot }}
            </main>
        </div>

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::FOOTER, scopes: $livewire?->getRenderHookScopes()) }}
    </div>
</x-filament-panels::layout.base>
