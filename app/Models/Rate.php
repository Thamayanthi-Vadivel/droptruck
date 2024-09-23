<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $fillable=['indent_id','rate','remarks','name', 'user_id', 'confirmToTrips'];

    public function indent()
    {
        return $this->belongsTo(Indent::class, 'indent_id');
    } 

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function supplierAdvances()
    {
        return $this->hasMany(SupplierAdvance::class);
    }
    
    public function suppliers()
    {
        return $this->hasMany(Supplier::class, 'id', 'user_id');
    }
}
