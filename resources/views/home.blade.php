@extends('layouts.app')

@section('content')
    <div id="calendar"></div>
@endsection

@push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Ensure jQuery is available -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                slotMinTime: '08:00:00',
                slotMaxTime: '19:00:00',
                events: @json($events), // Existing appointments
                dateClick: function(info) {
                    // Handle click on date to create an appointment
                    let start = info.dateStr; // Get clicked date and time
                    let employeeId = prompt("Enter employee ID:"); // Can be replaced with a dropdown/modal

                    if (employeeId) {
                        // Send data to server to create appointment
                        $.ajax({
                            url: '/appointments',  // Route for appointment creation
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',  // CSRF token for security
                                start_time: start,
                                employee_id: employeeId //will need to manually set this once user visits braider page/ profile
                            },
                            success: function(response) {
                                // Add the new event to the calendar after it's saved in the database
                                calendar.addEvent({
                                    title: response.client_name + ' (' + response.employee_name + ')',
                                    start: response.start_time,
                                    end: response.finish_time
                                });
                                alert("Appointment booked!");
                            },
                            error: function() {
                                alert("Failed to book appointment!");
                            }
                        });
                    }
                }
            });

            calendar.render();
        });
    </script>
@endpush
