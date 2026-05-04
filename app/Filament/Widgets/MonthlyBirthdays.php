<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class MonthlyBirthdays extends BaseWidget
{
    protected static ?int $sort = 12;
    protected int | string | array $columnSpan = 1;
    protected static ?string $heading = "Birthdays";

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Member::query()
                    ->whereMonth('tanggal_lahir', now()->month)
                    ->orderByRaw('EXTRACT(DAY FROM tanggal_lahir) ASC')
                    ->limit(3)
            )
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama')
                    ->limit(15),
                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->label('Tgl')
                    ->date('d/m'),
            ])
            ->recordUrl(
                fn (Member $record): string => \App\Filament\Resources\MemberResource::getUrl('view', ['record' => $record]),
            );
    }
}
