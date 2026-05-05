<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class MonthlyBirthdays extends BaseWidget
{
    protected static ?int $sort = 13;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = "Birthdays";

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Member::query()
                    ->whereMonth('tanggal_lahir', now()->month)
                    ->orderByRaw('EXTRACT(DAY FROM tanggal_lahir) ASC')
                    ->limit(5)
            )
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->label('Tgl')
                    ->date('d M')
                    ->weight('bold')
                    ->description(fn (Member $record): string => 
                        $record->tanggal_lahir ? 'Ke-' . (now()->year - \Carbon\Carbon::parse($record->tanggal_lahir)->year) : '-'
                    )
                    ->color('primary'),
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Member')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('instagram')
                    ->label('Instagram')
                    ->icon('heroicon-m-camera')
                    ->formatStateUsing(fn (string $state): string => '@' . ltrim($state, '@'))
                    ->url(fn (Member $record): ?string => $record->instagram ? 'https://instagram.com/' . ltrim($record->instagram, '@') : null)
                    ->openUrlInNewTab()
                    ->color('info')
                    ->placeholder('-'),
            ])
            ->recordUrl(
                fn (Member $record): string => \App\Filament\Resources\MemberResource::getUrl('view', ['record' => $record]),
            );
    }
}
