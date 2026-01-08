<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseListResource extends JsonResource
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
            'expense_number' => $this->expense_number,
            'creditor_name' => $this->creditor_name,
            'expense_value' => $this->expense_value,
            'status' => $this->status,
            'value_status' => $this->value_status,
            'notes' => $this->notes,
            'pay_date' => $this->pay_date,
            'file_name' => $this->file_name,
            'expense_date' => $this->expense_date,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at
        ];
    }
}
