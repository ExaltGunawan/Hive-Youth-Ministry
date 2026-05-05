<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class MyProfile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'My Profile';
    protected static ?string $title = 'My Profile';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static bool $shouldRegisterNavigation = false; // Sembunyikan dari sidebar utama, biarkan di user menu saja

    protected static string $view = 'filament.pages.my-profile';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'nama_lengkap' => auth()->user()->member?->nama_lengkap,
            'nama_panggilan' => auth()->user()->member?->nama_panggilan,
            'email' => auth()->user()->email,
        ]);
    }

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
                                    <div class="flex flex-col items-center justify-center space-y-4 mb-4 pt-4">
                                        <div class="relative group">
                                            <img src="'.$url.'" class="w-40 h-40 rounded-full object-cover border-4 border-amber-500 shadow-2xl" />
                                        </div>
                                        <div class="text-center">
                                            <h3 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">'.auth()->user()->member?->nama_lengkap.'</h3>
                                            <p class="text-amber-600 font-bold text-lg tracking-widest uppercase">'.(auth()->user()->divisi?->nama_divisi ?? 'Pengurus').'</p>
                                        </div>
                                    </div>
                                ');
                            })
                            ->columnSpanFull(),

                        TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->disabled(),

                        TextInput::make('nama_panggilan')
                            ->label('Nama Panggilan')
                            ->disabled(),

                        TextInput::make('email')
                            ->label('Email Akun')
                            ->disabled()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Update Password')
                    ->description('Ubah password Anda secara berkala untuk keamanan.')
                    ->schema([
                        TextInput::make('current_password')
                            ->label('Password Saat Ini')
                            ->password()
                            ->required()
                            ->revealable(),

                        TextInput::make('password')
                            ->label('Password Baru')
                            ->password()
                            ->required()
                            ->rule(Password::default())
                            ->revealable(),

                        TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password Baru')
                            ->password()
                            ->required()
                            ->same('password')
                            ->revealable(),
                    ])
                    ->columns(1),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveAction(),
        ];
    }

    public function getSaveAction(): Action
    {
        return Action::make('save')
            ->label('Simpan Perubahan')
            ->submit('save');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Validasi password saat ini
        if (!Hash::check($data['current_password'], auth()->user()->password)) {
            $this->addError('data.current_password', 'Password saat ini tidak cocok.');
            return;
        }

        auth()->user()->update([
            'password' => Hash::make($data['password']),
        ]);

        $this->form->fill([
            'current_password' => '',
            'password' => '',
            'password_confirmation' => '',
            'nama_panggilan' => auth()->user()->member?->nama_panggilan,
            'email' => auth()->user()->email,
        ]);

        Notification::make()
            ->title('Password berhasil diperbarui!')
            ->success()
            ->send();
    }
}
