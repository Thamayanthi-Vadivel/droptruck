<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TruckType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name'];
    
    public function indents()
    {
        return $this->hasMany(Indent::class);
    }

    public function customerRate()
    {
        return $this->hasOne(CustomerRate::class);
    }
}
