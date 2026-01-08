<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ReceiptsListResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoicesListResource extends JsonResource
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
            'user' => $this->user,
            'invoice_number' => $this->invoice_number,
            'customer_name' => $this->customer_name,
            'invoice_value' => $this->invoice_value,
            'discount_value' => $this->discount_value,
            'vat_value' => $this->vat_value,
            'total_value' => $this->total_value,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'value_status' => $this->value_status,
            'notes' => $this->notes,
            'invoice_date' => $this->invoice_date,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'file_name' => $this->file_name,

            'products' => ProductResource::collection($this->whenLoaded('products')),
            'receipts' => ReceiptsListResource::collection($this->whenLoaded('receipts'))
        ];
    }
}
