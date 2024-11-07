<?php

namespace App\Http\Controllers;

use App\Models\EmployeeAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    // Display availability view for the employee
    public function index()
    {
        // Fetch availabilities for the authenticated employee
        $employeeId = Auth::check() ? Auth::id() : 1;
        $availabilities = EmployeeAvailability::where('employee_id', $employeeId)->get();
        
        // Return availability data to the view, marking booked ones
        $availabilitiesJson = $availabilities->map(function ($availability) {
            return [
                'id' => $availability->id,
                'title' => $availability->booked ? 'Booked' : 'Available', // Mark booked slots
                'start' => $availability->start_time,
                'end' => $availability->end_time,
                'backgroundColor' => $availability->booked ? '#dc3545' : '#28a745', // Red for booked, green for available
                'borderColor' => $availability->booked ? '#dc3545' : '#28a745',
            ];
        });
    
        return view('availability', ['availabilities' => $availabilitiesJson]);
    }

    // Store availability
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'availability_type' => 'required|string',  // Handle recurring or one-time availability
            'location' => 'nullable|string|max:255', // Add location validation
        ]);

        // Set employee_id to authenticated user ID or default to 1 if not authenticated
        $employeeId = Auth::check() ? Auth::id() : 1;

        // Create the availability
        $availability = EmployeeAvailability::create([
            'employee_id' => $employeeId,  // Set employee_id
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'availability_type' => $request->availability_type,  // one-time or recurring
            'location' => $request->location, // Set the location
        ]);

        // Return the saved availability as a JSON response including the ID to the frontend for rendering
        return response()->json([
            'id' => $availability->id,
            'start_time' => $availability->start_time,
            'end_time' => $availability->end_time,
            'location' => $availability->location,
        ]);
    }

    // Delete availability
    public function destroy($id)
    {
        // Find the availability by ID
        $availability = EmployeeAvailability::find($id);

        $employeeId = Auth::check() ? Auth::id() : 1; // change this in official code 

        // Check if availability exists and if the authenticated user owns the availability
        if ($availability && $availability->employee_id == $employeeId) {
            $availability->delete();
            return response()->json(['success' => true], 200);
        }

        // Return an error response if the availability was not found or unauthorized access
        return response()->json(['success' => false], 403);
    }
}
