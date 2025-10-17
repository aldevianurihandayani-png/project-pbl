<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersRestoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = [
            ['id' => 4,  'name' => 'Fara Apriliana', 'nama' => 'Fara Apriliana',             'email' => 'fara.apriliana@mhs.politala.ac.id',  'role' => 'dosen_pembimbing', 'password' => Hash::make('secret123'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5,  'name' => 'Noorma', 'nama' => 'Noorma',                     'email' => 'noorma@mhs.politala.ac.id',          'role' => 'admins', 'password' => Hash::make('secret123'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8,  'name' => 'Aldevianuri Handayani', 'nama' => 'Aldevianuri Handayani',      'email' => 'aldevianuri.handayani@mhs.politala.ac.id', 'role' => 'dosen_penguji', 'password' => Hash::make('secret123'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'name' => 'Wike Widia', 'nama' => 'Wike Widia',                 'email' => 'wike.widia@mhs.politala.ac.id',      'role' => 'mahasiswa', 'password' => Hash::make('secret123'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'name' => 'Kania Ardhita', 'nama' => 'Kania Ardhita',              'email' => 'syifa.kania@mhs.politala.ac.id',     'role' => 'koordinator', 'password' => Hash::make('secret123'), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'name' => 'Ferdi', 'nama' => 'Ferdi',                      'email' => 'ferdi@mhs.politala.ac.id',           'role' => 'jaminan_mutu', 'password' => Hash::make('secret123'), 'created_at' => now(), 'updated_at' => now()],
        ];

        User::upsert($rows, ['id', 'email'], ['name', 'nama', 'role', 'password', 'updated_at']);
    }
}
