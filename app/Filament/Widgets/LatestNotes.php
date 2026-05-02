<?php

namespace App\Filament\Widgets;

use App\Models\Note;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestNotes extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->query(
                Note::latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Notulensi'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
            ])
            ->recordUrl(
                fn (Note $record): string => \App\Filament\Resources\NoteResource::getUrl('view', ['record' => $record]),
            );
    }
}
