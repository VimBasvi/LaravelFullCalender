<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeAvailability extends Model
{
    protected $fillable = ['employee_id', 'start_time', 'end_time', 'availability_type', 'booked'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
