<?php

namespace App\Filament\Resources;

use Z3d0X\FilamentLogger\Resources\ActivityResource as BaseActivityResource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Filament\Resources\ActivityResource\Pages;

class ActivityResource extends BaseActivityResource
{
    protected static ?string $navigationGroup = 'Pengaturan';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('log_name')
                    ->badge()
                    ->colors(static::getLogNameColors())
                    ->label(__('filament-logger::filament-logger.resource.label.type'))
                    ->formatStateUsing(fn ($state) => ucwords($state))
                    ->sortable(),

                TextColumn::make('event')
                    ->label(__('filament-logger::filament-logger.resource.label.event'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('description')
                    ->label(__('filament-logger::filament-logger.resource.label.description'))
                    ->wrap()
                    ->searchable(),

                TextColumn::make('subject_type')
                    ->label(__('filament-logger::filament-logger.resource.label.subject'))
                    ->formatStateUsing(function ($state, Model $record) {
                        if (!$state) {
                            return '-';
                        }
                        return Str::of($state)->afterLast('\\')->headline().' # '.$record->subject_id;
                    })
                    ->searchable(),

                TextColumn::make('causer.name')
                    ->label(__('filament-logger::filament-logger.resource.label.user'))
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label(__('filament-logger::filament-logger.resource.label.logged_at'))
                    ->dateTime(config('filament-logger.datetime_format', 'd/m/Y H:i:s'), config('app.timezone'))
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('log_name')
                    ->label(__('filament-logger::filament-logger.resource.label.type'))
                    ->options(static::getLogNameList()),

                \Filament\Tables\Filters\SelectFilter::make('subject_type')
                    ->label(__('filament-logger::filament-logger.resource.label.subject_type'))
                    ->options(static::getSubjectTypeList()),

                \Filament\Tables\Filters\Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('logged_at')
                            ->label(__('filament-logger::filament-logger.resource.label.logged_at'))
                            ->displayFormat(config('filament-logger.date_format', 'd/m/Y')),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query
                            ->when(
                                $data['logged_at'],
                                fn (\Illuminate\Database\Eloquent\Builder $query, $date): \Illuminate\Database\Eloquent\Builder => $query->whereDate('created_at', $date),
                            );
                    }),
                
                \Filament\Tables\Filters\Filter::make('properties->old')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('old')
                            ->label(__('filament-logger::filament-logger.resource.label.old')),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        if (!$data['old']) return $query;
                        return $query->where('properties->old', 'like', "%{$data['old']}%");
                    }),

                \Filament\Tables\Filters\Filter::make('properties->attributes')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('new')
                            ->label(__('filament-logger::filament-logger.resource.label.new')),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        if (!$data['new']) return $query;
                        return $query->where('properties->attributes', 'like', "%{$data['new']}%");
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'view' => Pages\ViewActivity::route('/{record}'),
        ];
    }
}
