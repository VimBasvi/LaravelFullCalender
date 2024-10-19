<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

// handle the appointment creation request
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

        // Get the client ID
        $client_id = auth()->check() ? auth()->user()->id : 1; // replace this for COCO

        if (!$client_id) {
            return response()->json(['error' => 'Client not logged in'], 401);  // Return error if no client
        }

        // Create the appointment
        $appointment = Appointment::create([
            'client_id' => $client_id,  // Assuming the client is logged in
            'employee_id' => $validated['employee_id'],
            'start_time' => $validated['start_time'],
            'finish_time' => $validated['finish_time'],  // Optional, can be calculated
        ]);
        
        // load the related client and employee data
        $appointment->load('client', 'employee');

        // check if the appointment was created and client and employee are included
        if (!$appointment->client) {
            return response()->json(['error' => 'Client not found'], 404);
        }
        
        if (!$appointment->employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        // Return JSON response with appointment details to update FullCalendar
        return response()->json([
            'client_name' =>  $appointment->client->name, // return/access client name
            'employee_name' => $appointment->employee->name,
            'start_time' => $appointment->start_time,
            'finish_time' => $appointment->finish_time,
        ]);
    }
}