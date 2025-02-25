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
                'name' => 'Kelas 2.09',
                'desc' => 'Kelas biasa digunakan saja',
                'image' => 'kelas.png',
                'is_available' => true
            ]
        ]);
    }
}
