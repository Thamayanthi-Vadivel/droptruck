<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name', 'contact', 'email', 'password', 'designation','remarks'
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'employee_user', 'employee_id', 'user_id');
    }

}
