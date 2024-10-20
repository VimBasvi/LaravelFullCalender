<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Employee;
use App\Models\EmployeeAvailability;
use Illuminate\Http\Request;

// handle the appointment creation request
class AppointmentController extends Controller
{
    /**
     * Show the employee's calendar with their availability.
     */
    public function showEmployeeCalendar(Request $request)
    {
        // Set the default employee ID to 1 (you can change this as needed)
        $employeeId = 1;

        // Fetch availability for the default employee (ID = 1)
        $availabilities = EmployeeAvailability::where('employee_id', $employeeId)->get();

        // Fetch the employee object based on the ID
        $employee = Employee::find($employeeId);

        // Ensure the employee exists
        if (!$employee) {
            abort(404, 'Employee not found');
        }

        // Convert availability data to FullCalendar format
        $availabilitiesJson = $availabilities->map(function ($availability) {
            return [
                'id' => $availability->id,
                'title' => 'Available',
                'start' => $availability->start_time,
                'end' => $availability->end_time,
                'backgroundColor' => '#28a745',
                'borderColor' => '#28a745',
            ];
        });

        // Pass availability and employee information to the view
        return view('home', [
            'availabilities' => $availabilitiesJson->toJson(), // Pass as JSON
            'employee' => $employee  // Pass the employee object to the view
        ]);
    }

    /**
     * Store a new appointment.
     */
    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',  // Ensure valid employee
            'start_time' => 'required|date',
            'finish_time' => 'nullable|date|after:start_time',  // Optional finish time
        ]);

        // Ensure the user is authenticated
        // if (!auth()->check()) {
        //     return response()->json(['error' => 'Client not logged in'], 401);
        // }

        // Get the client ID (authenticated user)
        $client_id = 1; //auth()->user()->id;

        // Create the appointment
        $appointment = Appointment::create([
            'client_id' => $client_id,  // Client is the logged-in user
            'employee_id' => $validated['employee_id'],
            'start_time' => $validated['start_time'],
            'finish_time' => $validated['finish_time'],  // Optional, can be calculated
        ]);

        // Eager load the related client and employee data
        $appointment->load('client', 'employee');

        // Return JSON response with appointment details to update FullCalendar
        return response()->json([
            'client_name' => $appointment->client->name,  // Access client name
            'employee_name' => $appointment->employee->name,  // Access employee name
            'start_time' => $appointment->start_time,
            'finish_time' => $appointment->finish_time,
        ]);
    }
}
