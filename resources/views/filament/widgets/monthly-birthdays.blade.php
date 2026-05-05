<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col py-2">
            <h2 class="text-sm font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-[0.2em] mb-4">Birthdays</h2>
            @if($birthdays->count() > 0)
                <div class="space-y-4 flex flex-col">
                    <div class="flex flex-wrap gap-3">
                        @foreach($birthdays as $birthday)
                            <a href="/admin/members/{{ $birthday->id }}" class="block group w-full sm:w-[calc(50%-0.375rem)] md:w-[calc(33.333%-0.5rem)] lg:w-[calc(25%-0.5625rem)] xl:w-[calc(20%-0.6rem)]">
                                <div class="flex flex-col p-3 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 group-hover:border-primary-500 transition-colors h-full">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <span class="text-sm font-bold text-gray-900 dark:text-white line-clamp-2 leading-tight">{{ $birthday->nama_lengkap }}</span>
                                        <div class="flex flex-col items-end shrink-0">
                                            <span class="text-[12px] font-bold text-primary-500 uppercase">{{ \Carbon\Carbon::parse($birthday->tanggal_lahir)->format('d M') }}</span>
                                            <span class="text-[10px] text-gray-400 dark:text-gray-500">Ke-{{ now()->year - \Carbon\Carbon::parse($birthday->tanggal_lahir)->year }}</span>
                                        </div>
                                    </div>
                                    <div class="mt-auto pt-2 border-t border-gray-200 dark:border-gray-700">
                                        @if($birthday->instagram)
                                            <a href="https://instagram.com/{{ ltrim($birthday->instagram, '@') }}" target="_blank" class="flex items-center gap-1.5 text-sky-500 hover:text-sky-600 transition-colors">
                                                <x-heroicon-m-camera class="w-3.5 h-3.5" />
                                                <span class="text-[10px] font-bold line-clamp-1">&commat;{{ ltrim($birthday->instagram, '@') }}</span>
                                            </a>
                                        @else
                                            <span class="text-[10px] text-gray-400 italic">No Instagram</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="py-12 flex flex-col items-center justify-center text-center opacity-30 grayscale">
                    <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-full mb-4">
                        <x-heroicon-o-cake class="w-8 h-8 text-gray-400" />
                    </div>
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-gray-500">No birthdays this month</p>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
