@extends('layouts.app')

@section('content')
    <h2>Manage Your Availability</h2>

    <!-- FullCalendar will be rendered here -->
    <div id="calendar"></div>

    <!-- Modal for setting availability (use Bootstrap modal structure) -->
    <div class="modal fade" id="availabilityModal" tabindex="-1" aria-labelledby="availabilityModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="availabilityModalLabel">Set Availability</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="availabilityForm">
						@csrf  <!-- This will automatically generate the CSRF token -->
						<div class="mb-3">
							<label for="availability_type" class="form-label">Availability Type:</label>
                            <select id="availability_type" name="availability_type" class="form-select">
                                <option value="one_time">One-Time</option>
                                <option value="recurring">Recurring</option>
                            </select>
						</div>
						<!-- for location optional -->
						<div class="mb-3">
							<label for="location" class="form-label">Location:</label>
							<input type="text" class="form-control" id="location" name="location" placeholder="Enter location">
						</div>
						<input type="hidden" id="start_time" name="start_time" value="">
                        <input type="hidden" id="end_time" name="end_time" value="">
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary" id="saveAvailabilityBtn">Save Availability</button>
						</div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
@endsection

@push('scripts') 
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
				selectable: true,  // Allow employees to select time slots
				slotMinTime: '08:00:00',
				slotMaxTime: '24:00:00',
 				// Load existing availability data from the database into calendar in specific format
				events: @json($availabilities).map(event => {
					let titleText = event.title;
					if (event.location) {
						titleText += ' - ' + event.location;
					}
					return {
						id: event.id,
						title: titleText,
						start: event.start,
						end: event.end,
						backgroundColor: event.backgroundColor,
						borderColor: event.borderColor,
					};
				}),

				// Event selection handler
				select: function(info) {
					document.getElementById('start_time').value = info.startStr;
					document.getElementById('end_time').value = info.endStr;

					var modal = new bootstrap.Modal(document.getElementById('availabilityModal'));
					modal.show();
				},

				// Event click handler for editing/deleting
				eventClick: function(info) {
					if (confirm("Do you want to delete this availability?")) {
						$.ajax({
							url: '/availabilities/' + info.event.id,
							type: 'DELETE',
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // Correct way to include CSRF token
							}, // dont forget to modify the layout rmemeber to add the csrf token
							success: function() {
								info.event.remove();  // Remove the event from the calendar
								alert('Availability deleted successfully.');
							},
							error: function() {
								alert("Failed to delete availability.");
							}
						});
					}
				} // <-- Missing closing curly brace was added here
			});

			calendar.render();

			// Handle form submission for saving availability
			$('#availabilityForm').on('submit', function(e) {
				e.preventDefault();

				$.ajax({
					url: '/availabilities',  // Route for saving availability
					type: 'POST',
					data: $(this).serialize(),
					success: function(response) {
						// Add the event to the calendar with location if provided
						let titleText = 'Available';
						if (response.location) {
							titleText += ' - ' + response.location;
						}
						calendar.addEvent({
							id: response.id,  // Set id so we can delete it later
							title: titleText,
							start: response.start_time,
							end: response.end_time,
							backgroundColor: '#28a745',  // Customize color for availability
							borderColor: '#28a745'
						});
						var modal = bootstrap.Modal.getInstance(document.getElementById('availabilityModal'));
						modal.hide();
					},
					error: function() {
						alert("Failed to save availability.");
					}
				});
			});
		});
	</script>
@endpush