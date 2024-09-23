<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerRate extends Model
{
    use HasFactory;
    protected $fillable = ['rate', 'indent_id'];
        
    public function indent()
    {
        return $this->belongsTo(Indent::class);
    }
    public function customerAdvances()
    {
        return $this->hasMany(CustomerAdvance::class);
    }

}
