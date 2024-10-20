@extends('layouts.app')

@section('content')
    <div id="calendar"></div>

    <!-- Modal for collecting employee ID and confirming the appointment -->
    <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModalLabel">Book Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="appointmentForm">
                        <div class="mb-3">
                            <label for="employeeId" class="form-label">Employee ID</label>
                            <input type="text" class="form-control" id="employeeId" name="employee_id" required>
                        </div>
                        <input type="hidden" id="startTime" name="start_time">
                        <input type="hidden" id="endTime" name="finish_time">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="saveAppointmentBtn" class="btn btn-primary">Save Appointment</button>
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
				selectable: true,  // Enable selection by dragging
				slotMinTime: '06:00:00',
				slotMaxTime: '24:00:00',
				events: @json($events), // Existing appointments

				// Restrict past dates and times
				validRange: {
					start: new Date().toISOString().split('T')[0], // Today's date
				},

				// Callback when a range of time is selected
				select: function(info) {
					var now = new Date();  // Get current date and time

					// Check if selected date is today
					if (new Date(info.startStr).toDateString() === now.toDateString()) {
						// Check if the selected time is in the past
						if (new Date(info.startStr) < now) {
							alert("Cannot book appointments in the past!");
							calendar.unselect();  // Deselect the invalid selection
							return;
						}
					}

					// Store the selected start and end time in hidden inputs
					$('#startTime').val(info.startStr);
					$('#endTime').val(info.endStr);

					// Show the modal to select employee ID
					$('#appointmentModal').modal('show');
				}
			});

			// Handle the save button click from the modal
			$('#saveAppointmentBtn').click(function() {
				let employeeId = $('#employeeId').val();
				let start = $('#startTime').val();
				let end = $('#endTime').val();

				if (employeeId) {
					// Send the data to the server via AJAX
					$.ajax({
						url: '/appointments',  // Route for appointment creation
						type: 'POST',
						data: {
							_token: '{{ csrf_token() }}',  // CSRF token for security
							start_time: start,
							finish_time: end,
							employee_id: employeeId
						},
						success: function(response) {
							// Add the new event to the calendar
							calendar.addEvent({
								title: response.client_name + ' (' + response.employee_name + ')',
								start: response.start_time,
								end: response.finish_time
							});

							// Close the modal
							$('#appointmentModal').modal('hide');
							alert("Appointment booked!");
						},
						error: function() {
							alert("Failed to book appointment!");
						}
					});
				}
			});

			calendar.render();
		});
	</script>


@endpush
