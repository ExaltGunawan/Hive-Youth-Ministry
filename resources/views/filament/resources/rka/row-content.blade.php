@php
    $record = $getRecord();
@endphp

<div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-800">
    <div class="flex flex-col gap-3">
        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-400">
            <x-heroicon-m-list-bullet class="w-4 h-4"/>
            <span>Rincian Item (Quick View)</span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($record->items as $index => $item)
                <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden group">
                    {{-- Hierarchical ID Badge --}}
                    <div class="absolute top-0 left-0 px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-[9px] font-black text-gray-400 rounded-br-lg border-r border-b border-gray-200 dark:border-gray-600">
                        {{ $record->id }}.{{ $index + 1 }}
                    </div>

                    <div class="flex flex-col pl-4 pt-1 w-full">
                        <div class="text-sm font-bold text-gray-800 dark:text-gray-100 mb-1">
                            {{ !empty($item->item_name) ? $item->item_name : 'Item (No Name)' }}
                        </div>
                        <div class="flex justify-between items-end mt-1">
                            <span class="text-[10px] text-gray-400 uppercase tracking-tight">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            <div class="flex flex-col items-end">
                                <span class="text-[10px] text-gray-400">Anggaran: Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                <span class="text-xs font-bold text-primary-600 dark:text-primary-400">Sisa: Rp {{ number_format($item->remaining_balance, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($record->items->isEmpty())
            <div class="text-center py-4 text-sm text-gray-400 italic">
                Tidak ada rincian item.
            </div>
        @endif
    </div>
</div>
