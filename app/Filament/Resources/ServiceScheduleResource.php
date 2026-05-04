<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceScheduleResource\Pages;
use App\Models\ServiceSchedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceScheduleResource extends Resource
{
    protected static ?string $model = ServiceSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Schedules';
    protected static ?string $navigationLabel = 'Service Schedule';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('worship_title_id')
                    ->relationship('worshipTitle', 'title')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                        if ($state) {
                            $worshipTitle = \App\Models\WorshipTitle::find($state);
                            if ($worshipTitle) {
                                $set('tanggal', $worshipTitle->date->format('Y-m-d'));
                            }
                        }
                    }),
                Forms\Components\DatePicker::make('tanggal')
                    ->required(),
                
                Forms\Components\Select::make('pic_id')
                    ->label('PIC (Penanggung Jawab)')
                    ->relationship('pic', 'nama_lengkap')
                    ->searchable()
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('keterangan')
                    ->columnSpanFull(),
                
                Forms\Components\Section::make('Assignments')
                    ->schema([
                        Forms\Components\Repeater::make('assignments')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('member_id')
                                    ->relationship('member', 'nama_lengkap')
                                    ->required()
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(fn (Forms\Set $set) => $set('kontak', null)),
                                
                                Forms\Components\Placeholder::make('kontak')
                                    ->label('Hubungi Member')
                                    ->content(function ($get) {
                                        $memberId = $get('member_id');
                                        if (!$memberId) return '-';
                                        
                                        $member = \App\Models\Member::find($memberId);
                                        if (!$member || !$member->kontak) return 'No telp tidak tersedia';
                                        
                                        $waNumber = preg_replace('/[^0-9]/', '', $member->kontak);
                                        if (str_starts_with($waNumber, '0')) {
                                            $waNumber = '62' . substr($waNumber, 1);
                                        }
                                        
                                        return new \Illuminate\Support\HtmlString("
                                            <a href='https://wa.me/{$waNumber}' target='_blank' class='flex items-center gap-2 text-primary-600 hover:text-primary-500 font-bold underline'>
                                                <x-heroicon-m-phone class='w-4 h-4'/>
                                                {$member->kontak}
                                            </a>
                                        ");
                                    }),

                                Forms\Components\Select::make('service_role_id')
                                    ->relationship('serviceRole', 'role_name')
                                    ->required(),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('worshipTitle.title')
                    ->label('Worship Title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pic.nama_lengkap')
                    ->label('PIC')
                    ->badge()
                    ->color('primary')
                    ->searchable(),
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
            'index' => Pages\ListServiceSchedules::route('/'),
            'create' => Pages\CreateServiceSchedule::route('/create'),
            'edit' => Pages\EditServiceSchedule::route('/{record}/edit'),
        ];
    }
}
