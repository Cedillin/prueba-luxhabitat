<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buildings = [
            'Al Safa Tower',
            'Marina Sapphire',
            'Jumeirah Pearl',
            'Burj Al Mina',
            'Palm Heights',
            'Deira Diamond',
            'Al Barsha Palace',
            'Downtown Emerald',
            'Creek Horizon',
            'Golden Sands Tower',
            'Al Karama Oasis',
            'Meadows Platinum',
            'Dubai Hills Estate Tower',
            'Blue Ocean Tower',
            'Al Wasl Crystal',
            'Silicon Oasis Tower',
            'Falcon City Tower',
            'Arabian Ranches Spire',
            'Lakeside Prism Tower',
            'Sunset Bay Tower',
        ];

        foreach ($buildings as $building) {
            DB::table('buildings')->insert([
                'name' => $building,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
