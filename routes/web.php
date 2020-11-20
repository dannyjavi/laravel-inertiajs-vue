<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MyEventController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;

Route::redirect('/', '/login');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
	Route::get('/myEvents', MyEventController::class);

	Route::get('/events', SearchController::class)->name('events');
		
	Route::resource('users', UserController::class);

	Route::resource('appointment', AppointmentController::class);

});
