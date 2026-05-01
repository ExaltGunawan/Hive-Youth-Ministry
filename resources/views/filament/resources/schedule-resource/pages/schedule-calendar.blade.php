<x-filament-panels::page>
    <style>
        /* 1. LOCK TOPBAR & NAVBAR - ONLY CONTENT SCROLLS */
        /* Target Filament's main layout containers */
        html, body { height: 100%; overflow: hidden; }
        .fi-main-ctn { height: 100vh !important; overflow: hidden !important; display: flex !important; flex-direction: column !important; }
        .fi-main-ctn > main { flex: 1 !important; overflow-y: auto !important; scroll-behavior: smooth; }
        
        /* Remove Filament's default max-width constraints for this page */
        .fi-main-ctn > main > div { max-width: none !important; padding: 0 !important; }
        .fi-page-header { padding: 2rem 4rem 0 4rem !important; margin-bottom: 1rem !important; }

        /* 2. SIDEBAR LOGO FIX - NO MORE LEAKS */
        .fi-sidebar-header { padding-top: 4rem !important; margin-bottom: 2rem !important; }
        .fi-sidebar-nav { margin-top: 1rem !important; }

        /* 3. MAIN DASHBOARD LAYOUT */
        .schedule-container {
            display: flex;
            gap: 2.5rem;
            padding: 0 4rem 3rem 4rem;
            align-items: flex-start;
            width: 100%;
        }

        .schedule-list-column {
            flex: 1;
            min-width: 450px;
            max-width: 900px;
        }
        
        .schedule-sidebar-column {
            width: 320px;
            flex-shrink: 0;
            position: sticky;
            top: 2rem;
        }

        /* 4. RESPONSIVE FIXES (TABLET LANDSCAPE & MOBILE) */
        @media (max-width: 1280px) {
            .schedule-container { flex-direction: column-reverse; align-items: center; padding: 0 1.5rem 3rem 1.5rem; }
            .schedule-sidebar-column { width: 100%; max-width: 450px; position: static; margin-bottom: 2rem; margin-top: 0; }
            .schedule-list-column { width: 100%; min-width: 0; }
            .fi-page-header { padding: 2rem 1.5rem 0 1.5rem !important; }
        }

        @media (max-width: 768px) {
            .schedule-container { padding: 0 1rem 3rem 1rem; }
            .fi-page-header { padding: 1.5rem 1rem 0 1rem !important; }
        }
    </style>

    <div class="schedule-container">
        {{-- Left Side: Detailed Timeline --}}
        <div class="schedule-list-column">
            @livewire('schedule-list')
        </div>

        {{-- Right Side: Minimalist Calendar Widget --}}
        <div class="schedule-sidebar-column">
            @livewire('calendar-widget')
        </div>
    </div>
</x-filament-panels::page>
