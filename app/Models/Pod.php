<?php

// app/Models/Pod.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pod extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['courier_receipt_no', 'pod_soft_copy', 'pod_courier', 'indent_id'];

    public function indent()
    {
        return $this->belongsTo(Indent::class);
    }
    
    // Define the relationship with IndentRate
    public function indentRate()
    {
        return $this->hasMany(Rate::class, 'indent_id');
    }
}

