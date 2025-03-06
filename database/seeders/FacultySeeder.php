<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('faculties')->insert([
            [
                'code' => 'FT',
                'name' => 'Fakultas Teknik',
            ],
            [
                'code' => 'FEB',
                'name' => 'Fakultas Ekonomi Bisnis',
            ],
            [
                'code' => 'FH',
                'name' => 'Fakultas Hukum',
            ],
            [
                'code' => 'FISIP',
                'name' => 'Fakultas Ilmu Sosial dan Ilmu Politik',
            ],
        ]);
    }
}
