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
        <div id="report-content" class="bg-white p-12 text-black font-serif leading-relaxed" style="color: black !important;">
            {{-- Professional Header --}}
            <div class="border-b-4 border-black pb-6 mb-8 text-center">
                <h1 class="text-3xl font-bold uppercase tracking-widest">HIVE YOUTH MINISTRY</h1>
                <p class="text-sm font-medium mt-1 uppercase tracking-widest">Gereja Kristen Indonesia Cimahi - Youth Team</p>
                <div class="mt-4 flex justify-center items-center gap-4 text-xs font-bold uppercase">
                    <span class="border-y border-black py-1 px-4">Laporan Pertanggungjawaban Anggaran</span>
                </div>
            </div>

            <div class="flex justify-between items-end mb-10 text-xs font-bold uppercase">
                <div>
                    <p>Periode: 
                        <span class="ml-2">
                            @if($startDate && $endDate)
                                {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                            @else
                                Seluruh Periode
                            @endif
                        </span>
                    </p>
                    <p class="mt-1 text-slate-500 italic">No. Dokumen: HYM/LPJ/{{ now()->format('Y/m/d') }}/{{ str()->random(4) }}</p>
                </div>
                <div class="text-right">
                    <p>Tanggal Cetak: {{ now()->format('d F Y') }}</p>
                    <p class="mt-1">Halaman: 1 dari 1</p>
                </div>
            </div>

            @foreach($rkaData as $rka)
                <div class="mb-10 last:mb-0">
                    <div class="bg-slate-100 p-2 mb-3 border-l-4 border-black">
                        <h2 class="text-sm font-bold uppercase tracking-wider">{{ $rka->name }}</h2>
                    </div>

                    <table class="w-full text-xs border-collapse mb-4">
                        <thead>
                            <tr class="border-y-2 border-black">
                                <th class="py-3 px-2 text-left w-24">KODE</th>
                                <th class="py-3 px-2 text-left">DESKRIPSI KEGIATAN</th>
                                <th class="py-3 px-2 text-right w-32">ANGGARAN (IDR)</th>
                                <th class="py-3 px-2 text-right w-32">REALISASI (IDR)</th>
                                <th class="py-3 px-2 text-right w-32">SELISIH/SISA</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700">
                            @php 
                                $totalBudget = 0; $totalActual = 0; $totalRemaining = 0;
                            @endphp
                            @foreach($rka->items as $item)
                                @php
                                    $budget = (float)($item->price * $item->quantity);
                                    $filteredWithdrawalItems = $item->withdrawalItems->filter(function ($wItem) use ($startDate, $endDate) {
                                        if (!$wItem->withdrawalRequest || $wItem->withdrawalRequest->status !== 'actualized') return false;
                                        $date = \Carbon\Carbon::parse($wItem->withdrawalRequest->withdrawal_date);
                                        if ($startDate && $date->lt(\Carbon\Carbon::parse($startDate))) return false;
                                        if ($endDate && $date->gt(\Carbon\Carbon::parse($endDate))) return false;
                                        return true;
                                    });
                                    $actual = $filteredWithdrawalItems->sum('actual_amount');
                                    $remaining = $item->remaining_balance;
                                    $totalBudget += $budget; $totalActual += $actual; $totalRemaining += $remaining;
                                @endphp
                                <tr class="border-b border-slate-300 border-dashed" style="border-bottom-style: dashed !important; border-bottom-width: 1px !important;">
                                    <td class="py-2 px-2 font-mono text-slate-500 text-[10px]">{{ $item->manual_id ?? '-' }}</td>
                                    <td class="py-2 px-2 font-medium text-[11px]">{{ $item->item_name }}</td>
                                    <td class="py-2 px-2 text-right font-mono text-[11px]">{{ number_format($budget, 0, ',', '.') }}</td>
                                    <td class="py-2 px-2 text-right font-mono text-[11px]">{{ number_format($actual, 0, ',', '.') }}</td>
                                    <td class="py-2 px-2 text-right font-mono text-[11px]">{{ number_format($remaining, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-black font-bold bg-slate-50">
                                <td colspan="2" class="py-3 px-2 text-right uppercase">Total {{ $rka->name }}</td>
                                <td class="py-3 px-2 text-right font-mono">{{ number_format($totalBudget, 0, ',', '.') }}</td>
                                <td class="py-3 px-2 text-right font-mono">{{ number_format($totalActual, 0, ',', '.') }}</td>
                                <td class="py-3 px-2 text-right font-mono">{{ number_format($totalRemaining, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endforeach

            {{-- Summary Formal --}}
            <div class="mt-12 border-2 border-black p-6">
                <h3 class="text-sm font-bold uppercase mb-4 border-b border-black pb-2">Ringkasan Kumulatif Anggaran</h3>
                @php
                    $grandTotalBudget = $rkaData->sum(fn($r) => $r->items->sum(fn($i) => $i->price * $i->quantity));
                    $grandTotalActual = $rkaData->sum(function($r) use ($startDate, $endDate) {
                        return $r->items->sum(function($i) use ($startDate, $endDate) {
                            return $i->withdrawalItems->filter(function ($wItem) use ($startDate, $endDate) {
                                if (!$wItem->withdrawalRequest || $wItem->withdrawalRequest->status !== 'actualized') return false;
                                $date = \Carbon\Carbon::parse($wItem->withdrawalRequest->withdrawal_date);
                                if ($startDate && $date->lt(\Carbon\Carbon::parse($startDate))) return false;
                                if ($endDate && $date->gt(\Carbon\Carbon::parse($endDate))) return false;
                                return true;
                            })->sum('actual_amount');
                        });
                    });
                    $grandTotalRemaining = $rkaData->sum(fn($r) => $r->items->sum(fn($i) => $i->remaining_balance));
                @endphp
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between border-b border-dotted border-slate-300 pb-1">
                        <span>Total Anggaran Disetujui (Budgeted)</span>
                        <span class="font-mono font-bold">Rp {{ number_format($grandTotalBudget, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between border-b border-dotted border-slate-300 pb-1">
                        <span>Total Penyerapan Dana (Actualized)</span>
                        <span class="font-mono font-bold">Rp {{ number_format($grandTotalActual, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between pt-2">
                        <span class="font-bold uppercase">Sisa Dana Tersedia (Remaining)</span>
                        <span class="font-mono font-bold text-lg decoration-double underline">Rp {{ number_format($grandTotalRemaining, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Signature Formal --}}
            <div class="mt-20">
                <table class="w-full text-xs font-bold uppercase">
                    <tr>
                                <td class="w-1/2 text-center align-top px-10">
                                    <p class="mb-4">Mengetahui/Menyetujui,</p>
                                    <div class="h-32"></div> {{-- Even larger space for physical signature --}}
                                    <p class="underline decoration-2 underline-offset-4">{{ $ketuaName }}</p>
                                    <p class="mt-1 font-medium italic">Ketua Youth Ministry</p>
                                </td>
                                <td class="w-1/2 text-center align-top px-10">
                                    <p class="mb-4">Dibuat Oleh,</p>
                                    <div class="h-32"></div> {{-- Even larger space for physical signature --}}
                                    <p class="underline decoration-2 underline-offset-4">{{ $bendaharaName }}</p>
                                    <p class="mt-1 font-medium italic">Bendahara Pelaksana</p>
                                </td>
                    </tr>
                </table>
            </div>

            {{-- Formal Footer --}}
            <div class="mt-24 pt-4 border-t border-slate-300 text-center italic text-[9px] text-slate-500">
                Laporan ini merupakan dokumen resmi Hive Youth Ministry yang dihasilkan secara sistematis.
            </div>
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
