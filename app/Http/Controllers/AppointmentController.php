<?php

namespace App\Http\Controllers;

use App\Http\Resources\Appointment as AppointmentResources;
use App\Models\Appointment;

use App\Http\Requests\Appointment as AppointmentRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AppointmentController extends Controller
{

    protected $apt;

    public function __construct(Appointment $appointment) {
        $this->apt = $appointment;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Appointment::select("id","title","start","end","session","booked_by")->get();
        return Inertia::render('Agenda/Books',['events' => $events]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AppointmentRequest $request)
    {
        // Retrieve the validated input data...
        if($request->has('session') == 1800){
            $request->price = 30;
        }
        
        if($request->has('session') == 3600){
            $request->price = 45;
        }
        
        Appointment::create([
            'title'=> $request->title,
            'start' => $request->start,
            'end' => $request->end,
            'session' => $request->session,
            'price' => $request->price,
            'booked_by' => Auth::id()
        ]);
        
        return redirect()->back()->with('message','Cita creada con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment)
    {
        return response()->json(new AppointmentResources($appointment));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function edit(Appointment $appointment)
    {
        return $appointment;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(AppointmentRequest $request, Appointment $appointment)
    {
        $appointment->update($request->all());

        return response()->json(new AppointmentResources($appointment));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        
        return response()->json(null,204);
    }
}