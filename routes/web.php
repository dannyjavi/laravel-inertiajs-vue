<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MyEventController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\isAdmin;

Route::redirect('/', '/login');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
	
	Route::get('/events', SearchController::class)->name('events');
	
	Route::resource('appointment', AppointmentController::class);
	
	Route::middleware([isAdmin::class])->group(function(){
		Route::get('/myEvents', MyEventController::class);
		Route::resource('users', UserController::class);
		Route::get('/users/{id}/event', [UserController::class, 'view'])->name('users.event');
	});
});