<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramStudySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('study_programs')->insert([
            [
                'faculty_id' => 1,
                'name' => 'Teknik Informatika'
            ],
            [
                'faculty_id' => 1,
                'name' => 'Teknik Elektro'
            ],
            [
                'faculty_id' => 1,
                'name' => 'Teknik Industri'
            ],
            [
                'faculty_id' => 2,
                'name' => 'Manajemen'
            ],
            [
                'faculty_id' => 2,
                'name' => 'Administrasi Bisnis'
            ],
        ]);
    }
}
