<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'faculty_id' => 1,
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'password' => Hash::make('12345678'),
                'nim' => '1204210092',
                'study_program_id' => 1,
                'semester' => '4',
                'role' => 'superadmin'
            ],
            [
                'faculty_id' => 1,
                'name' => 'Dosen Teknik',
                'email' => 'dosenteknik@gmail.com',
                'password' => Hash::make('12345678'),
                'nim' => '1204210023',
                'study_program_id' => 3,
                'semester' => '4',
                'role' => 'dosen'
            ],
            [
                'faculty_id' => 2,
                'name' => 'Dosen FEB',
                'email' => 'dosenfeb@gmail.com',
                'password' => Hash::make('12345678'),
                'nim' => '1204210679',
                'study_program_id' => 5,
                'semester' => '4',
                'role' => 'dosen'
            ],
            [
                'faculty_id' => 1,
                'name' => 'Super Barang',
                'email' => 'adminbarang1@gmail.com',
                'password' => Hash::make('12345678'),
                'nim' => '1204210092',
                'study_program_id' => 1,
                'semester' => '4',
                'role' => 'admin_barang'
            ],
            [
                'faculty_id' => 1,
                'name' => 'Super Ruangan',
                'email' => 'adminruangan1@gmail.com',
                'password' => Hash::make('12345678'),
                'nim' => '1204210034',
                'study_program_id' => 1,
                'semester' => '4',
                'role' => 'admin_ruangan'
            ],
            [
                'faculty_id' => 1,
                'name' => 'User',
                'email' => 'user1@gmail.com',
                'password' => Hash::make('12345678'),
                'nim' => '1204210020',
                'study_program_id' => 1,
                'semester' => '4',
                'role' => 'user'
            ],
            [
                'faculty_id' => 2,
                'name' => 'Super Barang',
                'email' => 'adminbarang2@gmail.com',
                'password' => Hash::make('12345678'),
                'nim' => '120421987',
                'study_program_id' => 2,
                'semester' => '4',
                'role' => 'admin_barang'
            ],
            [
                'faculty_id' => 2,
                'name' => 'Super Ruangan',
                'email' => 'adminruangan2@gmail.com',
                'password' => Hash::make('12345678'),
                'nim' => '1204210245',
                'study_program_id' => 2,
                'semester' => '4',
                'role' => 'admin_ruangan'
            ],
            [
                'faculty_id' => 2,
                'name' => 'User',
                'email' => 'user2@gmail.com',
                'password' => Hash::make('12345678'),
                'nim' => '1204210642',
                'study_program_id' => 2,
                'semester' => '4',
                'role' => 'user'
            ],
        ]);
    }
}
