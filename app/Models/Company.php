<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'nit',
        'social_reason',
        'site_direction',
        'code_number',
        'phone_number',
        'email',
        'website',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
