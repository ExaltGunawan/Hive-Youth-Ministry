<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col py-2">
            <h2 class="text-sm font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-[0.2em] mb-4">Petugas Ibadah Minggu Ini</h2>
            @if($schedule)
                <div class="space-y-6">
                    {{-- Info Ibadah & PIC --}}
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-4 border-b border-gray-50 dark:border-gray-800/50">
                        <div class="space-y-1">
                            <h3 class="text-xl font-black uppercase tracking-tight text-primary-600 dark:text-primary-400 leading-tight">
                                {{ $schedule->worshipTitle?->title ?? 'Ibadah Youth' }}
                            </h3>
                            <div class="flex items-center gap-2 text-gray-400 dark:text-gray-500">
                                <x-heroicon-m-calendar-days class="w-3.5 h-3.5" />
                                <span class="text-[10px] font-bold uppercase tracking-widest">
                                    {{ \Carbon\Carbon::parse($schedule->tanggal)->format('l, d F Y') }}
                                </span>
                            </div>
                        </div>
                        
                        @if($schedule->pic)
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 dark:bg-white/5 rounded-full border border-gray-100 dark:border-white/5 w-fit">
                                <span class="text-[8px] font-black uppercase tracking-widest text-primary-500">P.I.C</span>
                                <span class="text-[11px] font-bold text-gray-700 dark:text-gray-300">{{ $schedule->pic->nama_lengkap }}</span>
                                @if($schedule->pic->kontak)
                                    @php
                                        $picPhone = preg_replace('/[^0-9]/', '', $schedule->pic->kontak);
                                        if (str_starts_with($picPhone, '0')) $picPhone = '62' . substr($picPhone, 1);
                                    @endphp
                                    <a href="https://wa.me/{{ $picPhone }}" target="_blank" class="text-emerald-500 hover:scale-110 transition-transform">
                                        <x-heroicon-m-chat-bubble-left-right class="w-3.5 h-3.5" />
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Daftar Petugas --}}
                    <div class="divide-y divide-gray-50 dark:divide-gray-800/50">
                        @forelse($schedule->assignments as $assignment)
                            <div class="flex items-center justify-between py-3 first:pt-0 last:pb-0 group">
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-[9px] font-black uppercase tracking-[0.1em] text-gray-400 dark:text-gray-500 group-hover:text-primary-500 transition-colors">
                                        {{ $assignment->serviceRole?->role_name }}
                                    </span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ $assignment->member?->nama_lengkap }}
                                    </span>
                                </div>
                                
                                @if($assignment->member?->kontak)
                                    @php
                                        $rawPhone = preg_replace('/[^0-9]/', '', $assignment->member->kontak);
                                        $waPhone = str_starts_with($rawPhone, '0') ? '62' . substr($rawPhone, 1) : $rawPhone;
                                        $displayPhone = str_starts_with($rawPhone, '0') ? '+62 ' . substr($rawPhone, 1) : '+' . $rawPhone;
                                    @endphp
                                    <a href="https://wa.me/{{ $waPhone }}" 
                                       target="_blank"
                                       class="flex items-center gap-2 px-3 py-1.5 bg-emerald-500/5 hover:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-lg transition-all border border-emerald-500/10 group-hover:translate-x-[-4px]">
                                        <x-heroicon-m-phone class="w-3.5 h-3.5" />
                                        <span class="text-[10px] font-black tracking-wider font-mono">
                                            {{ $displayPhone }}
                                        </span>
                                    </a>
                                @else
                                    <span class="text-[9px] font-bold text-gray-300 dark:text-gray-700 uppercase italic">No Phone</span>
                                @endif
                            </div>
                        @empty
                            <div class="py-6 text-center">
                                <p class="text-xs text-gray-400 italic font-medium uppercase tracking-widest">Belum ada penugasan petugas</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @else
                <div class="py-12 flex flex-col items-center justify-center text-center opacity-30 grayscale">
                    <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-full mb-4">
                        <x-heroicon-o-calendar class="w-8 h-8 text-gray-400" />
                    </div>
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-gray-500">Belum ada jadwal ibadah terdekat</p>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
