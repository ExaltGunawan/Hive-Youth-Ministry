<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Judul Ibadah Sebulan Ini</h2>
            <a href="/admin/worship-titles" class="text-[10px] font-bold text-primary-500 uppercase hover:underline">Lihat Semua</a>
        </div>
        @if($titles->count() > 0)
            <div class="flex flex-wrap gap-2">
                @foreach($titles as $title)
                    <a href="/admin/worship-titles/{{ $title->id }}" class="px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-gray-800 border dark:border-gray-700 hover:border-primary-500 transition-colors">
                        <span class="text-[10px] block text-gray-500 uppercase">{{ $title->date->format('d M') }}</span>
                        <span class="text-sm font-semibold line-clamp-1">{{ $title->title }}</span>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-gray-400 italic text-sm">Belum ada jadwal ibadah bulan ini</p>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
