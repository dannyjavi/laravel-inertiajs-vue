<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MyEventController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('/myEvents', MyEventController::class);

Route::get('/events', function (){
    return Inertia::render('Agenda/Books');
})->name('events');

Route::resource('users', UserController::class);
Route::resource('appointment', AppointmentController::class);
