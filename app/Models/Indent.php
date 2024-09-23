<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Indent extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'customer_name',
        'company_name',
        'number_1',
        'number_2',
        'source_of_lead',
        'pickup_location_id',
        'drop_location_id',
        'material_type_id',
        'truck_type_id',
        'body_type',
        'weight',
        'weight_unit',
        'pod_soft_hard_copy',
        'remarks',
        'customer_rate',
        'payment_terms',
        'confirmed_date',
        'new_material_type',
        'new_body_type',
        'new_truck_type',
        'new_source_type',
        'pickup_city',
        'drop_city',
        'required_date',
        'customer_id',
        'customer_rate',
        'user_id',
    ];
    
    public function supplierAdvances()
    {
        return $this->hasMany(SupplierAdvance::class, 'indent_id');
    }

    public function customerAdvances()
    {
        return $this->hasMany(CustomerAdvance::class, 'indent_id');
    }
    public function driverDetails()
    {
        return $this->hasMany(DriverDetail::class, 'indent_id');
    }

    public function suppliers()
{
    return $this->hasMany(Supplier::class, 'indent_id');
}

    public function suppliersData()
{
    return $this->hasMany(Supplier::class, 'indent_id');
}

public function indentRate()
{
    return $this->hasMany(Rate::class, 'indent_id', 'id')->latest();
}

public function indentRatesAll()
{
    return $this->hasMany(Rate::class, 'indent_id', 'id');
}

public function indentConfirmedDate()
{
    return $this->hasOne(Rate::class, 'indent_id', 'id')->where('is_confirmed_rate', '1');
}

    public function user()
    {
        return $this->belongsTo(User::class);
    }


// Inside Indent.php model
public function pickupLocation()
{
    return $this->belongsTo(Location::class, 'pickup_location_id');
}

public function dropLocation()
{
    return $this->belongsTo(Location::class, 'drop_location_id');
}

public function materialType()
{
    return $this->belongsTo(MaterialType::class, 'material_type_id');
}

public function truckType()
{
    return $this->belongsTo(TruckType::class, 'truck_type_id');
}

public function getUniqueENQNumber()
{
    $id = $this->id;
    $prefix = 'DT';
    $suffix = str_pad($id, 2, '0', STR_PAD_LEFT); // Padding with zeros if the ID is less than 10

    return $prefix . $suffix;
}

// Inside Indent model
public function cancelReasons()
{
    return $this->belongsToMany(CancelReason::class, 'indent_cancel_reason', 'indent_id', 'cancel_reason_id')->withTimestamps();
}

public function cancelReason()
{
    return $this->hasMany(CancelReason::class);
}

public function customerRate()
{
    return $this->hasOne(CustomerRate::class);
}
public function extraCosts()
{
    return $this->hasMany(ExtraCost::class);
}

public function createdUser() {
    return $this->belongsTo(User::class, 'customer_id');
}

  public function pod()
{
   return $this->belongsTo(Pod::class, 'indent_id');
}

// In Indent model
public function pods()
{
    return $this->hasMany(Pod::class, 'indent_id'); // Ensure the foreign key is correctly named
}

public function extracost()
{
    return $this->hasMany(ExtraCost::class, 'indent_id'); // Ensure the foreign key is correctly named
}

}
