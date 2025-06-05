<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            'available_quantity' => $this->available_quantity,
            'category_id' => $this->category_id,
            'color_id' => $this->color_id,
            'model_3d' => $this->model_3d,
            'fabric_id' => $this->fabric_id,
            'wood_id' => $this->wood_id,
        ];
    }
}
