<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Employee;
use App\Models\EmployeeAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // 
use App\Mail\AppointmentConfirmation; // to import the Mailable class

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

        // Fetch availability for the default employee (limiting displayed appts to only booked appts )
        $availabilities =  EmployeeAvailability::where('employee_id', $employeeId)
                                                    ->where('booked', false) // Only show unbooked slots
                                                    ->get();

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

        // Get the client ID (assuming no authentication for now)
        $client_id = 1; // or use auth()->user()->id if authentication is present

        // Create the appointment
        $appointment = Appointment::create([
            'client_id' => $client_id,  // Client is the logged-in user
            'employee_id' => $validated['employee_id'],
            'start_time' => $validated['start_time'],
            'finish_time' => $validated['finish_time'],  // Optional, can be calculated
        ]);

        // Mark the availability as booked
        EmployeeAvailability::where('employee_id', $validated['employee_id'])
            ->where('start_time', $validated['start_time'])
            ->update(['booked' => true]);
        // Send appointment confirmation email
        $clientEmail = "vimbisai.basvi@yale.edu"; //$appointment->client->email; // use client email attribute
        $stylistEmail = "basvi.vimbisai@gmail.com";//$appointment->employee->email; // use employee email attribute

        Mail::to($clientEmail)->send(new AppointmentConfirmation($appointment));
        // Send email to the stylist
        Mail::to($stylistEmail)->send(new AppointmentConfirmation($appointment));
        // Return JSON response with appointment details to update FullCalendar
        return response()->json([
            'event_id' => $appointment->id,  // Return the event ID to manipulate on the frontend
            'client_name' => $appointment->client->name,
            'employee_name' => $appointment->employee->name,
            'start_time' => $appointment->start_time,
            'finish_time' => $appointment->finish_time,
        ]);
    }

}
