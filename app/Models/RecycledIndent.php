<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecycledIndent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['indent_id']; // Add other fillable columns as needed

    public function indent()
    {
        return $this->belongsTo(Indent::class);
    }

    // Add other methods or relationships as needed

    // Example method for restoring the indent
    public function restoreIndent()
    {
        // You may need to implement the actual logic to restore the indent
        // For example, move the record from recycled_indents table back to indents table
        $restoredIndent = Indent::withTrashed()->find($this->indent_id);
        if ($restoredIndent) {
            $restoredIndent->restore();
        }

        return $restoredIndent;
    }
}
