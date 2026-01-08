<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\InvoicesList;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'value'     => 'decimal:2',
    ];
    
    
    protected $fillable=[
        'product_name',
        'description',
        'value',
        'quantity',
        'section_id',
        'created_by'
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class)->withTrashed();
    }

    public function invoicesLists() : BelongsToMany
    {
        return $this->belongsToMany(InvoicesList::class,'product_invoice_list','product_id','invoice_list_id')->withPivot('units')->withTrashed();
    }
}
