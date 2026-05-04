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
                    ->label('Pengambil')
                    ->live()
                    ->afterStateUpdated(fn (Forms\Set $set) => $set('divisi_name', null)),
                
                Forms\Components\Placeholder::make('divisi_name')
                    ->label('Divisi Pengambil')
                    ->content(function ($get) {
                        $pengambilId = $get('pengambil_id');
                        if (!$pengambilId) return '-';
                        
                        $user = \App\Models\User::find($pengambilId);
                        return $user?->divisi?->nama_divisi ?? 'Tidak ada divisi';
                    }),

                Forms\Components\Select::make('rka_detail_id')
                    ->relationship('rkaDetail', 'item_name')
                    ->required()
                    ->label('Item RKA')
                    ->live()
                    ->afterStateUpdated(fn (Forms\Set $set) => $set('balance_info', null)),

                Forms\Components\Placeholder::make('balance_info')
                    ->label('Sisa Saldo Item RKA')
                    ->content(function ($get) {
                        $rkaDetailId = $get('rka_detail_id');
                        if (!$rkaDetailId) return '-';
                        
                        $rkaDetail = \App\Models\RkaDetail::find($rkaDetailId);
                        if (!$rkaDetail) return 'Data tidak ditemukan';
                        
                        return 'Rp ' . number_format($rkaDetail->balance, 0, ',', '.');
                    }),

                Forms\Components\TextInput::make('jumlah_diambil')
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->rules([
                        fn ($get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                            $rkaDetailId = $get('rka_detail_id');
                            if (!$rkaDetailId) return;

                            $rkaDetail = \App\Models\RkaDetail::find($rkaDetailId);
                            if ($rkaDetail && $value > $rkaDetail->balance) {
                                $fail("Jumlah yang diambil (Rp " . number_format($value, 0, ',', '.') . ") melebihi sisa saldo yang tersedia (Rp " . number_format($rkaDetail->balance, 0, ',', '.') . ").");
                            }
                        },
                    ]),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pengambil.name')
                    ->label('Pengambil')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('pengambil.divisi.nama_divisi')
                    ->label('Divisi')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('rkaDetail.item_name')
                    ->label('Item RKA')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah_diambil')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'submitted' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Input')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordUrl(
                fn (WithdrawalSchedule $record): string => Pages\ViewWithdrawalSchedule::getUrl([$record->id]),
            )
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'submitted' => 'Submitted',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->hidden(fn (WithdrawalSchedule $record) => $record->status !== 'submitted' || !auth()->user()->hasRole('super_admin'))
                    ->requiresConfirmation()
                    ->action(function (WithdrawalSchedule $record) {
                        $rkaDetail = $record->rkaDetail;
                        
                        if ($rkaDetail->balance < $record->jumlah_diambil) {
                            \Filament\Notifications\Notification::make()
                                ->title('Dana Tidak Cukup')
                                ->body("Sisa saldo item RKA ({$rkaDetail->balance}) tidak mencukupi untuk penarikan ini.")
                                ->danger()
                                ->send();
                            return;
                        }

                        $rkaDetail->decrement('balance', $record->jumlah_diambil);
                        $record->update(['status' => 'approved']);

                        // Automatically create ExpenditureReport when approved
                        \App\Models\ExpenditureReport::create([
                            'month' => $record->created_at->format('F'),
                            'year' => $record->created_at->year,
                            'withdrawal_schedule_id' => $record->id,
                            'actual_amount' => $record->jumlah_diambil, // Default to taken amount
                            'created_by' => auth()->id(),
                            'notes' => 'Otomatis dibuat dari persetujuan pengambilan dana.',
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Berhasil Disetujui')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->hidden(fn (WithdrawalSchedule $record) => $record->status !== 'submitted' || !auth()->user()->hasRole('super_admin'))
                    ->requiresConfirmation()
                    ->action(function (WithdrawalSchedule $record) {
                        $record->update(['status' => 'rejected']);

                        \Filament\Notifications\Notification::make()
                            ->title('Berhasil Ditolak')
                            ->info()
                            ->send();
                    }),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->hidden(fn ($record) => $record->trashed() || $record->status !== 'submitted'),
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn (WithdrawalSchedule $record) => 
                        $record->trashed() || 
                        ($record->status !== 'submitted' && !auth()->user()->hasRole('super_admin'))
                    )
                    ->before(function (WithdrawalSchedule $record) {
                        // If an approved record is deleted by Super Admin, restore the balance
                        if ($record->status === 'approved') {
                            $rkaDetail = $record->rkaDetail;
                            if ($rkaDetail) {
                                $rkaDetail->increment('balance', $record->jumlah_diambil);
                            }

                            // Also delete the associated automatic expenditure report
                            \App\Models\ExpenditureReport::where('withdrawal_schedule_id', $record->id)->delete();
                        }
                    }),
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
