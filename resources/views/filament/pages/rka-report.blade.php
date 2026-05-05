<x-filament-panels::page>
    <x-filament-panels::form wire:submit="generateReport">
        {{ $this->form }}

        <div class="flex justify-start">
            <x-filament::button type="submit">
                Tampilkan Laporan
            </x-filament::button>
        </div>
    </x-filament-panels::form>

    @if($rkaData)
        <div id="report-content" class="space-y-10 bg-white dark:bg-gray-950 p-12 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 text-gray-900 dark:text-gray-100">
            {{-- Professional Header --}}
            <div class="flex justify-between items-start border-b-2 border-gray-900 dark:border-gray-600 pb-8 mb-8">
                <div class="flex items-center gap-6">
                    <div>
                        <h1 class="text-3xl font-black uppercase tracking-tighter leading-none dark:text-white">HIVE YOUTH MINISTRY</h1>
                        <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mt-1 uppercase tracking-widest">Laporan Pertanggungjawaban Anggaran</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="inline-block px-4 py-1 bg-gray-900 dark:bg-gray-800 text-white dark:text-gray-100 text-[10px] font-black uppercase tracking-widest rounded-full mb-2 border dark:border-gray-700">
                        OFFICIAL REPORT
                    </div>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-widest font-bold">Generated: {{ now()->format('d F Y / H:i') }}</p>
                </div>
            </div>

            {{-- Period & Info --}}
            <div class="grid grid-cols-2 gap-4 mb-10 text-gray-900 dark:text-gray-100">
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Periode Laporan</p>
                    <p class="text-lg font-bold dark:text-white">
                        @if($startDate && $endDate)
                            {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} — {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                        @elseif($startDate)
                            Sejak {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
                        @elseif($endDate)
                            Hingga {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                        @else
                            Seluruh Periode Aktif
                        @endif
                    </p>
                </div>
                <div class="text-right space-y-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Program</p>
                    <p class="text-lg font-bold dark:text-white">{{ $rkaData->count() }} RKA / Event Terpilih</p>
                </div>
            </div>

            @foreach($rkaData as $rka)
                <div class="mb-12 last:mb-0 group">
                    <div class="flex justify-between items-end mb-4 border-b border-gray-100 dark:border-gray-800 pb-2 text-gray-900 dark:text-gray-100">
                        <h2 class="text-xl font-black dark:text-white uppercase tracking-tight">{{ $rka->name }}</h2>
                        <span class="text-[10px] font-mono font-bold text-gray-400">REF-ID: {{ $rka->id }} / FY-{{ $rka->fiscal_year }}</span>
                    </div>

                    <div class="overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800">
                        <table class="w-full text-left border-collapse table-fixed bg-white dark:bg-gray-950">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-900/50">
                                    <th class="p-4 w-24 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800">ID</th>
                                    <th class="p-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800">Deskripsi Item</th>
                                    <th class="p-4 w-36 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800 text-right">Anggaran</th>
                                    <th class="p-4 w-40 text-[10px] font-black text-primary-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800 text-right bg-primary-500/5 dark:bg-primary-500/10">Realisasi (Aktual)</th>
                                    <th class="p-4 w-36 text-[10px] font-black text-gray-900 dark:text-white uppercase tracking-widest border-b border-gray-100 dark:border-gray-800 text-right">Sisa</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-900/30">
                                @php 
                                    $totalBudget = 0; $totalActual = 0; $totalRemaining = 0;
                                @endphp
                                @foreach($rka->items as $item)
                                    @php
                                        $budget = (float)($item->price * $item->quantity);
                                        
                                        // Calculate actual amount based on filtered withdrawal items
                                        $filteredWithdrawalItems = $item->withdrawalItems
                                            ->filter(function ($wItem) use ($startDate, $endDate) {
                                                if (!$wItem->withdrawalRequest || $wItem->withdrawalRequest->status !== 'actualized') {
                                                    return false;
                                                }
                                                
                                                $date = \Carbon\Carbon::parse($wItem->withdrawalRequest->withdrawal_date);
                                                
                                                if ($startDate && $date->lt(\Carbon\Carbon::parse($startDate))) {
                                                    return false;
                                                }
                                                
                                                if ($endDate && $date->gt(\Carbon\Carbon::parse($endDate))) {
                                                    return false;
                                                }
                                                
                                                return true;
                                            });

                                        $actual = $filteredWithdrawalItems->sum('actual_amount');
                                        
                                        // Remaining balance should always reflect the total overall balance
                                        $remaining = $item->remaining_balance;
                                        
                                        $totalBudget += $budget; $totalActual += $actual; $totalRemaining += $remaining;
                                    @endphp
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/20 transition-colors text-gray-900 dark:text-gray-100">
                                        <td class="p-4 text-xs font-mono font-bold text-gray-400 truncate">{{ $item->manual_id ?? '-' }}</td>
                                        <td class="p-4 text-sm font-bold text-gray-800 dark:text-gray-200">{{ $item->item_name }}</td>
                                        <td class="p-4 text-sm font-mono text-gray-600 dark:text-gray-400 text-right">Rp {{ number_format($budget, 0, ',', '.') }}</td>
                                        <td class="p-4 text-sm font-black font-mono text-primary-600 dark:text-primary-400 text-right bg-primary-500/5 dark:bg-primary-500/10">Rp {{ number_format($actual, 0, ',', '.') }}</td>
                                        <td class="p-4 text-sm font-black font-mono text-gray-900 dark:text-white text-right">Rp {{ number_format($remaining, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-900 text-white dark:bg-gray-800 dark:text-white">
                                <tr>
                                    <td colspan="2" class="p-4 text-xs font-black uppercase tracking-widest text-right">SUB-TOTAL ({{ $rka->name }})</td>
                                    <td class="p-4 text-sm font-black font-mono text-right border-l border-white/10 dark:border-gray-700">Rp {{ number_format($totalBudget, 0, ',', '.') }}</td>
                                    <td class="p-4 text-sm font-black font-mono text-right border-l border-white/10 dark:border-gray-700">Rp {{ number_format($totalActual, 0, ',', '.') }}</td>
                                    <td class="p-4 text-sm font-black font-mono text-right border-l border-white/10 dark:border-gray-700">Rp {{ number_format($totalRemaining, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endforeach

            {{-- Summary Card --}}
            <div class="mt-16 bg-gray-50 dark:bg-gray-900/50 rounded-3xl p-10 border border-gray-100 dark:border-gray-800 print:border-2 print:border-gray-900">
                <div class="flex justify-between items-center mb-8 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-black uppercase tracking-tighter dark:text-white">Ringkasan Eksekutif</h3>
                    <div class="flex gap-2">
                        <div class="w-2 h-2 rounded-full bg-primary-500"></div>
                        <div class="w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-700"></div>
                        <div class="w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-700"></div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-gray-900 dark:text-gray-100">
                    @php
                        $grandTotalBudget = $rkaData->sum(fn($r) => $r->items->sum(fn($i) => $i->price * $i->quantity));
                        
                        $grandTotalActual = $rkaData->sum(function($r) use ($startDate, $endDate) {
                            return $r->items->sum(function($i) use ($startDate, $endDate) {
                                return $i->withdrawalItems
                                    ->filter(function ($wItem) use ($startDate, $endDate) {
                                        if (!$wItem->withdrawalRequest || $wItem->withdrawalRequest->status !== 'actualized') {
                                            return false;
                                        }
                                        $date = \Carbon\Carbon::parse($wItem->withdrawalRequest->withdrawal_date);
                                        if ($startDate && $date->lt(\Carbon\Carbon::parse($startDate))) return false;
                                        if ($endDate && $date->gt(\Carbon\Carbon::parse($endDate))) return false;
                                        return true;
                                    })
                                    ->sum('actual_amount');
                            });
                        });
                        
                        $grandTotalRemaining = $rkaData->sum(fn($r) => $r->items->sum(fn($i) => $i->remaining_balance));
                    @endphp
                    <div class="space-y-2">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Anggaran Terencana</span>
                        <p class="text-2xl font-black font-mono dark:text-white">Rp {{ number_format($grandTotalBudget, 0, ',', '.') }}</p>
                    </div>
                    <div class="space-y-2">
                        <span class="text-[10px] font-black text-primary-500 uppercase tracking-widest">Total Realisasi (Aktual)</span>
                        <p class="text-2xl font-black font-mono text-primary-600 dark:text-primary-400">Rp {{ number_format($grandTotalActual, 0, ',', '.') }}</p>
                    </div>
                    <div class="space-y-2 border-l-0 md:border-l-2 border-gray-900 dark:border-gray-700 pl-0 md:pl-8">
                        <span class="text-[10px] font-black text-gray-900 dark:text-white uppercase tracking-widest">Sisa Saldo Kumulatif</span>
                        <p class="text-3xl font-black font-mono dark:text-white underline decoration-primary-500 decoration-4 underline-offset-8">Rp {{ number_format($grandTotalRemaining, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Signature Area --}}
            <div class="mt-24 px-10">
                <table class="w-full">
                    <tr>
                        <td class="w-1/2 text-center align-top">
                            <div class="space-y-24">
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Persetujuan Autoritas</p>
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-tighter italic">Diketahui Oleh,</p>
                                </div>
                                <div class="pt-20">
                                    <p class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-wider border-b-2 border-gray-900 dark:border-gray-600 pb-2 mx-auto inline-block px-8">{{ $ketuaName }}</p>
                                    <p class="text-[9px] text-gray-500 dark:text-gray-400 font-black uppercase tracking-widest mt-3">Ketua Youth Ministry</p>
                                </div>
                            </div>
                        </td>
                        <td class="w-1/2 text-center align-top">
                            <div class="space-y-24">
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Verifikasi Keuangan</p>
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-tighter italic">Dibuat Oleh,</p>
                                </div>
                                <div class="pt-20">
                                    <p class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-wider border-b-2 border-gray-900 dark:border-white pb-2 mx-auto inline-block px-8">{{ $bendaharaName }}</p>
                                    <p class="text-[9px] text-gray-500 dark:text-gray-400 font-black uppercase tracking-widest mt-3">Bendahara Pelaksana</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- Footer Note --}}
            <div class="mt-16 pt-6 border-t border-gray-100 dark:border-gray-800 text-center">
                <p class="text-[8px] text-gray-400 dark:text-gray-600 font-bold uppercase tracking-[0.2em]">Dokumen ini dihasilkan secara otomatis melalui Hive Information System</p>
            </div>
        </div>
    @endif

    <script>
        window.addEventListener('print-report', () => {
            const style = document.createElement('style');
            style.innerHTML = `
                @media print {
                    @page { margin: 15mm; size: A4; }
                    body { background: white !important; color: black !important; -webkit-print-color-adjust: exact; }
                    #report-content { 
                        box-shadow: none !important; 
                        border: none !important; 
                        padding: 0 !important;
                        margin: 0 !important;
                        width: 100% !important;
                    }
                    .fi-header, .fi-sidebar, .fi-topbar, .fi-form, button, .fi-header-actions, .fi-section { 
                        display: none !important; 
                    }
                    tr { page-break-inside: avoid; }
                    .divide-y > * + * { border-top-width: 1px !important; }
                    .bg-gray-900 { background-color: #111827 !important; color: white !important; }
                    .bg-primary-500\\/5 { background-color: rgba(245, 158, 11, 0.05) !important; }
                    .text-primary-600 { color: #d97706 !important; }
                    .rounded-xl, .rounded-2xl, .rounded-3xl { border-radius: 8px !important; }
                }
            `;
            document.head.appendChild(style);
            window.print();
            setTimeout(() => { document.head.removeChild(style); }, 1000);
        });
    </script>
</x-filament-panels::page>
