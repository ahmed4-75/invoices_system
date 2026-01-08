<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\ReceiptsList;

class InvoicesList extends Model
{
    use HasFactory;
    use SoftDeletes;

    const CREATED_AT = 'invoice_date';
    const UPDATED_AT = 'updated_at';

    protected $casts = [
        'invoice_value' => 'decimal:2',
        'vat_value' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'total_value' => 'decimal:2',
        'due_date' => 'date'
    ];

    protected $fillable=[
        'user', 
        'invoice_number',         
        'customer_name',
        'invoice_value',
        'discount_value',
        'vat_value',
        'total_value',
        'due_date',
        'status', 
        'value_status', 
        'notes',
        'file_name'
   ];

   public function products() : BelongsToMany
   {
     return $this->belongsToMany(Product::class,'product_invoice_list','invoice_list_id','product_id')->withPivot('units')->withTrashed();
   }

   public function receipts() : HasMany{
     return $this->hasMany(ReceiptsList::class,'invoice_id')->withTrashed();
   }
}
