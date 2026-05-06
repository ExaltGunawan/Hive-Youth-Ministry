<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $recordTitleAttribute = 'email';

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Manajemen Pengurus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('member_id')
                    ->relationship('member', 'nama_lengkap')
                    ->searchable()
                    ->preload()
                    ->label('Nama Jemaat'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
                Forms\Components\Select::make('role')
                    ->options(\Spatie\Permission\Models\Role::all()->pluck('name', 'name'))
                    ->required()
                    ->label('Role')
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        $stateLower = strtolower($state ?? '');
                        if (str_contains($stateLower, 'outreach')) {
                            $divisi = \App\Models\Divisi::where('nama_divisi', 'ilike', '%outreach%')->first();
                            if ($divisi) $set('divisi_id', $divisi->id);
                        } elseif (str_contains($stateLower, 'ministry')) {
                            $divisi = \App\Models\Divisi::where('nama_divisi', 'ilike', '%ministry%')->first();
                            if ($divisi) $set('divisi_id', $divisi->id);
                        } elseif (str_contains($stateLower, 'community')) {
                            $divisi = \App\Models\Divisi::where('nama_divisi', 'ilike', '%community%')->first();
                            if ($divisi) $set('divisi_id', $divisi->id);
                        }
                    }),
                Forms\Components\Select::make('divisi_id')
                    ->relationship('divisi', 'nama_divisi')
                    ->label('Divisi/Jabatan')
                    ->placeholder('Pilih divisi'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('member.nama_lengkap')
                    ->label('Nama Pengurus')
                    ->sortable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'super_admin' => 'danger',
                        'Super Admin' => 'danger',
                        'admin' => 'warning',
                        'treasurer' => 'success',
                        'secretary' => 'info',
                        'member' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('divisi.nama_divisi')
                    ->label('Divisi')
                    ->sortable(),
            ])
            ->recordUrl(
                fn (User $record): string => Pages\ViewUser::getUrl([$record->id]),
            )
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options(\Spatie\Permission\Models\Role::all()->pluck('name', 'name')),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->hidden(fn ($record) => $record->trashed()),
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn (User $record) => $record->id === auth()->id()),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make()
                    ->hidden(fn (User $record) => $record->id === auth()->id()),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

