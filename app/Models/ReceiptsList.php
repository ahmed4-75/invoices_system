<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Models\InvoicesList;

class ReceiptsList extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = NULL;
    
    protected $casts = [
        'value'     => 'decimal:2',
        'due_value' => 'decimal:2',
    ];
    

    protected $fillable = [
        'value',
        'invoice_id',
        'due_value',
        'file_name'
    ];

    public function invoice() : BelongsTo{
        return $this->belongsTo(InvoicesList::class,'invoice_id')->withTrashed();
    }
}
