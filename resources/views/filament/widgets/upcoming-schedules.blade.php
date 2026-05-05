<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col py-2">
            <h2 class="text-sm font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-[0.2em] mb-4">Upcoming Schedules</h2>
            @if($schedules->count() > 0)
                <div class="space-y-4">
                    @foreach($schedules as $schedule)
                        <a href="/admin/schedules/{{ $schedule->id }}" class="block group">
                            <div class="flex flex-col p-3 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 group-hover:border-primary-500 transition-colors">
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <span class="text-sm font-bold text-gray-900 dark:text-white line-clamp-2 leading-tight">{{ $schedule->schedule_name }}</span>
                                    <div class="flex flex-col items-end shrink-0">
                                        <span class="text-[10px] font-bold text-primary-500 uppercase">{{ \Carbon\Carbon::parse($schedule->tanggal)->format('d/m') }}</span>
                                        <span class="text-[10px] text-gray-400 dark:text-gray-500">{{ \Carbon\Carbon::parse($schedule->jam)->format('H:i') }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between mt-auto pt-2 border-t border-gray-200 dark:border-gray-700">
                                    <span class="text-[10px] text-gray-500 line-clamp-1 mr-2">
                                        {{ $schedule->tempat ?? '-' }}{{ $schedule->sub_tempat ? " • {$schedule->sub_tempat}" : "" }}
                                    </span>
                                    @if($schedule->divisi)
                                        @php
                                            $color = $schedule->divisi->color ?? '#3b82f6';
                                        @endphp
                                        <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase tracking-wider shrink-0" 
                                              style="background-color: {{ $color }}22; color: {{ $color }}; border: 1px solid {{ $color }}44;">
                                            {{ $schedule->divisi->nama_divisi }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="py-12 flex flex-col items-center justify-center text-center opacity-30 grayscale">
                    <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-full mb-4">
                        <x-heroicon-o-calendar-days class="w-8 h-8 text-gray-400" />
                    </div>
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-gray-500">No schedules</p>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
