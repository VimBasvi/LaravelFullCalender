<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    // Define the fillable fields. fillable fields are fields that can be mass assigned
    protected $fillable = ['name'];
}
