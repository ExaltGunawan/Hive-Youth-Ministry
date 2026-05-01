<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Schedules';
    protected static ?string $navigationLabel = 'Kegiatan Youth';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('divisi_id')
                    ->relationship('divisi', 'nama_divisi')
                    ->required(),
                Forms\Components\TextInput::make('schedule_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('sub_schedule')
                    ->maxLength(255),
                Forms\Components\TextInput::make('tempat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('sub_tempat')
                    ->maxLength(255),
                Forms\Components\TimePicker::make('jam')
                    ->required()
                    ->seconds(false)
                    ->displayFormat('H:i'),
                Forms\Components\DatePicker::make('tanggal')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('divisi.nama_divisi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('schedule_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam')
                    ->time(),
                Tables\Columns\TextColumn::make('tempat')
                    ->searchable(),
            ])
            ->recordUrl(
                fn (Schedule $record): string => Pages\ViewSchedule::getUrl([$record->id]),
            )
            ->filters([
                Tables\Filters\SelectFilter::make('divisi_id')
                    ->relationship('divisi', 'nama_divisi'),
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
            'index' => Pages\ScheduleCalendar::route('/'),
            'list' => Pages\ListSchedules::route('/list'),
            'create' => Pages\CreateSchedule::route('/create'),
            'view' => Pages\ViewSchedule::route('/{record}'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
