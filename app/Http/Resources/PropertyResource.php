<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'neighborhood' => $this->neighborhood->name,
            'building' => $this->building->name,
            'price' => $this->price,
            'category' => $this->category,
            'bedrooms' => $this->bedrooms,
        ];
    }
}
