<div class="space-y-4">
    @foreach($getRecord()->comments as $comment)
        <div class="flex flex-col {{ $comment->user_id === auth()->id() ? 'items-end' : 'items-start' }}">
            <div class="max-w-[80%] rounded-2xl px-4 py-2 {{ $comment->user_id === auth()->id() ? 'bg-primary-500 text-white rounded-tr-none' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-tl-none' }}">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider opacity-75">
                        {{ $comment->user->name }}
                    </span>
                    <span class="text-[9px] opacity-50">
                        {{ $comment->created_at->diffForHumans() }}
                    </span>
                </div>
                <p class="text-sm leading-relaxed">
                    {{ $comment->comment }}
                </p>
            </div>
        </div>
    @endforeach

    @if($getRecord()->comments->isEmpty())
        <div class="text-center py-6 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl">
            <p class="text-sm text-gray-400 italic">Belum ada diskusi. Bendahara akan menulis pesan jika butuh info lebih lanjut.</p>
        </div>
    @endif
</div>
