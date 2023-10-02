<?php

namespace App\Http\Controllers;

use App\Http\Resources\PropertyResource;
use App\Models\Property;

class PropertyController extends Controller
{
    public function index()
    {
        // In a real example I would never retrieve ALL the fields, but the needed ones
        $properties = Property::paginate(10);
        return PropertyResource::collection($properties);
    }

    public function show(Property $property)
    {
        return new PropertyResource($property);
    }
}
