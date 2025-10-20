<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = DB::table('role')->insertGetId(['nama_role' => 'Administrator']);
        $dokterRole = DB::table('role')->insertGetId(['nama_role' => 'Dokter']);
        $perawatRole = DB::table('role')->insertGetId(['nama_role' => 'Perawat']);
        $resepsionisRole = DB::table('role')->insertGetId(['nama_role' => 'Resepsionis']);

    $adminUserId = DB::table('user')->insertGetId([
            'nama' => 'Admin User',
            'email' => 'admin@rshp.com',
            'password' => bcrypt('password123'),
        ]);

    DB::table('role_user')->insert([
            'iduser' => $adminUserId,
            'idrole' => $adminRole,
            'status' => '1',
        ]);

    $dokterUserId = DB::table('user')->insertGetId([
            'nama' => 'Dr. Dokter Hewan',
            'email' => 'dokter@rshp.com',
            'password' => bcrypt('password123'),
        ]);

    DB::table('role_user')->insert([
            'iduser' => $dokterUserId,
            'idrole' => $dokterRole,
            'status' => '1',
        ]);

    $pemilikUserId = DB::table('user')->insertGetId([
            'nama' => 'Pemilik Hewan',
            'email' => 'pemilik@rshp.com',
            'password' => bcrypt('password123'),
        ]);

    DB::table('pemilik')->insert([
            'iduser' => $pemilikUserId,
        ]);

    DB::table('user')->insert([
            'nama' => 'Regular User',
            'email' => 'user@rshp.com',
            'password' => bcrypt('password123'),
        ]);
    }
}
