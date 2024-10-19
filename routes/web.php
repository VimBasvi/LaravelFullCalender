<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

// route the "/" (home page) to the __invoke method of homecontroller
Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');

