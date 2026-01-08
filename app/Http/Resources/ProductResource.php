<?php

namespace App\Http\Resources;

use App\Http\Resources\InvoicesListResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $status = (!$this->invoices_lists_exists and $this->quantity == 0) ? 'Empty' : 'Unempty';
        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'description' => $this->description,
            'section_id' => $this->section_id,
            'value' => $this->value,
            'quantity' => $this->quantity,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,

            'section' => $this->whenLoaded('section', function () {
                    return new SectionResource($this->section);
                }),
            'invoicesList' => InvoicesListResource::collection($this->whenLoaded('invoicesLists')),
            
            'units' => $this->pivot->units ?? 1,
            'status' => $status
        ];
    }
}
