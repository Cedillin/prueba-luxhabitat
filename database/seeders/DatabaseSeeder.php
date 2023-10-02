<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Follow;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PropertyCategoriesTableSeeder::class,
            NeighborhoodsTableSeeder::class,
            BuildingTableSeeder::class,
            PropertiesTableSeeder::class,
            UserSeeder::class,

        ]);

        Follow::factory(20)->create();
    }
}
