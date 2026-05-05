<?php

namespace App\Filament\Resources\WithdrawalRequestResource\Pages;

use App\Filament\Resources\WithdrawalRequestResource;
use Filament\Resources\Pages\EditRecord;

class EditWithdrawalRequest extends EditRecord
{
    protected static string $resource = WithdrawalRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('actualize')
                ->label('Aktualisasi Dana')
                ->icon('heroicon-m-check-badge')
                ->color('success')
                ->visible(fn ($record) => $record->status === 'approved')
                ->modalHeading('Aktualisasi Penggunaan Dana')
                ->modalDescription('Masukkan jumlah dana yang benar-benar terpakai dan lampirkan bukti pembayaran.')
                ->form([
                    \Filament\Forms\Components\Repeater::make('items')
                        ->relationship('items')
                        ->schema([
                            \Filament\Forms\Components\TextInput::make('item_name_display')
                                ->label('Item')
                                ->formatStateUsing(fn ($record) => $record->rkaItem->item_name)
                                ->disabled(),
                            \Filament\Forms\Components\TextInput::make('requested_amount')
                                ->label('Dana Diajukan')
                                ->disabled()
                                ->prefix('Rp'),
                            \Filament\Forms\Components\TextInput::make('actual_amount')
                                ->label('Dana Terpakai')
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->default(fn ($record) => $record->requested_amount),
                            \Filament\Forms\Components\FileUpload::make('proof_images')
                                ->label('Bukti Pembayaran (Foto/Nota)')
                                ->multiple()
                                ->image()
                                ->directory('proofs')
                                ->getUploadedFileNameForStorageUsing(function ($file, $getRecord) {
                                    return "proof-" . now()->timestamp . "-" . $file->getClientOriginalName();
                                })
                                ->columnSpanFull(),
                        ])
                        ->columns(3)
                        ->addable(false)
                        ->deletable(false),
                ])
                ->action(function ($record, array $data) {
                    $record->update(['status' => 'actualized']);
                    \Filament\Notifications\Notification::make()
                        ->title('Dana Berhasil Diaktualisasi')
                        ->success()
                        ->send();
                }),
        ];
    }
}
