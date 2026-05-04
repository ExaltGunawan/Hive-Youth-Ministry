<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenditureReportResource\Pages;
use App\Filament\Resources\ExpenditureReportResource\RelationManagers;
use App\Models\ExpenditureReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpenditureReportResource extends Resource
{
    protected static ?string $model = ExpenditureReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Laporan Pengeluaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pengambilan Dana')
                    ->schema([
                        Forms\Components\Select::make('withdrawal_schedule_id')
                            ->label('Data Pengambilan Uang')
                            ->relationship('withdrawalSchedule', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "ID: {$record->id} - {$record->pengambil?->name} ({$record->rkaDetail?->item_name})")
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $withdrawal = \App\Models\WithdrawalSchedule::find($state);
                                    if ($withdrawal) {
                                        $set('month', $withdrawal->created_at->format('F'));
                                        $set('year', $withdrawal->created_at->year);
                                        $set('taken_amount_info', $withdrawal->jumlah_diambil);
                                    }
                                }
                            }),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('month')
                                    ->label('Bulan')
                                    ->readonly()
                                    ->dehydrated(),
                                Forms\Components\TextInput::make('year')
                                    ->label('Tahun')
                                    ->numeric()
                                    ->readonly()
                                    ->dehydrated(),
                            ]),
                        
                        Forms\Components\Placeholder::make('taken_amount_info_placeholder')
                            ->label('Dana yang Diambil')
                            ->content(function ($get) {
                                $amount = $get('taken_amount_info');
                                return $amount ? 'Rp ' . number_format($amount, 0, ',', '.') : '-';
                            }),
                    ]),

                Forms\Components\Section::make('Laporan Penggunaan Aktual')
                    ->schema([
                        Forms\Components\TextInput::make('actual_amount')
                            ->label('Jumlah Pengeluaran Riil')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->live(onBlur: true),
                        
                        Forms\Components\FileUpload::make('proof_image')
                            ->label('Foto Bukti/Struk')
                            ->image()
                            ->directory('expenditure-proofs')
                            ->required(),
                        
                        Forms\Components\Textarea::make('notes')
                            ->label('Keterangan Tambahan')
                            ->columnSpanFull(),
                        
                        Forms\Components\Hidden::make('created_by')
                            ->default(auth()->id()),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('month')
                    ->label('Bulan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('withdrawalSchedule.pengambil.name')
                    ->label('Pengambil')
                    ->searchable(),
                Tables\Columns\TextColumn::make('withdrawalSchedule.rkaDetail.item_name')
                    ->label('Item RKA'),
                Tables\Columns\TextColumn::make('withdrawalSchedule.jumlah_diambil')
                    ->label('Dana Diambil')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('actual_amount')
                    ->label('Pengeluaran Riil')
                    ->money('IDR')
                    ->sortable()
                    ->color('warning'),
                Tables\Columns\ImageColumn::make('proof_image')
                    ->label('Bukti Struk')
                    ->placeholder('Belum ada bukti')
                    ->circular(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Lapor')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('month')
                    ->options([
                        'January' => 'Januari',
                        'February' => 'Februari',
                        'March' => 'Maret',
                        'April' => 'April',
                        'May' => 'Mei',
                        'June' => 'Juni',
                        'July' => 'Juli',
                        'August' => 'Agustus',
                        'September' => 'September',
                        'October' => 'Oktober',
                        'November' => 'November',
                        'December' => 'Desember',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpenditureReports::route('/'),
            'create' => Pages\CreateExpenditureReport::route('/create'),
            'edit' => Pages\EditExpenditureReport::route('/{record}/edit'),
        ];
    }
}
