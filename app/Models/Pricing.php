<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pricing extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'pickup_city',
        'drop_city',
        'vehicle_type',
        'body_type',
        'rate_from',
        'rate_to',
        'remarks',
    ];
    
    public function truckType()
    {
        return $this->belongsTo(TruckType::class, 'vehicle_type');
    }
}
