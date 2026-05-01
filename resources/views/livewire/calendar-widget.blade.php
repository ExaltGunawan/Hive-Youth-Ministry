<div class="bg-white dark:bg-gray-900 p-6 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-800">
    {{-- Calendar Header --}}
    <div class="flex items-center justify-between mb-8 px-1">
        <div class="flex items-center gap-3">
            <button wire:click="previousMonth" class="w-8 h-8 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-400 dark:text-gray-500 hover:bg-[#F59E0B] hover:text-white transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <span class="text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-[0.2em] italic">{{ $monthName }}</span>
            <button wire:click="nextMonth" class="w-8 h-8 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-400 dark:text-gray-500 hover:bg-[#F59E0B] hover:text-white transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>
    </div>

    {{-- Calendar Grid --}}
    <div class="grid grid-cols-7 gap-1 mb-8 text-center px-1">
        @foreach(['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $day)
            <div class="text-[9px] font-black text-gray-300 dark:text-gray-600 py-1 uppercase tracking-widest">{{ $day }}</div>
        @endforeach

        @foreach($calendar as $item)
            @php
                $currentDateObj = \Carbon\Carbon::parse($item['date']);
                $isToday = $currentDateObj->isToday();
                $hasEvents = isset($scheduleDates[$item['date']]);
            @endphp
            <div class="p-0.5">
                <button 
                    wire:click="selectDate('{{ $item['date'] }}')"
                    @class([
                        'relative h-8 w-full flex items-center justify-center text-[10px] rounded-xl transition-all duration-300 border-2',
                        'text-gray-900 dark:text-white font-black' => $item['currentMonth'],
                        'text-gray-200 dark:text-gray-700' => !$item['currentMonth'],
                        'bg-[#F59E0B] text-white shadow-lg shadow-orange-200/50 z-10 border-[#F59E0B] scale-110' => $selectedDate === $item['date'],
                        'border-[#F59E0B] bg-orange-100/50 dark:bg-orange-500/20 text-[#F59E0B] dark:text-white' => $isToday && $selectedDate !== $item['date'],
                        'border-orange-100 dark:border-orange-500/10 bg-orange-50/30 dark:bg-orange-500/5 text-[#F59E0B]/70' => $hasEvents && !$isToday && $selectedDate !== $item['date'],
                        'border-transparent hover:bg-gray-50 dark:hover:bg-gray-800 hover:scale-105' => !$isToday && !$hasEvents && $selectedDate !== $item['date'],
                    ])
                >
                    {{ $item['day'] }}
                    
                    {{-- Event Dots --}}
                    @if($hasEvents && $selectedDate !== $item['date'])
                        <div class="absolute bottom-1 flex justify-center w-full">
                            <div @class([
                                'w-1 h-1 rounded-full',
                                'bg-[#F59E0B]' => !$isToday,
                                'bg-white' => $isToday,
                            ])></div>
                        </div>
                    @endif
                </button>
            </div>
        @endforeach
    </div>

    {{-- Division Filters --}}
    <div class="space-y-2.5 pt-6 border-t border-gray-100 dark:border-gray-800 px-1">
        @foreach($divisions as $div)
            <label class="flex items-center space-x-3 cursor-pointer group">
                <input type="checkbox" 
                    wire:click="toggleDivision({{ $div->id }})"
                    @if(in_array($div->id, $this->selectedDivisions)) checked @endif
                    class="w-4 h-4 rounded border-gray-200 dark:border-gray-700 text-[#F59E0B] focus:ring-[#F59E0B] dark:bg-gray-800 cursor-pointer transition-all group-hover:scale-110"
                >
                <span class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest group-hover:text-[#F59E0B] transition-colors italic">{{ $div->nama_divisi }}</span>
            </label>
        @endforeach
    </div>
</div>
