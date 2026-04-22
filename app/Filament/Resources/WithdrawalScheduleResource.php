<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WithdrawalScheduleResource\Pages;
use App\Models\WithdrawalSchedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WithdrawalScheduleResource extends Resource
{
    protected static ?string $model = WithdrawalSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Jadwal Ambil Uang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pengambil_id')
                    ->relationship('pengambil', 'email')
                    ->required()
                    ->label('Pengambil'),
                Forms\Components\Select::make('rka_detail_id')
                    ->relationship('rkaDetail', 'item_name')
                    ->required()
                    ->label('Item RKA'),
                Forms\Components\TextInput::make('jumlah_diambil')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'requested' => 'Requested',
                        'verified' => 'Verified',
                        'completed' => 'Completed',
                    ])
                    ->required()
                    ->default('requested'),
                Forms\Components\Select::make('bendahara_id')
                    ->relationship('bendahara', 'email')
                    ->label('Bendahara (Verifier)'),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pengambil.email')
                    ->label('Pengambil')
                    ->sortable(),
                Tables\Columns\TextColumn::make('rkaDetail.item_name')
                    ->label('Item RKA')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_diambil')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'requested' => 'warning',
                        'verified' => 'info',
                        'completed' => 'success',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordUrl(
                fn (WithdrawalSchedule $record): string => Pages\ViewWithdrawalSchedule::getUrl([$record->id]),
            )
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'requested' => 'Requested',
                        'verified' => 'Verified',
                        'completed' => 'Completed',
                    ]),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListWithdrawalSchedules::route('/'),
            'create' => Pages\CreateWithdrawalSchedule::route('/create'),
            'view' => Pages\ViewWithdrawalSchedule::route('/{record}'),
            'edit' => Pages\EditWithdrawalSchedule::route('/{record}/edit'),
        ];
    }
}
