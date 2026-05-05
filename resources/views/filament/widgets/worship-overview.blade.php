<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col justify-center py-2">
            <h2 class="text-sm font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-[0.2em] mb-2">Tema Bulan Ini</h2>
            @if($theme)
                <a href="/admin/worship-themes" class="hover:opacity-75 transition-opacity">
                    <p class="text-4xl font-black text-primary-600 dark:text-primary-400 leading-tight tracking-tighter">
                        {{ $theme->theme_title }}
                    </p>
                </a>
            @else
                <div class="py-12 flex flex-col items-center justify-center text-center opacity-30 grayscale">
                    <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-full mb-4">
                        <x-heroicon-o-bookmark-square class="w-8 h-8 text-gray-400" />
                    </div>
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-gray-500">Belum ada tema bulan ini</p>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
