<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ExpenseList extends Model
{
    use HasFactory;
    use SoftDeletes;

    const CREATED_AT = 'expense_date';
    const UPDATED_AT = 'updated_at';
    protected $casts = ['pay_date' => 'date'];

    protected $fillable = [
        'user', 
        'expense_number',
        'creditor_name',
        'expense_value',
        'status',
        'value_status',
        'notes',
        'pay_date',
        'file_name',
    ];
}
