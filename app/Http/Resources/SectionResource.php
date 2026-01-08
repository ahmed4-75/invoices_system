<?php

namespace App\Http\Resources;

use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    { 
        $status = $this->products_exists ? 'Unempty':'Empty';
        return [
            'id' => $this->id,
            'section_name' => $this->section_name,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,

            'products' => ProductResource::collection($this->whenLoaded('products')),
            'status' => $status
        ];
    }
}
