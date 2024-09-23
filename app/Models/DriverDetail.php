<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverDetail extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'indent_id',
        'driver_name',
        'driver_number',
        'vehicle_number',
        'driver_base_location',
        'vehicle_photo',
        'driver_license',
        'rc_book',
        'insurance',
        'vehicle_type'
    ];

    public function indent()
    {
        return $this->belongsTo(Indent::class, 'indent_id');
    }


}
