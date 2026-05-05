<div class="flex items-center gap-4">
    <div class="flex flex-col md:flex-row gap-2 md:gap-4">
        {{-- Total Accumulation Badge --}}
        <div class="flex items-center gap-3 px-4 py-2 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl">
            <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Total Anggaran {{ $currentYear }}:</span>
            <span class="text-base font-black text-gray-700 dark:text-gray-200">
                Rp {{ number_format($totalAmount, 0, ',', '.') }}
            </span>
        </div>

        {{-- Remaining Balance Badge --}}
        <div class="flex items-center gap-3 px-4 py-2 bg-amber-500/10 border border-amber-500/20 rounded-2xl">
            <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-widest">Sisa Dana Keseluruhan:</span>
            <span class="text-base font-black text-amber-600 dark:text-amber-400">
                Rp {{ number_format($totalRemaining, 0, ',', '.') }}
            </span>
        </div>
    </div>

    {{-- Year Navigation --}}
    <div class="flex items-center gap-1 p-1 bg-gray-100 dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700">
        <button wire:click="previousYear" 
                class="p-1.5 hover:bg-white dark:hover:bg-gray-700 rounded-xl transition-all text-gray-500 hover:text-amber-500 shadow-sm hover:shadow">
            <x-heroicon-m-chevron-left class="w-5 h-5"/>
        </button>

        <div class="px-4 py-1 text-center min-w-[80px]">
            <span class="text-lg font-black tracking-tighter text-gray-700 dark:text-gray-200">
                {{ $currentYear }}
            </span>
        </div>

        <button wire:click="nextYear" 
                class="p-1.5 hover:bg-white dark:hover:bg-gray-700 rounded-xl transition-all text-gray-500 hover:text-amber-500 shadow-sm hover:shadow">
            <x-heroicon-m-chevron-right class="w-5 h-5"/>
        </button>
    </div>
</div>
