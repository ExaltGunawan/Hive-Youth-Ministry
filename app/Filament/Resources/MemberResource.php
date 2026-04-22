<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Database Jemaat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('photo')
                    ->image()
                    ->directory('members/photos')
                    ->getUploadedFileNameForStorageUsing(
                        fn ($file, $get, $record): string => (string) str($get('nama_lengkap'))
                            ->slug()
                            ->append('-' . ($record?->id ?? 'new-' . str()->random(4)))
                            ->append('-' . now()->timestamp . '.' . $file->extension()),
                    ),
                Forms\Components\TextInput::make('nama_lengkap')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_panggilan')
                    ->maxLength(255),
                Forms\Components\TextInput::make('instagram')
                    ->maxLength(255),
                Forms\Components\TextInput::make('angkatan')
                    ->maxLength(255),
                Forms\Components\TagsInput::make('etnis')
                    ->placeholder('Tambah etnis...'),
                Forms\Components\TextInput::make('tempat_lahir')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tanggal_lahir'),
                Forms\Components\Textarea::make('alamat')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('kontak')
                    ->maxLength(255),
                Forms\Components\Select::make('kesibukan')
                    ->options([
                        'kerja' => 'Kerja',
                        'kuliah' => 'Kuliah',
                        'sekolah' => 'Sekolah',
                        'cuti' => 'Cuti',
                        'menganggur' => 'Menganggur',
                    ]),
                Forms\Components\Textarea::make('keterangan')
                    ->columnSpanFull(),
                Forms\Components\Select::make('status_anggota')
                    ->options([
                        'Anggota' => 'Anggota',
                        'Simpatisan' => 'Simpatisan',
                        'Unknown' => 'Unknown',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('golongan_darah')
                    ->maxLength(255),
                Forms\Components\TagsInput::make('hobi_interest')
                    ->placeholder('Tambah hobi...'),
                Forms\Components\TagsInput::make('minat_pelayanan')
                    ->suggestions(\App\Models\ServiceRole::all()->pluck('role_name', 'role_name'))
                    ->reorderable()
                    ->helperText('Pilih/ketik minat pelayanan dan urutkan dari yang paling disukai.')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->circular(),
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_panggilan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_anggota')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Anggota' => 'success',
                        'Simpatisan' => 'warning',
                        'Unknown' => 'gray',
                    }),
                Tables\Columns\TextColumn::make('kontak')
                    ->searchable(),
                Tables\Columns\TextColumn::make('angkatan')
                    ->sortable(),
            ])
            ->recordUrl(
                fn (Member $record): string => Pages\ViewMember::getUrl([$record->id]),
            )
            ->filters([
                Tables\Filters\SelectFilter::make('status_anggota')
                    ->options([
                        'Anggota' => 'Anggota',
                        'Simpatisan' => 'Simpatisan',
                        'Unknown' => 'Unknown',
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
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'view' => Pages\ViewMember::route('/{record}'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
