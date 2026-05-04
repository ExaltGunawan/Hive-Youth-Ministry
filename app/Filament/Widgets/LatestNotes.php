<?php

namespace App\Filament\Widgets;

use App\Models\Note;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestNotes extends BaseWidget
{
    protected static ?int $sort = 10;
    protected int | string | array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->query(
                Note::latest()->limit(3)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->limit(15),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl')
                    ->date('d/m'),
            ])
            ->recordUrl(
                fn (Note $record): string => \App\Filament\Resources\NoteResource::getUrl('view', ['record' => $record]),
            );
    }
}
