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
                <p class="text-gray-400 italic text-sm">Belum ada tema bulan ini</p>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
