<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\Employee;

class Appointment extends Model
{
    protected $fillable = [
        'start_time',
        'finish_time',
        'comments',
        'client_id',
        'employee_id',
    ];

    // Correct relationship definition
    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}