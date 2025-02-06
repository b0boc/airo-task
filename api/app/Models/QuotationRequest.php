<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationRequest extends Model
{
    protected $fillable = [
        'age',
        'currency_id',
        'start_date',
        'end_date',
    ];
}
