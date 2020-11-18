<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MyEventController;
use App\Http\Controllers\UserController;

Route::redirect('/','/login');

Route::middleware(['auth:sanctum', 'verified'])->group(function(){
    Route::get('/myEvents', MyEventController::class);
    
    Route::get('/events', function () {
        return Inertia::render('Agenda/Books');
    })->name('events');
    
    Route::resource('users', UserController::class);
    Route::resource('appointment', AppointmentController::class);
});