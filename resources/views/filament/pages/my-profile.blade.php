<x-filament-panels::page>
    <div class="max-w-4xl mx-auto">
        <form wire:submit="save">
            {{ $this->form }}

            <div class="mt-6 flex justify-center">
                {{ $this->getSaveAction() }}
            </div>
        </form>
    </div>

    <style>
        .fi-section {
            background: rgba(255, 255, 255, 0.96) !important;
            backdrop-filter: blur(8px) !important;
            border-radius: 35px !important;
            border: 1px solid rgba(255, 255, 255, 0.4) !important;
            box-shadow: 0 20px 50px rgba(0,0,0,0.05) !important;
            padding: 2rem !important; /* Tambahkan padding agar tidak mepet */
        }

        /* DARK MODE OVERRIDES */
        :root.dark .fi-section {
            background: rgba(30, 30, 30, 0.8) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3) !important;
        }

        :root.dark .fi-section * {
            color: white !important;
        }

        :root.dark .fi-section-header-description {
            color: rgba(255, 255, 255, 0.6) !important;
        }

        .fi-section-header-title {
            font-weight: 900 !important;
            text-transform: uppercase !important;
            letter-spacing: -1px !important;
            margin-bottom: 0.5rem !important;
        }
        
        button[type="submit"] {
            background: linear-gradient(135deg, #FFB300, #FFA000) !important;
            border-radius: 15px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            padding: 0.75rem 2.5rem !important;
            color: white !important;
            box-shadow: 0 10px 20px rgba(255, 179, 0, 0.3) !important;
            transition: all 0.3s ease !important;
        }

        button[type="submit"]:hover {
            transform: scale(1.05) !important;
            box-shadow: 0 15px 30px rgba(255, 179, 0, 0.4) !important;
        }
    </style>
</x-filament-panels::page>
