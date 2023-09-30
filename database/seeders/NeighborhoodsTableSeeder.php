<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NeighborhoodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $neighborhoods = [
            'Al Barari',
            'Al Habtoor City',
            'Arabian Ranches',
            'Bluewaters Island',
            'Business Bay',
            'Bur Dubai',
            'Culture Village',
            'Deira',
        ];

        foreach ($neighborhoods as $neighborhood) {
            DB::table('neighborhoods')->insert([
                'name' => $neighborhood,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
