<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorshipThemeResource\Pages;
use App\Models\WorshipTheme;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WorshipThemeResource extends Resource
{
    protected static ?string $model = WorshipTheme::class;
    protected static ?string $recordTitleAttribute = 'theme_title';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Worship Title';
    protected static ?string $navigationLabel = 'Tema Bulanan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('month')
                    ->required()
                    ->format('Y-m')
                    ->displayFormat('F Y'),
                Forms\Components\TextInput::make('theme_title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('month')
                    ->date('F Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('theme_title'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
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
            'index' => Pages\ListWorshipThemes::route('/'),
            'create' => Pages\CreateWorshipTheme::route('/create'),
            'edit' => Pages\EditWorshipTheme::route('/{record}/edit'),
        ];
    }
}

