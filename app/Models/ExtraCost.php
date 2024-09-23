<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraCost extends Model
{
    use HasFactory;
    protected $fillable = ['extra_cost_type', 'amount', 'bill_copy', 'unloading_photo', 'bill_copies', 'indent_id'];

    public function indent()
    {
        return $this->belongsTo(Indent::class);
    }
}
