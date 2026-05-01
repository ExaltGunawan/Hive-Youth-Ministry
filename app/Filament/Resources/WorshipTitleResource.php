<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorshipTitleResource\Pages;
use App\Models\WorshipTitle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WorshipTitleResource extends Resource
{
    protected static ?string $model = WorshipTitle::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Worship Title';
    protected static ?string $navigationLabel = 'Judul Ibadah';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('worship_theme_id')
                    ->relationship('theme', 'theme_title')
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('scripture')
                    ->maxLength(255),
                Forms\Components\Textarea::make('background_context')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('objective')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('scripture')
                    ->searchable(),
                Tables\Columns\TextColumn::make('background_context')
                    ->limit(50)
                    ->wrap(),
                Tables\Columns\TextColumn::make('objective')
                    ->limit(50)
                    ->wrap(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('worship_theme_id')
                    ->relationship('theme', 'theme_title'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(fn ($record) => $record->trashed()),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorshipTitles::route('/'),
            'create' => Pages\CreateWorshipTitle::route('/create'),
            'edit' => Pages\EditWorshipTitle::route('/{record}/edit'),
        ];
    }
}
