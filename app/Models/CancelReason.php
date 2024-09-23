<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelReason extends Model
{
    use HasFactory;

    protected $fillable = ['reason'];

// Inside CancelReason model
public function indents()
{
    return $this->belongsToMany(Indent::class, 'indent_cancel_reason');
}

public function indent()
{
    return $this->belongsTo(Indent::class);
}

}
