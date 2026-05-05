<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class EditProfile extends BaseEditProfile
{
    protected static string $view = 'filament.pages.auth.edit-profile';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Profil Pengurus')
                    ->description('Informasi lengkap akun Anda.')
                    ->schema([
                        Placeholder::make('photo_display')
                            ->label('')
                            ->content(function () {
                                $url = auth()->user()->member?->photo 
                                    ? Storage::url(auth()->user()->member->photo) 
                                    : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=FFFFFF&background=FFB300';
                                
                                return new \Illuminate\Support\HtmlString('
                                    <div class="flex flex-col items-center justify-center space-y-4 mb-4">
                                        <div class="relative group">
                                            <img src="'.$url.'" class="w-32 h-32 rounded-full object-cover border-4 border-amber-500 shadow-xl" />
                                            <div class="absolute inset-0 rounded-full bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <span class="text-white text-xs font-bold uppercase tracking-widest">Active</span>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <h3 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">'.auth()->user()->member?->nama_lengkap.'</h3>
                                            <p class="text-amber-600 font-bold text-sm tracking-widest uppercase">'.(auth()->user()->divisi?->nama_divisi ?? 'Pengurus').'</p>
                                        </div>
                                    </div>
                                ');
                            })
                            ->columnSpanFull(),

                        TextInput::make('nama_panggilan_display')
                            ->label('Nama Panggilan')
                            ->default(auth()->user()->member?->nama_panggilan)
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('email')
                            ->label('Email Akun')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),

                Section::make('Keamanan Akun')
                    ->description('Ubah password Anda secara berkala.')
                    ->schema([
                        TextInput::make('current_password')
                            ->label('Password Saat Ini')
                            ->password()
                            ->required()
                            ->currentPassword()
                            ->revealable()
                            ->placeholder('Masukkan password lama Anda'),

                        TextInput::make('password')
                            ->label('Password Baru')
                            ->password()
                            ->required()
                            ->rule(Password::default())
                            ->revealable()
                            ->placeholder('Minimal 8 karakter'),

                        TextInput::make('passwordConfirmation')
                            ->label('Konfirmasi Password Baru')
                            ->password()
                            ->required()
                            ->same('password')
                            ->revealable()
                            ->placeholder('Ulangi password baru'),
                    ])
                    ->columns(1),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
        ];
    }
}
