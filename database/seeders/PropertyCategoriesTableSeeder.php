<?php

namespace Database\Seeders;

use App\Models\PropertyCategory;
use Illuminate\Database\Seeder;

class PropertyCategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        PropertyCategory::create(['name' => 'apartment']);
        PropertyCategory::create(['name' => 'villa']);
    }
}
