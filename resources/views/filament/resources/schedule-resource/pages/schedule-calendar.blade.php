<x-filament-panels::page>
    <div class="flex flex-col lg:flex-row gap-10 mt-2 items-start w-full max-w-full">
        {{-- Left Side: Our Schedule (Dominan & Estetik) --}}
        <div class="flex-1 bg-white dark:bg-gray-900 p-10 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-800 min-h-[600px]">
            @livewire('schedule-list')
        </div>

        {{-- Right Side: Calendar (Kecil & Minimalis) --}}
        <div class="w-full lg:w-[280px] sticky top-6 shrink-0">
            @livewire('calendar-widget')
        </div>
    </div>
</x-filament-panels::page>
