<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('items')->insert([
            [
                'faculty_id' => 1,
                'name' => 'HT',
                'desc' => 'HT biasa digunakan saja',
                'stock' => 5,
            ],
            [
                'faculty_id' => 2,
                'name' => 'HT',
                'desc' => 'HT biasa digunakan saja',
                'stock' => 10,
            ],
            [
                'faculty_id' => 3,
                'name' => 'HT',
                'desc' => 'HT biasa digunakan saja',
                'stock' => 7,
            ],
            [
                'faculty_id' => 1,
                'name' => 'Kursi',
                'desc' => 'Kursi biasa digunakan saja',
                'stock' => 45,
            ],
            [
                'faculty_id' => 2,
                'name' => 'Kursi',
                'desc' => 'Kursi biasa digunakan saja',
                'stock' => 40,
            ],
            [
                'faculty_id' => 3,
                'name' => 'Kursi',
                'desc' => 'Kursi biasa digunakan saja',
                'stock' => 47,
            ],
        ]);
    }
}
