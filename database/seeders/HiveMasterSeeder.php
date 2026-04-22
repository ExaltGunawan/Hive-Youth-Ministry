<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Member;
use App\Models\User;
use Spatie\Permission\Models\Role;

class HiveMasterSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Divisi
        $divisiNames = ['Outreach', 'Ministry', 'Community'];
        foreach ($divisiNames as $d) {
            DB::table('divisi')->updateOrInsert(
                ['nama_divisi' => $d],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        // Seed Service Roles
        $serviceRoles = [
            'PIC', 'Liturgos', 'Singer', 'Pemusik (Piano)', 'Pemusik (Gitar)', 'Pemusik (Bass)', 'Pemusik (Drum)', 'Pemusik (Saxophone)', 'Pemusik (Cajon)', 'Designer Slide', 'Operator Slide', 'Usher', 'Kolektan', 'Audio Engineer'
        ];
        foreach ($serviceRoles as $role) {
            DB::table('service_roles')->updateOrInsert(
                ['role_name' => $role],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        // Pastikan Role super_admin ada
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);

        // Seed Data Member untuk Admin
        $adminMember = Member::firstOrCreate(
            ['nama_lengkap' => 'Admin Hive'],
            [
                'nama_lengkap' => 'Admin Hive',
                'email' => 'admin@hive.com',
                'nama_panggilan' => 'Admin',
                'status_anggota' => 'Anggota',
                'kesibukan' => 'kerja',
                'keterangan' => 'Hive Youth Ministry',
                'minat_pelayanan' => ['Multimedia', 'Ministry'],
            ]
        );

        // Seed Akun Super Admin
        $user = User::where('email', 'admin@hive.com')->first();

        if (!$user) {
            $user = User::create([
                'member_id' => $adminMember->id,
                'email' => 'admin@hive.com',
                'password' => Hash::make('adminhive321'),
                'role' => 'super_admin',
                'divisi_id' => null,
            ]);
        }

        // role super_admin ke user
        $user->assignRole($superAdminRole);
    }
}