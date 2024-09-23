<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerOtp extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'customer_otp';

    protected $fillable = [
        'customer_id',
        'otp',
        'status',
        'contact_number',
    ];
}
