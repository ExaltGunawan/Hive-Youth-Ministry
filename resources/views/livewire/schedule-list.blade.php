<div class="space-y-6">
    @forelse($schedules as $dateString => $dateSchedules)
        @php 
            $carbonDate = \Carbon\Carbon::parse($dateString);
            $isSelected = $dateString === $selectedDate;
        @endphp
        <div @class([
            'flex gap-8 p-8 rounded-[2.5rem] transition-all duration-500 group',
            'bg-[#FDF9F0]/80 dark:bg-gray-800/80 shadow-xl border-l-[10px] border-[#F59E0B] scale-[1.02]' => $isSelected,
            'opacity-40 grayscale hover:opacity-100 hover:grayscale-0' => !$isSelected && $selectedDate,
        ])>
            {{-- Date Sidebar --}}
            <div @class([
                'flex flex-col items-center min-w-[70px] pt-1 border-r pr-8 transition-colors duration-500',
                'border-[#F59E0B]/30' => $isSelected,
                'border-gray-100 dark:border-gray-800' => !$isSelected,
            ])>
                <span @class([
                    'text-[12px] font-black uppercase tracking-[0.3em] mb-2 transition-colors duration-500',
                    'text-[#F59E0B]' => $isSelected,
                    'text-gray-300 dark:text-gray-600' => !$isSelected,
                ])>{{ $carbonDate->format('D') }}</span>
                <span @class([
                    'text-5xl font-[1000] leading-none tracking-tighter transition-all duration-500',
                    'text-[#F59E0B] scale-110' => $isSelected,
                    'text-gray-900 dark:text-white' => !$isSelected,
                ])>{{ $carbonDate->format('d') }}</span>
            </div>

            {{-- Events for this day --}}
            <div class="flex-1 space-y-8">
                @foreach($dateSchedules as $schedule)
                    <a href="{{ \App\Filament\Resources\ScheduleResource::getUrl('edit', ['record' => $schedule->id]) }}" class="block group/item">
                        <div class="flex items-center justify-between gap-10">
                            <div class="flex-1 min-w-0">
                                <h3 @class([
                                    'text-2xl font-[1000] uppercase leading-tight tracking-tight transition-all duration-500 italic',
                                    'text-[#F59E0B]' => $isSelected,
                                    'text-gray-900 dark:text-white group-hover/item:text-[#F59E0B]' => !$isSelected,
                                ])>{{ $schedule->schedule_name }}</h3>
                                <div class="mt-3 flex flex-wrap items-center gap-6">
                                    <div @class([
                                        'flex items-center font-black transition-colors duration-500',
                                        'text-[#F59E0B]' => $isSelected,
                                        'text-gray-500 dark:text-gray-400' => !$isSelected,
                                    ])>
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span class="text-xs uppercase tracking-widest">{{ \Carbon\Carbon::parse($schedule->jam)->format('g:i A') }}</span>
                                    </div>
                                    <div class="flex items-center text-gray-400 dark:text-gray-500 overflow-hidden font-bold">
                                        <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        <span class="text-[11px] uppercase truncate tracking-widest">
                                            {{ $schedule->tempat }} {{ $schedule->sub_tempat ? '• ' . $schedule->sub_tempat : '' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="shrink-0">
                                <span @class([
                                    'px-6 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-[0.25em] text-white shadow-xl block text-center min-w-[110px] transition-all duration-500 group-hover/item:scale-110',
                                    'bg-red-500 shadow-red-200/50 dark:shadow-none' => $schedule->divisi->nama_divisi === 'Ministry',
                                    'bg-emerald-500 shadow-emerald-200/50 dark:shadow-none' => $schedule->divisi->nama_divisi === 'Community',
                                    'bg-[#F59E0B] shadow-orange-200/50 dark:shadow-none' => $schedule->divisi->nama_divisi === 'Outreach',
                                    'bg-blue-500 shadow-blue-200/50 dark:shadow-none' => !in_array($schedule->divisi->nama_divisi, ['Ministry', 'Community', 'Outreach']),
                                ])>
                                    {{ $schedule->divisi->nama_divisi }}
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @empty
        <div class="py-12 flex flex-col items-center justify-center text-center space-y-3 grayscale opacity-30">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">No events scheduled</p>
        </div>
    @endforelse
</div>
