<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vehicle_number',
        'vehicle_type',
        'body_type',
        'tonnage_passing',
        'driver_number',
        'status',
        'rc_book',
        'driving_license',
        'vehicle_photo',
        'insurance',
        'remarks',
    ];
    
    public function truckType()
    {
        return $this->belongsTo(TruckType::class, 'vehicle_type');
    }
    
}
