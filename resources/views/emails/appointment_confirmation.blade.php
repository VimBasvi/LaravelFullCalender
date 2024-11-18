<!DOCTYPE html>
<html>
<head>
    <title>Appointment Confirmation</title>
</head>
<body>
    <h1>Appointment Confirmation</h1>
    <p>Dear {{ $appointment->client->name }},</p>
    <p>Your appointment with {{ $appointment->employee->name }} has been confirmed.</p>
    <p><strong>Date & Time:</strong> {{ $appointment->start_time }} to {{ $appointment->finish_time }}</p>
    <p>Location: {{ $appointment->location }}</p>
    <p>Thank you!</p>
</body>
</html>