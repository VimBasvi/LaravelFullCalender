<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',  // Ensure valid employee
            'start_time' => 'required|date',
            'finish_time' => 'nullable|date|after:start_time',  // Optional finish time
        ]);

        // Create the appointment
        $appointment = Appointment::create([
            'client_id' => auth()->user()->id,  // Assuming the client is logged in
            'employee_id' => $validated['employee_id'],
            'start_time' => $validated['start_time'],
            'finish_time' => $validated['finish_time'],  // Optional, can be calculated
        ]);

        // Return JSON response with appointment details to update FullCalendar
        return response()->json([
            'client_name' => auth()->user()->name,
            'employee_name' => $appointment->employee->name,
            'start_time' => $appointment->start_time,
            'finish_time' => $appointment->finish_time,
        ]);
    }
}