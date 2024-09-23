<?php

// app/Models/CustomerAdvance.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAdvance extends Model
{
    use HasFactory;

    protected $fillable = ['indent_id',  'advance_amount', 'balance_amount', 'payment_type'];

    public function indent()
    {
        return $this->belongsTo(Indent::class);
    }

    public function customerRate()
    {
        return $this->belongsTo(CustomerRate::class);
    }
}
