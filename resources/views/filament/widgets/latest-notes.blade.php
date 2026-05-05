<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col py-2">
            <h2 class="text-sm font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-[0.2em] mb-4">Latest Notes</h2>
            @if($notes->count() > 0)
                <div class="space-y-4">
                    @foreach($notes as $note)
                        <a href="/admin/notes/{{ $note->id }}" class="block group">
                            <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 group-hover:border-primary-500 transition-colors">
                                <span class="text-sm font-bold text-gray-900 dark:text-white line-clamp-1 pr-2">{{ $note->title }}</span>
                                <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase shrink-0">{{ $note->created_at->format('d/m') }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="py-12 flex flex-col items-center justify-center text-center opacity-30 grayscale">
                    <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-full mb-4">
                        <x-heroicon-o-document-text class="w-8 h-8 text-gray-400" />
                    </div>
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-gray-500">No notes</p>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
