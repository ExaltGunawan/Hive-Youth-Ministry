<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RkaResource\Pages;
use App\Models\Rka;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RkaResource extends Resource
{
    protected static ?string $model = Rka::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Rencana Anggaran (RKA)';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('year')
                            ->numeric()
                            ->required()
                            ->default(date('Y')),
                        Forms\Components\Select::make('month')
                            ->options([
                                'Januari' => 'Januari',
                                'Februari' => 'Februari',
                                'Maret' => 'Maret',
                                'April' => 'April',
                                'Mei' => 'Mei',
                                'Juni' => 'Juni',
                                'Juli' => 'Juli',
                                'Agustus' => 'Agustus',
                                'September' => 'September',
                                'Oktober' => 'Oktober',
                                'November' => 'November',
                                'Desember' => 'Desember',
                            ])
                            ->required()
                            ->searchable(),
                    ]),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\Hidden::make('created_by')
                    ->default(auth()->id()),

                Forms\Components\Section::make('RKA Details')
                    ->schema([
                        Forms\Components\Repeater::make('details')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('id')
                                    ->label('ID Detail')
                                    ->numeric()
                                    ->placeholder('Input ID...')
                                    ->helperText('Masukkan ID secara manual jika diperlukan'),
                                Forms\Components\TextInput::make('item_name')
                                    ->required(),
                                Forms\Components\TextInput::make('amount')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->live(onBlur: true),
                                Forms\Components\Textarea::make('notes')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                        
                        Forms\Components\Placeholder::make('total_amount_placeholder')
                            ->label('')
                            ->content(function ($get) {
                                $total = collect($get('details'))
                                    ->pluck('amount')
                                    ->sum();
                                
                                $formattedTotal = 'Rp ' . number_format($total, 0, ',', '.');
                                
                                return new \Illuminate\Support\HtmlString("
                                    <div class='flex items-center justify-between p-4 bg-primary-500/10 border border-primary-500/20 rounded-xl'>
                                        <span class='text-sm font-bold text-gray-400 uppercase tracking-wider'>Total Anggaran Terakumulasi:</span>
                                        <span class='text-2xl font-black text-primary-500'>{$formattedTotal}</span>
                                    </div>
                                ");
                            })
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('year')
                    ->sortable(),
                Tables\Columns\TextColumn::make('month')
                    ->sortable(),
                Tables\Columns\TextColumn::make('details_sum_amount')
                    ->sum('details', 'amount')
                    ->label('Total Anggaran')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('details_sum_balance')
                    ->sum('details', 'balance')
                    ->label('Sisa Anggaran')
                    ->money('IDR')
                    ->sortable()
                    ->color('success'),
                Tables\Columns\TextColumn::make('creator.email')
                    ->label('Created By'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordUrl(
                fn (Rka $record): string => Pages\ViewRka::getUrl([$record->id]),
            )
            ->filters([
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
            'index' => Pages\ListRkas::route('/'),
            'create' => Pages\CreateRka::route('/create'),
            'view' => Pages\ViewRka::route('/{record}'),
            'edit' => Pages\EditRka::route('/{record}/edit'),
        ];
    }
}
