<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{

    protected $fillable = [
        'details',
        'total_iva_collected',
        'total_amount_payable',
        'date_issuance',
        'date_payment',
        'type',
        'state',
        'status',
        'company_id'
    ];

    use HasFactory;
}
