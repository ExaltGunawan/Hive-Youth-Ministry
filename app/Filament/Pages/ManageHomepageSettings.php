<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ManageHomepageSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $title = 'Homepage Builder';

    protected static string $view = 'filament.pages.manage-homepage-settings';

    public ?array $data = [];

    public function mount(): void
    {
        // Fetch saved json or use default
        $savedSections = Setting::get('homepage_sections');
        $sectionsData = $savedSections ? json_decode($savedSections, true) : $this->getDefaultSections();

        $this->form->fill([
            'homepage_sections' => $sectionsData,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Builder::make('homepage_sections')
                    ->label('Homepage Content Sections')
                    ->blocks([
                        Block::make('hero')
                            ->label('Hero Section')
                            ->icon('heroicon-m-photo')
                            ->schema([
                                TextInput::make('title')->label('Hero Title')->required(),
                                Textarea::make('subtitle')->label('Hero Subtitle')->rows(3)->required(),
                                FileUpload::make('background_image')->label('Background Image')->image()->directory('settings'),
                            ]),

                        Block::make('schedule')
                            ->label('Schedule Section')
                            ->icon('heroicon-m-calendar-days')
                            ->schema([
                                TextInput::make('tag')->label('Tag (Small Text)')->default('JADWAL PERSEKUTUAN'),
                                TextInput::make('heading')->label('Heading')->default('Ibadah Pemuda Mingguan'),
                                Textarea::make('description')->label('Description')->rows(2),
                                TextInput::make('day')->label('Day')->default('Setiap Hari Minggu'),
                                TextInput::make('time')->label('Time')->default('Pukul 07:00 WIB'),
                                TextInput::make('location')->label('Location')->default('GKI Cimahi, Ruang Kebaktian 2'),
                            ])->columns(2),

                        Block::make('event')
                            ->label('Upcoming Event')
                            ->icon('heroicon-m-ticket')
                            ->schema([
                                TextInput::make('tag')->label('Tag')->default('UPCOMING EVENT'),
                                TextInput::make('heading')->label('Section Heading')->default('Worship Night'),
                                Textarea::make('description')->label('Section Description')->rows(2)->columnSpanFull(),
                                FileUpload::make('image')->label('Event Thumbnail')->image()->directory('settings')->columnSpanFull(),
                                TextInput::make('event_title')->label('Event Title')->default('Worship Night: Beehive'),
                                Textarea::make('event_desc')->label('Event Description')->rows(2)->columnSpanFull(),
                                TextInput::make('button_text')->label('Button Text')->default('Daftar Sekarang'),
                                TextInput::make('button_link')->label('Button Link')->url(),
                            ])->columns(2),

                        Block::make('media')
                            ->label('Media & Komunitas')
                            ->icon('heroicon-m-share')
                            ->schema([
                                TextInput::make('tag')->label('Tag')->default('Media & Komunitas'),
                                TextInput::make('heading')->label('Heading')->default('Koneksi Sosial Media Hub'),
                                Textarea::make('description')->label('Description')->rows(2)->columnSpanFull(),
                                Repeater::make('social_links')
                                    ->label('Social Media Buttons')
                                    ->schema([
                                        TextInput::make('platform')->label('Platform (e.g. IG, YT)')->required(),
                                        TextInput::make('handle')->label('Handle/Name')->required(),
                                        TextInput::make('link')->label('URL')->url()->required(),
                                    ])->columns(3)->columnSpanFull(),
                            ])->columns(2),

                        Block::make('board')
                            ->label('Board Directory (Kontak Pengurus)')
                            ->icon('heroicon-m-users')
                            ->schema([
                                TextInput::make('tag')->label('Tag')->default('BOARD & DIRECTORY'),
                                TextInput::make('heading')->label('Heading')->default('Kontak Pengurus'),
                                Textarea::make('description')->label('Description')->rows(2)->columnSpanFull(),
                            ])->columns(2),
                    ])
                    ->collapsible()
                    ->cloneable()
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Page')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $state = $this->form->getState();

        Setting::set('homepage_sections', json_encode($state['homepage_sections']), 'json');

        Notification::make()
            ->success()
            ->title('Homepage saved successfully!')
            ->send();
    }

    private function getDefaultSections(): array
    {
        return [
            [
                'type' => 'hero',
                'data' => [
                    'title' => 'Connecting Youths, Growing in Faith',
                    'subtitle' => 'Membangun komunitas anak muda yang solid, bersukacita, dan bertumbuh bersama di dalam iman Kristen. Mari bergabung bersama kami di persekutuan mingguan!',
                    'background_image' => null,
                ]
            ],
            [
                'type' => 'schedule',
                'data' => [
                    'tag' => 'JADWAL PERSEKUTUAN',
                    'heading' => 'Ibadah Pemuda Mingguan',
                    'description' => 'Persekutuan pemuda dirancang khusus untuk membawa pesan yang relevan, pujian penyembahan yang bersemangat, serta ruang untuk saling bertumbuh dan berbagi dalam kelompok kecil.',
                    'day' => 'Setiap Hari Minggu',
                    'time' => 'Pukul 07:00 WIB',
                    'location' => 'GKI Cimahi, Ruang Kebaktian 2',
                ]
            ],
            [
                'type' => 'event',
                'data' => [
                    'tag' => 'UPCOMING EVENT',
                    'heading' => 'Worship Night',
                    'description' => 'Jangan lewatkan momen pujian penyembahan bersama dalam Worship Night kami yang akan datang.',
                    'event_title' => 'Worship Night: Beehive',
                    'event_desc' => 'Bergabunglah bersama kami untuk sebuah malam yang penuh dengan hadirat Tuhan. Daftarkan diri Anda sekarang melalui tautan di bawah ini.',
                    'button_text' => 'Daftar Sekarang',
                    'button_link' => 'https://goers.co/worshipnightbeehive',
                    'image' => null,
                ]
            ],
            [
                'type' => 'media',
                'data' => [
                    'tag' => 'Media & Komunitas',
                    'heading' => 'Koneksi Sosial Media Hub',
                    'description' => 'Ikuti kabar terbaru, keseruan persekutuan, dokumentasi ibadah, dan renungan menarik melalui kanal sosial media resmi kami.',
                    'social_links' => [
                        ['platform' => 'IG', 'handle' => '@kp_gkicimahi', 'link' => 'https://instagram.com/kp_gkicimahi'],
                        ['platform' => 'IG', 'handle' => '@koreci_gki', 'link' => 'https://instagram.com/koreci_gki'],
                        ['platform' => 'YT', 'handle' => 'GKICimahi', 'link' => 'https://youtube.com/@GKICimahi'],
                    ]
                ]
            ],
            [
                'type' => 'board',
                'data' => [
                    'tag' => 'BOARD & DIRECTORY',
                    'heading' => 'Kontak Pengurus',
                    'description' => 'Punya pertanyaan seputar pelayanan pemuda atau butuh teman berbagi? Silakan hubungi jajaran pengurus kami secara langsung.',
                ]
            ]
        ];
    }
}
