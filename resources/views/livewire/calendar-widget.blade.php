<div class="bg-white dark:bg-gray-900 p-6 rounded-[2rem] shadow-2xl border border-gray-100 dark:border-gray-800">
    {{-- Calendar Header --}}
    <div class="flex items-center justify-between mb-6">
        <button wire:click="previousMonth" class="p-2 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <span class="text-[11px] font-black uppercase tracking-[0.2em] italic text-gray-900 dark:text-white">{{ $monthName }}</span>
        <button wire:click="nextMonth" class="p-2 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
        </button>
    </div>

    {{-- Calendar Grid --}}
    <div class="grid grid-cols-7 gap-1 mb-8 text-center">
        @foreach(['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $day)
            <div class="text-[9px] font-black text-gray-300 dark:text-gray-600 py-2 uppercase tracking-widest">{{ $day }}</div>
        @endforeach

        @foreach($calendar as $item)
            @php
                $currentDateObj = \Carbon\Carbon::parse($item['date']);
                $isToday = $currentDateObj->isToday();
                $dateEvents = $scheduleDates[$item['date']] ?? collect();
                $hasEvents = $dateEvents->isNotEmpty();
                $isCurrentSelected = $selectedDate === $item['date'];
            @endphp
            <div class="aspect-square p-0.5">
                @php
                    $isToday = \Carbon\Carbon::parse($item['date'])->isToday();
                    $uniqueDivisions = $dateEvents->unique('divisi_id');
                    $count = $uniqueDivisions->count();
                @endphp
                
                <button wire:click="selectDate('{{ $item['date'] }}')"
                    @class([
                        'relative w-full h-full flex flex-col items-center rounded-xl transition-all duration-300 border-2',
                        'font-black' => $item['currentMonth'],
                        'text-gray-900 dark:text-white' => $item['currentMonth'] && !$isCurrentSelected,
                        'text-gray-200 dark:text-gray-700' => !$item['currentMonth'] && !$isCurrentSelected,
                        'scale-105 z-10 shadow-xl' => $isCurrentSelected,
                        'border-transparent' => !$isToday && !$isCurrentSelected,
                    ])
                    style="
                        {{ $isCurrentSelected ? 'background-color: #EA580C !important; color: white !important; border-color: #EA580C !important;' : '' }}
                        {{ $isToday && !$isCurrentSelected ? 'border-color: #EA580C !important; background-color: #FFF7ED !important;' : '' }}
                    ">
                    <span class="text-[11px] leading-none pt-2.5 pb-1">{{ $item['day'] }}</span>
                    
                    {{-- Indicators - Positioned carefully at the bottom --}}
                    @if($count > 0)
                        <div class="flex gap-1 justify-center w-full pb-1.5 mt-auto">
                            @foreach($uniqueDivisions->take(3) as $div)
                                <div class="h-1.5 w-1.5 rounded-full shrink-0 shadow-sm" 
                                     style="background-color: {{ $isCurrentSelected ? 'white' : ($div->divisi->color ?? '#F59E0B') }} !important;">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </button>
            </div>
        @endforeach
    </div>

    {{-- Division Filter --}}
    <div class="space-y-3 pt-6 border-t border-gray-50 dark:border-gray-800">
        <h4 class="text-[9px] font-black text-gray-300 uppercase tracking-[0.2em] mb-4">Filter Divisions</h4>
        @foreach($divisions as $div)
            @php $isActive = in_array($div->id, $this->selectedDivisions); @endphp
            <button wire:click="toggleDivision({{ $div->id }})"
                @class([
                    'flex items-center w-full gap-3 p-3 rounded-2xl transition-all duration-300 group border-2',
                    'shadow-lg scale-[1.02]' => $isActive,
                    'border-transparent hover:bg-gray-50/50' => !$isActive,
                ])
                style="{{ $isActive ? "background-color: white; border-color: " . ($div->color ?? '#E5E7EB') . ";" : "" }}">
                <div class="w-3 h-3 rounded-full shadow-inner" style="background-color: {{ $div->color ?? '#E5E7EB' }}"></div>
                <span @class([
                    'text-[10px] font-black uppercase tracking-widest italic transition-colors',
                    'text-gray-900' => $isActive,
                    'text-gray-400 group-hover:text-gray-600' => !$isActive,
                ])>
                    {{ $div->nama_divisi }}
                </span>
            </button>
        @endforeach
    </div>
</div>

