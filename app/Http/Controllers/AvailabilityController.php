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
        // Fetch availabilities for the authenticated employee, or for employee_id = 1 by default
        $employeeId = Auth::check() ? Auth::id() : 1;
        $availabilities = EmployeeAvailability::where('employee_id', $employeeId)->get();
        return view('availability', compact('availabilities'));
    }

    // Store availability
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'availability_type' => 'required|string',  // Handle recurring or one-time availability
        ]);

        // Set employee_id to authenticated user ID or default to 1 if not authenticated
        $employeeId = Auth::check() ? Auth::id() : 1;

        // Create the availability
        $availability = EmployeeAvailability::create([
            'employee_id' => $employeeId,  // Set employee_id
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'availability_type' => $request->availability_type,  // one-time or recurring
        ]);

        // Return the saved availability as a JSON response
        return response()->json([
            'start_time' => $availability->start_time,
            'end_time' => $availability->end_time
        ]);
    }
}


// namespace App\Http\Controllers;

// use App\Models\EmployeeAvailability;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;

// class AvailabilityController extends Controller
// {
//     // Display availability view for the employee
//     // Method to load the availability view (GET)
//     public function index()
//     {
//         $availabilities = EmployeeAvailability::where('employee_id', Auth::id())->get();
//         return view('availability', compact('availabilities'));
//     }

//     // Store availability
//     public function store(Request $request)
//     {
//         $request->validate([
//             //'employee_id' => 'required|exists:employees,id',
//             'start_time' => 'required|date',
//             'end_time' => 'required|date|after:start_time',
//             'availability_type' => 'required|string',  // Handle recurring or one-time availability
//         ]);

//         $availability = EmployeeAvailability::create([
//             'employee_id' => $request->employee_id,
//             'start_time' => $request->start_time,
//             'end_time' => $request->end_time,
//             'availability_type' => $request->availability_type // one-time or recurring
//         ]);

//         return response()->json([
//             'start_time' => $availability->start_time,
//             'end_time' => $availability->end_time
//         ]);
//     }
// }
