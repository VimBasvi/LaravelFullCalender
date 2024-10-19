<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        $events = [];

        // Fetch appointments with associated client and employee data
        $appointments = Appointment::with(['client', 'employee'])->get();
        
        foreach ($appointments as $appointment) {
            $events[] = [
                'title' => $appointment->client->name . ' ('.$appointment->employee->name.')',
                'start' => $appointment->start_time,
                'end' => $appointment->finish_time,
            ];
        }
        
        // Pass the events to the view
        return view('home', compact('events'));
    }
}