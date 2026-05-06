<div class="space-y-12 relative">
    @forelse($schedules as $dateString => $dateSchedules)
        @php 
            $carbonDate = \Carbon\Carbon::parse($dateString);
            $isSelected = $dateString === $selectedDate;
            $primaryColor = $dateSchedules->first()->divisi->color ?? '#0EA5E9';
            $isLast = $loop->last;
        @endphp
        
        <div id="date-{{ $dateString }}" 
             @class([
                'flex flex-col md:flex-row gap-4 md:gap-6 p-4 md:p-6 rounded-[2rem] transition-all duration-500 relative overflow-hidden border-b border-gray-300 dark:border-gray-800',
                'bg-white dark:bg-gray-900 shadow-2xl scale-[1.01] z-10 border-2' => $isSelected,
                'bg-gray-50/50 dark:bg-gray-800/30 opacity-60 hover:opacity-100' => !$isSelected,
             ])
             style="{{ $isSelected ? "border-color: $primaryColor;" : "" }}">
            
            {{-- Date Indicator --}}
            <div @class([
                'flex md:flex-col items-center justify-center min-w-[80px] pr-6',
                'border-b md:border-b-0 md:border-r border-gray-100 dark:border-gray-800 pb-4 md:pb-0' => true
            ])>
                <div class="flex flex-col items-center">
                    <span class="text-[11px] font-black uppercase tracking-[0.3em] opacity-40 mb-1">{{ $carbonDate->format('D') }}</span>
                    <span class="text-4xl font-[1000] tracking-tighter" style="color: {{ $isSelected ? $primaryColor : 'inherit' }}">{{ $carbonDate->format('d') }}</span>
                </div>
            </div>

            {{-- Events --}}
            <div class="flex-1 space-y-6 py-1 min-w-0">
                @foreach($dateSchedules as $schedule)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 group/item">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start gap-3 mb-2">
                                <h3 class="text-xl md:text-2xl font-[1000] uppercase italic tracking-tighter leading-tight group-hover/item:text-orange-500 transition-colors break-words">
                                    {{ $schedule->schedule_name }} {{ $schedule->sub_schedule ? '• ' . $schedule->sub_schedule : '' }}
                                </h3>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-6 gap-y-2 ml-1 sm:ml-6">
                                <div class="flex items-center text-xs font-bold text-gray-500 uppercase tracking-[0.2em]">
                                    <svg class="w-4 h-4 mr-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ \Carbon\Carbon::parse($schedule->jam)->format('H:i') }}
                                </div>
                                <div class="flex items-center text-xs font-bold text-gray-500 uppercase tracking-[0.2em]">
                                    <svg class="w-4 h-4 mr-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                    {{ $schedule->tempat }} {{ $schedule->sub_tempat ? '• ' . $schedule->sub_tempat : '' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="shrink-0 flex items-center justify-between sm:justify-end gap-3 mt-2 sm:mt-0">
                            <span class="px-4 py-1.5 rounded-full text-[9px] font-[1000] uppercase tracking-[0.15em] shadow-lg border-2 border-black/10"
                                  style="background-color: {{ $schedule->divisi->color ?? '#0EA5E9' }}; color: #000000; box-shadow: 0 4px 12px -2px {{ $schedule->divisi->color ?? '#0EA5E9' }}88;">
                                {{ $schedule->divisi->nama_divisi ?? 'Global' }}
                            </span>
                            <a href="{{ \App\Filament\Resources\ScheduleResource::getUrl('edit', ['record' => $schedule->id]) }}" 
                               class="p-2 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-400 hover:text-orange-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="py-20 flex flex-col items-center justify-center text-center opacity-20 grayscale">
            <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <p class="text-sm font-black uppercase tracking-[0.2em]">No events found</p>
        </div>
    @endforelse
</div>

