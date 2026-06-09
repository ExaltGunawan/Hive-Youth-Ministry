<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Display the guest landing page.
     */
    public function index()
    {
        try {
            // In GKI Hive Youth, positions like "Ketua" and "Wakil" are stored in the "divisi" table.
            // We fetch the users belonging to these divisions to display the actual leaders.
            $pengurus = User::with(['member', 'divisi'])
                ->whereHas('divisi', function ($query) {
                    $query->where('nama_divisi', 'like', '%ketua%')
                          ->orWhere('nama_divisi', 'like', '%wakil%')
                          ->orWhere('nama_divisi', 'like', '%Ketua%')
                          ->orWhere('nama_divisi', 'like', '%Wakil%');
                })
                ->get()
                ->map(function ($user) {
                    $divisiName = $user->divisi?->nama_divisi ?? '';
                    
                    // Standardize position display name
                    $position = 'Pengurus';
                    if (stripos($divisiName, 'ketua') !== false && stripos($divisiName, 'wakil') === false) {
                        $position = 'Ketua Pemuda';
                    } elseif (stripos($divisiName, 'wakil') !== false) {
                        $position = 'Wakil Ketua Pemuda';
                    }

                    return [
                        'nama' => trim($user->member?->nama_lengkap ?? $user->member?->nama_panggilan ?? $user->name ?? 'Pengurus Hive'),
                        'jabatan' => $position,
                        'instagram' => $user->member?->instagram ?? 'hive.gki',
                        'kontak' => $user->member?->kontak ?? '-',
                        'photo' => $user->member?->photo ? asset('storage/' . $user->member->photo) : null,
                        'divisi' => $divisiName,
                    ];
                });
        } catch (\Exception $e) {
            // Safe fallback if database queries fail
            $pengurus = collect([]);
        }

        // Load homepage builder sections
        $savedSections = \App\Models\Setting::get('homepage_sections');
        
        if ($savedSections) {
            $sections = json_decode($savedSections, true);
        } else {
            // Default blocks if not saved yet
            $sections = [
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

        return view('welcome', compact('pengurus', 'sections'));
    }
}
