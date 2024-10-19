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
                selectable: true,  // Enable selection by dragging
                slotMinTime: '08:00:00',
                slotMaxTime: '19:00:00',
                events: @json($events), // Existing appointments

                // Callback when a range of time is selected
                select: function(info) {
                    // Get the selected start and end time
                    let start = info.startStr;
                    let end = info.endStr;

                    // You can replace this with a modal or form to ask for additional details
                    let employeeId = prompt("Enter employee ID:");

                    if (employeeId) {
                        // Send data to the server to create the appointment
                        $.ajax({
                            url: '/appointments',  // Route for appointment creation
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',  // CSRF token for security
                                start_time: start,
                                finish_time: end,  // Selected end time
                                employee_id: employeeId
                            },
                            success: function(response) {
                                // Add the new event to the calendar after it's saved
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
