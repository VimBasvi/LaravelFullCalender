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
                slotMaxTime: '19:00:00',
                events: @json($availabilities), // Load existing availability data from the database

                select: function(info) {
                    // Open the modal when a time slot is selected
                    document.getElementById('start_time').value = info.startStr;
                    document.getElementById('end_time').value = info.endStr;

                    var modal = new bootstrap.Modal(document.getElementById('availabilityModal'));
                    modal.show();
                }
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
                        calendar.addEvent({
                            title: 'Available',
                            start: response.start_time,
                            end: response.end_time,
                            backgroundColor: '#28a745',  // Customize color for availability
                            borderColor: '#28a745'
                        });
                        var modal = bootstrap.Modal.getInstance(document.getElementById('availabilityModal'));
                        modal.hide();
                    },
                    error: function() {
                        alert("Failed to save availability");
                    }
                });
            });
        });
    </script>
@endpush
