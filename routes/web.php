<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

use App\Http\Controllers\AvailabilityController;

// route the "/" (home page) to the __invoke method of homecontroller
Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');

use App\Http\Controllers\AppointmentController;

// route the POST request to the store method of appointmentcontroller
Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');

Route::get('employee/availability', [AvailabilityController::class, 'index'])->name('employee.availability'); // Employee availability page
Route::post('/availabilities', [AvailabilityController::class, 'store'])->name('employee.availability.store'); // Store availability of employee

