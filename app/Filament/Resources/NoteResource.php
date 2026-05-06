<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NoteResource\Pages;
use App\Models\Note;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NoteResource extends Resource
{
    protected static ?string $model = Note::class;
    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationGroup = 'Notes';
    protected static ?string $navigationLabel = 'Notulensi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('id_pembuat')
                    ->default(auth()->id())
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->placeholder('Judul Notulensi...')
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('value')
                    ->label('Isi Notulensi')
                    ->required()
                    ->fileAttachmentsDirectory('notes/attachments')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('conclusion')
                    ->label('Kesimpulan / Action Plan')
                    ->autosize()
                    ->placeholder('Tulis kesimpulan di sini...')
                    ->columnSpanFull(),
                Forms\Components\Section::make('Daftar Hadir Pengurus')
                    ->description('Centang untuk hadir, hilangkan centang untuk tidak hadir.')
                    ->schema([
                        Forms\Components\CheckboxList::make('attendance')
                            ->label('')
                            ->options(\App\Models\User::all()->pluck('name', 'id'))
                            ->default(\App\Models\User::all()->pluck('id')->toArray())
                            ->columns(3)
                            ->columnSpanFull()
                            ->bulkToggleable(),
                    ])
                    ->collapsible(),
                Forms\Components\Select::make('allowed_viewers')
                    ->multiple()
                    ->options(\App\Models\Divisi::all()->pluck('nama_divisi', 'nama_divisi')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->sortable()
                    ->default('(Tanpa Judul)'),
                Tables\Columns\TextColumn::make('value')
                    ->label('Isi Notulensi')
                    ->html()
                    ->limit(50),
                Tables\Columns\TextColumn::make('attendance_count')
                    ->label('Kehadiran')
                    ->badge()
                    ->color('success')
                    ->getStateUsing(function ($record) {
                        $totalUsers = \App\Models\User::count();
                        $attendance = $record->attendance;
                        
                        if (is_string($attendance)) {
                            $attendance = json_decode($attendance, true);
                        }
                        
                        $count = is_array($attendance) ? count($attendance) : 0;
                        return "{$count} / {$totalUsers}";
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat pada')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordUrl(
                fn (Note $record): string => Pages\ViewNote::getUrl([$record->id]),
            )
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('download')
                    ->label('Download PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('warning')
                    ->url(fn (Note $record): string => route('admin.notes.download', $record))
                    ->openUrlInNewTab(),
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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();
        
        // 1. ALL: Jika punya izin 'view_all_note', tampilkan SEMUA
        if ($user->can('view_all_note')) {
            return $query;
        }

        // 2. LOGIKA GABUNGAN: Divisi, Allowed Viewers, dan Milik Sendiri
        return $query->where(function ($q) use ($user) {
            // Selalu tampilkan milik sendiri
            $q->where('id_pembuat', $user->id)
              // Selalu tampilkan jika divisinya diizinkan secara manual
              ->orWhereJsonContains('allowed_viewers', $user->divisi?->nama_divisi);
            
            // Jika punya izin 'view_divisi_note', tampilkan semua milik rekan sedivisi
            if ($user->can('view_divisi_note')) {
                $q->orWhereHas('pembuat', function ($sub) use ($user) {
                    $sub->where('divisi_id', $user->divisi_id);
                });
            }
        });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotes::route('/'),
            'create' => Pages\CreateNote::route('/create'),
            'view' => Pages\ViewNote::route('/{record}'),
            'edit' => Pages\EditNote::route('/{record}/edit'),
        ];
    }
}

