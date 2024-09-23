<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_name',
        'supplier_type',
        'company_name',
        'contact_number',
        'pan_card_number',
        'pan_card',
        'business_card',
        'memo',
        'remarks',
        'indent_id',
        'bank_name', // Add 'bank_name' to the fillable array
        'ifsc_code', // Add 'ifsc_code' to the fillable array
        'account_number',
        're_account_number',
        'bank_details',
        'eway_bill',
        'trips_invoices',
        'other_document',
        'created_by',
    ];
    public function indent()
    {
        return $this->belongsTo(Indent::class, 'indent_id');
    }
    
    // Define the relationship with IndentRate
    public function indentRate()
    {
        return $this->hasMany(Rate::class, 'indent_id');
    }
    
    public function supplierRate()
    {
        return $this->hasMany(Rate::class, 'indent_id', 'indent_id')->latest();
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
