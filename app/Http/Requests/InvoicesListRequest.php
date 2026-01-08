<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;

class InvoicesListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'invoice_number' => 'required|integer|unique:invoices_lists|min:0',
            'customer_name' => 'required|regex:/^[\p{L}\p{M}\s]+$/u',
            'products' => 'required|array|min:1',
            'products.*' => 'exists:products,id',
            'units' => 'required|array|min:1',
            'units.*' =>['required','integer','min:0',
                            function ($attribute,$value,$fail){
                                $indexU=explode('.',$attribute)[1] ?? null; # getting unitsKEY == units[$indexU] == $attribute == units.$indexU = value,unitsKEY == units.$indexU & productsKEY == products.$indexP So $indexU == $indexP
                                $productId = $this->input("products.$indexU");
                                if($productId){
                                    $product = Product::findOrFail($productId);
                                    if($product && $value > $product->quantity){
                                        $fail("The Quantity for Product '{$product->product_name}' Exceed available Stock ({$product->quantity})");
                                    }
                                }
                            }
                        ],
            'invoice_value' => 'required|decimal:2|min:0',
            'discount_value' => 'required|decimal:2|min:0',
            'vat_value' => 'required|decimal:2|min:0',
            'total_value' => 'required|decimal:2|min:0',
            'due_date' => 'required|date_format:Y-m-d',
            'notes' => 'string',
            'attaches' => 'nullable|array',
            'attaches.*' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:6120'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'invoice_number.required' => 'Enter Invoice Number',
            'invoice_number.integer' => 'Enter the CORRECT NUMBER',
            'invoice_number.unique' => 'This Number has been USED before',
            'invoice_number.min' => 'Enter the CORRECT NUMBER',
            'customer_name.required' => 'Enter the Customer Name',
            'customer_name.regex' => 'Enter Customer Name CORRECTLY',
            'products.required' => 'SELECT a Product',
            'products.array' => 'Invalid Product list format.',
            'products.exists' => 'SELECT a Product from the OPTIONS',
            'units.required' => 'Enter Units',
            'units.array' => 'Invalid units list format',
            'units.*.required' => 'Enter Units',
            'units.*.integer' => 'Enter the CORRECT Units LIKE 5',
            'units.*.min' => 'Units cannot be less than 0',
            'invoice_value.required' => 'Enter the Invoice Value',
            'invoice_value.decimal' => 'Enter the CORRECT Value LIKE 0.00',
            'invoice_value.min' => 'Enter the CORRECT Value Minimum 0.00',
            'discount_value.required' => 'Enter the Discount Value',
            'discount_value.decimal' => 'Enter the CORRECT Value LIKE 0.00',
            'discount_value.min' => 'Enter the CORRECT Value Minimum 0.00',
            'vat_value.required' => 'Enter the Vat Value',
            'vat_value.decimal' => 'Enter the CORRECT Value LIKE 0.00',
            'vat_value.min' => 'Enter the CORRECT Value Minimum 0.00',
            'total_value.required' => 'Enter the Total Value',
            'total_value.decimal' => 'Enter the CORRECT Value LIKE 0.00',
            'total_value.min' => 'Enter the CORRECT Value Minimum 0.00',
            'due_date.required' => 'Enter Due Date',
            'due_date.date_format' => 'Enter by the CORRECT Format Date',
            'attaches.array' => 'Attachments must be sent as an array.',
            'attaches.*.file' => 'Each attachment must be a valid file.',
            'attaches.*.mimes' => 'Attachments must be PDF, JPG, JPEG or PNG.',
            'attaches.*.max' => 'Each attachment must not exceed 6 MB.'
        ];
    }
}
