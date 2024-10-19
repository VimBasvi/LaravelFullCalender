<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'start_time',
        'finish_time',
        'comments',
        'client_id',
        'employee_id',
    ];
 
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
 
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
