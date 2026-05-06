<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RkaResource\Pages;
use App\Models\Rka;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class RkaResource extends Resource
{
    protected static ?string $model = Rka::class;
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Rencana Anggaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(new HtmlString('&nbsp;&nbsp;&nbsp;General Information'))
                    ->schema([
                        Forms\Components\TextInput::make('id')
                            ->label('ID / Kode Anggaran')
                            ->placeholder('e.g. 1 / A.1 / 2024-001')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Program / Pembelian')
                            ->placeholder('e.g. Sunday Run / Beli Kamera')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('fiscal_year')
                            ->label('Tahun Fiskal')
                            ->numeric()
                            ->required()
                            ->default(date('Y')),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Singkat')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(new HtmlString('&nbsp;&nbsp;&nbsp;Rincian Barang / Detail Anggaran'))
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->minItems(1)
                            ->schema([
                                Forms\Components\TextInput::make('manual_id')
                                    ->label('ID Detail')
                                    ->placeholder('e.g. 1.14.45')
                                    ->maxLength(50),
                                Forms\Components\TextInput::make('item_name')
                                    ->label('Nama Barang / Detail')
                                    ->required()
                                    ->placeholder('e.g. Minum / Topi'),
                                Forms\Components\TextInput::make('price')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->live(onBlur: true),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->live(onBlur: true),
                                Forms\Components\Placeholder::make('sub_total')
                                    ->label('Sub-Total')
                                    ->content(function ($get) {
                                        $price = (float)($get('price') ?? 0);
                                        $qty = (int)($get('quantity') ?? 1);
                                        return 'Rp ' . number_format($price * $qty, 0, ',', '.');
                                    })
                                    ->extraAttributes(['class' => 'font-bold text-primary-600']),
                                Forms\Components\TextInput::make('notes')
                                    ->label('Keterangan')
                                    ->placeholder('e.g. Merk Aqua')
                                    ->columnSpanFull(),
                            ])
                            ->columns(5)
                            ->itemLabel(fn (array $state): string => ($state['manual_id'] ? $state['manual_id'] . ' - ' : '') . ($state['item_name'] ?? '')),
                        
                        Forms\Components\Placeholder::make('total_amount_placeholder')
                            ->label('')
                            ->content(function ($get) {
                                $items = collect($get('items') ?? []);
                                $total = $items->sum(fn ($item) => (float)($item['price'] ?? 0) * (int)($item['quantity'] ?? 1));
                                
                                $formattedTotal = 'Rp ' . number_format($total, 0, ',', '.');
                                
                                return new HtmlString("
                                    <div class='flex items-center justify-between p-4 bg-primary-500/10 border border-primary-500/20 rounded-xl'>
                                        <span class='text-sm font-bold text-gray-400 uppercase tracking-wider'>Total Anggaran Program:</span>
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
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\TextColumn::make('id')
                        ->label('ID')
                        ->sortable(),
                    Tables\Columns\TextColumn::make('name')
                        ->label('Nama Program')
                        ->sortable()
                        ->description(fn (Rka $record): string => $record->description ? str($record->description)->limit(40) : ''),
                    Tables\Columns\TextColumn::make('items_sum_total')
                        ->label('Total Anggaran')
                        ->money('IDR')
                        ->getStateUsing(function (Rka $record) {
                            return $record->items->sum(fn ($item) => $item->price * $item->quantity);
                        })
                        ->sortable(false)
                        ->color('gray'),
                    Tables\Columns\TextColumn::make('remaining_balance')
                        ->label('Sisa Dana')
                        ->money('IDR')
                        ->getStateUsing(function (Rka $record) {
                            return $record->items->sum(fn ($item) => $item->remaining_balance);
                        })
                        ->sortable(false)
                        ->weight('bold')
                        ->color('primary')
                        ->alignEnd(),
                ]),
                Tables\Columns\Layout\Panel::make([
                    Tables\Columns\Layout\View::make('filament.resources.rka.row-content'),
                ])->collapsible(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->button()
                    ->size('sm'),
                Tables\Actions\DeleteAction::make()
                    ->button()
                    ->size('sm'),
            ])
            ->actionsColumnLabel('Aksi');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRkas::route('/'),
            'create' => Pages\CreateRka::route('/create'),
            'edit' => Pages\EditRka::route('/{record}/edit'),
        ];
    }
}

