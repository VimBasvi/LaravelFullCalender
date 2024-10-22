@extends('layouts.app')

@section('content')
    <h2>Book a Slot with {{ $employee->name }}</h2> <!-- Employee name for context -->

    <!-- FullCalendar will be rendered here -->
    <div id="calendar"></div>

    <!-- Modal for confirming the appointment -->
    <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModalLabel">Book Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="appointmentForm">
                        <p>Do you want to book this slot?</p>
                        <input type="hidden" id="startTime" name="start_time">
                        <input type="hidden" id="endTime" name="finish_time">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="saveAppointmentBtn" class="btn btn-primary">Book Appointment</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Include FullCalendar, Bootstrap, and jQuery -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			var calendarEl = document.getElementById('calendar');

			var calendar = new FullCalendar.Calendar(calendarEl, {
				initialView: 'timeGridWeek',
				selectable: false,  // Disable free selection
				slotMinTime: '06:00:00',
				slotMaxTime: '24:00:00',
				events: {!! $availabilities !!},  // Load employee availability into the calendar

				// Handle click on an available slot (event)
				eventClick: function(info) {
					if (info.event.title === "Booked Appointment") {
						alert("This slot is already booked.");
						return; // Prevent any further action for booked slots
					}

					$('#startTime').val(info.event.startStr);
					$('#endTime').val(info.event.endStr);

					// Show the booking modal
					$('#appointmentModal').modal('show');
				}
			});

			// Handle the save button click to book the appointment
			$('#saveAppointmentBtn').click(function() {
				let start = $('#startTime').val();
				let end = $('#endTime').val();
				let employeeId = {{ $employee->id }};  // Employee ID passed from backend

				// Send booking request via AJAX
				$.ajax({
					url: '/appointments',  // Route for appointment creation
					type: 'POST',
					data: {
						_token: '{{ csrf_token() }}',  // CSRF token for security
						start_time: start,
						finish_time: end,
						employee_id: employeeId  // Employee ID from backend
					},
					success: function(response) {
						// Remove the "Available" event from the calendar
						var availableEvent = calendar.getEvents().find(event => event.startStr === start && event.title === 'Available');
						if (availableEvent) {
							availableEvent.remove();  // Remove available slot immediately
						}

						// Add the new "Booked" event
						calendar.addEvent({
							id: response.event_id,  // Use the event ID returned from the server
							title: 'Booked Appointment',
							start: response.start_time,
							end: response.finish_time,
							backgroundColor: '#dc3545',  // Customize color for booked slots
							borderColor: '#dc3545'
						});

						// Close the modal
						$('#appointmentModal').modal('hide');
						alert("Appointment booked!");
					},
					error: function() {
						alert("Failed to book appointment!");
					}
				});
			});

			calendar.render();
		});
	</script>

@endpush
