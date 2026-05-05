<x-filament-panels::page.simple>
    <div class="max-w-2xl mx-auto w-full">
        <x-filament-panels::form wire:submit="save">
            {{ $this->form }}

            <x-filament-panels::form.actions
                :actions="$this->getFormActions()"
                :full-width="$this->hasFullWidthFormActions()"
                class="mt-6"
            />
        </x-filament-panels::form>
    </div>

    <style>
        /* Paksa container utama agar mau melebar */
        .fi-simple-main, 
        .fi-simple-main-ctn,
        main.fi-simple-main {
            max-width: 55rem !important;
            width: 100% !important;
        }

        @media (min-width: 1024px) {
            .fi-simple-main {
                min-width: 50rem !important;
            }
        }
        
        .fi-section {
            background: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(10px) !important;
            border-radius: 30px !important;
            border: 1px solid rgba(255, 255, 255, 0.4) !important;
            box-shadow: 0 20px 40px rgba(0,0,0,0.05) !important;
        }

        .fi-section-header-title {
            font-weight: 900 !important;
            text-transform: uppercase !important;
            letter-spacing: -1px !important;
        }

        .fi-btn-color-primary {
            background: linear-gradient(135deg, #FFB300, #FFA000) !important;
            border-radius: 15px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            padding: 1rem 2rem !important;
            box-shadow: 0 10px 20px rgba(255, 179, 0, 0.3) !important;
        }
    </style>
</x-filament-panels::page.simple>
