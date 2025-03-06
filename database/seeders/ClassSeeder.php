<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('classmodels')->insert([
            [
                'faculty_id' => 1,
                'name' => 'Kelas 2.09',
                'desc' => 'Kelas biasa digunakan saja',
                'image' => 'kelas.png',
                'is_available' => true
            ],
            [
                'faculty_id' => 1,
                'name' => 'Kelas 2.29',
                'desc' => 'Kelas biasa digunakan saja',
                'image' => 'kelas.png',
                'is_available' => true
            ],
            [
                'faculty_id' => 1,
                'name' => 'Kelas 1.06',
                'desc' => 'Kelas biasa digunakan saja',
                'image' => 'kelas.png',
                'is_available' => true
            ],
            [
                'faculty_id' => 2,
                'name' => 'Kelas 2.02',
                'desc' => 'Kelas biasa digunakan saja',
                'image' => 'kelas.png',
                'is_available' => true
            ],
            [
                'faculty_id' => 2,
                'name' => 'Kelas 2.04',
                'desc' => 'Kelas biasa digunakan saja',
                'image' => 'kelas.png',
                'is_available' => true
            ],
            [
                'faculty_id' => 2,
                'name' => 'Kelas 1.08',
                'desc' => 'Kelas biasa digunakan saja',
                'image' => 'kelas.png',
                'is_available' => true
            ],
        ]);
    }
}
