<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\HomeController;

// route the "/" (home page) to the __invoke method of homecontroller
Route::get('/', HomeController::class)->name('home');

// Route for storing appointments
Route::get('/', [AppointmentController::class, 'showEmployeeCalendar'])->name('appointments.show');
Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');

// Routes for employee availability
Route::get('employee/availability', [AvailabilityController::class, 'index'])->name('employee.availability'); // Employee availability page
Route::post('/availabilities', [AvailabilityController::class, 'store'])->name('employee.availability.store'); // Store availability of employee
Route::delete('/availabilities/{id}', [AvailabilityController::class, 'destroy'])->name('employee.availability.destroy');
