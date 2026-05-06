<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WithdrawalRequestResource\Pages;
use App\Models\WithdrawalRequest;
use App\Models\Rka;
use App\Models\RkaItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class WithdrawalRequestResource extends Resource
{
    protected static ?string $model = WithdrawalRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Rencana Ambil Dana';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(new HtmlString('&nbsp;&nbsp;&nbsp;Informasi Pengajuan'))
                    ->schema([
                        Forms\Components\Hidden::make('user_id')
                            ->default(Auth::id()),
                        Forms\Components\TextInput::make('user_name')
                            ->label('Nama Pengambil')
                            ->placeholder('Nama Pengambil')
                            ->formatStateUsing(fn ($record) => $record?->user?->name ?? Auth::user()->name)
                            ->disabled(),
                        Forms\Components\DatePicker::make('withdrawal_date')
                            ->label('Rencana Tanggal Pengambilan')
                            ->required()
                            ->default(now()),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'submitted' => 'Submitted',
                                'more_info' => 'Need More Info',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'actualized' => 'Actualized',
                            ])
                            ->default('submitted')
                            ->required()
                            ->disabled(fn () => !Auth::user()->can('update_withdrawal::request'))
                            ->dehydrated(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Keterangan / Tujuan Pengambilan')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(new HtmlString('&nbsp;&nbsp;&nbsp;Rincian Item Anggaran'))
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('rka_id')
                                    ->label('Pilih RKA / Program')
                                    ->options(Rka::all()->pluck('name', 'id')->map(fn ($name) => $name ?? 'Untitled RKA'))
                                    ->live()
                                    ->required()
                                    ->afterStateHydrated(function (Forms\Components\Select $component, $record, $state) {
                                        if ($record && $record->rka_item_id) {
                                            $item = RkaItem::find($record->rka_item_id);
                                            if ($item) $component->state($item->rka_id);
                                        }
                                    }),
                                Forms\Components\Select::make('rka_item_id')
                                    ->label('Pilih Sub-Item')
                                    ->options(fn (Forms\Get $get) => 
                                        RkaItem::where('rka_id', $get('rka_id'))
                                            ->get()
                                            ->mapWithKeys(fn ($item) => [
                                                $item->id => ($item->manual_id ? $item->manual_id . ' - ' : '') . ($item->item_name ?? 'Unnamed Item') . " (Sisa: Rp " . number_format($item->remaining_balance ?? 0, 0, ',', '.') . ")"
                                            ])
                                    )
                                    ->required()
                                    ->live(),
                                Forms\Components\TextInput::make('requested_amount')
                                    ->label('Nominal yang Diambil')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->rules([
                                        fn (Forms\Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                                            $item = RkaItem::find($get('rka_item_id'));
                                            if ($item && $value > $item->remaining_balance) {
                                                $fail("Nominal melebihi sisa dana yang tersedia (Rp " . number_format($item->remaining_balance, 0, ',', '.') . ").");
                                            }
                                        },
                                    ]),
                                Forms\Components\TextInput::make('actual_amount')
                                    ->label('Terpakai Riil')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->visible(fn (Forms\Get $get) => $get('../../status') === 'actualized'),
                                Forms\Components\FileUpload::make('proof_images')
                                    ->label('Bukti / Nota')
                                    ->image()
                                    ->multiple()
                                    ->directory('withdrawal-proofs')
                                    ->downloadable()
                                    ->disabled()
                                    ->visible(fn (Forms\Get $get) => $get('../../status') === 'actualized')
                                    ->columnSpanFull(),
                            ])
                            ->columns(3)
                            ->defaultItems(1),
                    ]),

                Forms\Components\Section::make('Diskusi / Keterangan Lebih Lanjut')
                    ->hidden(fn ($record) => !$record || $record->status !== 'more_info')
                    ->schema([
                        Forms\Components\ViewField::make('discussion_history')
                            ->view('filament.resources.withdrawal.discussion'),
                        Forms\Components\Textarea::make('new_comment')
                            ->label('Balas / Tambah Keterangan')
                            ->placeholder('Tulis balasan Anda di sini...')
                            ->dehydrated(false)
                            ->saveRelationshipsUsing(function ($record, $state) {
                                if ($state) {
                                    $record->comments()->create([
                                        'user_id' => Auth::id(),
                                        'comment' => $state,
                                    ]);
                                }
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengaju')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.divisi.nama_divisi')
                    ->label('Divisi')
                    ->sortable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('withdrawal_date')
                    ->label('Tgl Ambil')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'submitted' => 'gray',
                        'more_info' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'actualized' => 'primary',
                    }),
                Tables\Columns\TextColumn::make('items_sum_requested_amount')
                    ->label('Dana Diminta')
                    ->money('IDR')
                    ->sum('items', 'requested_amount')
                    ->color('warning')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('items_sum_actual_amount')
                    ->label('Dana Terpakai')
                    ->sum('items', 'actual_amount')
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->status !== 'actualized') {
                            return '-';
                        }
                        return 'Rp ' . number_format((float)$state, 0, ',', '.');
                    })
                    ->color(fn ($record) => $record->status === 'actualized' ? 'success' : 'gray')
                    ->weight('bold'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Pengajuan')
                    ->options([
                        'submitted' => 'Submitted (Menunggu Persetujuan)',
                        'more_info' => 'Need More Info (Perlu Diskusi)',
                        'approved' => 'Approved (Disetujui / Uang Cair)',
                        'rejected' => 'Rejected (Ditolak)',
                        'actualized' => 'Actualized (Selesai & Dilaporkan)',
                    ]),
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Pengaju')
                    ->relationship('user', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->name ?? $record->email ?? 'Unknown User')
                    ->searchable()
                    ->preload()
                    ->visible(fn () => Auth::user()->hasRole(['super_admin', 'bendahara'])),
                Tables\Filters\Filter::make('withdrawal_date')
                    ->form([
                        Forms\Components\DatePicker::make('date_from')->label('Tgl Ambil (Dari)'),
                        Forms\Components\DatePicker::make('date_until')->label('Tgl Ambil (Sampai)'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('withdrawal_date', '>=', $date),
                            )
                            ->when(
                                $data['date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('withdrawal_date', '<=', $date),
                            );
                    }),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('actualize')
                    ->label('Aktualisasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('primary')
                    ->modalHeading('Aktualisasi Penggunaan Dana')
                    ->modalDescription('Masukkan nominal riil yang terpakai untuk setiap item dan unggah bukti/nota pembayaran.')
                    ->modalSubmitActionLabel('Simpan Aktualisasi')
                    ->visible(fn ($record) => $record->status === 'approved' && ($record->user_id === Auth::id() || Auth::user()->hasRole(['super_admin', 'bendahara'])))
                    ->form(function ($record) {
                        $fields = [];
                        foreach ($record->items as $item) {
                            $rkaItemName = $item->rkaItem ? $item->rkaItem->item_name : 'Item';
                            $requested = 'Rp ' . number_format($item->requested_amount, 0, ',', '.');
                            
                            $fields[] = Forms\Components\Section::make("{$rkaItemName} (Diminta: {$requested})")
                                ->schema([
                                    Forms\Components\TextInput::make("items.{$item->id}.actual_amount")
                                        ->label('Nominal Terpakai (Riil)')
                                        ->numeric()
                                        ->prefix('Rp')
                                        ->required()
                                        ->default($item->requested_amount),
                                    Forms\Components\FileUpload::make("items.{$item->id}.proof_images")
                                        ->label('Foto Bukti / Nota (Bisa lebih dari 1)')
                                        ->image()
                                        ->multiple()
                                        ->directory('withdrawal-proofs')
                                        ->downloadable()
                                        ->getUploadedFileNameForStorageUsing(
                                            fn (\Illuminate\Http\UploadedFile $file): string => 
                                                'nota-req_' . $record->id . '-item_' . $item->id . '-' . time() . '-' . str()->slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension()
                                        )
                                        ->required(),
                                ]);
                        }
                        return $fields;
                    })
                    ->action(function ($record, array $data) {
                        foreach ($record->items as $item) {
                            $itemData = $data['items'][$item->id] ?? null;
                            if ($itemData) {
                                $item->update([
                                    'actual_amount' => $itemData['actual_amount'],
                                    'proof_images' => $itemData['proof_images'] ?? null,
                                ]);
                            }
                        }
                        $record->update(['status' => 'actualized']);
                        Notification::make()
                            ->title('Aktualisasi Berhasil')
                            ->body('Dana telah diperbarui dan sisa RKA telah disesuaikan.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListWithdrawalRequests::route('/'),
            'create' => Pages\CreateWithdrawalRequest::route('/create'),
            'edit' => Pages\EditWithdrawalRequest::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (!Auth::user()->can('update_withdrawal::request') && !Auth::user()->hasRole('super_admin')) {
            $query->where('user_id', Auth::id());
        }

        return $query;
    }
}

