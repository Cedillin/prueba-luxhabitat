<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Neighborhood;
use App\Models\Property;
use App\Models\PropertyCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run($recordsToCreate = 50)
    {
        $neighborhoods = Neighborhood::all();
        $buildings = Building::all();
        $propertyCategories = PropertyCategory::all();

        if ($neighborhoods->isEmpty() || $buildings->isEmpty() || $propertyCategories->isEmpty()) {
            return;
        }

        foreach (range(1, $recordsToCreate) as $i) {
            Property::create([
                'neighborhood_id' => $neighborhoods->random()->id,
                'building_id' => $buildings->random()->id,
                'property_category_id' => $propertyCategories->random()->id,
                'price' => rand(2, 20) * 50000,
                'bedrooms' => rand(3, 10),
            ]);
        }
    }
}
