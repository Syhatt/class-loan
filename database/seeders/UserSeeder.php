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
        // DB::table('users')->truncate(); // optional: biar data lama bersih

        DB::table('users')->insert([
            // === SUPER ADMIN ===
            [
                'faculty_id' => 1,
                'study_program_id' => 1,
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'password' => Hash::make('12345678'),
                'nim' => '0000000001',
                'semester' => '4',
                'role' => 'superadmin',
            ],

            // === DOSEN ===
            [
                'faculty_id' => 1,
                'study_program_id' => 1,
                'name' => 'Dosen Teknik',
                'email' => 'dosenteknik@gmail.com',
                'password' => Hash::make('12345678'),
                'nim' => '0000001001',
                'semester' => '4',
                'role' => 'dosen',
            ],
            [
                'faculty_id' => 2,
                'study_program_id' => 4,
                'name' => 'Dosen FEB',
                'email' => 'dosenfeb@gmail.com',
                'password' => Hash::make('12345678'),
                'nim' => '0000002001',
                'semester' => '4',
                'role' => 'dosen',
            ],

            // === ADMIN FAKULTAS ===
            [
                'faculty_id' => 1,
                'study_program_id' => 1,
                'name' => 'Admin Fakultas Teknik',
                'email' => 'adminft@gmail.com',
                'password' => Hash::make('12345678'),
                'nim' => '0000003001',
                'semester' => '4',
                'role' => 'admin_fakultas',
            ],
            [
                'faculty_id' => 2,
                'study_program_id' => 4,
                'name' => 'Admin Fakultas FEB',
                'email' => 'adminfeb@gmail.com',
                'password' => Hash::make('12345678'),
                'nim' => '0000003002',
                'semester' => '4',
                'role' => 'admin_fakultas',
            ],
            [
                'faculty_id' => 3,
                'study_program_id' => 6,
                'name' => 'Admin Fakultas Hukum',
                'email' => 'adminfh@gmail.com',
                'password' => Hash::make('12345678'),
                'nim' => '0000003003',
                'semester' => '4',
                'role' => 'admin_fakultas',
            ],

            // === USER (Mahasiswa) ===
            [
                'faculty_id' => 1,
                'study_program_id' => 1,
                'name' => 'User FT',
                'email' => 'userft@gmail.com',
                'password' => Hash::make('12345678'),
                'nim' => '0000004001',
                'semester' => '4',
                'role' => 'user',
            ],
            [
                'faculty_id' => 2,
                'study_program_id' => 4,
                'name' => 'User FEB',
                'email' => 'userfeb@gmail.com',
                'password' => Hash::make('12345678'),
                'nim' => '0000004002',
                'semester' => '4',
                'role' => 'user',
            ],
            [
                'faculty_id' => 3,
                'study_program_id' => 6,
                'name' => 'User FH',
                'email' => 'userfh@gmail.com',
                'password' => Hash::make('12345678'),
                'nim' => '0000004003',
                'semester' => '4',
                'role' => 'user',
            ],
        ]);
    }
}
