<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class MonthlyBirthdays extends BaseWidget
{
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 1;
    protected static ?string $heading = "This Month's Birthdays";

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Member::query()
                    ->whereMonth('tanggal_lahir', now()->month)
                    ->orderByRaw('EXTRACT(DAY FROM tanggal_lahir) ASC')
            )
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->label('Tanggal')
                    ->date('d F')
                    ->sortable(),
                Tables\Columns\TextColumn::make('instagram')
                    ->label('Instagram')
                    ->icon('heroicon-m-camera')
                    ->url(fn ($record) => $record->instagram ? "https://instagram.com/" . str_replace('@', '', $record->instagram) : null)
                    ->openUrlInNewTab()
                    ->placeholder('-'),
            ])
            ->recordUrl(
                fn (Member $record): string => \App\Filament\Resources\MemberResource::getUrl('view', ['record' => $record]),
            );
    }
}
