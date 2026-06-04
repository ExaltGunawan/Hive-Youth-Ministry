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
                    $query->whereRaw('LOWER(nama_divisi) LIKE ?', ['%ketua%'])
                          ->orWhereRaw('LOWER(nama_divisi) LIKE ?', ['%wakil%']);
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

        return view('welcome', compact('pengurus'));
    }
}
